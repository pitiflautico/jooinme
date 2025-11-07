<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Gestionar Participantes: {{ $conversation->title }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('conversations.participants.invite', $conversation) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition text-sm">
                    + Invitar participantes
                </a>
                <a href="{{ route('conversations.show', $conversation) }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition text-sm">
                    Ver conversación
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Messages -->
            @if(session('success'))
                <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-200 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-200 px-4 py-3 rounded relative">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Stats Overview -->
            <div class="grid md:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Participantes Activos</div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $accepted->count() }}</div>
                    <div class="text-xs text-gray-500 mt-1">de {{ $conversation->max_participants }} máximo</div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Solicitudes Pendientes</div>
                    <div class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $pending->count() }}</div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Invitaciones Enviadas</div>
                    <div class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $pendingInvitations->count() }}</div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Espacios Disponibles</div>
                    <div class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $conversation->max_participants - $accepted->count() }}</div>
                </div>
            </div>

            <!-- Pending Requests -->
            @if($pending->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Solicitudes Pendientes ({{ $pending->count() }})</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($pending as $participation)
                                <div class="flex items-center justify-between p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 bg-yellow-600 rounded-full flex items-center justify-center text-white text-xl font-bold">
                                            {{ substr($participation->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-gray-900 dark:text-white">
                                                <a href="{{ route('users.show', $participation->user) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">
                                                    {{ $participation->user->name }}
                                                </a>
                                            </h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $participation->user->email }}</p>
                                            <p class="text-xs text-gray-500 mt-1">Solicitó unirse {{ $participation->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <form action="{{ route('conversations.participants.approve', [$conversation, $participation]) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition text-sm">
                                                ✓ Aprobar
                                            </button>
                                        </form>
                                        <form action="{{ route('conversations.participants.reject', [$conversation, $participation]) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition text-sm">
                                                ✗ Rechazar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Accepted Participants -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Participantes Activos ({{ $accepted->count() }})</h3>
                </div>
                <div class="p-6">
                    @if($accepted->count() > 0)
                        <div class="grid md:grid-cols-2 gap-4">
                            @foreach($accepted as $participation)
                                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 bg-indigo-600 rounded-full flex items-center justify-center text-white text-xl font-bold">
                                            {{ substr($participation->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-gray-900 dark:text-white">
                                                <a href="{{ route('users.show', $participation->user) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">
                                                    {{ $participation->user->name }}
                                                </a>
                                                @if($participation->user_id === $conversation->owner_id)
                                                    <span class="text-xs bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-400 px-2 py-1 rounded ml-2">Organizador</span>
                                                @else
                                                    <span class="text-xs bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 px-2 py-1 rounded ml-2">{{ ucfirst($participation->role) }}</span>
                                                @endif
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Unido {{ $participation->joined_at?->diffForHumans() ?? $participation->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    @if($participation->user_id !== $conversation->owner_id)
                                        <div class="relative group">
                                            <button class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white p-2">
                                                ⋮
                                            </button>
                                            <div class="hidden group-hover:block absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-10">
                                                <form action="{{ route('conversations.participants.update-role', [$conversation, $participation]) }}" method="POST" class="p-2">
                                                    @csrf
                                                    <select name="role" onchange="this.form.submit()" class="w-full text-sm rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                                        <option value="member" {{ $participation->role === 'member' ? 'selected' : '' }}>Miembro</option>
                                                        <option value="moderator" {{ $participation->role === 'moderator' ? 'selected' : '' }}>Moderador</option>
                                                        <option value="co_host" {{ $participation->role === 'co_host' ? 'selected' : '' }}>Co-anfitrión</option>
                                                    </select>
                                                </form>
                                                <form action="{{ route('conversations.participants.remove', [$conversation, $participation]) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar a este participante?')" class="p-2 border-t border-gray-200 dark:border-gray-700">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="w-full text-left text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 px-2 py-1 rounded">
                                                        Eliminar
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            No hay participantes activos aún.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Pending Invitations -->
            @if($pendingInvitations->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Invitaciones Pendientes ({{ $pendingInvitations->count() }})</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            @foreach($pendingInvitations as $invitation)
                                <div class="flex items-center justify-between p-3 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800 rounded-lg">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">
                                            @if($invitation->invitee)
                                                {{ $invitation->invitee->name }} ({{ $invitation->invitee->email }})
                                            @else
                                                {{ $invitation->email }}
                                            @endif
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            Invitado por {{ $invitation->inviter->name }} {{ $invitation->created_at->diffForHumans() }}
                                            · Expira {{ $invitation->expires_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    <span class="text-xs bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400 px-3 py-1 rounded-full">
                                        Pendiente
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
