<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\ScheduleSlot;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ScheduleSlotController extends Controller
{
    /**
     * Display calendar view for a conversation
     */
    public function index(Conversation $conversation, Request $request)
    {
        // Check if user is owner or participant
        if (!$conversation->isOwner(auth()->user()) && !$conversation->isMember(auth()->user())) {
            return redirect()->route('conversations.show', $conversation)
                ->with('error', 'No tienes permisos para ver el calendario.');
        }

        $view = $request->get('view', 'month'); // day, week, month

        $slots = $conversation->scheduleSlots()
            ->with('attendances')
            ->orderBy('scheduled_at')
            ->get();

        return view('schedule-slots.index', compact('conversation', 'slots', 'view'));
    }

    /**
     * Show form to create a new session
     */
    public function create(Conversation $conversation)
    {
        if (!$conversation->isOwner(auth()->user())) {
            return redirect()->route('conversations.show', $conversation)
                ->with('error', 'Solo el organizador puede crear sesiones.');
        }

        return view('schedule-slots.create', compact('conversation'));
    }

    /**
     * Store a new session
     */
    public function store(Request $request, Conversation $conversation)
    {
        if (!$conversation->isOwner(auth()->user())) {
            return back()->with('error', 'No tienes permisos.');
        }

        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'scheduled_at' => 'required|date|after:now',
            'duration_minutes' => 'required|integer|min:15|max:480',
            'meeting_url' => 'nullable|url',
            'location' => 'nullable|string|max:255',
            'max_attendees' => 'nullable|integer|min:1',
        ]);

        $slot = ScheduleSlot::create([
            'conversation_id' => $conversation->id,
            'title' => $request->title ?? $conversation->title,
            'description' => $request->description,
            'scheduled_at' => $request->scheduled_at,
            'duration_minutes' => $request->duration_minutes,
            'meeting_url' => $request->meeting_url ?? $conversation->meeting_url,
            'location' => $request->location ?? $conversation->location,
            'max_attendees' => $request->max_attendees ?? $conversation->max_participants,
            'status' => 'scheduled',
        ]);

        return redirect()->route('schedule-slots.show', $slot)
            ->with('success', 'Sesión creada exitosamente.');
    }

    /**
     * Show a specific session
     */
    public function show(ScheduleSlot $scheduleSlot)
    {
        $conversation = $scheduleSlot->conversation;

        if (!$conversation->isOwner(auth()->user()) && !$conversation->isMember(auth()->user())) {
            return redirect()->route('conversations.show', $conversation)
                ->with('error', 'No tienes permisos.');
        }

        $scheduleSlot->load(['conversation', 'attendances.user']);

        $userAttendance = $scheduleSlot->attendances()->where('user_id', auth()->id())->first();

        return view('schedule-slots.show', compact('scheduleSlot', 'conversation', 'userAttendance'));
    }

    /**
     * Show form to edit a session
     */
    public function edit(ScheduleSlot $scheduleSlot)
    {
        $conversation = $scheduleSlot->conversation;

        if (!$conversation->isOwner(auth()->user())) {
            return redirect()->route('schedule-slots.show', $scheduleSlot)
                ->with('error', 'Solo el organizador puede editar sesiones.');
        }

        return view('schedule-slots.edit', compact('scheduleSlot', 'conversation'));
    }

    /**
     * Update a session
     */
    public function update(Request $request, ScheduleSlot $scheduleSlot)
    {
        $conversation = $scheduleSlot->conversation;

        if (!$conversation->isOwner(auth()->user())) {
            return back()->with('error', 'No tienes permisos.');
        }

        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'scheduled_at' => 'required|date',
            'duration_minutes' => 'required|integer|min:15|max:480',
            'meeting_url' => 'nullable|url',
            'location' => 'nullable|string|max:255',
            'max_attendees' => 'nullable|integer|min:1',
            'status' => 'required|in:scheduled,in_progress,completed,cancelled',
        ]);

        $scheduleSlot->update($request->all());

        return redirect()->route('schedule-slots.show', $scheduleSlot)
            ->with('success', 'Sesión actualizada exitosamente.');
    }

    /**
     * Delete a session
     */
    public function destroy(ScheduleSlot $scheduleSlot)
    {
        $conversation = $scheduleSlot->conversation;

        if (!$conversation->isOwner(auth()->user())) {
            return back()->with('error', 'No tienes permisos.');
        }

        $scheduleSlot->delete();

        return redirect()->route('schedule-slots.index', $conversation)
            ->with('success', 'Sesión eliminada.');
    }

    /**
     * RSVP to a session
     */
    public function rsvp(Request $request, ScheduleSlot $scheduleSlot)
    {
        $conversation = $scheduleSlot->conversation;

        if (!$conversation->isMember(auth()->user())) {
            return back()->with('error', 'Debes ser participante para confirmar asistencia.');
        }

        $request->validate([
            'status' => 'required|in:confirmed,tentative,declined',
        ]);

        Attendance::updateOrCreate(
            [
                'schedule_slot_id' => $scheduleSlot->id,
                'user_id' => auth()->id(),
            ],
            [
                'conversation_id' => $conversation->id,
                'status' => $request->status,
                'rsvp_at' => now(),
            ]
        );

        $message = match($request->status) {
            'confirmed' => 'Asistencia confirmada',
            'tentative' => 'Marcado como tentativo',
            'declined' => 'Asistencia declinada',
        };

        return back()->with('success', $message);
    }

    /**
     * Export session to iCal format
     */
    public function exportIcal(ScheduleSlot $scheduleSlot)
    {
        $conversation = $scheduleSlot->conversation;

        if (!$conversation->isMember(auth()->user()) && !$conversation->isOwner(auth()->user())) {
            abort(403);
        }

        $ical = $this->generateIcal($scheduleSlot);

        $filename = \Str::slug($scheduleSlot->title ?? $conversation->title) . '.ics';

        return Response::make($ical, 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Generate iCal content
     */
    private function generateIcal(ScheduleSlot $slot): string
    {
        $conversation = $slot->conversation;

        $dtStart = $slot->scheduled_at->format('Ymd\THis\Z');
        $dtEnd = $slot->scheduled_at->copy()->addMinutes($slot->duration_minutes)->format('Ymd\THis\Z');
        $dtStamp = now()->format('Ymd\THis\Z');
        $uid = $slot->id . '@joinme.app';

        $location = $slot->location ?: ($slot->meeting_url ? 'Online' : '');
        $description = $slot->description ?: $conversation->description;

        // Add meeting URL to description if present
        if ($slot->meeting_url) {
            $description .= "\n\nUnirse: " . $slot->meeting_url;
        }

        $ical = "BEGIN:VCALENDAR\r\n";
        $ical .= "VERSION:2.0\r\n";
        $ical .= "PRODID:-//JoinMe//Calendar//ES\r\n";
        $ical .= "BEGIN:VEVENT\r\n";
        $ical .= "UID:{$uid}\r\n";
        $ical .= "DTSTAMP:{$dtStamp}\r\n";
        $ical .= "DTSTART:{$dtStart}\r\n";
        $ical .= "DTEND:{$dtEnd}\r\n";
        $ical .= "SUMMARY:" . $this->escapeString($slot->title ?? $conversation->title) . "\r\n";

        if ($description) {
            $ical .= "DESCRIPTION:" . $this->escapeString($description) . "\r\n";
        }

        if ($location) {
            $ical .= "LOCATION:" . $this->escapeString($location) . "\r\n";
        }

        if ($slot->meeting_url) {
            $ical .= "URL:" . $slot->meeting_url . "\r\n";
        }

        $ical .= "STATUS:CONFIRMED\r\n";
        $ical .= "END:VEVENT\r\n";
        $ical .= "END:VCALENDAR\r\n";

        return $ical;
    }

    /**
     * Escape string for iCal format
     */
    private function escapeString(string $string): string
    {
        $string = str_replace(['\\', ',', ';', "\n"], ['\\\\', '\\,', '\\;', '\\n'], $string);
        return $string;
    }

    /**
     * Add to Google Calendar
     */
    public function googleCalendar(ScheduleSlot $scheduleSlot)
    {
        $conversation = $scheduleSlot->conversation;

        if (!$conversation->isMember(auth()->user()) && !$conversation->isOwner(auth()->user())) {
            abort(403);
        }

        $title = urlencode($scheduleSlot->title ?? $conversation->title);
        $description = urlencode($scheduleSlot->description ?? $conversation->description);
        $location = urlencode($scheduleSlot->location ?? $scheduleSlot->meeting_url ?? '');

        $start = $scheduleSlot->scheduled_at->format('Ymd\THis\Z');
        $end = $scheduleSlot->scheduled_at->copy()->addMinutes($scheduleSlot->duration_minutes)->format('Ymd\THis\Z');

        $url = "https://calendar.google.com/calendar/render?action=TEMPLATE";
        $url .= "&text={$title}";
        $url .= "&dates={$start}/{$end}";
        $url .= "&details={$description}";
        $url .= "&location={$location}";

        return redirect()->away($url);
    }
}
