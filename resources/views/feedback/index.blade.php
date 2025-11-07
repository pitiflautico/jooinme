<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Feedback: {{ $conversation->title }}
            </h2>
            <a href="{{ route('conversations.show', $conversation) }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition text-sm">
                ← Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Average Ratings -->
            <div class="grid md:grid-cols-5 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 text-center">
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">General</div>
                    <div class="text-4xl font-bold text-yellow-500 mb-1">{{ $averages['overall'] ?: '—' }}</div>
                    <div class="text-yellow-500 text-2xl">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= round($averages['overall']))
                                ★
                            @else
                                ☆
                            @endif
                        @endfor
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 text-center">
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Contenido</div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $averages['content'] ?: '—' }}</div>
                    <div class="text-xs text-gray-500 mt-1">de 5</div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 text-center">
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Organización</div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $averages['organization'] ?: '—' }}</div>
                    <div class="text-xs text-gray-500 mt-1">de 5</div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 text-center">
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Ambiente</div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $averages['atmosphere'] ?: '—' }}</div>
                    <div class="text-xs text-gray-500 mt-1">de 5</div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 text-center">
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Recomendaría</div>
                    <div class="text-3xl font-bold text-green-600 dark:text-green-400">{{ round($averages['would_recommend']) }}%</div>
                    <div class="text-xs text-gray-500 mt-1">de participantes</div>
                </div>
            </div>

            <!-- Public Testimonials -->
            @if($testimonials->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Testimonios Destacados</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid md:grid-cols-2 gap-4">
                            @foreach($testimonials as $testimonial)
                                <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-lg p-4 border border-indigo-200 dark:border-indigo-800">
                                    <div class="flex items-start gap-3 mb-3">
                                        <div class="w-10 h-10 bg-indigo-600 rounded-full flex items-center justify-center text-white font-bold flex-shrink-0">
                                            {{ substr($testimonial->user->name, 0, 1) }}
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $testimonial->user->name }}</p>
                                            <div class="text-yellow-500 text-sm">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $testimonial->rating)
                                                        ★
                                                    @else
                                                        ☆
                                                    @endif
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-700 dark:text-gray-300 italic">"{{ $testimonial->testimonial }}"</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- All Feedback -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Todas las Valoraciones ({{ $feedback->total() }})
                    </h3>
                </div>
                <div class="p-6">
                    @forelse($feedback as $item)
                        <div class="mb-6 last:mb-0 pb-6 last:pb-0 border-b last:border-0 border-gray-200 dark:border-gray-700">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-indigo-600 rounded-full flex items-center justify-center text-white font-bold flex-shrink-0">
                                    {{ substr($item->user->name, 0, 1) }}
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-2">
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $item->user->name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                Sesión: {{ $item->scheduleSlot->scheduled_at->format('d/m/Y') }}
                                                · {{ $item->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-yellow-500 text-xl">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $item->rating)
                                                        ★
                                                    @else
                                                        ☆
                                                    @endif
                                                @endfor
                                            </div>
                                        </div>
                                    </div>

                                    @if($item->content_rating || $item->organization_rating || $item->atmosphere_rating)
                                        <div class="flex gap-4 text-xs text-gray-600 dark:text-gray-400 mb-2">
                                            @if($item->content_rating)
                                                <span>Contenido: {{ $item->content_rating }}/5</span>
                                            @endif
                                            @if($item->organization_rating)
                                                <span>Organización: {{ $item->organization_rating }}/5</span>
                                            @endif
                                            @if($item->atmosphere_rating)
                                                <span>Ambiente: {{ $item->atmosphere_rating }}/5</span>
                                            @endif
                                        </div>
                                    @endif

                                    @if($item->comment)
                                        <p class="text-gray-700 dark:text-gray-300 mb-2">{{ $item->comment }}</p>
                                    @endif

                                    <div class="flex gap-2 text-xs">
                                        @if($item->would_recommend)
                                            <span class="bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400 px-2 py-1 rounded">
                                                ✓ Recomendaría
                                            </span>
                                        @endif
                                        @if($item->attended)
                                            <span class="bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 px-2 py-1 rounded">
                                                Asistió
                                            </span>
                                        @else
                                            <span class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 px-2 py-1 rounded">
                                                No asistió
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                            <p class="mt-4 text-gray-600 dark:text-gray-400">No hay valoraciones aún</p>
                        </div>
                    @endforelse

                    @if($feedback->hasPages())
                        <div class="mt-6">
                            {{ $feedback->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
