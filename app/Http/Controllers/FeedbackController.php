<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Feedback;
use App\Models\ScheduleSlot;
use App\Models\User;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * Show feedback form for a session
     */
    public function create(ScheduleSlot $scheduleSlot)
    {
        $conversation = $scheduleSlot->conversation;

        // Check if user is a participant
        if (!$conversation->isMember(auth()->user())) {
            return redirect()->route('conversations.show', $conversation)
                ->with('error', 'Debes ser participante para dejar feedback.');
        }

        // Check if user already submitted feedback for this slot
        $existingFeedback = Feedback::where('schedule_slot_id', $scheduleSlot->id)
            ->where('user_id', auth()->id())
            ->whereNull('rated_user_id')
            ->first();

        if ($existingFeedback) {
            return redirect()->route('conversations.show', $conversation)
                ->with('info', 'Ya has enviado feedback para esta sesión.');
        }

        // Get other participants to rate
        $participants = $conversation->acceptedParticipants()
            ->where('users.id', '!=', auth()->id())
            ->get();

        return view('feedback.create', compact('scheduleSlot', 'conversation', 'participants'));
    }

    /**
     * Store feedback for a session
     */
    public function store(Request $request, ScheduleSlot $scheduleSlot)
    {
        $conversation = $scheduleSlot->conversation;

        if (!$conversation->isMember(auth()->user())) {
            return back()->with('error', 'No tienes permisos.');
        }

        $request->validate([
            'attended' => 'required|boolean',
            'rating' => 'required|integer|min:1|max:5',
            'content_rating' => 'nullable|integer|min:1|max:5',
            'organization_rating' => 'nullable|integer|min:1|max:5',
            'atmosphere_rating' => 'nullable|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'testimonial' => 'nullable|string|max:500',
            'is_public' => 'boolean',
            'would_recommend' => 'boolean',
            'tags' => 'nullable|array',
            'participant_ratings' => 'nullable|array',
            'participant_ratings.*.user_id' => 'exists:users,id',
            'participant_ratings.*.rating' => 'integer|min:1|max:5',
            'participant_ratings.*.comment' => 'nullable|string|max:500',
        ]);

        // Create session feedback
        Feedback::create([
            'schedule_slot_id' => $scheduleSlot->id,
            'user_id' => auth()->id(),
            'conversation_id' => $conversation->id,
            'rating' => $request->rating,
            'content_rating' => $request->content_rating,
            'organization_rating' => $request->organization_rating,
            'atmosphere_rating' => $request->atmosphere_rating,
            'comment' => $request->comment,
            'testimonial' => $request->testimonial,
            'is_public' => $request->boolean('is_public'),
            'attended' => $request->boolean('attended'),
            'would_recommend' => $request->boolean('would_recommend'),
            'tags' => $request->tags,
        ]);

        // Create participant ratings
        if ($request->participant_ratings) {
            foreach ($request->participant_ratings as $participantRating) {
                if (!empty($participantRating['rating'])) {
                    Feedback::create([
                        'schedule_slot_id' => $scheduleSlot->id,
                        'user_id' => auth()->id(),
                        'conversation_id' => $conversation->id,
                        'rated_user_id' => $participantRating['user_id'],
                        'rating' => $participantRating['rating'],
                        'comment' => $participantRating['comment'] ?? null,
                        'attended' => true,
                    ]);
                }
            }
        }

        return redirect()->route('conversations.show', $conversation)
            ->with('success', '¡Gracias por tu feedback!');
    }

    /**
     * View all feedback for a conversation
     */
    public function index(Conversation $conversation)
    {
        // Check if user is the owner or a participant
        if (!$conversation->isOwner(auth()->user()) && !$conversation->isMember(auth()->user())) {
            return redirect()->route('conversations.show', $conversation)
                ->with('error', 'No tienes permisos para ver el feedback.');
        }

        $conversation->load(['scheduleSlots' => function($q) {
            $q->where('status', 'completed')->orderBy('scheduled_at', 'desc');
        }]);

        // Get all feedback
        $feedback = Feedback::where('conversation_id', $conversation->id)
            ->whereNull('rated_user_id')
            ->with(['user', 'scheduleSlot'])
            ->latest()
            ->paginate(20);

        // Calculate averages
        $averages = [
            'overall' => round($feedback->avg('rating'), 2),
            'content' => round($feedback->avg('content_rating'), 2),
            'organization' => round($feedback->avg('organization_rating'), 2),
            'atmosphere' => round($feedback->avg('atmosphere_rating'), 2),
            'would_recommend' => round($feedback->where('would_recommend', true)->count() / max($feedback->count(), 1) * 100),
        ];

        // Get public testimonials
        $testimonials = Feedback::where('conversation_id', $conversation->id)
            ->whereNotNull('testimonial')
            ->where('is_public', true)
            ->with('user')
            ->latest()
            ->take(10)
            ->get();

        return view('feedback.index', compact('conversation', 'feedback', 'averages', 'testimonials'));
    }

    /**
     * View feedback for a specific user (their reputation)
     */
    public function userFeedback(User $user)
    {
        // Get feedback received as rated_user
        $receivedFeedback = Feedback::where('rated_user_id', $user->id)
            ->with(['user', 'conversation', 'scheduleSlot'])
            ->latest()
            ->paginate(20);

        $averageRating = round($receivedFeedback->avg('rating'), 2);
        $totalRatings = $receivedFeedback->count();

        // Get public testimonials for conversations they organized
        $testimonials = Feedback::whereHas('conversation', function($q) use ($user) {
                $q->where('owner_id', $user->id);
            })
            ->whereNotNull('testimonial')
            ->where('is_public', true)
            ->with(['user', 'conversation'])
            ->latest()
            ->take(10)
            ->get();

        return view('feedback.user', compact('user', 'receivedFeedback', 'averageRating', 'totalRatings', 'testimonials'));
    }
}
