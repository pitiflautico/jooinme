<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\NotificationController;
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
