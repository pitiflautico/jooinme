<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Invitation;
use App\Models\Participation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ParticipantController extends Controller
{
    /**
     * Display the participant management panel for a conversation
     */
    public function index(Conversation $conversation)
    {
        // Check if user is the owner
        if (!$conversation->isOwner(auth()->user())) {
            return redirect()->route('conversations.show', $conversation)
                ->with('error', 'No tienes permisos para gestionar participantes.');
        }

        $conversation->load(['participations.user', 'invitations.inviter', 'invitations.invitee']);

        // Get participations by status
        $accepted = $conversation->participations()->where('status', 'accepted')->with('user')->get();
        $pending = $conversation->participations()->where('status', 'pending')->with('user')->get();
        $rejected = $conversation->participations()->where('status', 'rejected')->with('user')->get();

        // Get invitations by status
        $pendingInvitations = $conversation->invitations()->pending()->with(['inviter', 'invitee'])->get();
        $acceptedInvitations = $conversation->invitations()->where('status', 'accepted')->with(['inviter', 'invitee'])->get();

        return view('conversations.participants.index', compact(
            'conversation',
            'accepted',
            'pending',
            'rejected',
            'pendingInvitations',
            'acceptedInvitations'
        ));
    }

    /**
     * Approve a pending participation
     */
    public function approve(Conversation $conversation, Participation $participation)
    {
        if (!$conversation->isOwner(auth()->user())) {
            return back()->with('error', 'No tienes permisos.');
        }

        if ($participation->conversation_id !== $conversation->id) {
            return back()->with('error', 'Participación no encontrada.');
        }

        if ($conversation->isFull()) {
            return back()->with('error', 'La conversación está llena.');
        }

        $participation->update([
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);

        $conversation->increment('current_participants');

        // TODO: Send notification to user

        return back()->with('success', "Solicitud de {$participation->user->name} aprobada.");
    }

    /**
     * Reject a pending participation
     */
    public function reject(Conversation $conversation, Participation $participation)
    {
        if (!$conversation->isOwner(auth()->user())) {
            return back()->with('error', 'No tienes permisos.');
        }

        if ($participation->conversation_id !== $conversation->id) {
            return back()->with('error', 'Participación no encontrada.');
        }

        $participation->update([
            'status' => 'rejected',
        ]);

        // TODO: Send notification to user

        return back()->with('success', "Solicitud de {$participation->user->name} rechazada.");
    }

    /**
     * Update participant role
     */
    public function updateRole(Conversation $conversation, Participation $participation, Request $request)
    {
        if (!$conversation->isOwner(auth()->user())) {
            return back()->with('error', 'No tienes permisos.');
        }

        $request->validate([
            'role' => 'required|in:member,moderator,co_host',
        ]);

        $participation->update([
            'role' => $request->role,
        ]);

        return back()->with('success', "Rol de {$participation->user->name} actualizado.");
    }

    /**
     * Remove a participant from the conversation
     */
    public function remove(Conversation $conversation, Participation $participation)
    {
        if (!$conversation->isOwner(auth()->user())) {
            return back()->with('error', 'No tienes permisos.');
        }

        if ($participation->conversation_id !== $conversation->id) {
            return back()->with('error', 'Participación no encontrada.');
        }

        // Cannot remove the owner
        if ($participation->user_id === $conversation->owner_id) {
            return back()->with('error', 'No puedes eliminar al organizador.');
        }

        $userName = $participation->user->name;
        $participation->delete();

        if ($participation->status === 'accepted') {
            $conversation->decrement('current_participants');
        }

        // TODO: Send notification to user

        return back()->with('success', "{$userName} ha sido eliminado de la conversación.");
    }

    /**
     * Show invite form
     */
    public function inviteForm(Conversation $conversation)
    {
        if (!$conversation->isOwner(auth()->user())) {
            return redirect()->route('conversations.show', $conversation)
                ->with('error', 'No tienes permisos.');
        }

        // Get all users except those already in conversation
        $existingUserIds = $conversation->participants()->pluck('users.id')->toArray();
        $availableUsers = User::whereNotIn('id', $existingUserIds)
            ->where('id', '!=', auth()->id())
            ->get();

        return view('conversations.participants.invite', compact('conversation', 'availableUsers'));
    }

    /**
     * Send invitations
     */
    public function invite(Conversation $conversation, Request $request)
    {
        if (!$conversation->isOwner(auth()->user())) {
            return back()->with('error', 'No tienes permisos.');
        }

        $request->validate([
            'type' => 'required|in:email,user',
            'emails' => 'required_if:type,email|array',
            'emails.*' => 'email',
            'user_ids' => 'required_if:type,user|array',
            'user_ids.*' => 'exists:users,id',
            'message' => 'nullable|string|max:500',
        ]);

        $invitedCount = 0;

        if ($request->type === 'email') {
            foreach ($request->emails as $email) {
                // Check if already invited
                $existing = Invitation::where('conversation_id', $conversation->id)
                    ->where('email', $email)
                    ->where('status', 'pending')
                    ->first();

                if ($existing) {
                    continue;
                }

                $invitation = Invitation::create([
                    'conversation_id' => $conversation->id,
                    'inviter_id' => auth()->id(),
                    'email' => $email,
                    'message' => $request->message,
                ]);

                // TODO: Send email with invitation link

                $invitedCount++;
            }
        } else {
            foreach ($request->user_ids as $userId) {
                // Check if user is already a participant
                if ($conversation->participants()->where('users.id', $userId)->exists()) {
                    continue;
                }

                // Check if already invited
                $existing = Invitation::where('conversation_id', $conversation->id)
                    ->where('invitee_id', $userId)
                    ->where('status', 'pending')
                    ->first();

                if ($existing) {
                    continue;
                }

                $invitation = Invitation::create([
                    'conversation_id' => $conversation->id,
                    'inviter_id' => auth()->id(),
                    'invitee_id' => $userId,
                    'message' => $request->message,
                ]);

                // TODO: Send notification to user

                $invitedCount++;
            }
        }

        return redirect()->route('conversations.participants.index', $conversation)
            ->with('success', "{$invitedCount} invitaciones enviadas.");
    }

    /**
     * Accept an invitation
     */
    public function acceptInvitation($token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        if (!$invitation->isPending()) {
            return redirect()->route('conversations.show', $invitation->conversation)
                ->with('error', 'Esta invitación ya no es válida.');
        }

        if ($invitation->isExpired()) {
            $invitation->update(['status' => 'expired']);
            return redirect()->route('conversations.show', $invitation->conversation)
                ->with('error', 'Esta invitación ha expirado.');
        }

        // Check if conversation is full
        if ($invitation->conversation->isFull()) {
            return redirect()->route('conversations.show', $invitation->conversation)
                ->with('error', 'Esta conversación está llena.');
        }

        // Accept the invitation
        $invitation->accept();

        // Create participation
        Participation::create([
            'conversation_id' => $invitation->conversation_id,
            'user_id' => auth()->id(),
            'status' => $invitation->conversation->require_approval ? 'pending' : 'accepted',
            'role' => 'member',
            'joined_at' => now(),
        ]);

        if (!$invitation->conversation->require_approval) {
            $invitation->conversation->increment('current_participants');
        }

        return redirect()->route('conversations.show', $invitation->conversation)
            ->with('success', 'Te has unido a la conversación.');
    }

    /**
     * Reject an invitation
     */
    public function rejectInvitation($token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        if (!$invitation->isPending()) {
            return redirect()->route('conversations.index')
                ->with('error', 'Esta invitación ya no es válida.');
        }

        $invitation->reject();

        return redirect()->route('conversations.index')
            ->with('success', 'Invitación rechazada.');
    }
}
