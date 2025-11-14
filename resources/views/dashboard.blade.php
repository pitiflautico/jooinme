<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0">{{ __('Mi Dashboard') }}</h2>
            <a href="{{ route('conversations.create') }}" class="btn btn-primary">
                <i class="ti ti-plus me-2"></i>
                Nueva conversación
            </a>
        </div>
    </x-slot>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <x-backend.card>
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-lg rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center">
                            <i class="ti ti-message-circle text-primary" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-1">Conversaciones</p>
                        <h3 class="mb-0 fw-bold">{{ $stats['participations'] }}</h3>
                    </div>
                </div>
            </x-backend.card>
        </div>

        <div class="col-md-4">
            <x-backend.card>
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-lg rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center">
                            <i class="ti ti-briefcase text-success" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-1">Organizando</p>
                        <h3 class="mb-0 fw-bold">{{ $stats['organizing'] }}</h3>
                    </div>
                </div>
            </x-backend.card>
        </div>

        <div class="col-md-4">
            <x-backend.card>
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-lg rounded-circle bg-info bg-opacity-10 d-flex align-items-center justify-content-center">
                            <i class="ti ti-users text-info" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-1">Participantes totales</p>
                        <h3 class="mb-0 fw-bold">{{ $stats['total_participants'] }}</h3>
                    </div>
                </div>
            </x-backend.card>
        </div>
    </div>

    <!-- Quick Actions -->
    <x-backend.card title="Acciones rápidas" class="mb-4">
        <div class="row g-3">
            <div class="col-md-4">
                <a href="{{ route('conversations.create') }}" class="d-block p-4 border border-2 border-dashed rounded text-decoration-none hover-card">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar rounded bg-primary bg-opacity-10">
                                <i class="ti ti-plus text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-dark">Crear conversación</h6>
                            <small class="text-muted">Inicia una nueva conversación</small>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="{{ route('conversations.index') }}" class="d-block p-4 border border-2 border-dashed rounded text-decoration-none hover-card">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar rounded bg-success bg-opacity-10">
                                <i class="ti ti-search text-success"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-dark">Explorar conversaciones</h6>
                            <small class="text-muted">Busca y únete a conversaciones</small>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="{{ route('profile.edit') }}" class="d-block p-4 border border-2 border-dashed rounded text-decoration-none hover-card">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar rounded bg-info bg-opacity-10">
                                <i class="ti ti-user text-info"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-dark">Editar perfil</h6>
                            <small class="text-muted">Actualiza tu información</small>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </x-backend.card>

    <div class="row g-4">
        <!-- Mis Conversaciones -->
        <div class="col-lg-6">
            <x-backend.card>
                <x-slot name="title">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <h5 class="mb-0">Mis conversaciones</h5>
                        <a href="{{ route('conversations.index') }}" class="btn btn-sm btn-outline-primary">
                            Ver todas <i class="ti ti-arrow-right ms-1"></i>
                        </a>
                    </div>
                </x-slot>

                @forelse($myConversations as $participation)
                    <div class="d-flex align-items-start p-3 rounded bg-light mb-3 hover-card">
                        <div class="flex-grow-1">
                            <h6 class="mb-2">
                                <a href="{{ route('conversations.show', $participation->conversation) }}" class="text-decoration-none text-dark">
                                    {{ $participation->conversation->title }}
                                </a>
                            </h6>
                            <div class="d-flex align-items-center gap-2 text-sm">
                                <span class="badge bg-primary bg-opacity-10 text-primary">
                                    {{ $participation->conversation->topic->name ?? 'General' }}
                                </span>
                                <span>{{ $participation->conversation->type === 'online' ? '🌐 Online' : '📍 Presencial' }}</span>
                                <span class="text-muted">• {{ ucfirst($participation->conversation->frequency) }}</span>
                            </div>
                        </div>
                        <span class="badge {{ $participation->status === 'accepted' ? 'bg-success' : 'bg-warning' }}">
                            {{ $participation->status === 'accepted' ? 'Activo' : 'Pendiente' }}
                        </span>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="ti ti-message-off text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2 mb-0">No estás en ninguna conversación aún</p>
                        <a href="{{ route('conversations.index') }}" class="btn btn-sm btn-primary mt-3">
                            Explorar conversaciones
                        </a>
                    </div>
                @endforelse
            </x-backend.card>
        </div>

        <!-- Conversaciones que organizo -->
        <div class="col-lg-6">
            <x-backend.card>
                <x-slot name="title">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <h5 class="mb-0">Organizando</h5>
                        <a href="{{ route('conversations.create') }}" class="btn btn-sm btn-outline-success">
                            <i class="ti ti-plus me-1"></i> Nueva
                        </a>
                    </div>
                </x-slot>

                @forelse($organizing as $conversation)
                    <div class="d-flex align-items-start p-3 rounded bg-light mb-3 hover-card">
                        <div class="flex-grow-1">
                            <h6 class="mb-2">
                                <a href="{{ route('conversations.show', $conversation) }}" class="text-decoration-none text-dark">
                                    {{ $conversation->title }}
                                </a>
                            </h6>
                            <div class="d-flex align-items-center gap-2 text-sm text-muted">
                                <span><i class="ti ti-users me-1"></i>{{ $conversation->participations_count }} participantes</span>
                                <span>{{ $conversation->type === 'online' ? '🌐 Online' : '📍 Presencial' }}</span>
                            </div>
                        </div>
                        <a href="{{ route('conversations.edit', $conversation) }}" class="btn btn-sm btn-outline-primary">
                            <i class="ti ti-edit"></i>
                        </a>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="ti ti-briefcase-off text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2 mb-0">No has creado ninguna conversación</p>
                        <a href="{{ route('conversations.create') }}" class="btn btn-sm btn-success mt-3">
                            Crear tu primera conversación
                        </a>
                    </div>
                @endforelse
            </x-backend.card>
        </div>
    </div>
</x-app-layout>
