<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Gestionar Participantes: {{ $conversation->title }}</h2>
            <div class="d-flex gap-2">
                <a href="{{ route('conversations.participants.invite', $conversation) }}" class="btn btn-primary btn-sm">
                    <i class="ti ti-plus me-1"></i>
                    Invitar participantes
                </a>
                <a href="{{ route('conversations.show', $conversation) }}" class="btn btn-secondary btn-sm">
                    Ver conversación
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Messages -->
    @if(session('success'))
        <x-backend.alert type="success" class="mb-4">
            {{ session('success') }}
        </x-backend.alert>
    @endif

    @if(session('error'))
        <x-backend.alert type="danger" class="mb-4">
            {{ session('error') }}
        </x-backend.alert>
    @endif

    <!-- Stats Overview -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <x-backend.card>
                <div class="card-body text-center">
                    <div class="small text-muted mb-2">Participantes Activos</div>
                    <div class="h2 mb-1">{{ $accepted->count() }}</div>
                    <div class="small text-muted">de {{ $conversation->max_participants }} máximo</div>
                </div>
            </x-backend.card>
        </div>

        <div class="col-md-3">
            <x-backend.card>
                <div class="card-body text-center">
                    <div class="small text-muted mb-2">Solicitudes Pendientes</div>
                    <div class="h2 text-warning mb-1">{{ $pending->count() }}</div>
                </div>
            </x-backend.card>
        </div>

        <div class="col-md-3">
            <x-backend.card>
                <div class="card-body text-center">
                    <div class="small text-muted mb-2">Invitaciones Enviadas</div>
                    <div class="h2 text-primary mb-1">{{ $pendingInvitations->count() }}</div>
                </div>
            </x-backend.card>
        </div>

        <div class="col-md-3">
            <x-backend.card>
                <div class="card-body text-center">
                    <div class="small text-muted mb-2">Espacios Disponibles</div>
                    <div class="h2 text-success mb-1">{{ $conversation->max_participants - $accepted->count() }}</div>
                </div>
            </x-backend.card>
        </div>
    </div>

    <!-- Pending Requests -->
    @if($pending->count() > 0)
        <x-backend.card class="mb-4">
            <div class="card-header">
                <h3 class="h5 mb-0">Solicitudes Pendientes ({{ $pending->count() }})</h3>
            </div>
            <div class="card-body">
                @foreach($pending as $participation)
                    <div class="card border-warning bg-light mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 1.25rem;">
                                        {{ substr($participation->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h4 class="h6 mb-1">
                                            <a href="{{ route('users.show', $participation->user) }}" class="text-decoration-none">
                                                {{ $participation->user->name }}
                                            </a>
                                        </h4>
                                        <p class="text-muted small mb-1">{{ $participation->user->email }}</p>
                                        <p class="text-muted small mb-0">Solicitó unirse {{ $participation->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <form action="{{ route('conversations.participants.approve', [$conversation, $participation]) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="ti ti-check me-1"></i>
                                            Aprobar
                                        </button>
                                    </form>
                                    <form action="{{ route('conversations.participants.reject', [$conversation, $participation]) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="ti ti-x me-1"></i>
                                            Rechazar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-backend.card>
    @endif

    <!-- Accepted Participants -->
    <x-backend.card class="mb-4">
        <div class="card-header">
            <h3 class="h5 mb-0">Participantes Activos ({{ $accepted->count() }})</h3>
        </div>
        <div class="card-body">
            @if($accepted->count() > 0)
                <div class="row g-3">
                    @foreach($accepted as $participation)
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 1.25rem;">
                                                {{ substr($participation->user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <h4 class="h6 mb-1">
                                                    <a href="{{ route('users.show', $participation->user) }}" class="text-decoration-none">
                                                        {{ $participation->user->name }}
                                                    </a>
                                                    @if($participation->user_id === $conversation->owner_id)
                                                        <span class="badge bg-purple ms-2">Organizador</span>
                                                    @else
                                                        <span class="badge bg-info ms-2">{{ ucfirst($participation->role) }}</span>
                                                    @endif
                                                </h4>
                                                <p class="text-muted small mb-0">Unido {{ $participation->joined_at?->diffForHumans() ?? $participation->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                        @if($participation->user_id !== $conversation->owner_id)
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    <i class="ti ti-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <form action="{{ route('conversations.participants.update-role', [$conversation, $participation]) }}" method="POST" class="px-3 py-2">
                                                            @csrf
                                                            <label class="form-label small mb-2">Cambiar rol</label>
                                                            <select name="role" onchange="this.form.submit()" class="form-select form-select-sm">
                                                                <option value="member" {{ $participation->role === 'member' ? 'selected' : '' }}>Miembro</option>
                                                                <option value="moderator" {{ $participation->role === 'moderator' ? 'selected' : '' }}>Moderador</option>
                                                                <option value="co_host" {{ $participation->role === 'co_host' ? 'selected' : '' }}>Co-anfitrión</option>
                                                            </select>
                                                        </form>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="{{ route('conversations.participants.remove', [$conversation, $participation]) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar a este participante?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="ti ti-trash me-2"></i>
                                                                Eliminar
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="ti ti-users-off" style="font-size: 4rem;"></i>
                    <p class="mt-3">No hay participantes activos aún.</p>
                </div>
            @endif
        </div>
    </x-backend.card>

    <!-- Pending Invitations -->
    @if($pendingInvitations->count() > 0)
        <x-backend.card>
            <div class="card-header">
                <h3 class="h5 mb-0">Invitaciones Pendientes ({{ $pendingInvitations->count() }})</h3>
            </div>
            <div class="card-body">
                @foreach($pendingInvitations as $invitation)
                    <div class="card border-primary bg-light mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="mb-1 fw-medium">
                                        @if($invitation->invitee)
                                            {{ $invitation->invitee->name }} ({{ $invitation->invitee->email }})
                                        @else
                                            {{ $invitation->email }}
                                        @endif
                                    </p>
                                    <p class="text-muted small mb-0">
                                        Invitado por {{ $invitation->inviter->name }} {{ $invitation->created_at->diffForHumans() }}
                                        · Expira {{ $invitation->expires_at->diffForHumans() }}
                                    </p>
                                </div>
                                <span class="badge bg-primary">Pendiente</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-backend.card>
    @endif
</x-app-layout>
