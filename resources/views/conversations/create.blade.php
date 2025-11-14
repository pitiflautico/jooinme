<x-app-layout>
    <x-slot name="header">
        <h2 class="mb-0">{{ __('Crear Nueva Conversación') }}</h2>
    </x-slot>

    <x-backend.card>
        <form method="POST" action="{{ route('conversations.store') }}">
            @csrf

            <!-- Tema -->
            <div class="mb-4">
                <x-backend.input-label for="topic_id" value="Tema *" />
                <small class="text-muted d-block mb-2">¿De qué tratará la conversación?</small>
                <x-backend.select id="topic_id" name="topic_id" required>
                    <option value="">Selecciona un tema</option>
                    @foreach($topics as $topic)
                        <option value="{{ $topic->id }}" {{ old('topic_id') == $topic->id ? 'selected' : '' }}>
                            {{ $topic->icon }} {{ $topic->name }}
                        </option>
                    @endforeach
                </x-backend.select>
                <x-backend.input-error field="topic_id" />
            </div>

            <!-- Título -->
            <div class="mb-4">
                <x-backend.input-label for="title" value="Título *" />
                <small class="text-muted d-block mb-2">Sé claro y atractivo</small>
                <x-backend.text-input type="text" id="title" name="title" value="{{ old('title') }}" required maxlength="255" placeholder="Ej: Club de Lectura Mensual: Ciencia Ficción" />
                <x-backend.input-error field="title" />
            </div>

            <!-- Descripción -->
            <div class="mb-4">
                <x-backend.input-label for="description" value="Descripción *" />
                <small class="text-muted d-block mb-2">Explica qué harán, qué pueden esperar</small>
                <x-backend.textarea id="description" name="description" required rows="4" placeholder="Describe tu conversación...">{{ old('description') }}</x-backend.textarea>
                <x-backend.input-error field="description" />
            </div>

            <hr class="my-4">

            <h3 class="h5 mb-3">Configuración</h3>

            <div class="row g-3 mb-4">
                <!-- Frecuencia -->
                <div class="col-md-6">
                    <x-backend.input-label for="frequency" value="Frecuencia *" />
                    <x-backend.select id="frequency" name="frequency" required>
                        <option value="once" {{ old('frequency') == 'once' ? 'selected' : '' }}>Una vez</option>
                        <option value="weekly" {{ old('frequency') == 'weekly' ? 'selected' : '' }}>Semanal</option>
                        <option value="biweekly" {{ old('frequency') == 'biweekly' ? 'selected' : '' }}>Quincenal</option>
                        <option value="monthly" {{ old('frequency') == 'monthly' ? 'selected' : '' }}>Mensual</option>
                    </x-backend.select>
                    <x-backend.input-error field="frequency" />
                </div>

                <!-- Tipo -->
                <div class="col-md-6">
                    <x-backend.input-label for="type" value="Modalidad *" />
                    <x-backend.select id="type" name="type" required onchange="toggleLocationFields()">
                        <option value="online" {{ old('type') == 'online' ? 'selected' : '' }}>🌐 Online</option>
                        <option value="in_person" {{ old('type') == 'in_person' ? 'selected' : '' }}>📍 Presencial</option>
                        <option value="hybrid" {{ old('type') == 'hybrid' ? 'selected' : '' }}>🔄 Híbrido</option>
                    </x-backend.select>
                    <x-backend.input-error field="type" />
                </div>

                <!-- Privacidad -->
                <div class="col-md-6">
                    <x-backend.input-label for="privacy" value="Privacidad *" />
                    <x-backend.select id="privacy" name="privacy" required>
                        <option value="public" {{ old('privacy') == 'public' ? 'selected' : '' }}>Pública (cualquiera puede unirse)</option>
                        <option value="moderated" {{ old('privacy') == 'moderated' ? 'selected' : '' }}>Moderada (requiere aprobación)</option>
                        <option value="private" {{ old('privacy') == 'private' ? 'selected' : '' }}>Privada (solo por invitación)</option>
                    </x-backend.select>
                    <x-backend.input-error field="privacy" />
                </div>

                <!-- Máximo de participantes -->
                <div class="col-md-6">
                    <x-backend.input-label for="max_participants" value="Máximo de participantes *" />
                    <x-backend.text-input type="number" id="max_participants" name="max_participants" value="{{ old('max_participants', 10) }}" required min="2" max="100" />
                    <x-backend.input-error field="max_participants" />
                </div>
            </div>

            <hr class="my-4">

            <h3 class="h5 mb-3">Fecha y hora</h3>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <x-backend.input-label for="starts_at" value="Fecha de inicio" />
                    <x-backend.text-input type="datetime-local" id="starts_at" name="starts_at" value="{{ old('starts_at') }}" />
                    <x-backend.input-error field="starts_at" />
                </div>

                <div class="col-md-6">
                    <x-backend.input-label for="ends_at" value="Fecha de fin (opcional)" />
                    <x-backend.text-input type="datetime-local" id="ends_at" name="ends_at" value="{{ old('ends_at') }}" />
                    <x-backend.input-error field="ends_at" />
                </div>
            </div>

            <hr class="my-4">

            <h3 class="h5 mb-3">Detalles del encuentro</h3>

            <div id="onlineFields" class="mb-4">
                <div class="mb-3">
                    <x-backend.input-label for="meeting_platform" value="Plataforma de videollamada" />
                    <x-backend.select id="meeting_platform" name="meeting_platform">
                        <option value="">Selecciona una plataforma</option>
                        <option value="zoom" {{ old('meeting_platform') == 'zoom' ? 'selected' : '' }}>Zoom</option>
                        <option value="google_meet" {{ old('meeting_platform') == 'google_meet' ? 'selected' : '' }}>Google Meet</option>
                        <option value="teams" {{ old('meeting_platform') == 'teams' ? 'selected' : '' }}>Microsoft Teams</option>
                        <option value="other" {{ old('meeting_platform') == 'other' ? 'selected' : '' }}>Otra</option>
                    </x-backend.select>
                    <x-backend.input-error field="meeting_platform" />
                </div>

                <div>
                    <x-backend.input-label for="meeting_url" value="Enlace de la videollamada" />
                    <x-backend.text-input type="url" id="meeting_url" name="meeting_url" value="{{ old('meeting_url') }}" placeholder="https://..." />
                    <x-backend.input-error field="meeting_url" />
                </div>
            </div>

            <div id="locationFields" style="display: none;">
                <div class="mb-3">
                    <x-backend.input-label for="location" value="Ubicación" />
                    <x-backend.text-input type="text" id="location" name="location" value="{{ old('location') }}" placeholder="Ciudad, país" />
                    <x-backend.input-error field="location" />
                </div>

                <div>
                    <x-backend.input-label for="location_details" value="Detalles del lugar" />
                    <x-backend.textarea id="location_details" name="location_details" rows="2" placeholder="Dirección exacta, referencias...">{{ old('location_details') }}</x-backend.textarea>
                    <x-backend.input-error field="location_details" />
                </div>
            </div>

            <hr class="my-4">

            <h3 class="h5 mb-3">Opciones adicionales</h3>

            <div class="mb-3">
                <div class="form-check">
                    <input type="checkbox" name="allow_chat" value="1" {{ old('allow_chat', true) ? 'checked' : '' }} class="form-check-input" id="allow_chat">
                    <label class="form-check-label" for="allow_chat">Permitir chat durante la conversación</label>
                </div>
            </div>

            <div class="mb-3">
                <div class="form-check">
                    <input type="checkbox" name="allow_recording" value="1" {{ old('allow_recording') ? 'checked' : '' }} class="form-check-input" id="allow_recording">
                    <label class="form-check-label" for="allow_recording">Permitir grabación de la sesión</label>
                </div>
            </div>

            <div class="mb-4">
                <div class="form-check">
                    <input type="checkbox" name="require_approval" value="1" {{ old('require_approval') ? 'checked' : '' }} class="form-check-input" id="require_approval">
                    <label class="form-check-label" for="require_approval">Requiere mi aprobación para unirse</label>
                </div>
            </div>

            <!-- Botones -->
            <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                <a href="{{ route('conversations.index') }}" class="btn btn-secondary">
                    Cancelar
                </a>
                <x-backend.button type="submit">
                    Crear conversación
                </x-backend.button>
            </div>
        </form>
    </x-backend.card>

    <script>
        function toggleLocationFields() {
            const type = document.getElementById('type').value;
            const onlineFields = document.getElementById('onlineFields');
            const locationFields = document.getElementById('locationFields');

            if (type === 'online') {
                onlineFields.style.display = 'block';
                locationFields.style.display = 'none';
            } else if (type === 'in_person') {
                onlineFields.style.display = 'none';
                locationFields.style.display = 'block';
            } else if (type === 'hybrid') {
                onlineFields.style.display = 'block';
                locationFields.style.display = 'block';
            }
        }

        // Llamar al cargar la página
        document.addEventListener('DOMContentLoaded', toggleLocationFields);
    </script>
</x-app-layout>
