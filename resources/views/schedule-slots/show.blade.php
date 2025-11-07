<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Sesión: {{ $scheduleSlot->title ?? $conversation->title }}
            </h2>
            <a href="{{ route('schedule-slots.index', $conversation) }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition text-sm">
                ← Volver al calendario
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Session Details -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-8">
                <div class="grid md:grid-cols-2 gap-8 mb-8">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Detalles de la Sesión</h3>

                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-300">{{ $scheduleSlot->scheduled_at->format('d/m/Y H:i') }}</span>
                            </div>

                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-300">{{ $scheduleSlot->duration_minutes }} minutos</span>
                            </div>

                            @if($scheduleSlot->meeting_url)
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                    </svg>
                                    <a href="{{ $scheduleSlot->meeting_url }}" target="_blank" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                        {{ $scheduleSlot->meeting_url }}
                                    </a>
                                </div>
                            @endif

                            @if($scheduleSlot->location)
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">{{ $scheduleSlot->location }}</span>
                                </div>
                            @endif
                        </div>

                        @if($scheduleSlot->description)
                            <div class="mt-6">
                                <h4 class="font-medium text-gray-900 dark:text-white mb-2">Descripción</h4>
                                <p class="text-gray-700 dark:text-gray-300">{{ $scheduleSlot->description }}</p>
                            </div>
                        @endif
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Tu Asistencia</h3>

                        @if($userAttendance)
                            <div class="mb-4 p-4 bg-{{ $userAttendance->status === 'confirmed' ? 'green' : ($userAttendance->status === 'tentative' ? 'yellow' : 'red') }}-100 dark:bg-{{ $userAttendance->status === 'confirmed' ? 'green' : ($userAttendance->status === 'tentative' ? 'yellow' : 'red') }}-900/20 rounded-lg border border-{{ $userAttendance->status === 'confirmed' ? 'green' : ($userAttendance->status === 'tentative' ? 'yellow' : 'red') }}-300 dark:border-{{ $userAttendance->status === 'confirmed' ? 'green' : ($userAttendance->status === 'tentative' ? 'yellow' : 'red') }}-700">
                                <p class="text-sm font-medium text-{{ $userAttendance->status === 'confirmed' ? 'green' : ($userAttendance->status === 'tentative' ? 'yellow' : 'red') }}-800 dark:text-{{ $userAttendance->status === 'confirmed' ? 'green' : ($userAttendance->status === 'tentative' ? 'yellow' : 'red') }}-200">
                                    Tu respuesta: {{ ucfirst($userAttendance->status) }}
                                </p>
                            </div>
                        @endif

                        <form action="{{ route('schedule-slots.rsvp', $scheduleSlot) }}" method="POST" class="space-y-3">
                            @csrf
                            <button type="submit" name="status" value="confirmed" class="w-full bg-green-600 text-white px-4 py-3 rounded-lg hover:bg-green-700 transition font-medium">
                                ✓ Confirmar Asistencia
                            </button>
                            <button type="submit" name="status" value="tentative" class="w-full bg-yellow-600 text-white px-4 py-3 rounded-lg hover:bg-yellow-700 transition font-medium">
                                ? Tal vez
                            </button>
                            <button type="submit" name="status" value="declined" class="w-full bg-red-600 text-white px-4 py-3 rounded-lg hover:bg-red-700 transition font-medium">
                                ✗ No asistiré
                            </button>
                        </form>

                        <div class="mt-6 space-y-2">
                            <a href="{{ route('schedule-slots.export-ical', $scheduleSlot) }}" class="w-full bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition text-center block">
                                📅 Descargar .ics
                            </a>
                            <a href="{{ route('schedule-slots.google-calendar', $scheduleSlot) }}" target="_blank" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-center block">
                                Añadir a Google Calendar
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendees -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Asistentes ({{ $scheduleSlot->attendances->where('status', 'confirmed')->count() }}
                        @if($scheduleSlot->max_attendees) / {{ $scheduleSlot->max_attendees }} @endif)
                    </h3>
                </div>
                <div class="p-6">
                    @php
                        $confirmed = $scheduleSlot->attendances->where('status', 'confirmed');
                        $tentative = $scheduleSlot->attendances->where('status', 'tentative');
                    @endphp

                    @if($confirmed->count() > 0)
                        <div class="mb-6">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Confirmados</h4>
                            <div class="grid md:grid-cols-2 gap-3">
                                @foreach($confirmed as $attendance)
                                    <div class="flex items-center gap-3 p-3 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800">
                                        <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center text-white font-bold">
                                            {{ substr($attendance->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $attendance->user->name }}</p>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">Confirmó {{ $attendance->rsvp_at?->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($tentative->count() > 0)
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Tal vez</h4>
                            <div class="grid md:grid-cols-2 gap-3">
                                @foreach($tentative as $attendance)
                                    <div class="flex items-center gap-3 p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                                        <div class="w-10 h-10 bg-yellow-600 rounded-full flex items-center justify-center text-white font-bold">
                                            {{ substr($attendance->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $attendance->user->name }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($confirmed->count() === 0 && $tentative->count() === 0)
                        <p class="text-center text-gray-500 dark:text-gray-400 py-8">Nadie ha confirmado asistencia aún</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
