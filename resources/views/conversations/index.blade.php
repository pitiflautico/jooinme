<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Explorar Conversaciones') }}
            </h2>
            @auth
                <a href="{{ route('conversations.create') }}" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                    Crear conversación
                </a>
            @endauth
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filtros -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
                <form method="GET" action="{{ route('conversations.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Búsqueda -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Buscar</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Título o descripción..." class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                        </div>

                        <!-- Topic -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tema</label>
                            <select name="topic" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                <option value="">Todos los temas</option>
                                @foreach($topics as $topic)
                                    <option value="{{ $topic->id }}" {{ request('topic') == $topic->id ? 'selected' : '' }}>
                                        {{ $topic->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tipo -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipo</label>
                            <select name="type" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                <option value="">Todos</option>
                                <option value="online" {{ request('type') == 'online' ? 'selected' : '' }}>🌐 Online</option>
                                <option value="in_person" {{ request('type') == 'in_person' ? 'selected' : '' }}>📍 Presencial</option>
                                <option value="hybrid" {{ request('type') == 'hybrid' ? 'selected' : '' }}>🔄 Híbrido</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Privacidad -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Privacidad</label>
                            <select name="privacy" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                <option value="">Todas</option>
                                <option value="public" {{ request('privacy') == 'public' ? 'selected' : '' }}>Pública</option>
                                <option value="moderated" {{ request('privacy') == 'moderated' ? 'selected' : '' }}>Moderada</option>
                                <option value="private" {{ request('privacy') == 'private' ? 'selected' : '' }}>Privada</option>
                            </select>
                        </div>

                        <!-- Frecuencia -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Frecuencia</label>
                            <select name="frequency" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                <option value="">Todas</option>
                                <option value="once" {{ request('frequency') == 'once' ? 'selected' : '' }}>Una vez</option>
                                <option value="weekly" {{ request('frequency') == 'weekly' ? 'selected' : '' }}>Semanal</option>
                                <option value="biweekly" {{ request('frequency') == 'biweekly' ? 'selected' : '' }}>Quincenal</option>
                                <option value="monthly" {{ request('frequency') == 'monthly' ? 'selected' : '' }}>Mensual</option>
                            </select>
                        </div>

                        <!-- Ordenar -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ordenar por</label>
                            <select name="sort" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Más recientes</option>
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Más populares</option>
                                <option value="upcoming" {{ request('sort') == 'upcoming' ? 'selected' : '' }}>Próximas</option>
                            </select>
                        </div>

                        <!-- Botones -->
                        <div class="flex items-end gap-2">
                            <button type="submit" class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                                Filtrar
                            </button>
                            <a href="{{ route('conversations.index') }}" class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                                Limpiar
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Resultados -->
            <div class="grid md:grid-cols-3 gap-6 mb-6">
                @forelse($conversations as $conversation)
                    <div class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition">
                        @if($conversation->cover_image)
                            <img src="{{ $conversation->cover_image }}" alt="{{ $conversation->title }}" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-4xl">
                                {{ $conversation->topic->icon ?? '💬' }}
                            </div>
                        @endif

                        <div class="p-6">
                            <div class="flex items-center gap-2 mb-3 flex-wrap">
                                <span class="bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400 text-xs font-semibold px-3 py-1 rounded-full">
                                    {{ $conversation->topic->name ?? 'General' }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    {{ $conversation->type === 'online' ? '🌐 Online' : ($conversation->type === 'in_person' ? '📍 Presencial' : '🔄 Híbrido') }}
                                </span>
                                @if($conversation->privacy === 'private')
                                    <span class="text-xs text-amber-600">🔒 Privada</span>
                                @endif
                            </div>

                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">{{ $conversation->title }}</h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-2">{{ $conversation->description }}</p>

                            <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                    <span>{{ $conversation->current_participants }}/{{ $conversation->max_participants }}</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>{{ ucfirst($conversation->frequency) }}</span>
                                </div>
                            </div>

                            <a href="{{ route('conversations.show', $conversation) }}" class="block w-full text-center bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition font-semibold">
                                Ver detalles
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12 bg-white dark:bg-gray-800 rounded-lg">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No se encontraron conversaciones</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Intenta ajustar los filtros o crea una nueva conversación.</p>
                        @auth
                            <div class="mt-6">
                                <a href="{{ route('conversations.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                    Crear conversación
                                </a>
                            </div>
                        @endauth
                    </div>
                @endforelse
            </div>

            <!-- Paginación -->
            @if($conversations->hasPages())
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4">
                    {{ $conversations->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
