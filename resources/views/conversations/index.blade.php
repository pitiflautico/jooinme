<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0">{{ __('Explorar Conversaciones') }}</h2>
            @auth
                <a href="{{ route('conversations.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus me-2"></i>
                    Crear conversación
                </a>
            @endauth
        </div>
    </x-slot>

    <!-- Filtros -->
    <x-backend.card class="mb-4">
        <form method="GET" action="{{ route('conversations.index') }}">
            <div class="row g-3 mb-3">
                <!-- Búsqueda -->
                <div class="col-md-6">
                    <x-backend.input-label for="search" value="Buscar" />
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Título o descripción..." class="form-control">
                </div>

                <!-- Topic -->
                <div class="col-md-3">
                    <x-backend.input-label for="topic" value="Tema" />
                    <x-backend.select name="topic" id="topic">
                        <option value="">Todos los temas</option>
                        @foreach($topics as $topic)
                            <option value="{{ $topic->id }}" {{ request('topic') == $topic->id ? 'selected' : '' }}>
                                {{ $topic->name }}
                            </option>
                        @endforeach
                    </x-backend.select>
                </div>

                <!-- Tipo -->
                <div class="col-md-3">
                    <x-backend.input-label for="type" value="Tipo" />
                    <x-backend.select name="type" id="type">
                        <option value="">Todos</option>
                        <option value="online" {{ request('type') == 'online' ? 'selected' : '' }}>🌐 Online</option>
                        <option value="in_person" {{ request('type') == 'in_person' ? 'selected' : '' }}>📍 Presencial</option>
                        <option value="hybrid" {{ request('type') == 'hybrid' ? 'selected' : '' }}>🔄 Híbrido</option>
                    </x-backend.select>
                </div>
            </div>

            <div class="row g-3">
                <!-- Privacidad -->
                <div class="col-md-3">
                    <x-backend.input-label for="privacy" value="Privacidad" />
                    <x-backend.select name="privacy" id="privacy">
                        <option value="">Todas</option>
                        <option value="public" {{ request('privacy') == 'public' ? 'selected' : '' }}>Pública</option>
                        <option value="moderated" {{ request('privacy') == 'moderated' ? 'selected' : '' }}>Moderada</option>
                        <option value="private" {{ request('privacy') == 'private' ? 'selected' : '' }}>Privada</option>
                    </x-backend.select>
                </div>

                <!-- Frecuencia -->
                <div class="col-md-3">
                    <x-backend.input-label for="frequency" value="Frecuencia" />
                    <x-backend.select name="frequency" id="frequency">
                        <option value="">Todas</option>
                        <option value="once" {{ request('frequency') == 'once' ? 'selected' : '' }}>Una vez</option>
                        <option value="weekly" {{ request('frequency') == 'weekly' ? 'selected' : '' }}>Semanal</option>
                        <option value="biweekly" {{ request('frequency') == 'biweekly' ? 'selected' : '' }}>Quincenal</option>
                        <option value="monthly" {{ request('frequency') == 'monthly' ? 'selected' : '' }}>Mensual</option>
                    </x-backend.select>
                </div>

                <!-- Ordenar -->
                <div class="col-md-3">
                    <x-backend.input-label for="sort" value="Ordenar por" />
                    <x-backend.select name="sort" id="sort">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Más recientes</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Más populares</option>
                        <option value="upcoming" {{ request('sort') == 'upcoming' ? 'selected' : '' }}>Próximas</option>
                    </x-backend.select>
                </div>

                <!-- Botones -->
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <x-backend.button type="submit" class="flex-fill">
                        <i class="ti ti-filter me-1"></i>
                        Filtrar
                    </x-backend.button>
                    <a href="{{ route('conversations.index') }}" class="btn btn-secondary">
                        <i class="ti ti-x"></i>
                    </a>
                </div>
            </div>
        </form>
    </x-backend.card>

    <!-- Resultados -->
    <div class="row g-4 mb-4">
        @forelse($conversations as $conversation)
            <div class="col-md-4">
                <div class="card h-100 hover-card">
                    @if($conversation->cover_image)
                        <img src="{{ $conversation->cover_image }}" alt="{{ $conversation->title }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-gradient-primary d-flex align-items-center justify-content-center text-white" style="height: 200px; font-size: 3rem;">
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
                            @if($conversation->privacy === 'private')
                                <span class="badge bg-warning">🔒 Privada</span>
                            @endif
                        </div>

                        <h5 class="card-title">{{ $conversation->title }}</h5>
                        <p class="card-text text-muted small">{{ Str::limit($conversation->description, 100) }}</p>

                        <div class="d-flex justify-content-between align-items-center mb-3 small text-muted">
                            <span>
                                <i class="ti ti-users me-1"></i>
                                {{ $conversation->current_participants }}/{{ $conversation->max_participants }}
                            </span>
                            <span>
                                <i class="ti ti-repeat me-1"></i>
                                {{ ucfirst($conversation->frequency) }}
                            </span>
                        </div>

                        <a href="{{ route('conversations.show', $conversation) }}" class="btn btn-primary w-100">
                            Ver detalles
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <x-backend.card>
                    <div class="text-center py-5">
                        <i class="ti ti-message-off text-muted" style="font-size: 4rem;"></i>
                        <h5 class="mt-3">No se encontraron conversaciones</h5>
                        <p class="text-muted">Intenta ajustar los filtros o crea una nueva conversación.</p>
                        @auth
                            <a href="{{ route('conversations.create') }}" class="btn btn-primary mt-3">
                                <i class="ti ti-plus me-2"></i>
                                Crear conversación
                            </a>
                        @endauth
                    </div>
                </x-backend.card>
            </div>
        @endforelse
    </div>

    <!-- Paginación -->
    @if($conversations->hasPages())
        <x-backend.card>
            {{ $conversations->links() }}
        </x-backend.card>
    @endif
</x-app-layout>
