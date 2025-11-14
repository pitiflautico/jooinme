<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Notificaciones</h2>
            @if($notifications->where('read_at', null)->count() > 0)
                <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                    @csrf
                    <x-backend.button type="submit" variant="outline-primary">
                        <i class="ti ti-check-all me-1"></i>
                        Marcar todas como leídas
                    </x-backend.button>
                </form>
            @endif
        </div>
    </x-slot>

    <!-- Stats -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <x-backend.card>
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-lg rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center">
                            <i class="ti ti-bell text-primary" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-1">Total</p>
                        <h3 class="mb-0 fw-bold">{{ $notifications->count() }}</h3>
                    </div>
                </div>
            </x-backend.card>
        </div>
        <div class="col-md-4">
            <x-backend.card>
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-lg rounded-circle bg-info bg-opacity-10 d-flex align-items-center justify-content-center">
                            <i class="ti ti-bell-ringing text-info" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-1">No leídas</p>
                        <h3 class="mb-0 fw-bold">{{ $notifications->where('read_at', null)->count() }}</h3>
                    </div>
                </div>
            </x-backend.card>
        </div>
        <div class="col-md-4">
            <x-backend.card>
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-lg rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center">
                            <i class="ti ti-bell-check text-success" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-1">Leídas</p>
                        <h3 class="mb-0 fw-bold">{{ $notifications->whereNotNull('read_at')->count() }}</h3>
                    </div>
                </div>
            </x-backend.card>
        </div>
    </div>

    <!-- Notifications List -->
    <x-backend.card title="Todas las Notificaciones">
        <div class="list-group list-group-flush">
            @forelse($notifications as $notification)
                <div class="list-group-item p-4 {{ $notification->read_at ? 'opacity-75' : '' }}">
                    <div class="d-flex align-items-start gap-3">
                        <!-- Icon based on notification type -->
                        <div class="flex-shrink-0">
                            <div class="avatar rounded-circle
                                {{ $notification->type === 'invitation' ? 'bg-primary' : '' }}
                                {{ $notification->type === 'participation' ? 'bg-success' : '' }}
                                {{ $notification->type === 'feedback' ? 'bg-warning' : '' }}
                                {{ $notification->type === 'session' ? 'bg-info' : '' }}
                                {{ !in_array($notification->type, ['invitation', 'participation', 'feedback', 'session']) ? 'bg-secondary' : '' }}
                            ">
                                @if($notification->type === 'invitation')
                                    <i class="ti ti-mail"></i>
                                @elseif($notification->type === 'participation')
                                    <i class="ti ti-users"></i>
                                @elseif($notification->type === 'feedback')
                                    <i class="ti ti-star"></i>
                                @elseif($notification->type === 'session')
                                    <i class="ti ti-calendar"></i>
                                @else
                                    <i class="ti ti-bell"></i>
                                @endif
                            </div>
                        </div>

                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $notification->data['title'] ?? 'Notificación' }}</h6>
                            <p class="mb-2 text-muted small">{{ $notification->data['message'] ?? '' }}</p>
                            <div class="d-flex align-items-center gap-3 text-muted small">
                                <span>
                                    <i class="ti ti-clock me-1"></i>
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                                @if($notification->read_at)
                                    <span>
                                        <i class="ti ti-check me-1"></i>
                                        Leída {{ $notification->read_at->diffForHumans() }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="flex-shrink-0">
                            <div class="d-flex gap-2">
                                @if(!$notification->read_at)
                                    <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success" title="Marcar como leída">
                                            <i class="ti ti-check"></i>
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" onsubmit="return confirm('¿Eliminar esta notificación?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Action link if provided -->
                    @if(isset($notification->data['action_url']))
                        <div class="mt-3 ms-5">
                            <a href="{{ $notification->data['action_url'] }}" class="btn btn-sm btn-primary">
                                {{ $notification->data['action_text'] ?? 'Ver más' }}
                                <i class="ti ti-arrow-right ms-1"></i>
                            </a>
                        </div>
                    @endif
                </div>
            @empty
                <div class="p-5 text-center">
                    <i class="ti ti-bell-off text-muted" style="font-size: 4rem;"></i>
                    <p class="mt-3 text-muted">No tienes notificaciones</p>
                </div>
            @endforelse
        </div>

        @if($notifications->hasPages())
            <div class="card-footer">
                {{ $notifications->links() }}
            </div>
        @endif
    </x-backend.card>
</x-app-layout>
