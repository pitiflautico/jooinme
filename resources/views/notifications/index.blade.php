<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Notificaciones
            </h2>
            @if($notifications->where('read_at', null)->count() > 0)
                <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                        Marcar todas como leídas
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats -->
            <div class="grid md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total</div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $notifications->count() }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">No leídas</div>
                    <div class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $notifications->where('read_at', null)->count() }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Leídas</div>
                    <div class="text-3xl font-bold text-gray-600 dark:text-gray-400">{{ $notifications->whereNotNull('read_at')->count() }}</div>
                </div>
            </div>

            <!-- Notifications List -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Todas las Notificaciones</h3>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($notifications as $notification)
                        <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition {{ $notification->read_at ? 'opacity-60' : '' }}">
                            <div class="flex items-start gap-4">
                                <!-- Icon based on notification type -->
                                <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center
                                    {{ $notification->type === 'invitation' ? 'bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400' : '' }}
                                    {{ $notification->type === 'participation' ? 'bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400' : '' }}
                                    {{ $notification->type === 'feedback' ? 'bg-yellow-100 dark:bg-yellow-900 text-yellow-600 dark:text-yellow-400' : '' }}
                                    {{ $notification->type === 'session' ? 'bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400' : '' }}
                                    {{ !in_array($notification->type, ['invitation', 'participation', 'feedback', 'session']) ? 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400' : '' }}
                                ">
                                    @if($notification->type === 'invitation')
                                        ✉️
                                    @elseif($notification->type === 'participation')
                                        👥
                                    @elseif($notification->type === 'feedback')
                                        ⭐
                                    @elseif($notification->type === 'session')
                                        📅
                                    @else
                                        🔔
                                    @endif
                                </div>

                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $notification->data['title'] ?? 'Notificación' }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        {{ $notification->data['message'] ?? '' }}
                                    </p>
                                    <div class="flex items-center gap-3 mt-2">
                                        <span class="text-xs text-gray-500 dark:text-gray-500">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </span>
                                        @if($notification->read_at)
                                            <span class="text-xs text-gray-500">
                                                · Leída {{ $notification->read_at->diffForHumans() }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex-shrink-0 flex items-center gap-2">
                                    @if(!$notification->read_at)
                                        <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">
                                                Marcar leída
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar esta notificación?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-red-600 dark:text-red-400 hover:underline">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Action link if provided -->
                            @if(isset($notification->data['action_url']))
                                <div class="mt-3 pl-14">
                                    <a href="{{ $notification->data['action_url'] }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline inline-flex items-center gap-1">
                                        {{ $notification->data['action_text'] ?? 'Ver más' }}
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="p-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <p class="mt-4 text-gray-600 dark:text-gray-400">No tienes notificaciones</p>
                        </div>
                    @endforelse
                </div>

                @if($notifications->hasPages())
                    <div class="p-6 border-t border-gray-200 dark:border-gray-700">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
