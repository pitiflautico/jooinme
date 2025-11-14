<x-app-layout>
    <x-slot name="header">
        <h2 class="mb-0">Nueva Sesión: {{ $conversation->title }}</h2>
    </x-slot>

    <x-backend.card>
        <form action="{{ route('schedule-slots.store', $conversation) }}" method="POST">
            @csrf

            <div class="mb-4">
                <x-backend.input-label for="scheduled_at" value="Fecha y Hora *" />
                <x-backend.text-input type="datetime-local" id="scheduled_at" name="scheduled_at" required value="{{ old('scheduled_at') }}" />
                <x-backend.input-error field="scheduled_at" />
            </div>

            <div class="mb-4">
                <x-backend.input-label for="duration_minutes" value="Duración (minutos) *" />
                <x-backend.text-input type="number" id="duration_minutes" name="duration_minutes" required value="{{ old('duration_minutes', $conversation->duration_minutes ?? 60) }}" min="15" max="480" />
                <x-backend.input-error field="duration_minutes" />
            </div>

            <div class="mb-4">
                <x-backend.input-label for="title" value="Título (opcional, por defecto el de la conversación)" />
                <x-backend.text-input type="text" id="title" name="title" value="{{ old('title') }}" placeholder="{{ $conversation->title }}" />
                <x-backend.input-error field="title" />
            </div>

            <div class="mb-4">
                <x-backend.input-label for="description" value="Descripción (opcional)" />
                <x-backend.textarea id="description" name="description" rows="3" placeholder="Temas específicos de esta sesión...">{{ old('description') }}</x-backend.textarea>
                <x-backend.input-error field="description" />
            </div>

            <div class="mb-4">
                <x-backend.input-label for="meeting_url" value="Enlace de videollamada (opcional)" />
                <x-backend.text-input type="url" id="meeting_url" name="meeting_url" value="{{ old('meeting_url', $conversation->meeting_url) }}" placeholder="https://..." />
                <x-backend.input-error field="meeting_url" />
            </div>

            <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                <a href="{{ route('schedule-slots.index', $conversation) }}" class="btn btn-secondary">
                    Cancelar
                </a>
                <x-backend.button type="submit">
                    Crear Sesión
                </x-backend.button>
            </div>
        </form>
    </x-backend.card>
</x-app-layout>
