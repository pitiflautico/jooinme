<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>JoinMe - Conecta a través de conversaciones significativas</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-sans bg-gray-50 dark:bg-gray-900">
        <!-- Navigation -->
        <nav class="fixed w-full bg-white/80 dark:bg-gray-900/80 backdrop-blur-sm border-b border-gray-200 dark:border-gray-800 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <a href="/" class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                            JoinMe
                        </a>
                    </div>

                    <div class="hidden md:flex items-center space-x-8">
                        <a href="#como-funciona" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition">Cómo funciona</a>
                        <a href="#conversaciones" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition">Explorar</a>
                        <a href="#faq" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition">FAQ</a>
                    </div>

                    <div class="flex items-center space-x-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition">Iniciar sesión</a>
                            <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition shadow-md">Únete</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="pt-32 pb-20 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="text-center">
                    <h1 class="text-5xl md:text-6xl font-bold text-gray-900 dark:text-white mb-6">
                        Conecta a través de
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">
                            conversaciones significativas
                        </span>
                    </h1>
                    <p class="text-xl text-gray-600 dark:text-gray-400 mb-10 max-w-3xl mx-auto">
                        Descubre personas con tus mismos intereses, únete a conversaciones inspiradoras y construye relaciones auténticas. Ya sea online o presencial, JoinMe hace que conectar sea fácil y significativo.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-8 py-4 rounded-lg hover:bg-indigo-700 transition text-lg font-semibold shadow-lg hover:shadow-xl">
                            Comienza gratis
                        </a>
                        <a href="#conversaciones" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-8 py-4 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition text-lg font-semibold border-2 border-gray-200 dark:border-gray-700">
                            Explorar conversaciones
                        </a>
                    </div>
                </div>

                <!-- Stats -->
                <div class="mt-20 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                    <div>
                        <div class="text-4xl font-bold text-indigo-600 dark:text-indigo-400">{{ \App\Models\User::count() }}+</div>
                        <div class="text-gray-600 dark:text-gray-400 mt-2">Usuarios activos</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-indigo-600 dark:text-indigo-400">{{ \App\Models\Conversation::count() }}+</div>
                        <div class="text-gray-600 dark:text-gray-400 mt-2">Conversaciones</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-indigo-600 dark:text-indigo-400">{{ \App\Models\Topic::count() }}+</div>
                        <div class="text-gray-600 dark:text-gray-400 mt-2">Temas</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-indigo-600 dark:text-indigo-400">4.8</div>
                        <div class="text-gray-600 dark:text-gray-400 mt-2">Valoración media</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Cómo funciona -->
        <section id="como-funciona" class="py-20 bg-white dark:bg-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">¿Cómo funciona?</h2>
                    <p class="text-xl text-gray-600 dark:text-gray-400">En 3 simples pasos empiezas a conectar</p>
                </div>

                <div class="grid md:grid-cols-3 gap-12">
                    <div class="text-center">
                        <div class="bg-indigo-100 dark:bg-indigo-900 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">1. Explora</h3>
                        <p class="text-gray-600 dark:text-gray-400">Descubre conversaciones sobre tus intereses: tecnología, arte, emprendimiento, salud y más.</p>
                    </div>

                    <div class="text-center">
                        <div class="bg-indigo-100 dark:bg-indigo-900 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">2. Únete o Crea</h3>
                        <p class="text-gray-600 dark:text-gray-400">Participa en una conversación existente o crea la tuya propia y atrae a tu comunidad.</p>
                    </div>

                    <div class="text-center">
                        <div class="bg-indigo-100 dark:bg-indigo-900 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">3. Conecta</h3>
                        <p class="text-gray-600 dark:text-gray-400">Conoce personas increíbles, intercambia ideas y construye relaciones significativas.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Conversaciones Destacadas -->
        <section id="conversaciones" class="py-20 bg-gray-50 dark:bg-gray-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">Conversaciones destacadas</h2>
                    <p class="text-xl text-gray-600 dark:text-gray-400">Únete a estas conversaciones populares</p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    @php
                        $featuredConversations = \App\Models\Conversation::with(['owner', 'topic'])
                            ->where('is_featured', true)
                            ->where('is_active', true)
                            ->latest()
                            ->take(6)
                            ->get();
                    @endphp

                    @forelse($featuredConversations as $conversation)
                        <div class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition">
                            @if($conversation->cover_image)
                                <img src="{{ $conversation->cover_image }}" alt="{{ $conversation->title }}" class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gradient-to-br from-indigo-500 to-purple-600"></div>
                            @endif

                            <div class="p-6">
                                <div class="flex items-center mb-3">
                                    <span class="bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400 text-xs font-semibold px-3 py-1 rounded-full">
                                        {{ $conversation->topic->name ?? 'General' }}
                                    </span>
                                    <span class="ml-2 text-sm text-gray-500">
                                        {{ $conversation->type === 'online' ? '🌐 Online' : '📍 Presencial' }}
                                    </span>
                                </div>

                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">{{ $conversation->title }}</h3>
                                <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-2">{{ $conversation->description }}</p>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center text-sm text-gray-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                        </svg>
                                        {{ $conversation->current_participants }}/{{ $conversation->max_participants }}
                                    </div>
                                    <a href="{{ route('conversations.show', $conversation) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 font-semibold text-sm">
                                        Ver más →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 text-center py-12">
                            <p class="text-gray-500 dark:text-gray-400">No hay conversaciones destacadas disponibles aún.</p>
                        </div>
                    @endforelse
                </div>

                <div class="text-center mt-12">
                    <a href="{{ route('conversations.index') }}" class="inline-block bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-indigo-700 transition font-semibold">
                        Ver todas las conversaciones
                    </a>
                </div>
            </div>
        </section>

        <!-- FAQ -->
        <section id="faq" class="py-20 bg-white dark:bg-gray-800">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">Preguntas frecuentes</h2>
                </div>

                <div class="space-y-6">
                    <details class="bg-gray-50 dark:bg-gray-900 rounded-lg p-6">
                        <summary class="font-semibold text-gray-900 dark:text-white cursor-pointer">¿Es gratis usar JoinMe?</summary>
                        <p class="mt-4 text-gray-600 dark:text-gray-400">Sí, JoinMe es completamente gratuito. Puedes crear y unirte a conversaciones sin ningún costo.</p>
                    </details>

                    <details class="bg-gray-50 dark:bg-gray-900 rounded-lg p-6">
                        <summary class="font-semibold text-gray-900 dark:text-white cursor-pointer">¿Puedo crear mi propia conversación?</summary>
                        <p class="mt-4 text-gray-600 dark:text-gray-400">Por supuesto. Una vez registrado, puedes crear conversaciones sobre cualquier tema que te interese y configurar si son públicas, moderadas o privadas.</p>
                    </details>

                    <details class="bg-gray-50 dark:bg-gray-900 rounded-lg p-6">
                        <summary class="font-semibold text-gray-900 dark:text-white cursor-pointer">¿Las conversaciones son online o presenciales?</summary>
                        <p class="mt-4 text-gray-600 dark:text-gray-400">Ambas opciones están disponibles. Puedes elegir crear conversaciones online (videollamada), presenciales (con ubicación) o híbridas.</p>
                    </details>

                    <details class="bg-gray-50 dark:bg-gray-900 rounded-lg p-6">
                        <summary class="font-semibold text-gray-900 dark:text-white cursor-pointer">¿Cómo funcionan las valoraciones?</summary>
                        <p class="mt-4 text-gray-600 dark:text-gray-400">Después de cada conversación, los participantes pueden valorarse mutuamente y dejar comentarios. Esto ayuda a construir una comunidad de confianza.</p>
                    </details>

                    <details class="bg-gray-50 dark:bg-gray-900 rounded-lg p-6">
                        <summary class="font-semibold text-gray-900 dark:text-white cursor-pointer">¿Qué pasa si una conversación no me gusta?</summary>
                        <p class="mt-4 text-gray-600 dark:text-gray-400">Puedes abandonar una conversación en cualquier momento. También puedes reportar contenido inapropiado para que nuestro equipo lo revise.</p>
                    </details>
                </div>
            </div>
        </section>

        <!-- CTA Final -->
        <section class="py-20 bg-gradient-to-r from-indigo-600 to-purple-600">
            <div class="max-w-4xl mx-auto text-center px-4">
                <h2 class="text-4xl font-bold text-white mb-6">¿Listo para empezar?</h2>
                <p class="text-xl text-indigo-100 mb-8">Únete a miles de personas que ya están teniendo conversaciones significativas.</p>
                <a href="{{ route('register') }}" class="inline-block bg-white text-indigo-600 px-8 py-4 rounded-lg hover:bg-gray-100 transition text-lg font-semibold shadow-lg">
                    Crear mi cuenta gratis
                </a>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900 text-gray-400 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-4 gap-8">
                    <div>
                        <h3 class="text-white font-bold text-lg mb-4">JoinMe</h3>
                        <p class="text-sm">Conecta a través de conversaciones significativas.</p>
                    </div>
                    <div>
                        <h4 class="text-white font-semibold mb-4">Producto</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="#" class="hover:text-white transition">Características</a></li>
                            <li><a href="#" class="hover:text-white transition">Precios</a></li>
                            <li><a href="#" class="hover:text-white transition">FAQ</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-white font-semibold mb-4">Empresa</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="#" class="hover:text-white transition">Sobre nosotros</a></li>
                            <li><a href="#" class="hover:text-white transition">Blog</a></li>
                            <li><a href="#" class="hover:text-white transition">Contacto</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-white font-semibold mb-4">Legal</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="#" class="hover:text-white transition">Privacidad</a></li>
                            <li><a href="#" class="hover:text-white transition">Términos</a></li>
                            <li><a href="#" class="hover:text-white transition">Cookies</a></li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm">
                    <p>&copy; {{ date('Y') }} JoinMe. Todos los derechos reservados.</p>
                </div>
            </div>
        </footer>
    </body>
</html>
