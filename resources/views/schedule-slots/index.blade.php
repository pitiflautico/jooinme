<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Calendario: {{ $conversation->title }}</h2>
            <div class="d-flex gap-2">
                @if($conversation->isOwner(auth()->user()))
                    <a href="{{ route('schedule-slots.create', $conversation) }}" class="btn btn-primary btn-sm">
                        <i class="ti ti-plus me-1"></i>
                        Nueva sesión
                    </a>
                @endif
                <a href="{{ route('conversations.show', $conversation) }}" class="btn btn-secondary btn-sm">
                    <i class="ti ti-arrow-left me-1"></i>
                    Volver
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Upcoming Sessions -->
    <x-backend.card class="mb-4">
        <div class="card-header">
            <h3 class="h5 mb-0">Próximas Sesiones</h3>
        </div>
        <div class="card-body">
            @php
                $upcoming = $slots->filter(fn($slot) => $slot->scheduled_at->isFuture())->sortBy('scheduled_at');
            @endphp

            @forelse($upcoming as $slot)
                <div class="card border-primary bg-light mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="flex-fill">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <h4 class="h5 mb-0">{{ $slot->title ?? $conversation->title }}</h4>
                                    <span class="badge bg-success">{{ ucfirst($slot->status) }}</span>
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center gap-2 text-muted">
                                            <i class="ti ti-calendar"></i>
                                            <span class="fw-medium">{{ $slot->scheduled_at->format('d/m/Y') }}</span>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center gap-2 text-muted">
                                            <i class="ti ti-clock"></i>
                                            <span>{{ $slot->scheduled_at->format('H:i') }} ({{ $slot->duration_minutes }} min)</span>
                                        </div>
                                    </div>

                                    @if($slot->meeting_url)
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center gap-2 text-muted">
                                                <i class="ti ti-world"></i>
                                                <a href="{{ $slot->meeting_url }}" target="_blank" class="text-primary">
                                                    Unirse online
                                                </a>
                                            </div>
                                        </div>
                                    @endif

                                    @if($slot->location)
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center gap-2 text-muted">
                                                <i class="ti ti-map-pin"></i>
                                                <span>{{ $slot->location }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                @if($slot->description)
                                    <p class="text-muted small mb-3">{{ $slot->description }}</p>
                                @endif

                                <div class="small text-muted">
                                    <i class="ti ti-users me-1"></i>
                                    {{ $slot->attendances->where('status', 'confirmed')->count() }} confirmados
                                    @if($slot->max_attendees)
                                        de {{ $slot->max_attendees }}
                                    @endif
                                </div>
                            </div>

                            <div class="d-flex flex-column gap-2 ms-3">
                                <a href="{{ route('schedule-slots.show', $slot) }}" class="btn btn-primary btn-sm">
                                    Ver detalles
                                </a>
                                <a href="{{ route('schedule-slots.export-ical', $slot) }}" class="btn btn-secondary btn-sm">
                                    <i class="ti ti-calendar-event me-1"></i>
                                    .ics
                                </a>
                                <a href="{{ route('schedule-slots.google-calendar', $slot) }}" target="_blank" class="btn btn-info btn-sm">
                                    Google
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="ti ti-calendar-off text-muted" style="font-size: 4rem;"></i>
                    <p class="mt-3 text-muted">No hay sesiones próximas programadas</p>
                    @if($conversation->isOwner(auth()->user()))
                        <a href="{{ route('schedule-slots.create', $conversation) }}" class="btn btn-primary mt-3">
                            <i class="ti ti-plus me-1"></i>
                            Crear primera sesión
                        </a>
                    @endif
                </div>
            @endforelse
        </div>
    </x-backend.card>

    <!-- Past Sessions -->
    @php
        $past = $slots->filter(fn($slot) => $slot->scheduled_at->isPast())->sortByDesc('scheduled_at');
    @endphp

    @if($past->count() > 0)
        <x-backend.card>
            <div class="card-header">
                <h3 class="h5 mb-0">Sesiones Pasadas</h3>
            </div>
            <div class="card-body">
                @foreach($past as $slot)
                    <div class="card bg-light mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="flex-fill">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <h4 class="h6 mb-0">{{ $slot->title ?? $conversation->title }}</h4>
                                        <span class="badge bg-secondary">{{ ucfirst($slot->status) }}</span>
                                    </div>
                                    <p class="text-muted small mb-0">
                                        {{ $slot->scheduled_at->format('d/m/Y H:i') }}
                                        · {{ $slot->attendances->where('status', 'confirmed')->count() }} asistentes
                                    </p>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('schedule-slots.show', $slot) }}" class="btn btn-sm btn-outline-primary">
                                        Ver detalles
                                    </a>
                                    @if($slot->status === 'completed')
                                        <a href="{{ route('feedback.create', $slot) }}" class="btn btn-sm btn-success">
                                            Valorar
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-backend.card>
    @endif
</x-app-layout>
