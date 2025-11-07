<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    /**
     * Display the specified user's public profile.
     */
    public function show(User $user)
    {
        // Cargar relaciones
        $user->load(['achievements', 'followers', 'following']);

        // Conversaciones organizadas por el usuario
        $organizing = $user->conversations()
            ->where('is_active', true)
            ->withCount('participations')
            ->latest()
            ->take(6)
            ->get();

        // Conversaciones en las que participa
        $participating = $user->participations()
            ->with(['conversation.topic', 'conversation.owner'])
            ->where('status', 'accepted')
            ->whereHas('conversation', function($q) {
                $q->where('is_active', true);
            })
            ->latest()
            ->take(6)
            ->get();

        // Estadísticas
        $stats = [
            'organizing' => $user->conversations()->where('is_active', true)->count(),
            'participating' => $user->participations()->where('status', 'accepted')->count(),
            'followers' => $user->followers()->count(),
            'following' => $user->following()->count(),
            'achievements' => $user->achievements()->count(),
        ];

        // Verificar si el usuario autenticado sigue a este usuario
        $isFollowing = auth()->check() ? auth()->user()->following()->where('followed_id', $user->id)->exists() : false;

        return view('users.show', compact('user', 'organizing', 'participating', 'stats', 'isFollowing'));
    }

    /**
     * Follow a user
     */
    public function follow(User $user)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'No puedes seguirte a ti mismo.');
        }

        auth()->user()->following()->syncWithoutDetaching([$user->id]);

        return back()->with('success', "Ahora sigues a {$user->name}");
    }

    /**
     * Unfollow a user
     */
    public function unfollow(User $user)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        auth()->user()->following()->detach($user->id);

        return back()->with('success', "Has dejado de seguir a {$user->name}");
    }
}
