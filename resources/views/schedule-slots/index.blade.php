<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Calendario: {{ $conversation->title }}
            </h2>
            <div class="flex gap-2">
                @if($conversation->isOwner(auth()->user()))
                    <a href="{{ route('schedule-slots.create', $conversation) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition text-sm">
                        + Nueva sesión
                    </a>
                @endif
                <a href="{{ route('conversations.show', $conversation) }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition text-sm">
                    ← Volver
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Upcoming Sessions -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Próximas Sesiones</h3>
                </div>
                <div class="p-6">
                    @php
                        $upcoming = $slots->filter(fn($slot) => $slot->scheduled_at->isFuture())->sortBy('scheduled_at');
                    @endphp

                    @forelse($upcoming as $slot)
                        <div class="mb-4 last:mb-0 p-6 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 rounded-lg border border-indigo-200 dark:border-indigo-800">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h4 class="text-xl font-semibold text-gray-900 dark:text-white">
                                            {{ $slot->title ?? $conversation->title }}
                                        </h4>
                                        <span class="bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400 px-3 py-1 rounded-full text-xs font-medium">
                                            {{ ucfirst($slot->status) }}
                                        </span>
                                    </div>

                                    <div class="grid md:grid-cols-2 gap-4 mb-3">
                                        <div class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span class="font-medium">{{ $slot->scheduled_at->format('d/m/Y') }}</span>
                                        </div>

                                        <div class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span>{{ $slot->scheduled_at->format('H:i') }} ({{ $slot->duration_minutes }} min)</span>
                                        </div>

                                        @if($slot->meeting_url)
                                            <div class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                                </svg>
                                                <a href="{{ $slot->meeting_url }}" target="_blank" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                                    Unirse online
                                                </a>
                                            </div>
                                        @endif

                                        @if($slot->location)
                                            <div class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                </svg>
                                                <span>{{ $slot->location }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    @if($slot->description)
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">{{ $slot->description }}</p>
                                    @endif

                                    <div class="flex items-center gap-2 text-sm">
                                        <span class="text-gray-600 dark:text-gray-400">
                                            {{ $slot->attendances->where('status', 'confirmed')->count() }} confirmados
                                        </span>
                                        @if($slot->max_attendees)
                                            <span class="text-gray-600 dark:text-gray-400">de {{ $slot->max_attendees }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex flex-col gap-2 ml-4">
                                    <a href="{{ route('schedule-slots.show', $slot) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition text-sm text-center whitespace-nowrap">
                                        Ver detalles
                                    </a>
                                    <a href="{{ route('schedule-slots.export-ical', $slot) }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition text-sm text-center whitespace-nowrap">
                                        📅 .ics
                                    </a>
                                    <a href="{{ route('schedule-slots.google-calendar', $slot) }}" target="_blank" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm text-center whitespace-nowrap">
                                        Google
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="mt-4 text-gray-600 dark:text-gray-400">No hay sesiones próximas programadas</p>
                            @if($conversation->isOwner(auth()->user()))
                                <a href="{{ route('schedule-slots.create', $conversation) }}" class="mt-4 inline-block text-indigo-600 dark:text-indigo-400 hover:underline">
                                    Crear primera sesión
                                </a>
                            @endif
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Past Sessions -->
            @php
                $past = $slots->filter(fn($slot) => $slot->scheduled_at->isPast())->sortByDesc('scheduled_at');
            @endphp

            @if($past->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Sesiones Pasadas</h3>
                    </div>
                    <div class="p-6">
                        @foreach($past as $slot)
                            <div class="mb-4 last:mb-0 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-1">
                                            <h4 class="font-medium text-gray-900 dark:text-white">
                                                {{ $slot->title ?? $conversation->title }}
                                            </h4>
                                            <span class="bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 px-2 py-1 rounded text-xs">
                                                {{ ucfirst($slot->status) }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ $slot->scheduled_at->format('d/m/Y H:i') }}
                                            · {{ $slot->attendances->where('status', 'confirmed')->count() }} asistentes
                                        </p>
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="{{ route('schedule-slots.show', $slot) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                                            Ver detalles
                                        </a>
                                        @if($slot->status === 'completed')
                                            <a href="{{ route('feedback.create', $slot) }}" class="text-sm text-green-600 dark:text-green-400 hover:underline">
                                                Valorar
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
