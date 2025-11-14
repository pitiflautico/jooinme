<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Feedback: {{ $conversation->title }}</h2>
            <a href="{{ route('conversations.show', $conversation) }}" class="btn btn-secondary btn-sm">
                <i class="ti ti-arrow-left me-1"></i>
                Volver
            </a>
        </div>
    </x-slot>

    <!-- Average Ratings -->
    <div class="row g-4 mb-4">
        <div class="col-md">
            <x-backend.card class="text-center">
                <div class="card-body">
                    <div class="small text-muted mb-2">General</div>
                    <div class="display-4 text-warning mb-2">{{ $averages['overall'] ?: '—' }}</div>
                    <div class="text-warning fs-4">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= round($averages['overall']))
                                <i class="ti ti-star-filled"></i>
                            @else
                                <i class="ti ti-star"></i>
                            @endif
                        @endfor
                    </div>
                </div>
            </x-backend.card>
        </div>

        <div class="col-md">
            <x-backend.card class="text-center">
                <div class="card-body">
                    <div class="small text-muted mb-2">Contenido</div>
                    <div class="h2 mb-1">{{ $averages['content'] ?: '—' }}</div>
                    <div class="small text-muted">de 5</div>
                </div>
            </x-backend.card>
        </div>

        <div class="col-md">
            <x-backend.card class="text-center">
                <div class="card-body">
                    <div class="small text-muted mb-2">Organización</div>
                    <div class="h2 mb-1">{{ $averages['organization'] ?: '—' }}</div>
                    <div class="small text-muted">de 5</div>
                </div>
            </x-backend.card>
        </div>

        <div class="col-md">
            <x-backend.card class="text-center">
                <div class="card-body">
                    <div class="small text-muted mb-2">Ambiente</div>
                    <div class="h2 mb-1">{{ $averages['atmosphere'] ?: '—' }}</div>
                    <div class="small text-muted">de 5</div>
                </div>
            </x-backend.card>
        </div>

        <div class="col-md">
            <x-backend.card class="text-center">
                <div class="card-body">
                    <div class="small text-muted mb-2">Recomendaría</div>
                    <div class="h2 text-success mb-1">{{ round($averages['would_recommend']) }}%</div>
                    <div class="small text-muted">de participantes</div>
                </div>
            </x-backend.card>
        </div>
    </div>

    <!-- Public Testimonials -->
    @if($testimonials->count() > 0)
        <x-backend.card class="mb-4">
            <div class="card-header">
                <h3 class="h5 mb-0">Testimonios Destacados</h3>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach($testimonials as $testimonial)
                        <div class="col-md-6">
                            <div class="card border-primary bg-light">
                                <div class="card-body">
                                    <div class="d-flex align-items-start gap-3 mb-3">
                                        <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            {{ substr($testimonial->user->name, 0, 1) }}
                                        </div>
                                        <div class="flex-fill">
                                            <p class="mb-1 fw-medium">{{ $testimonial->user->name }}</p>
                                            <div class="text-warning small">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $testimonial->rating)
                                                        <i class="ti ti-star-filled"></i>
                                                    @else
                                                        <i class="ti ti-star"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                    <p class="small fst-italic mb-0">"{{ $testimonial->testimonial }}"</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </x-backend.card>
    @endif

    <!-- All Feedback -->
    <x-backend.card>
        <div class="card-header">
            <h3 class="h5 mb-0">Todas las Valoraciones ({{ $feedback->total() }})</h3>
        </div>
        <div class="card-body">
            @forelse($feedback as $item)
                <div class="border-bottom pb-4 mb-4 last:border-0 last:pb-0 last:mb-0">
                    <div class="d-flex gap-3">
                        <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            {{ substr($item->user->name, 0, 1) }}
                        </div>
                        <div class="flex-fill">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <p class="mb-1 fw-medium">{{ $item->user->name }}</p>
                                    <p class="text-muted small mb-0">
                                        Sesión: {{ $item->scheduleSlot->scheduled_at->format('d/m/Y') }}
                                        · {{ $item->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                <div class="text-warning fs-5">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $item->rating)
                                            <i class="ti ti-star-filled"></i>
                                        @else
                                            <i class="ti ti-star"></i>
                                        @endif
                                    @endfor
                                </div>
                            </div>

                            @if($item->content_rating || $item->organization_rating || $item->atmosphere_rating)
                                <div class="d-flex gap-3 small text-muted mb-2">
                                    @if($item->content_rating)
                                        <span>Contenido: {{ $item->content_rating }}/5</span>
                                    @endif
                                    @if($item->organization_rating)
                                        <span>Organización: {{ $item->organization_rating }}/5</span>
                                    @endif
                                    @if($item->atmosphere_rating)
                                        <span>Ambiente: {{ $item->atmosphere_rating }}/5</span>
                                    @endif
                                </div>
                            @endif

                            @if($item->comment)
                                <p class="mb-2">{{ $item->comment }}</p>
                            @endif

                            <div class="d-flex gap-2">
                                @if($item->would_recommend)
                                    <span class="badge bg-success">
                                        <i class="ti ti-check me-1"></i>
                                        Recomendaría
                                    </span>
                                @endif
                                @if($item->attended)
                                    <span class="badge bg-info">Asistió</span>
                                @else
                                    <span class="badge bg-secondary">No asistió</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="ti ti-star-off text-muted" style="font-size: 4rem;"></i>
                    <p class="mt-3 text-muted">No hay valoraciones aún</p>
                </div>
            @endforelse

            @if($feedback->hasPages())
                <div class="mt-4">
                    {{ $feedback->links() }}
                </div>
            @endif
        </div>
    </x-backend.card>
</x-app-layout>
