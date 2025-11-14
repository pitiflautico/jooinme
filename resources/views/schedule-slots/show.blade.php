<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Sesión: {{ $scheduleSlot->title ?? $conversation->title }}</h2>
            <a href="{{ route('schedule-slots.index', $conversation) }}" class="btn btn-secondary btn-sm">
                <i class="ti ti-arrow-left me-1"></i>
                Volver al calendario
            </a>
        </div>
    </x-slot>

    <div class="row g-4">
        <!-- Session Details -->
        <div class="col-lg-8">
            <x-backend.card class="mb-4">
                <div class="card-body">
                    <h3 class="h5 mb-4">Detalles de la Sesión</h3>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2">
                                <i class="ti ti-calendar text-muted"></i>
                                <span>{{ $scheduleSlot->scheduled_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2">
                                <i class="ti ti-clock text-muted"></i>
                                <span>{{ $scheduleSlot->duration_minutes }} minutos</span>
                            </div>
                        </div>

                        @if($scheduleSlot->meeting_url)
                            <div class="col-md-12">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ti ti-world text-muted"></i>
                                    <a href="{{ $scheduleSlot->meeting_url }}" target="_blank" class="text-primary">
                                        {{ $scheduleSlot->meeting_url }}
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if($scheduleSlot->location)
                            <div class="col-md-12">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ti ti-map-pin text-muted"></i>
                                    <span>{{ $scheduleSlot->location }}</span>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if($scheduleSlot->description)
                        <div class="mt-4">
                            <h4 class="h6 mb-2">Descripción</h4>
                            <p class="text-muted">{{ $scheduleSlot->description }}</p>
                        </div>
                    @endif
                </div>
            </x-backend.card>

            <!-- Attendees -->
            <x-backend.card>
                <div class="card-header">
                    <h3 class="h5 mb-0">
                        Asistentes ({{ $scheduleSlot->attendances->where('status', 'confirmed')->count() }}
                        @if($scheduleSlot->max_attendees) / {{ $scheduleSlot->max_attendees }} @endif)
                    </h3>
                </div>
                <div class="card-body">
                    @php
                        $confirmed = $scheduleSlot->attendances->where('status', 'confirmed');
                        $tentative = $scheduleSlot->attendances->where('status', 'tentative');
                    @endphp

                    @if($confirmed->count() > 0)
                        <div class="mb-4">
                            <h4 class="h6 text-muted mb-3">Confirmados</h4>
                            <div class="row g-3">
                                @foreach($confirmed as $attendance)
                                    <div class="col-md-6">
                                        <div class="card border-success bg-light">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="avatar bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                        {{ substr($attendance->user->name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <p class="mb-0 fw-medium">{{ $attendance->user->name }}</p>
                                                        <p class="text-muted small mb-0">Confirmó {{ $attendance->rsvp_at?->diffForHumans() }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($tentative->count() > 0)
                        <div>
                            <h4 class="h6 text-muted mb-3">Tal vez</h4>
                            <div class="row g-3">
                                @foreach($tentative as $attendance)
                                    <div class="col-md-6">
                                        <div class="card border-warning bg-light">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="avatar bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                        {{ substr($attendance->user->name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <p class="mb-0 fw-medium">{{ $attendance->user->name }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($confirmed->count() === 0 && $tentative->count() === 0)
                        <div class="text-center py-5 text-muted">
                            <i class="ti ti-users-off" style="font-size: 3rem;"></i>
                            <p class="mt-3">Nadie ha confirmado asistencia aún</p>
                        </div>
                    @endif
                </div>
            </x-backend.card>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <x-backend.card class="mb-4">
                <div class="card-body">
                    <h3 class="h5 mb-4">Tu Asistencia</h3>

                    @if($userAttendance)
                        <x-backend.alert type="{{ $userAttendance->status === 'confirmed' ? 'success' : ($userAttendance->status === 'tentative' ? 'warning' : 'danger') }}" class="mb-4">
                            Tu respuesta: {{ ucfirst($userAttendance->status) }}
                        </x-backend.alert>
                    @endif

                    <form action="{{ route('schedule-slots.rsvp', $scheduleSlot) }}" method="POST">
                        @csrf
                        <div class="d-grid gap-2">
                            <button type="submit" name="status" value="confirmed" class="btn btn-success">
                                <i class="ti ti-check me-1"></i>
                                Confirmar Asistencia
                            </button>
                            <button type="submit" name="status" value="tentative" class="btn btn-warning">
                                <i class="ti ti-help me-1"></i>
                                Tal vez
                            </button>
                            <button type="submit" name="status" value="declined" class="btn btn-danger">
                                <i class="ti ti-x me-1"></i>
                                No asistiré
                            </button>
                        </div>
                    </form>
                </div>
            </x-backend.card>

            <x-backend.card>
                <div class="card-body">
                    <h3 class="h5 mb-3">Añadir a calendario</h3>
                    <div class="d-grid gap-2">
                        <a href="{{ route('schedule-slots.export-ical', $scheduleSlot) }}" class="btn btn-secondary">
                            <i class="ti ti-calendar-event me-1"></i>
                            Descargar .ics
                        </a>
                        <a href="{{ route('schedule-slots.google-calendar', $scheduleSlot) }}" target="_blank" class="btn btn-info">
                            <i class="ti ti-brand-google me-1"></i>
                            Añadir a Google Calendar
                        </a>
                    </div>
                </div>
            </x-backend.card>
        </div>
    </div>
</x-app-layout>
