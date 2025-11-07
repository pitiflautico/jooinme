<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $conversation->title }}
            </h2>
            @auth
                @if($conversation->isOwner(auth()->user()))
                    <div class="flex gap-2">
                        <a href="{{ route('conversations.edit', $conversation) }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                            Editar
                        </a>
                    </div>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Mensajes -->
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @if(session('info'))
                <div class="mb-6 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('info') }}</span>
                </div>
            @endif

            <div class="grid md:grid-cols-3 gap-6">
                <!-- Columna principal -->
                <div class="md:col-span-2 space-y-6">
                    <!-- Imagen y descripción -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
                        @if($conversation->cover_image)
                            <img src="{{ $conversation->cover_image }}" alt="{{ $conversation->title }}" class="w-full h-64 object-cover">
                        @else
                            <div class="w-full h-64 bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-6xl">
                                {{ $conversation->topic->icon ?? '💬' }}
                            </div>
                        @endif

                        <div class="p-6">
                            <div class="flex items-center gap-2 mb-4 flex-wrap">
                                <span class="bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400 text-sm font-semibold px-4 py-1 rounded-full">
                                    {{ $conversation->topic->name ?? 'General' }}
                                </span>
                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $conversation->type === 'online' ? '🌐 Online' : ($conversation->type === 'in_person' ? '📍 Presencial' : '🔄 Híbrido') }}
                                </span>
                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                    🔁 {{ ucfirst($conversation->frequency) }}
                                </span>
                                @if($conversation->privacy === 'private')
                                    <span class="text-sm text-amber-600">🔒 Privada</span>
                                @elseif($conversation->privacy === 'moderated')
                                    <span class="text-sm text-blue-600">✋ Moderada</span>
                                @endif
                            </div>

                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">{{ $conversation->title }}</h1>
                            <div class="prose dark:prose-invert max-w-none">
                                <p class="text-gray-600 dark:text-gray-400">{{ $conversation->description }}</p>
                            </div>

                            @if($conversation->tags && count($conversation->tags) > 0)
                                <div class="mt-6 flex flex-wrap gap-2">
                                    @foreach($conversation->tags as $tag)
                                        <span class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs px-3 py-1 rounded-full">
                                            #{{ $tag }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Detalles de ubicación/enlace -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Detalles del encuentro</h3>

                        @if($conversation->type === 'online' || $conversation->type === 'hybrid')
                            <div class="mb-4">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-indigo-600 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">Plataforma: {{ ucfirst($conversation->meeting_platform ?? 'Por definir') }}</p>
                                        @if($conversation->meeting_url && $isParticipant)
                                            <a href="{{ $conversation->meeting_url }}" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-700 mt-1 inline-block">
                                                Unirse a la videollamada →
                                            </a>
                                        @elseif($conversation->meeting_url && !$isParticipant)
                                            <p class="text-sm text-gray-500 mt-1">Enlace disponible solo para participantes</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($conversation->type === 'in_person' || $conversation->type === 'hybrid')
                            <div class="mb-4">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-indigo-600 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">Ubicación: {{ $conversation->location ?? 'Por definir' }}</p>
                                        @if($conversation->location_details)
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $conversation->location_details }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($conversation->starts_at)
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-indigo-600 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Inicio: {{ $conversation->starts_at->format('d/m/Y H:i') }}</p>
                                    @if($conversation->ends_at)
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Fin: {{ $conversation->ends_at->format('d/m/Y H:i') }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Próximas sesiones -->
                    @if($conversation->scheduleSlots->count() > 0)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Próximas sesiones</h3>
                            <div class="space-y-3">
                                @foreach($conversation->scheduleSlots as $slot)
                                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $slot->scheduled_at->format('d/m/Y') }}</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $slot->scheduled_at->format('H:i') }} - {{ $slot->ends_at->format('H:i') }}</p>
                                        </div>
                                        <span class="text-xs px-3 py-1 rounded-full {{ $slot->status === 'scheduled' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                            {{ ucfirst($slot->status) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Organizador -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Organizador</h3>
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-indigo-600 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                {{ substr($conversation->owner->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $conversation->owner->name }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $conversation->owner->email }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Participantes -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Participantes</h3>
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Ocupación</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $conversation->current_participants }}/{{ $conversation->max_participants }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ ($conversation->current_participants / $conversation->max_participants) * 100 }}%"></div>
                            </div>
                        </div>

                        @if($conversation->participations->where('status', 'accepted')->count() > 0)
                            <div class="space-y-2 max-h-48 overflow-y-auto">
                                @foreach($conversation->participations->where('status', 'accepted')->take(5) as $participation)
                                    <div class="flex items-center text-sm">
                                        <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center text-gray-700 font-medium mr-2">
                                            {{ substr($participation->user->name, 0, 1) }}
                                        </div>
                                        <span class="text-gray-900 dark:text-white">{{ $participation->user->name }}</span>
                                    </div>
                                @endforeach
                                @if($conversation->participations->where('status', 'accepted')->count() > 5)
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                        Y {{ $conversation->participations->where('status', 'accepted')->count() - 5 }} más...
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Acciones -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                        @auth
                            @if($isParticipant)
                                @if($userParticipation->status === 'pending')
                                    <div class="bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4 mb-4">
                                        <p class="text-sm text-yellow-800 dark:text-yellow-200">Tu solicitud está pendiente de aprobación</p>
                                    </div>
                                @else
                                    <div class="bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 rounded-lg p-4 mb-4">
                                        <p class="text-sm text-green-800 dark:text-green-200">✓ Eres parte de esta conversación</p>
                                    </div>
                                @endif

                                @if(!$conversation->isOwner(auth()->user()))
                                    <form action="{{ route('conversations.leave', $conversation) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full bg-red-600 text-white py-3 rounded-lg hover:bg-red-700 transition font-semibold" onclick="return confirm('¿Seguro que quieres abandonar esta conversación?')">
                                            Abandonar conversación
                                        </button>
                                    </form>
                                @endif
                            @elseif($canJoin)
                                <form action="{{ route('conversations.join', $conversation) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 transition font-semibold mb-2">
                                        {{ $conversation->require_approval ? 'Solicitar unirse' : 'Unirse ahora' }}
                                    </button>
                                </form>
                                @if($conversation->require_approval)
                                    <p class="text-xs text-gray-600 dark:text-gray-400 text-center">Esta conversación requiere aprobación del organizador</p>
                                @endif
                            @elseif($conversation->isFull())
                                <div class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 py-3 rounded-lg text-center font-semibold">
                                    Conversación llena
                                </div>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="block w-full bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 transition font-semibold text-center">
                                Inicia sesión para unirte
                            </a>
                        @endauth
                    </div>

                    <!-- Configuración -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Configuración</h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Chat permitido</span>
                                <span class="text-gray-900 dark:text-white">{{ $conversation->allow_chat ? '✓ Sí' : '✗ No' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Grabación</span>
                                <span class="text-gray-900 dark:text-white">{{ $conversation->allow_recording ? '✓ Sí' : '✗ No' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Requiere aprobación</span>
                                <span class="text-gray-900 dark:text-white">{{ $conversation->require_approval ? '✓ Sí' : '✗ No' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
