<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Dashboard de Organizador</h2>
        </div>
    </x-slot>

    <!-- Overall Stats -->
    <div class="row g-4 mb-4">
        <div class="col-md">
            <x-backend.card>
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-lg rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center">
                            <i class="ti ti-message-circle text-primary" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-1">Conversaciones</p>
                        <h3 class="mb-0 fw-bold">{{ $stats['total_conversations'] }}</h3>
                        <small class="text-muted">{{ $stats['active_conversations'] }} activas</small>
                    </div>
                </div>
            </x-backend.card>
        </div>

        <div class="col-md">
            <x-backend.card>
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-lg rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center">
                            <i class="ti ti-users text-success" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-1">Participantes</p>
                        <h3 class="mb-0 fw-bold">{{ $stats['total_participants'] }}</h3>
                        <small class="text-muted">Total acumulado</small>
                    </div>
                </div>
            </x-backend.card>
        </div>

        <div class="col-md">
            <x-backend.card>
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-lg rounded-circle bg-info bg-opacity-10 d-flex align-items-center justify-content-center">
                            <i class="ti ti-calendar text-info" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-1">Sesiones</p>
                        <h3 class="mb-0 fw-bold">{{ $stats['total_sessions'] }}</h3>
                        <small class="text-muted">Programadas</small>
                    </div>
                </div>
            </x-backend.card>
        </div>

        <div class="col-md">
            <x-backend.card>
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-lg rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center">
                            <i class="ti ti-star text-warning" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-1">Valoración</p>
                        <h3 class="mb-0 fw-bold">{{ $stats['average_rating'] }}/5</h3>
                        <small class="text-muted">Promedio</small>
                    </div>
                </div>
            </x-backend.card>
        </div>

        <div class="col-md">
            <x-backend.card>
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-lg rounded-circle bg-danger bg-opacity-10 d-flex align-items-center justify-content-center">
                            <i class="ti ti-alert-circle text-danger" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-1">Pendientes</p>
                        <h3 class="mb-0 fw-bold">{{ $pendingApprovals->count() }}</h3>
                        <small class="text-muted">Solicitudes</small>
                    </div>
                </div>
            </x-backend.card>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Pending Approvals -->
        @if($pendingApprovals->count() > 0)
            <div class="col-lg-6">
                <x-backend.card title="Solicitudes Pendientes">
                    <div class="list-group list-group-flush" style="max-height: 400px; overflow-y: auto;">
                        @foreach($pendingApprovals as $approval)
                            <div class="list-group-item d-flex align-items-center bg-warning bg-opacity-10 border-warning mb-2 rounded">
                                <div class="avatar rounded-circle bg-warning text-white me-3">
                                    {{ substr($approval->user->name, 0, 1) }}
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ $approval->user->name }}</h6>
                                    <small class="text-muted">{{ $approval->conversation->title }}</small>
                                </div>
                                <a href="{{ route('conversations.participants.index', $approval->conversation) }}" class="btn btn-sm btn-primary">
                                    Ver <i class="ti ti-arrow-right ms-1"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </x-backend.card>
            </div>
        @endif

        <!-- Upcoming Sessions -->
        <div class="col-lg-{{ $pendingApprovals->count() > 0 ? '6' : '12' }}">
            <x-backend.card title="Próximas Sesiones">
                <div class="list-group list-group-flush" style="max-height: 400px; overflow-y: auto;">
                    @forelse($upcomingSessions as $session)
                        <div class="list-group-item bg-info bg-opacity-10 border-info mb-2 rounded">
                            <h6 class="mb-1">{{ $session->conversation->title }}</h6>
                            <p class="mb-2 text-muted small">
                                <i class="ti ti-calendar me-1"></i>
                                {{ $session->scheduled_at->format('d/m/Y H:i') }}
                            </p>
                            <a href="{{ route('schedule-slots.show', $session) }}" class="btn btn-sm btn-info">
                                Ver detalles <i class="ti ti-arrow-right ms-1"></i>
                            </a>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="ti ti-calendar-off text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2 mb-0">No hay sesiones próximas</p>
                        </div>
                    @endforelse
                </div>
            </x-backend.card>
        </div>
    </div>

    <!-- Performance by Conversation -->
    @if(count($conversationPerformance) > 0)
        <x-backend.card title="Rendimiento por Conversación" class="mb-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Conversación</th>
                            <th>Sesiones</th>
                            <th>Asistencia Promedio</th>
                            <th>Valoración</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($conversationPerformance as $perf)
                            <tr>
                                <td>
                                    <h6 class="mb-0">{{ $perf['conversation']->title }}</h6>
                                    <small class="text-muted">{{ $perf['conversation']->participations_count }} participantes</small>
                                </td>
                                <td>{{ $perf['total_sessions'] }}</td>
                                <td>{{ $perf['average_attendance'] }}</td>
                                <td>
                                    <span class="text-warning">
                                        <i class="ti ti-star-filled"></i> {{ $perf['rating'] }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('conversations.show', $perf['conversation']) }}" class="btn btn-sm btn-primary">
                                        Ver <i class="ti ti-arrow-right ms-1"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-backend.card>
    @endif

    <!-- Recent Feedback -->
    @if($recentFeedback->count() > 0)
        <x-backend.card title="Feedback Reciente" class="mb-4">
            @foreach($recentFeedback as $feedback)
                <div class="d-flex align-items-start p-3 rounded bg-light mb-3">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <h6 class="mb-0">{{ $feedback->user->name }}</h6>
                            <span class="text-warning">
                                @for($i = 0; $i < $feedback->rating; $i++)<i class="ti ti-star-filled"></i>@endfor
                            </span>
                        </div>
                        <p class="text-muted small mb-1">{{ $feedback->conversation->title }}</p>
                        @if($feedback->comment)
                            <p class="mb-0 small">{{ Str::limit($feedback->comment, 100) }}</p>
                        @endif
                    </div>
                    <span class="badge bg-secondary">{{ $feedback->created_at->diffForHumans() }}</span>
                </div>
            @endforeach
        </x-backend.card>
    @endif

    <!-- Growth Chart (Text-based) -->
    <x-backend.card title="Crecimiento (Últimos 6 Meses)" class="mb-4">
        <div class="row g-3">
            @foreach($monthlyData as $month)
                <div class="col-md-2 text-center">
                    <small class="text-muted d-block mb-2">{{ $month['month'] }}</small>
                    <div class="bg-primary bg-opacity-10 rounded p-3 mb-2">
                        <h4 class="mb-0 text-primary fw-bold">{{ $month['participants'] }}</h4>
                        <small class="text-muted">participantes</small>
                    </div>
                    <div class="bg-info bg-opacity-10 rounded p-3">
                        <h4 class="mb-0 text-info fw-bold">{{ $month['sessions'] }}</h4>
                        <small class="text-muted">sesiones</small>
                    </div>
                </div>
            @endforeach
        </div>
    </x-backend.card>

    <!-- My Conversations -->
    <x-backend.card title="Mis Conversaciones" class="mb-4">
        <div class="row g-3">
            @forelse($conversations as $conversation)
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded hover-card">
                        <div class="d-flex align-items-start justify-content-between mb-2">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">
                                    <a href="{{ route('conversations.show', $conversation) }}" class="text-decoration-none text-dark">
                                        {{ $conversation->title }}
                                    </a>
                                </h6>
                                <div class="d-flex align-items-center gap-3 small text-muted">
                                    <span><i class="ti ti-users me-1"></i>{{ $conversation->participations_count }}</span>
                                    <span><i class="ti ti-calendar me-1"></i>{{ $conversation->schedule_slots_count }}</span>
                                    <span><i class="ti ti-star me-1"></i>{{ $conversation->feedback_count }}</span>
                                </div>
                            </div>
                            <span class="badge bg-{{ $conversation->is_active ? 'success' : 'secondary' }}">
                                {{ $conversation->is_active ? 'Activa' : 'Inactiva' }}
                            </span>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('conversations.participants.index', $conversation) }}" class="btn btn-sm btn-outline-primary">
                                Gestionar
                            </a>
                            <a href="{{ route('schedule-slots.index', $conversation) }}" class="btn btn-sm btn-outline-info">
                                Calendario
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="ti ti-message-off text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-2 mb-0">No has creado ninguna conversación aún</p>
                    <a href="{{ route('conversations.create') }}" class="btn btn-primary mt-3">
                        Crear tu primera conversación
                    </a>
                </div>
            @endforelse
        </div>
    </x-backend.card>
</x-app-layout>
