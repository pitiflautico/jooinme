<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Valorar Sesión
            </h2>
            <a href="{{ route('conversations.show', $conversation) }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition text-sm">
                ← Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-8">
                <div class="mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $conversation->title }}</h3>
                    <p class="text-gray-600 dark:text-gray-400">Sesión: {{ $scheduleSlot->scheduled_at->format('d/m/Y H:i') }}</p>
                </div>

                <form action="{{ route('feedback.store', $scheduleSlot) }}" method="POST" class="space-y-8">
                    @csrf

                    <!-- Attendance -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-4">¿Asististe a esta sesión?</h4>
                        <div class="flex gap-4">
                            <label class="flex items-center">
                                <input type="radio" name="attended" value="1" checked class="mr-2">
                                <span class="text-gray-700 dark:text-gray-300">Sí, asistí</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="attended" value="0" class="mr-2">
                                <span class="text-gray-700 dark:text-gray-300">No asistí</span>
                            </label>
                        </div>
                    </div>

                    <!-- Overall Rating -->
                    <div>
                        <label class="block text-lg font-semibold text-gray-900 dark:text-white mb-3">
                            Valoración General *
                        </label>
                        <div class="flex gap-4">
                            @for($i = 1; $i <= 5; $i++)
                                <label class="cursor-pointer group">
                                    <input type="radio" name="rating" value="{{ $i }}" required class="peer sr-only">
                                    <div class="text-5xl peer-checked:text-yellow-500 text-gray-300 dark:text-gray-600 group-hover:text-yellow-400 transition">
                                        ★
                                    </div>
                                </label>
                            @endfor
                        </div>
                        @error('rating')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Detailed Ratings -->
                    <div class="space-y-6 bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Valoración Detallada (Opcional)</h4>

                        <!-- Content Rating -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Contenido
                            </label>
                            <div class="flex gap-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <label class="cursor-pointer group">
                                        <input type="radio" name="content_rating" value="{{ $i }}" class="peer sr-only">
                                        <div class="text-3xl peer-checked:text-yellow-500 text-gray-300 dark:text-gray-600 group-hover:text-yellow-400 transition">
                                            ★
                                        </div>
                                    </label>
                                @endfor
                            </div>
                        </div>

                        <!-- Organization Rating -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Organización
                            </label>
                            <div class="flex gap-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <label class="cursor-pointer group">
                                        <input type="radio" name="organization_rating" value="{{ $i }}" class="peer sr-only">
                                        <div class="text-3xl peer-checked:text-yellow-500 text-gray-300 dark:text-gray-600 group-hover:text-yellow-400 transition">
                                            ★
                                        </div>
                                    </label>
                                @endfor
                            </div>
                        </div>

                        <!-- Atmosphere Rating -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Ambiente
                            </label>
                            <div class="flex gap-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <label class="cursor-pointer group">
                                        <input type="radio" name="atmosphere_rating" value="{{ $i }}" class="peer sr-only">
                                        <div class="text-3xl peer-checked:text-yellow-500 text-gray-300 dark:text-gray-600 group-hover:text-yellow-400 transition">
                                            ★
                                        </div>
                                    </label>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <!-- Comment -->
                    <div>
                        <label for="comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Comentarios (Opcional)
                        </label>
                        <textarea id="comment" name="comment" rows="4" placeholder="Comparte tu experiencia..." class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"></textarea>
                        @error('comment')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Testimonial -->
                    <div>
                        <label for="testimonial" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Testimonio Público (Opcional)
                        </label>
                        <textarea id="testimonial" name="testimonial" rows="3" placeholder="Un breve testimonio que podría aparecer en el perfil del organizador..." class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"></textarea>
                        <div class="mt-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_public" value="1" class="rounded border-gray-300 text-indigo-600 mr-2">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Hacer público este testimonio</span>
                            </label>
                        </div>
                        @error('testimonial')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Would Recommend -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="would_recommend" value="1" checked class="rounded border-gray-300 text-indigo-600 mr-2">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Recomendaría esta conversación</span>
                        </label>
                    </div>

                    <!-- Rate Participants -->
                    @if($participants->count() > 0)
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-8">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                Valora a otros participantes (Opcional)
                            </h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                                Ayuda a construir la reputación de la comunidad valorando a otros participantes
                            </p>

                            <div class="space-y-4">
                                @foreach($participants as $participant)
                                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                        <div class="flex items-center gap-3 mb-3">
                                            <div class="w-10 h-10 bg-indigo-600 rounded-full flex items-center justify-center text-white font-bold">
                                                {{ substr($participant->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900 dark:text-white">{{ $participant->name }}</p>
                                            </div>
                                        </div>

                                        <div class="flex gap-2 mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <label class="cursor-pointer group">
                                                    <input type="radio" name="participant_ratings[{{ $loop->parent->index }}][rating]" value="{{ $i }}" class="peer sr-only">
                                                    <input type="hidden" name="participant_ratings[{{ $loop->parent->index }}][user_id]" value="{{ $participant->id }}">
                                                    <div class="text-2xl peer-checked:text-yellow-500 text-gray-300 dark:text-gray-600 group-hover:text-yellow-400 transition">
                                                        ★
                                                    </div>
                                                </label>
                                            @endfor
                                        </div>

                                        <input type="text"
                                               name="participant_ratings[{{ $loop->index }}][comment]"
                                               placeholder="Comentario breve (opcional)"
                                               class="w-full text-sm rounded border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Submit -->
                    <div class="flex justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('conversations.show', $conversation) }}" class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-6 py-3 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                            Cancelar
                        </a>
                        <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-indigo-700 transition font-semibold">
                            Enviar Valoración
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
