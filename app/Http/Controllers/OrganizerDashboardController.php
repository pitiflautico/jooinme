<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Feedback;
use App\Models\Participation;
use App\Models\ScheduleSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrganizerDashboardController extends Controller
{
    /**
     * Display the organizer dashboard
     */
    public function index()
    {
        $user = auth()->user();

        // Get conversations organized by the user
        $conversations = Conversation::where('owner_id', $user->id)
            ->withCount(['participations', 'scheduleSlots', 'feedback'])
            ->with(['topic'])
            ->latest()
            ->get();

        // Overall Statistics
        $stats = [
            'total_conversations' => $conversations->count(),
            'active_conversations' => $conversations->where('is_active', true)->count(),
            'total_participants' => $conversations->sum('participations_count'),
            'total_sessions' => $conversations->sum('schedule_slots_count'),
            'average_rating' => round(Feedback::whereIn('conversation_id', $conversations->pluck('id'))->avg('rating'), 2) ?? 0,
        ];

        // Pending approvals
        $pendingApprovals = Participation::whereIn('conversation_id', $conversations->pluck('id'))
            ->where('status', 'pending')
            ->with(['user', 'conversation'])
            ->latest()
            ->take(10)
            ->get();

        // Upcoming sessions
        $upcomingSessions = ScheduleSlot::whereIn('conversation_id', $conversations->pluck('id'))
            ->where('scheduled_at', '>=', now())
            ->where('status', 'scheduled')
            ->with(['conversation'])
            ->orderBy('scheduled_at')
            ->take(5)
            ->get();

        // Recent feedback
        $recentFeedback = Feedback::whereIn('conversation_id', $conversations->pluck('id'))
            ->whereNull('rated_user_id')
            ->with(['user', 'conversation'])
            ->latest()
            ->take(5)
            ->get();

        // Conversation performance (attendance rates)
        $conversationPerformance = [];
        foreach ($conversations->where('is_active', true)->take(5) as $conversation) {
            $totalSlots = $conversation->scheduleSlots()->where('status', 'completed')->count();
            $totalRSVPs = DB::table('attendances')
                ->whereIn('schedule_slot_id', $conversation->scheduleSlots()->where('status', 'completed')->pluck('id'))
                ->where('status', 'confirmed')
                ->count();

            $conversationPerformance[] = [
                'conversation' => $conversation,
                'total_sessions' => $totalSlots,
                'average_attendance' => $totalSlots > 0 ? round($totalRSVPs / $totalSlots, 1) : 0,
                'rating' => round($conversation->feedback()->avg('rating'), 2) ?? 0,
            ];
        }

        // Growth over time (last 6 months)
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthKey = $month->format('Y-m');

            $newParticipants = Participation::whereIn('conversation_id', $conversations->pluck('id'))
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();

            $sessionsHeld = ScheduleSlot::whereIn('conversation_id', $conversations->pluck('id'))
                ->where('status', 'completed')
                ->whereYear('scheduled_at', $month->year)
                ->whereMonth('scheduled_at', $month->month)
                ->count();

            $monthlyData[] = [
                'month' => $month->format('M Y'),
                'participants' => $newParticipants,
                'sessions' => $sessionsHeld,
            ];
        }

        return view('organizer.dashboard', compact(
            'conversations',
            'stats',
            'pendingApprovals',
            'upcomingSessions',
            'recentFeedback',
            'conversationPerformance',
            'monthlyData'
        ));
    }
}
