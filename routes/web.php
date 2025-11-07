<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ScheduleSlotController;
use Illuminate\Support\Facades\Route;

// Landing Page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Conversations (Public access)
Route::get('/conversations', [ConversationController::class, 'index'])->name('conversations.index');
Route::get('/conversations/{conversation}', [ConversationController::class, 'show'])->name('conversations.show');

// User Profiles (Public access)
Route::get('/users/{user}', [UserProfileController::class, 'show'])->name('users.show');

// Invitation acceptance (Public but requires login)
Route::get('/invitations/{token}/accept', [ParticipantController::class, 'acceptInvitation'])->name('invitations.accept')->middleware('auth');
Route::get('/invitations/{token}/reject', [ParticipantController::class, 'rejectInvitation'])->name('invitations.reject')->middleware('auth');

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        $user = auth()->user();

        // Próximas conversaciones del usuario
        $myConversations = \App\Models\Participation::with(['conversation.topic', 'conversation.owner'])
            ->where('user_id', $user->id)
            ->where('status', 'accepted')
            ->whereHas('conversation', function($q) {
                $q->where('is_active', true);
            })
            ->latest()
            ->take(5)
            ->get();

        // Conversaciones que el usuario organiza
        $organizing = \App\Models\Conversation::where('owner_id', $user->id)
            ->where('is_active', true)
            ->withCount('participations')
            ->latest()
            ->take(5)
            ->get();

        // Estadísticas
        $stats = [
            'participations' => $myConversations->count(),
            'organizing' => $organizing->count(),
            'total_participants' => $organizing->sum('participations_count'),
        ];

        return view('dashboard', compact('myConversations', 'organizing', 'stats'));
    })->name('dashboard');

    // Conversations (Auth required)
    Route::post('/conversations/{conversation}/join', [ConversationController::class, 'join'])->name('conversations.join');
    Route::post('/conversations/{conversation}/leave', [ConversationController::class, 'leave'])->name('conversations.leave');
    Route::resource('conversations', ConversationController::class)->except(['index', 'show']);

    // Participant Management
    Route::get('/conversations/{conversation}/participants', [ParticipantController::class, 'index'])->name('conversations.participants.index');
    Route::get('/conversations/{conversation}/participants/invite', [ParticipantController::class, 'inviteForm'])->name('conversations.participants.invite');
    Route::post('/conversations/{conversation}/participants/invite', [ParticipantController::class, 'invite'])->name('conversations.participants.send-invite');
    Route::post('/conversations/{conversation}/participants/{participation}/approve', [ParticipantController::class, 'approve'])->name('conversations.participants.approve');
    Route::post('/conversations/{conversation}/participants/{participation}/reject', [ParticipantController::class, 'reject'])->name('conversations.participants.reject');
    Route::post('/conversations/{conversation}/participants/{participation}/update-role', [ParticipantController::class, 'updateRole'])->name('conversations.participants.update-role');
    Route::delete('/conversations/{conversation}/participants/{participation}', [ParticipantController::class, 'remove'])->name('conversations.participants.remove');

    // Feedback
    Route::get('/schedule-slots/{scheduleSlot}/feedback/create', [FeedbackController::class, 'create'])->name('feedback.create');
    Route::post('/schedule-slots/{scheduleSlot}/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
    Route::get('/conversations/{conversation}/feedback', [FeedbackController::class, 'index'])->name('feedback.index');
    Route::get('/users/{user}/feedback', [FeedbackController::class, 'userFeedback'])->name('feedback.user');

    // Schedule Slots (Sessions)
    Route::get('/conversations/{conversation}/calendar', [ScheduleSlotController::class, 'index'])->name('schedule-slots.index');
    Route::get('/conversations/{conversation}/sessions/create', [ScheduleSlotController::class, 'create'])->name('schedule-slots.create');
    Route::post('/conversations/{conversation}/sessions', [ScheduleSlotController::class, 'store'])->name('schedule-slots.store');
    Route::get('/sessions/{scheduleSlot}', [ScheduleSlotController::class, 'show'])->name('schedule-slots.show');
    Route::get('/sessions/{scheduleSlot}/edit', [ScheduleSlotController::class, 'edit'])->name('schedule-slots.edit');
    Route::put('/sessions/{scheduleSlot}', [ScheduleSlotController::class, 'update'])->name('schedule-slots.update');
    Route::delete('/sessions/{scheduleSlot}', [ScheduleSlotController::class, 'destroy'])->name('schedule-slots.destroy');
    Route::post('/sessions/{scheduleSlot}/rsvp', [ScheduleSlotController::class, 'rsvp'])->name('schedule-slots.rsvp');
    Route::get('/sessions/{scheduleSlot}/export-ical', [ScheduleSlotController::class, 'exportIcal'])->name('schedule-slots.export-ical');
    Route::get('/sessions/{scheduleSlot}/google-calendar', [ScheduleSlotController::class, 'googleCalendar'])->name('schedule-slots.google-calendar');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User Follow/Unfollow
    Route::post('/users/{user}/follow', [UserProfileController::class, 'follow'])->name('users.follow');
    Route::post('/users/{user}/unfollow', [UserProfileController::class, 'unfollow'])->name('users.unfollow');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
});

require __DIR__.'/auth.php';
