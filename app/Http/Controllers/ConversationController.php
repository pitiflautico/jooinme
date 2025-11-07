<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Topic;
use App\Models\Participation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    /**
     * Display a listing of conversations (Explorer)
     */
    public function index(Request $request)
    {
        $query = Conversation::with(['owner', 'topic', 'participations'])
            ->where('is_active', true);

        // Filtrar por búsqueda
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Filtrar por topic
        if ($request->filled('topic')) {
            $query->where('topic_id', $request->topic);
        }

        // Filtrar por tipo (online/presencial/híbrido)
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filtrar por privacidad
        if ($request->filled('privacy')) {
            $query->where('privacy', $request->privacy);
        }

        // Filtrar por frecuencia
        if ($request->filled('frequency')) {
            $query->where('frequency', $request->frequency);
        }

        // Ordenar
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'popular':
                $query->orderBy('current_participants', 'desc');
                break;
            case 'upcoming':
                $query->whereNotNull('starts_at')
                      ->where('starts_at', '>=', now())
                      ->orderBy('starts_at', 'asc');
                break;
            default:
                $query->latest();
        }

        $conversations = $query->paginate(12);
        $topics = Topic::where('is_active', true)->get();

        return view('conversations.index', compact('conversations', 'topics'));
    }

    /**
     * Show the form for creating a new conversation.
     */
    public function create()
    {
        $topics = Topic::where('is_active', true)->get();
        return view('conversations.create', compact('topics'));
    }

    /**
     * Store a newly created conversation.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'topic_id' => 'required|exists:topics,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'frequency' => 'required|in:once,weekly,biweekly,monthly',
            'type' => 'required|in:online,in_person,hybrid',
            'privacy' => 'required|in:public,moderated,private',
            'max_participants' => 'required|integer|min:2|max:100',
            'starts_at' => 'nullable|date|after:now',
            'ends_at' => 'nullable|date|after:starts_at',
            'location' => 'nullable|string|max:255',
            'location_details' => 'nullable|string',
            'meeting_platform' => 'nullable|string|max:100',
            'meeting_url' => 'nullable|url',
            'allow_chat' => 'boolean',
            'allow_recording' => 'boolean',
            'require_approval' => 'boolean',
            'tags' => 'nullable|array',
        ]);

        $validated['owner_id'] = Auth::id();
        $validated['current_participants'] = 1; // El creador cuenta como participante
        $validated['is_active'] = true;
        $validated['status'] = 'active';

        $conversation = Conversation::create($validated);

        // Crear participación automática del creador como host
        Participation::create([
            'user_id' => Auth::id(),
            'conversation_id' => $conversation->id,
            'status' => 'accepted',
            'role' => 'participant',
            'joined_at' => now(),
        ]);

        return redirect()->route('conversations.show', $conversation)
            ->with('success', '¡Conversación creada exitosamente!');
    }

    /**
     * Display the specified conversation.
     */
    public function show(Conversation $conversation)
    {
        $conversation->load(['owner', 'topic', 'participations.user', 'scheduleSlots' => function($query) {
            $query->where('scheduled_at', '>=', now())->orderBy('scheduled_at');
        }]);

        $isParticipant = false;
        $userParticipation = null;

        if (Auth::check()) {
            $userParticipation = $conversation->participations()
                ->where('user_id', Auth::id())
                ->first();
            $isParticipant = $userParticipation !== null;
        }

        $canJoin = Auth::check() && !$isParticipant && !$conversation->isFull();

        return view('conversations.show', compact('conversation', 'isParticipant', 'userParticipation', 'canJoin'));
    }

    /**
     * Join a conversation
     */
    public function join(Conversation $conversation)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para unirte.');
        }

        if ($conversation->isFull()) {
            return back()->with('error', 'Esta conversación está llena.');
        }

        if ($conversation->isMember(Auth::user())) {
            return back()->with('info', 'Ya eres parte de esta conversación.');
        }

        $status = $conversation->require_approval ? 'pending' : 'accepted';

        Participation::create([
            'user_id' => Auth::id(),
            'conversation_id' => $conversation->id,
            'status' => $status,
            'role' => 'participant',
            'joined_at' => $status === 'accepted' ? now() : null,
        ]);

        if ($status === 'accepted') {
            $conversation->increment('current_participants');
            return back()->with('success', '¡Te has unido a la conversación!');
        }

        return back()->with('info', 'Tu solicitud ha sido enviada. El organizador la revisará pronto.');
    }

    /**
     * Leave a conversation
     */
    public function leave(Conversation $conversation)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $participation = $conversation->participations()
            ->where('user_id', Auth::id())
            ->first();

        if (!$participation) {
            return back()->with('error', 'No eres parte de esta conversación.');
        }

        if ($conversation->isOwner(Auth::user())) {
            return back()->with('error', 'El organizador no puede abandonar su propia conversación.');
        }

        $participation->delete();
        $conversation->decrement('current_participants');

        return redirect()->route('conversations.index')
            ->with('success', 'Has abandonado la conversación.');
    }

    /**
     * Show edit form
     */
    public function edit(Conversation $conversation)
    {
        if (!$conversation->isOwner(Auth::user())) {
            abort(403, 'No tienes permiso para editar esta conversación.');
        }

        $topics = Topic::where('is_active', true)->get();
        return view('conversations.edit', compact('conversation', 'topics'));
    }

    /**
     * Update conversation
     */
    public function update(Request $request, Conversation $conversation)
    {
        if (!$conversation->isOwner(Auth::user())) {
            abort(403, 'No tienes permiso para editar esta conversación.');
        }

        $validated = $request->validate([
            'topic_id' => 'required|exists:topics,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'frequency' => 'required|in:once,weekly,biweekly,monthly',
            'type' => 'required|in:online,in_person,hybrid',
            'privacy' => 'required|in:public,moderated,private',
            'max_participants' => 'required|integer|min:2|max:100',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:starts_at',
            'location' => 'nullable|string|max:255',
            'location_details' => 'nullable|string',
            'meeting_platform' => 'nullable|string|max:100',
            'meeting_url' => 'nullable|url',
            'allow_chat' => 'boolean',
            'allow_recording' => 'boolean',
            'require_approval' => 'boolean',
            'tags' => 'nullable|array',
        ]);

        $conversation->update($validated);

        return redirect()->route('conversations.show', $conversation)
            ->with('success', 'Conversación actualizada exitosamente.');
    }

    /**
     * Delete conversation
     */
    public function destroy(Conversation $conversation)
    {
        if (!$conversation->isOwner(Auth::user())) {
            abort(403, 'No tienes permiso para eliminar esta conversación.');
        }

        $conversation->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Conversación eliminada exitosamente.');
    }
}
