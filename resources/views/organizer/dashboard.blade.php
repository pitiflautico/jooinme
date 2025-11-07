<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Dashboard de Organizador
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Overall Stats -->
            <div class="grid md:grid-cols-5 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-sm text-gray-600 dark:text-gray-400">Conversaciones</div>
                        <div class="p-2 bg-indigo-100 dark:bg-indigo-900 rounded">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_conversations'] }}</div>
                    <div class="text-xs text-gray-500 mt-1">{{ $stats['active_conversations'] }} activas</div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-sm text-gray-600 dark:text-gray-400">Participantes</div>
                        <div class="p-2 bg-green-100 dark:bg-green-900 rounded">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_participants'] }}</div>
                    <div class="text-xs text-gray-500 mt-1">Total acumulado</div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-sm text-gray-600 dark:text-gray-400">Sesiones</div>
                        <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_sessions'] }}</div>
                    <div class="text-xs text-gray-500 mt-1">Programadas</div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-sm text-gray-600 dark:text-gray-400">Valoración</div>
                        <div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded">
                            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['average_rating'] }}/5</div>
                    <div class="text-xs text-gray-500 mt-1">Promedio</div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-sm text-gray-600 dark:text-gray-400">Pendientes</div>
                        <div class="p-2 bg-red-100 dark:bg-red-900 rounded">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $pendingApprovals->count() }}</div>
                    <div class="text-xs text-gray-500 mt-1">Solicitudes</div>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <!-- Pending Approvals -->
                @if($pendingApprovals->count() > 0)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Solicitudes Pendientes</h3>
                        </div>
                        <div class="p-6 space-y-3 max-h-96 overflow-y-auto">
                            @foreach($pendingApprovals as $approval)
                                <div class="flex items-center justify-between p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-yellow-600 rounded-full flex items-center justify-center text-white font-bold">
                                            {{ substr($approval->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $approval->user->name }}</p>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">{{ $approval->conversation->title }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('conversations.participants.index', $approval->conversation) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                                        Ver →
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Upcoming Sessions -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Próximas Sesiones</h3>
                    </div>
                    <div class="p-6 space-y-3 max-h-96 overflow-y-auto">
                        @forelse($upcomingSessions as $session)
                            <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                                <p class="font-medium text-gray-900 dark:text-white">{{ $session->conversation->title }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    {{ $session->scheduled_at->format('d/m/Y H:i') }}
                                </p>
                                <a href="{{ route('schedule-slots.show', $session) }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline mt-1 inline-block">
                                    Ver detalles →
                                </a>
                            </div>
                        @empty
                            <p class="text-center text-gray-500 dark:text-gray-400 py-8">No hay sesiones próximas</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Performance by Conversation -->
            @if(count($conversationPerformance) > 0)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Rendimiento por Conversación</h3>
                    </div>
                    <div class="p-6 overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                                    <th class="pb-3 text-sm font-medium text-gray-700 dark:text-gray-300">Conversación</th>
                                    <th class="pb-3 text-sm font-medium text-gray-700 dark:text-gray-300">Sesiones</th>
                                    <th class="pb-3 text-sm font-medium text-gray-700 dark:text-gray-300">Asistencia Promedio</th>
                                    <th class="pb-3 text-sm font-medium text-gray-700 dark:text-gray-300">Valoración</th>
                                    <th class="pb-3 text-sm font-medium text-gray-700 dark:text-gray-300">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($conversationPerformance as $perf)
                                    <tr class="border-b border-gray-100 dark:border-gray-700 last:border-0">
                                        <td class="py-4">
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $perf['conversation']->title }}</p>
                                            <p class="text-xs text-gray-500">{{ $perf['conversation']->participations_count }} participantes</p>
                                        </td>
                                        <td class="py-4 text-gray-700 dark:text-gray-300">{{ $perf['total_sessions'] }}</td>
                                        <td class="py-4 text-gray-700 dark:text-gray-300">{{ $perf['average_attendance'] }}</td>
                                        <td class="py-4">
                                            <span class="text-yellow-500">★ {{ $perf['rating'] }}</span>
                                        </td>
                                        <td class="py-4">
                                            <a href="{{ route('conversations.show', $perf['conversation']) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                                                Ver →
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Recent Feedback -->
            @if($recentFeedback->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Feedback Reciente</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        @foreach($recentFeedback as $feedback)
                            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $feedback->user->name }}</p>
                                            <span class="text-yellow-500">
                                                @for($i = 0; $i < $feedback->rating; $i++)★@endfor
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $feedback->conversation->title }}</p>
                                        @if($feedback->comment)
                                            <p class="text-sm text-gray-700 dark:text-gray-300 mt-2">{{ Str::limit($feedback->comment, 100) }}</p>
                                        @endif
                                    </div>
                                    <span class="text-xs text-gray-500">{{ $feedback->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Growth Chart (Text-based) -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Crecimiento (Últimos 6 Meses)</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-6 gap-4">
                        @foreach($monthlyData as $month)
                            <div class="text-center">
                                <div class="text-xs text-gray-600 dark:text-gray-400 mb-2">{{ $month['month'] }}</div>
                                <div class="bg-indigo-100 dark:bg-indigo-900 rounded p-3">
                                    <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $month['participants'] }}</div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">participantes</div>
                                </div>
                                <div class="bg-blue-100 dark:bg-blue-900 rounded p-3 mt-2">
                                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $month['sessions'] }}</div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">sesiones</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- My Conversations -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Mis Conversaciones</h3>
                </div>
                <div class="p-6">
                    <div class="grid md:grid-cols-2 gap-4">
                        @forelse($conversations as $conversation)
                            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900 dark:text-white mb-1">
                                            <a href="{{ route('conversations.show', $conversation) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">
                                                {{ $conversation->title }}
                                            </a>
                                        </h4>
                                        <div class="flex items-center gap-3 text-xs text-gray-600 dark:text-gray-400">
                                            <span>👥 {{ $conversation->participations_count }}</span>
                                            <span>📅 {{ $conversation->schedule_slots_count }}</span>
                                            <span>⭐ {{ $conversation->feedback_count }}</span>
                                        </div>
                                    </div>
                                    <span class="text-xs px-2 py-1 rounded {{ $conversation->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $conversation->is_active ? 'Activa' : 'Inactiva' }}
                                    </span>
                                </div>
                                <div class="mt-3 flex gap-2">
                                    <a href="{{ route('conversations.participants.index', $conversation) }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">
                                        Gestionar participantes
                                    </a>
                                    <a href="{{ route('schedule-slots.index', $conversation) }}" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">
                                        Ver calendario
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-2 text-center py-8">
                                <p class="text-gray-500 dark:text-gray-400">No has creado ninguna conversación aún</p>
                                <a href="{{ route('conversations.create') }}" class="mt-4 inline-block text-indigo-600 dark:text-indigo-400 hover:underline">
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
