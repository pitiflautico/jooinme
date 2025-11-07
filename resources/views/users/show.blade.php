<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Perfil de {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Mensajes -->
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid md:grid-cols-3 gap-6">
                <!-- Sidebar -->
                <div class="md:col-span-1 space-y-6">
                    <!-- Información del usuario -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                        <div class="text-center">
                            <!-- Avatar -->
                            <div class="w-32 h-32 bg-indigo-600 rounded-full flex items-center justify-center text-white text-5xl font-bold mx-auto mb-4">
                                {{ substr($user->name, 0, 1) }}
                            </div>

                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $user->name }}</h3>

                            @if($user->bio)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">{{ $user->bio }}</p>
                            @endif

                            @if($user->location)
                                <div class="flex items-center justify-center text-sm text-gray-500 mb-4">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ $user->location }}
                                </div>
                            @endif

                            <!-- Botones de acción -->
                            @auth
                                @if(auth()->id() !== $user->id)
                                    <div class="mt-4">
                                        @if($isFollowing)
                                            <form action="{{ route('users.unfollow', $user) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="w-full bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-6 py-2 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition font-semibold">
                                                    Dejar de seguir
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('users.follow', $user) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="w-full bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition font-semibold">
                                                    Seguir
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @endif
                            @endauth
                        </div>
                    </div>

                    <!-- Estadísticas -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Estadísticas</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">Organizando</span>
                                <span class="font-bold text-gray-900 dark:text-white">{{ $stats['organizing'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">Participando</span>
                                <span class="font-bold text-gray-900 dark:text-white">{{ $stats['participating'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">Seguidores</span>
                                <span class="font-bold text-gray-900 dark:text-white">{{ $stats['followers'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">Siguiendo</span>
                                <span class="font-bold text-gray-900 dark:text-white">{{ $stats['following'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">Logros</span>
                                <span class="font-bold text-gray-900 dark:text-white">{{ $stats['achievements'] }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Intereses -->
                    @if($user->interests && count($user->interests) > 0)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Intereses</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach($user->interests as $interest)
                                    <span class="bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400 text-xs px-3 py-1 rounded-full">
                                        {{ $interest }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Logros -->
                    @if($user->achievements->count() > 0)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Logros Recientes</h4>
                            <div class="space-y-3">
                                @foreach($user->achievements->take(5) as $achievement)
                                    <div class="flex items-center">
                                        <div class="text-3xl mr-3">{{ $achievement->icon ?? '🏆' }}</div>
                                        <div>
                                            <p class="font-medium text-sm text-gray-900 dark:text-white">{{ $achievement->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $achievement->points }} puntos</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Contenido principal -->
                <div class="md:col-span-2 space-y-6">
                    <!-- Conversaciones organizadas -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Conversaciones que organiza</h3>
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
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2 line-clamp-2">{{ Str::limit($conversation->description, 100) }}</p>
                                            <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                                <span class="bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400 px-2 py-1 rounded">
                                                    {{ $conversation->topic->name ?? 'General' }}
                                                </span>
                                                <span>{{ $conversation->type === 'online' ? '🌐' : '📍' }}</span>
                                                <span>👥 {{ $conversation->participations_count }} participantes</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <p class="text-gray-500 dark:text-gray-400">No ha organizado conversaciones aún.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Conversaciones en las que participa -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Participando en</h3>
                        </div>
                        <div class="p-6">
                            @forelse($participating as $participation)
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
                                                <span>Organizado por {{ $participation->conversation->owner->name }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <p class="text-gray-500 dark:text-gray-400">No participa en conversaciones aún.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
