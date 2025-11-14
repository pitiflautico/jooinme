<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Invitar Participantes: {{ $conversation->title }}</h2>
            <a href="{{ route('conversations.participants.index', $conversation) }}" class="btn btn-secondary btn-sm">
                <i class="ti ti-arrow-left me-1"></i>
                Volver
            </a>
        </div>
    </x-slot>

    <x-backend.card>
        <div class="card-body">
            <div class="mb-4">
                <h3 class="h5 mb-2">Invitar participantes</h3>
                <p class="text-muted small">
                    Puedes invitar personas por email o seleccionar usuarios registrados.
                </p>
            </div>

            <!-- Tab Navigation -->
            <ul class="nav nav-tabs mb-4" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="users-tab" data-bs-toggle="tab" data-bs-target="#users-panel" type="button" role="tab">
                        Usuarios Registrados
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="email-tab" data-bs-toggle="tab" data-bs-target="#email-panel" type="button" role="tab">
                        Por Email
                    </button>
                </li>
            </ul>

            <div class="tab-content">
                <!-- Invite by User Selection -->
                <div class="tab-pane fade show active" id="users-panel" role="tabpanel">
                    <form action="{{ route('conversations.participants.invite', $conversation) }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="user">

                        <div class="mb-4">
                            <label class="form-label">Selecciona usuarios para invitar</label>

                            @if($availableUsers->count() > 0)
                                <div class="border rounded" style="max-height: 400px; overflow-y: auto;">
                                    @foreach($availableUsers as $user)
                                        <div class="form-check p-3 border-bottom hover-bg-light">
                                            <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="form-check-input" id="user_{{ $user->id }}">
                                            <label class="form-check-label d-flex align-items-center gap-3 w-100 cursor-pointer" for="user_{{ $user->id }}">
                                                <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    {{ substr($user->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="mb-0 fw-medium">{{ $user->name }}</p>
                                                    <p class="text-muted small mb-0">{{ $user->email }}</p>
                                                </div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-4">
                                    <x-backend.input-label for="message_users" value="Mensaje personalizado (opcional)" />
                                    <x-backend.textarea id="message_users" name="message" rows="3" placeholder="Escribe un mensaje de invitación..."></x-backend.textarea>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('conversations.participants.index', $conversation) }}" class="btn btn-secondary">
                                        Cancelar
                                    </a>
                                    <x-backend.button type="submit">
                                        Enviar invitaciones
                                    </x-backend.button>
                                </div>
                            @else
                                <div class="text-center py-5 bg-light rounded">
                                    <i class="ti ti-users-off text-muted" style="font-size: 3rem;"></i>
                                    <p class="mt-3 text-muted">No hay usuarios disponibles para invitar.</p>
                                    <p class="text-muted small">Todos los usuarios registrados ya están en esta conversación.</p>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Invite by Email -->
                <div class="tab-pane fade" id="email-panel" role="tabpanel">
                    <form action="{{ route('conversations.participants.invite', $conversation) }}" method="POST" id="emailForm">
                        @csrf
                        <input type="hidden" name="type" value="email">

                        <div class="mb-4">
                            <x-backend.input-label for="emails_input" value="Direcciones de email" />
                            <small class="text-muted d-block mb-2">Una por línea</small>
                            <textarea id="emails_input" rows="6" placeholder="email1@example.com&#10;email2@example.com&#10;email3@example.com" class="form-control"></textarea>
                            <p class="text-muted small mt-1">
                                Ingresa cada email en una línea separada
                            </p>
                        </div>

                        <div class="mb-4">
                            <x-backend.input-label for="message_email" value="Mensaje personalizado (opcional)" />
                            <x-backend.textarea id="message_email" name="message" rows="3" placeholder="Escribe un mensaje de invitación..."></x-backend.textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('conversations.participants.index', $conversation) }}" class="btn btn-secondary">
                                Cancelar
                            </a>
                            <x-backend.button type="submit">
                                Enviar invitaciones
                            </x-backend.button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-backend.card>

    <style>
        .hover-bg-light:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }
        .cursor-pointer {
            cursor: pointer;
        }
    </style>

    <script>
        // Parse emails from textarea before submit
        document.getElementById('emailForm').addEventListener('submit', function(e) {
            const textarea = document.getElementById('emails_input');
            const emails = textarea.value.split('\n').map(email => email.trim()).filter(email => email.length > 0);

            if (emails.length === 0) {
                e.preventDefault();
                alert('Por favor, ingresa al menos un email.');
                return;
            }

            // Create hidden inputs for each email
            emails.forEach(email => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'emails[]';
                input.value = email;
                this.appendChild(input);
            });
        });
    </script>
</x-app-layout>
