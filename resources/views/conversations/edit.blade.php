<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Conversación') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-8">
                <form method="POST" action="{{ route('conversations.update', $conversation) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Tema -->
                    <div>
                        <label for="topic_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tema * <span class="text-xs text-gray-500">(¿De qué tratará la conversación?)</span>
                        </label>
                        <select id="topic_id" name="topic_id" required class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            <option value="">Selecciona un tema</option>
                            @foreach($topics as $topic)
                                <option value="{{ $topic->id }}" {{ old('topic_id', $conversation->topic_id) == $topic->id ? 'selected' : '' }}>
                                    {{ $topic->icon }} {{ $topic->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('topic_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Título -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Título * <span class="text-xs text-gray-500">(Sé claro y atractivo)</span>
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title', $conversation->title) }}" required maxlength="255" placeholder="Ej: Club de Lectura Mensual: Ciencia Ficción" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Descripción -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Descripción * <span class="text-xs text-gray-500">(Explica qué harán, qué pueden esperar)</span>
                        </label>
                        <textarea id="description" name="description" required rows="4" placeholder="Describe tu conversación..." class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">{{ old('description', $conversation->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Configuración</h3>

                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Frecuencia -->
                            <div>
                                <label for="frequency" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Frecuencia *
                                </label>
                                <select id="frequency" name="frequency" required class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                    <option value="once" {{ old('frequency', $conversation->frequency) == 'once' ? 'selected' : '' }}>Una vez</option>
                                    <option value="weekly" {{ old('frequency', $conversation->frequency) == 'weekly' ? 'selected' : '' }}>Semanal</option>
                                    <option value="biweekly" {{ old('frequency', $conversation->frequency) == 'biweekly' ? 'selected' : '' }}>Quincenal</option>
                                    <option value="monthly" {{ old('frequency', $conversation->frequency) == 'monthly' ? 'selected' : '' }}>Mensual</option>
                                </select>
                                @error('frequency')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tipo -->
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Modalidad *
                                </label>
                                <select id="type" name="type" required class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" onchange="toggleLocationFields()">
                                    <option value="online" {{ old('type', $conversation->type) == 'online' ? 'selected' : '' }}>🌐 Online</option>
                                    <option value="in_person" {{ old('type', $conversation->type) == 'in_person' ? 'selected' : '' }}>📍 Presencial</option>
                                    <option value="hybrid" {{ old('type', $conversation->type) == 'hybrid' ? 'selected' : '' }}>🔄 Híbrido</option>
                                </select>
                                @error('type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Privacidad -->
                            <div>
                                <label for="privacy" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Privacidad *
                                </label>
                                <select id="privacy" name="privacy" required class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                    <option value="public" {{ old('privacy', $conversation->privacy) == 'public' ? 'selected' : '' }}>Pública (cualquiera puede unirse)</option>
                                    <option value="moderated" {{ old('privacy', $conversation->privacy) == 'moderated' ? 'selected' : '' }}>Moderada (requiere aprobación)</option>
                                    <option value="private" {{ old('privacy', $conversation->privacy) == 'private' ? 'selected' : '' }}>Privada (solo por invitación)</option>
                                </select>
                                @error('privacy')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Máximo de participantes -->
                            <div>
                                <label for="max_participants" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Máximo de participantes *
                                </label>
                                <input type="number" id="max_participants" name="max_participants" value="{{ old('max_participants', $conversation->max_participants) }}" required min="2" max="100" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                @error('max_participants')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Fechas -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Fecha y hora</h3>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="starts_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Fecha de inicio
                                </label>
                                <input type="datetime-local" id="starts_at" name="starts_at" value="{{ old('starts_at', $conversation->starts_at?->format('Y-m-d\TH:i')) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                @error('starts_at')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="ends_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Fecha de fin (opcional)
                                </label>
                                <input type="datetime-local" id="ends_at" name="ends_at" value="{{ old('ends_at', $conversation->ends_at?->format('Y-m-d\TH:i')) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                @error('ends_at')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Detalles de ubicación/online -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Detalles del encuentro</h3>

                        <div id="onlineFields" class="space-y-4 mb-4">
                            <div>
                                <label for="meeting_platform" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Plataforma de videollamada
                                </label>
                                <select id="meeting_platform" name="meeting_platform" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                    <option value="">Selecciona una plataforma</option>
                                    <option value="zoom" {{ old('meeting_platform', $conversation->meeting_platform) == 'zoom' ? 'selected' : '' }}>Zoom</option>
                                    <option value="google_meet" {{ old('meeting_platform', $conversation->meeting_platform) == 'google_meet' ? 'selected' : '' }}>Google Meet</option>
                                    <option value="teams" {{ old('meeting_platform', $conversation->meeting_platform) == 'teams' ? 'selected' : '' }}>Microsoft Teams</option>
                                    <option value="other" {{ old('meeting_platform', $conversation->meeting_platform) == 'other' ? 'selected' : '' }}>Otra</option>
                                </select>
                                @error('meeting_platform')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="meeting_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Enlace de la videollamada
                                </label>
                                <input type="url" id="meeting_url" name="meeting_url" value="{{ old('meeting_url', $conversation->meeting_url) }}" placeholder="https://..." class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                @error('meeting_url')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div id="locationFields" class="space-y-4" style="display: none;">
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Ubicación
                                </label>
                                <input type="text" id="location" name="location" value="{{ old('location', $conversation->location) }}" placeholder="Ciudad, país" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                @error('location')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="location_details" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Detalles del lugar
                                </label>
                                <textarea id="location_details" name="location_details" rows="2" placeholder="Dirección exacta, referencias..." class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">{{ old('location_details', $conversation->location_details) }}</textarea>
                                @error('location_details')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Opciones adicionales -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Opciones adicionales</h3>

                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="checkbox" name="allow_chat" value="1" {{ old('allow_chat', $conversation->allow_chat) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Permitir chat durante la conversación</span>
                            </label>

                            <label class="flex items-center">
                                <input type="checkbox" name="allow_recording" value="1" {{ old('allow_recording', $conversation->allow_recording) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Permitir grabación de la sesión</span>
                            </label>

                            <label class="flex items-center">
                                <input type="checkbox" name="require_approval" value="1" {{ old('require_approval', $conversation->require_approval) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Requiere mi aprobación para unirse</span>
                            </label>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('conversations.index') }}" class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-6 py-3 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                            Cancelar
                        </a>
                        <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-indigo-700 transition font-semibold">
                            Actualizar conversación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
