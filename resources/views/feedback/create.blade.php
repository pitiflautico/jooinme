<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Valorar Sesión</h2>
            <a href="{{ route('conversations.show', $conversation) }}" class="btn btn-secondary btn-sm">
                <i class="ti ti-arrow-left me-1"></i>
                Volver
            </a>
        </div>
    </x-slot>

    <x-backend.card>
        <div class="card-body">
            <div class="mb-4">
                <h3 class="h4 mb-2">{{ $conversation->title }}</h3>
                <p class="text-muted">Sesión: {{ $scheduleSlot->scheduled_at->format('d/m/Y H:i') }}</p>
            </div>

            <form action="{{ route('feedback.store', $scheduleSlot) }}" method="POST">
                @csrf

                <!-- Attendance -->
                <div class="bg-light rounded p-4 mb-4">
                    <h4 class="h6 mb-3">¿Asististe a esta sesión?</h4>
                    <div class="d-flex gap-4">
                        <div class="form-check">
                            <input type="radio" name="attended" value="1" checked class="form-check-input" id="attended_yes">
                            <label class="form-check-label" for="attended_yes">Sí, asistí</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="attended" value="0" class="form-check-input" id="attended_no">
                            <label class="form-check-label" for="attended_no">No asistí</label>
                        </div>
                    </div>
                </div>

                <!-- Overall Rating -->
                <div class="mb-4">
                    <label class="form-label h5">Valoración General *</label>
                    <div class="d-flex gap-2 fs-1">
                        @for($i = 1; $i <= 5; $i++)
                            <label class="star-rating cursor-pointer">
                                <input type="radio" name="rating" value="{{ $i }}" required class="d-none">
                                <i class="ti ti-star text-muted star-icon"></i>
                            </label>
                        @endfor
                    </div>
                    <x-backend.input-error field="rating" />
                </div>

                <!-- Detailed Ratings -->
                <div class="bg-light rounded p-4 mb-4">
                    <h4 class="h6 mb-4">Valoración Detallada (Opcional)</h4>

                    <!-- Content Rating -->
                    <div class="mb-4">
                        <label class="form-label small">Contenido</label>
                        <div class="d-flex gap-2 fs-3">
                            @for($i = 1; $i <= 5; $i++)
                                <label class="star-rating cursor-pointer">
                                    <input type="radio" name="content_rating" value="{{ $i }}" class="d-none">
                                    <i class="ti ti-star text-muted star-icon"></i>
                                </label>
                            @endfor
                        </div>
                    </div>

                    <!-- Organization Rating -->
                    <div class="mb-4">
                        <label class="form-label small">Organización</label>
                        <div class="d-flex gap-2 fs-3">
                            @for($i = 1; $i <= 5; $i++)
                                <label class="star-rating cursor-pointer">
                                    <input type="radio" name="organization_rating" value="{{ $i }}" class="d-none">
                                    <i class="ti ti-star text-muted star-icon"></i>
                                </label>
                            @endfor
                        </div>
                    </div>

                    <!-- Atmosphere Rating -->
                    <div class="mb-0">
                        <label class="form-label small">Ambiente</label>
                        <div class="d-flex gap-2 fs-3">
                            @for($i = 1; $i <= 5; $i++)
                                <label class="star-rating cursor-pointer">
                                    <input type="radio" name="atmosphere_rating" value="{{ $i }}" class="d-none">
                                    <i class="ti ti-star text-muted star-icon"></i>
                                </label>
                            @endfor
                        </div>
                    </div>
                </div>

                <!-- Comment -->
                <div class="mb-4">
                    <x-backend.input-label for="comment" value="Comentarios (Opcional)" />
                    <x-backend.textarea id="comment" name="comment" rows="4" placeholder="Comparte tu experiencia..."></x-backend.textarea>
                    <x-backend.input-error field="comment" />
                </div>

                <!-- Testimonial -->
                <div class="mb-4">
                    <x-backend.input-label for="testimonial" value="Testimonio Público (Opcional)" />
                    <x-backend.textarea id="testimonial" name="testimonial" rows="3" placeholder="Un breve testimonio que podría aparecer en el perfil del organizador..."></x-backend.textarea>
                    <div class="mt-2">
                        <div class="form-check">
                            <input type="checkbox" name="is_public" value="1" class="form-check-input" id="is_public">
                            <label class="form-check-label small" for="is_public">Hacer público este testimonio</label>
                        </div>
                    </div>
                    <x-backend.input-error field="testimonial" />
                </div>

                <!-- Would Recommend -->
                <div class="mb-4">
                    <div class="form-check">
                        <input type="checkbox" name="would_recommend" value="1" checked class="form-check-input" id="would_recommend">
                        <label class="form-check-label" for="would_recommend">Recomendaría esta conversación</label>
                    </div>
                </div>

                <!-- Rate Participants -->
                @if($participants->count() > 0)
                    <hr class="my-4">
                    <div>
                        <h4 class="h5 mb-3">Valora a otros participantes (Opcional)</h4>
                        <p class="text-muted small mb-4">
                            Ayuda a construir la reputación de la comunidad valorando a otros participantes
                        </p>

                        <div class="row g-3">
                            @foreach($participants as $participant)
                                <div class="col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center gap-3 mb-3">
                                                <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    {{ substr($participant->name, 0, 1) }}
                                                </div>
                                                <p class="mb-0 fw-medium">{{ $participant->name }}</p>
                                            </div>

                                            <div class="d-flex gap-2 mb-3">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <label class="star-rating cursor-pointer">
                                                        <input type="radio" name="participant_ratings[{{ $loop->parent->index }}][rating]" value="{{ $i }}" class="d-none">
                                                        <input type="hidden" name="participant_ratings[{{ $loop->parent->index }}][user_id]" value="{{ $participant->id }}">
                                                        <i class="ti ti-star text-muted star-icon fs-4"></i>
                                                    </label>
                                                @endfor
                                            </div>

                                            <input type="text"
                                                   name="participant_ratings[{{ $loop->index }}][comment]"
                                                   placeholder="Comentario breve (opcional)"
                                                   class="form-control form-control-sm">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Submit -->
                <div class="d-flex justify-content-end gap-2 pt-4 border-top mt-4">
                    <a href="{{ route('conversations.show', $conversation) }}" class="btn btn-secondary">
                        Cancelar
                    </a>
                    <x-backend.button type="submit">
                        Enviar Valoración
                    </x-backend.button>
                </div>
            </form>
        </div>
    </x-backend.card>

    <style>
        .star-rating:hover .star-icon,
        .star-rating input:checked ~ .star-icon {
            color: #ffc107 !important;
        }
        .cursor-pointer {
            cursor: pointer;
        }
    </style>

    <script>
        // Handle star rating clicks
        document.querySelectorAll('.star-rating').forEach(label => {
            const input = label.querySelector('input');
            const icon = label.querySelector('.star-icon');

            label.addEventListener('click', function() {
                // Update all stars in the same group
                const group = this.closest('.d-flex');
                const allStars = group.querySelectorAll('.star-icon');
                const allInputs = group.querySelectorAll('input[type="radio"]');
                const clickedIndex = Array.from(allInputs).indexOf(input);

                allStars.forEach((star, index) => {
                    if (index <= clickedIndex) {
                        star.classList.remove('text-muted');
                        star.classList.add('text-warning');
                    } else {
                        star.classList.remove('text-warning');
                        star.classList.add('text-muted');
                    }
                });
            });
        });
    </script>
</x-app-layout>
