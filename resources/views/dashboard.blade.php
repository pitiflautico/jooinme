<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Mi Dashboard') }}
            </h2>
            <a href="{{ route('conversations.create') }}" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                + Nueva conversación
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Stats Cards -->
            <div class="grid md:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-indigo-100 dark:bg-indigo-900 mr-4">
                            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Conversaciones</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['participations'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 dark:bg-green-900 mr-4">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Organizando</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['organizing'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900 mr-4">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Participantes totales</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_participants'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Acciones rápidas</h3>
                <div class="grid md:grid-cols-3 gap-4">
                    <a href="{{ route('conversations.create') }}" class="flex items-center p-4 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg hover:border-indigo-500 dark:hover:border-indigo-400 transition group">
                        <div class="p-2 bg-indigo-100 dark:bg-indigo-900 rounded mr-3 group-hover:bg-indigo-200">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Crear conversación</span>
                    </a>

                    <a href="{{ route('conversations.index') }}" class="flex items-center p-4 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg hover:border-green-500 dark:hover:border-green-400 transition group">
                        <div class="p-2 bg-green-100 dark:bg-green-900 rounded mr-3 group-hover:bg-green-200">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Explorar conversaciones</span>
                    </a>

                    <a href="{{ route('profile.edit') }}" class="flex items-center p-4 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg hover:border-purple-500 dark:hover:border-purple-400 transition group">
                        <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded mr-3 group-hover:bg-purple-200">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Editar perfil</span>
                    </a>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <!-- Mis Conversaciones -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Mis conversaciones</h3>
                            <a href="{{ route('conversations.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700 dark:text-indigo-400">
                                Ver todas →
                            </a>
                        </div>
                    </div>

                    <div class="p-6">
                        @forelse($myConversations as $participation)
                            <div class="mb-4 last:mb-0 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900 dark:text-white mb-1">
                                            <a href="{{ route('conversations.show', $participation->conversation) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">
                                                {{ $participation->conversation->title }}
                                            </a>
                                        </h4>
                                        <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                            <span class="bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400 px-2 py-1 rounded">
                                                {{ $participation->conversation->topic->name ?? 'General' }}
                                            </span>
                                            <span>{{ $participation->conversation->type === 'online' ? '🌐' : '📍' }}</span>
                                            <span>{{ ucfirst($participation->conversation->frequency) }}</span>
                                        </div>
                                    </div>
                                    <span class="text-xs px-2 py-1 rounded {{ $participation->status === 'accepted' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $participation->status === 'accepted' ? 'Activo' : 'Pendiente' }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">No estás en ninguna conversación aún</p>
                                <a href="{{ route('conversations.index') }}" class="mt-3 inline-block text-sm text-indigo-600 hover:text-indigo-700 dark:text-indigo-400">
                                    Explorar conversaciones
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Conversaciones que organizo -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Organizando</h3>
                            <a href="{{ route('conversations.create') }}" class="text-sm text-indigo-600 hover:text-indigo-700 dark:text-indigo-400">
                                + Nueva
                            </a>
                        </div>
                    </div>

                    <div class="p-6">
                        @forelse($organizing as $conversation)
                            <div class="mb-4 last:mb-0 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900 dark:text-white mb-1">
                                            <a href="{{ route('conversations.show', $conversation) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">
                                                {{ $conversation->title }}
                                            </a>
                                        </h4>
                                        <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                            <span>👥 {{ $conversation->participations_count }} participantes</span>
                                            <span>{{ $conversation->type === 'online' ? '🌐' : '📍' }}</span>
                                        </div>
                                    </div>
                                    <a href="{{ route('conversations.edit', $conversation) }}" class="text-xs text-indigo-600 hover:text-indigo-700 dark:text-indigo-400">
                                        Editar
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">No has creado ninguna conversación</p>
                                <a href="{{ route('conversations.create') }}" class="mt-3 inline-block text-sm text-indigo-600 hover:text-indigo-700 dark:text-indigo-400">
                                    Crear tu primera conversación
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
