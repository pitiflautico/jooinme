<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Nueva Sesión: {{ $conversation->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-8">
                <form action="{{ route('schedule-slots.store', $conversation) }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label for="scheduled_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Fecha y Hora *
                        </label>
                        <input type="datetime-local" id="scheduled_at" name="scheduled_at" required value="{{ old('scheduled_at') }}" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                        @error('scheduled_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="duration_minutes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Duración (minutos) *
                        </label>
                        <input type="number" id="duration_minutes" name="duration_minutes" required value="{{ old('duration_minutes', $conversation->duration_minutes ?? 60) }}" min="15" max="480" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                        @error('duration_minutes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Título (opcional, por defecto el de la conversación)
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" placeholder="{{ $conversation->title }}" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Descripción (opcional)
                        </label>
                        <textarea id="description" name="description" rows="3" placeholder="Temas específicos de esta sesión..." class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="meeting_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Enlace de videollamada (opcional)
                        </label>
                        <input type="url" id="meeting_url" name="meeting_url" value="{{ old('meeting_url', $conversation->meeting_url) }}" placeholder="https://..." class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                        @error('meeting_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('schedule-slots.index', $conversation) }}" class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-6 py-3 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                            Cancelar
                        </a>
                        <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-indigo-700 transition font-semibold">
                            Crear Sesión
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
