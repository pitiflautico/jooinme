<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0">{{ $conversation->title }}</h2>
            @auth
                @if($conversation->isOwner(auth()->user()))
                    <div class="d-flex gap-2">
                        <a href="{{ route('conversations.edit', $conversation) }}" class="btn btn-secondary">
                            <i class="ti ti-edit me-1"></i>
                            Editar
                        </a>
                    </div>
                @endif
            @endauth
        </div>
    </x-slot>

    <!-- Mensajes -->
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

    @if(session('info'))
        <x-backend.alert type="info" class="mb-4">
            {{ session('info') }}
        </x-backend.alert>
    @endif

    <div class="row g-4">
        <!-- Columna principal -->
        <div class="col-lg-8">
            <!-- Imagen y descripción -->
            <x-backend.card class="mb-4">
                @if($conversation->cover_image)
                    <img src="{{ $conversation->cover_image }}" alt="{{ $conversation->title }}" class="card-img-top" style="height: 300px; object-fit: cover;">
                @else
                    <div class="card-img-top bg-gradient-primary d-flex align-items-center justify-content-center text-white" style="height: 300px; font-size: 4rem;">
                        {{ $conversation->topic->icon ?? '💬' }}
                    </div>
                @endif

                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="badge bg-primary">
                            {{ $conversation->topic->name ?? 'General' }}
                        </span>
                        <span class="badge bg-secondary">
                            {{ $conversation->type === 'online' ? '🌐 Online' : ($conversation->type === 'in_person' ? '📍 Presencial' : '🔄 Híbrido') }}
                        </span>
                        <span class="badge bg-info">
                            <i class="ti ti-repeat me-1"></i>
                            {{ ucfirst($conversation->frequency) }}
                        </span>
                        @if($conversation->privacy === 'private')
                            <span class="badge bg-warning">🔒 Privada</span>
                        @elseif($conversation->privacy === 'moderated')
                            <span class="badge bg-info">✋ Moderada</span>
                        @endif
                    </div>

                    <h1 class="h3 mb-3">{{ $conversation->title }}</h1>
                    <p class="text-muted">{{ $conversation->description }}</p>

                    @if($conversation->tags && count($conversation->tags) > 0)
                        <div class="mt-3 d-flex flex-wrap gap-2">
                            @foreach($conversation->tags as $tag)
                                <span class="badge bg-light text-dark">#{{ $tag }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </x-backend.card>

            <!-- Detalles de ubicación/enlace -->
            <x-backend.card class="mb-4">
                <div class="card-body">
                    <h3 class="h5 mb-3">Detalles del encuentro</h3>

                    @if($conversation->type === 'online' || $conversation->type === 'hybrid')
                        <div class="mb-3">
                            <div class="d-flex align-items-start">
                                <i class="ti ti-world text-primary me-2 mt-1"></i>
                                <div>
                                    <p class="mb-1 fw-medium">Plataforma: {{ ucfirst($conversation->meeting_platform ?? 'Por definir') }}</p>
                                    @if($conversation->meeting_url && $isParticipant)
                                        <a href="{{ $conversation->meeting_url }}" target="_blank" class="text-primary">
                                            Unirse a la videollamada <i class="ti ti-external-link ms-1"></i>
                                        </a>
                                    @elseif($conversation->meeting_url && !$isParticipant)
                                        <p class="text-muted small mb-0">Enlace disponible solo para participantes</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($conversation->type === 'in_person' || $conversation->type === 'hybrid')
                        <div class="mb-3">
                            <div class="d-flex align-items-start">
                                <i class="ti ti-map-pin text-primary me-2 mt-1"></i>
                                <div>
                                    <p class="mb-1 fw-medium">Ubicación: {{ $conversation->location ?? 'Por definir' }}</p>
                                    @if($conversation->location_details)
                                        <p class="text-muted small mb-0">{{ $conversation->location_details }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($conversation->starts_at)
                        <div class="d-flex align-items-start">
                            <i class="ti ti-calendar text-primary me-2 mt-1"></i>
                            <div>
                                <p class="mb-1 fw-medium">Inicio: {{ $conversation->starts_at->format('d/m/Y H:i') }}</p>
                                @if($conversation->ends_at)
                                    <p class="text-muted small mb-0">Fin: {{ $conversation->ends_at->format('d/m/Y H:i') }}</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </x-backend.card>

            <!-- Próximas sesiones -->
            @if($conversation->scheduleSlots->count() > 0)
                <x-backend.card>
                    <div class="card-body">
                        <h3 class="h5 mb-3">Próximas sesiones</h3>
                        <div class="list-group list-group-flush">
                            @foreach($conversation->scheduleSlots as $slot)
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <p class="mb-1 fw-medium">{{ $slot->scheduled_at->format('d/m/Y') }}</p>
                                        <p class="text-muted small mb-0">{{ $slot->scheduled_at->format('H:i') }} - {{ $slot->ends_at->format('H:i') }}</p>
                                    </div>
                                    <span class="badge {{ $slot->status === 'scheduled' ? 'bg-info' : 'bg-success' }}">
                                        {{ ucfirst($slot->status) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </x-backend.card>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Organizador -->
            <x-backend.card class="mb-4">
                <div class="card-body">
                    <h3 class="h5 mb-3">Organizador</h3>
                    <div class="d-flex align-items-center">
                        <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; font-size: 1.25rem;">
                            {{ substr($conversation->owner->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="mb-0 fw-medium">{{ $conversation->owner->name }}</p>
                            <p class="text-muted small mb-0">{{ $conversation->owner->email }}</p>
                        </div>
                    </div>
                </div>
            </x-backend.card>

            <!-- Participantes -->
            <x-backend.card class="mb-4">
                <div class="card-body">
                    <h3 class="h5 mb-3">Participantes</h3>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small">Ocupación</span>
                            <span class="fw-medium">{{ $conversation->current_participants }}/{{ $conversation->max_participants }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ ($conversation->current_participants / $conversation->max_participants) * 100 }}%"></div>
                        </div>
                    </div>

                    @if($conversation->participations->where('status', 'accepted')->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($conversation->participations->where('status', 'accepted')->take(5) as $participation)
                                <div class="list-group-item d-flex align-items-center px-0 py-2">
                                    <div class="avatar bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 0.875rem;">
                                        {{ substr($participation->user->name, 0, 1) }}
                                    </div>
                                    <span class="small">{{ $participation->user->name }}</span>
                                </div>
                            @endforeach
                            @if($conversation->participations->where('status', 'accepted')->count() > 5)
                                <p class="text-muted small mt-2 mb-0">
                                    Y {{ $conversation->participations->where('status', 'accepted')->count() - 5 }} más...
                                </p>
                            @endif
                        </div>
                    @endif
                </div>
            </x-backend.card>

            <!-- Acciones -->
            <x-backend.card class="mb-4">
                <div class="card-body">
                    @auth
                        @if($isParticipant)
                            @if($userParticipation->status === 'pending')
                                <x-backend.alert type="warning" class="mb-3">
                                    Tu solicitud está pendiente de aprobación
                                </x-backend.alert>
                            @else
                                <x-backend.alert type="success" class="mb-3">
                                    ✓ Eres parte de esta conversación
                                </x-backend.alert>
                            @endif

                            @if(!$conversation->isOwner(auth()->user()))
                                <form action="{{ route('conversations.leave', $conversation) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger w-100" onclick="return confirm('¿Seguro que quieres abandonar esta conversación?')">
                                        <i class="ti ti-logout me-1"></i>
                                        Abandonar conversación
                                    </button>
                                </form>
                            @endif
                        @elseif($canJoin)
                            <form action="{{ route('conversations.join', $conversation) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100 mb-2">
                                    <i class="ti ti-plus me-1"></i>
                                    {{ $conversation->require_approval ? 'Solicitar unirse' : 'Unirse ahora' }}
                                </button>
                            </form>
                            @if($conversation->require_approval)
                                <p class="text-muted small text-center mb-0">Esta conversación requiere aprobación del organizador</p>
                            @endif
                        @elseif($conversation->isFull())
                            <div class="btn btn-secondary w-100 disabled">
                                <i class="ti ti-lock me-1"></i>
                                Conversación llena
                            </div>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary w-100">
                            <i class="ti ti-login me-1"></i>
                            Inicia sesión para unirte
                        </a>
                    @endauth
                </div>
            </x-backend.card>

            <!-- Configuración -->
            <x-backend.card>
                <div class="card-body">
                    <h3 class="h5 mb-3">Configuración</h3>
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Chat permitido</span>
                            <span class="small">{{ $conversation->allow_chat ? '✓ Sí' : '✗ No' }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Grabación</span>
                            <span class="small">{{ $conversation->allow_recording ? '✓ Sí' : '✗ No' }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Requiere aprobación</span>
                            <span class="small">{{ $conversation->require_approval ? '✓ Sí' : '✗ No' }}</span>
                        </div>
                    </div>
                </div>
            </x-backend.card>
        </div>
    </div>
</x-app-layout>
