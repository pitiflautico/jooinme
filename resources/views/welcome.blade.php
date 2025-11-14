<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>JoinMe - Conecta a través de conversaciones significativas</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=dm-serif-display:400|inter:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            .font-serif { font-family: 'DM Serif Display', serif; }
            .font-sans { font-family: 'Inter', sans-serif; }

            /* Custom color palette based on design */
            :root {
                --color-primary: #5B3FF5;
                --color-secondary: #FF5722;
                --color-accent: #FFC107;
                --color-purple-light: #8B7FE8;
                --color-cream: #FAF9F6;
                --color-dark: #1A1A1A;
            }

            .bg-cream { background-color: var(--color-cream); }
            .bg-primary { background-color: var(--color-primary); }
            .bg-secondary { background-color: var(--color-secondary); }
            .bg-accent { background-color: var(--color-accent); }
            .bg-dark { background-color: var(--color-dark); }

            .text-primary { color: var(--color-primary); }
            .text-secondary { color: var(--color-secondary); }
            .text-accent { color: var(--color-accent); }

            .gradient-text {
                background: linear-gradient(135deg, var(--color-secondary) 0%, var(--color-primary) 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            /* Floating elements animation */
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-20px); }
            }

            .float-animation {
                animation: float 3s ease-in-out infinite;
            }

            .float-animation-delay {
                animation: float 3s ease-in-out infinite;
                animation-delay: 1s;
            }

            /* Icon circles */
            .icon-circle {
                width: 64px;
                height: 64px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            }

            .icon-circle-lg {
                width: 80px;
                height: 80px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 8px 30px rgba(0,0,0,0.15);
            }
        </style>
    </head>
    <body class="antialiased font-sans bg-cream">
        <!-- Navigation -->
        <nav class="fixed w-full bg-white/90 backdrop-blur-md border-b border-gray-200 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <div class="flex items-center">
                        <a href="/" class="text-2xl font-bold text-gray-900">
                            <span class="font-serif">JoinMe</span>
                        </a>
                    </div>

                    <div class="hidden md:flex items-center space-x-8">
                        <a href="#ventajas" class="text-gray-700 hover:text-primary transition font-medium">Ventajas</a>
                        <a href="#como-funciona" class="text-gray-700 hover:text-primary transition font-medium">Cómo funciona</a>
                        <a href="#testimonios" class="text-gray-700 hover:text-primary transition font-medium">Testimonios</a>
                        <a href="{{ route('conversations.index') }}" class="text-gray-700 hover:text-primary transition font-medium">Conversaciones</a>
                    </div>

                    <div class="flex items-center space-x-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-primary transition font-medium">Dashboard</a>
                        @else
                            <a href="{{ route('register') }}" class="bg-primary text-white px-6 py-2.5 rounded-full hover:opacity-90 transition font-semibold shadow-lg">Únete Gratis</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="pt-32 pb-20 px-4 sm:px-6 lg:px-8 bg-cream relative overflow-hidden">
            <div class="max-w-7xl mx-auto">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <!-- Left Content -->
                    <div class="relative z-10">
                        <div class="inline-block mb-6">
                            <span class="bg-purple-100 text-primary px-4 py-2 rounded-full text-sm font-semibold uppercase tracking-wide">
                                Conecta con personas reales
                            </span>
                        </div>

                        <h1 class="font-serif text-5xl lg:text-6xl xl:text-7xl text-gray-900 mb-6 leading-tight">
                            Descubre <span class="gradient-text">Conversaciones</span><br>
                            Que Transforman<br>
                            Tu Perspectiva
                        </h1>

                        <p class="text-gray-600 text-lg mb-8 max-w-lg leading-relaxed">
                            Únete a comunidades de personas con tus mismos intereses. Participa en conversaciones online o presenciales sobre tecnología, arte, emprendimiento, salud y mucho más.
                        </p>

                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center bg-primary text-white px-8 py-4 rounded-full hover:opacity-90 transition font-semibold shadow-lg text-lg">
                                ÚNETE GRATIS
                            </a>
                            <a href="{{ route('conversations.index') }}" class="inline-flex items-center justify-center bg-gray-900 text-white px-8 py-4 rounded-full hover:bg-gray-800 transition font-semibold text-lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                VER CONVERSACIONES
                            </a>
                        </div>
                    </div>

                    <!-- Right Content - Video Conference Mockup -->
                    <div class="relative">
                        <!-- Main video window mockup -->
                        <div class="relative bg-white rounded-3xl shadow-2xl overflow-hidden p-4">
                            <!-- Browser bar -->
                            <div class="flex items-center gap-2 mb-4">
                                <div class="flex gap-1.5">
                                    <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                    <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                                    <div class="w-3 h-3 rounded-full bg-green-500"></div>
                                </div>
                                <div class="flex-1 bg-gray-100 rounded-lg h-6"></div>
                            </div>

                            <!-- Video grid placeholder -->
                            <div class="aspect-video bg-gradient-to-br from-purple-400 to-blue-500 rounded-2xl mb-4 flex items-center justify-center">
                                <div class="text-white text-center">
                                    <div class="text-6xl mb-2">💬</div>
                                    <p class="text-sm">Conversación en Vivo</p>
                                </div>
                            </div>

                            <!-- Participant thumbnails -->
                            <div class="grid grid-cols-4 gap-2">
                                <div class="aspect-video bg-gradient-to-br from-pink-400 to-red-500 rounded-lg"></div>
                                <div class="aspect-video bg-gradient-to-br from-blue-400 to-cyan-500 rounded-lg"></div>
                                <div class="aspect-video bg-gradient-to-br from-yellow-400 to-orange-500 rounded-lg"></div>
                                <div class="aspect-video bg-gradient-to-br from-green-400 to-emerald-500 rounded-lg"></div>
                            </div>
                        </div>

                        <!-- Floating icons -->
                        <div class="absolute -top-8 -left-8 icon-circle-lg bg-accent float-animation">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>

                        <div class="absolute top-1/4 -right-6 icon-circle-lg bg-primary float-animation-delay">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>

                        <div class="absolute bottom-16 -left-6 icon-circle-lg bg-secondary float-animation">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>

                        <div class="absolute -bottom-4 right-1/4 icon-circle-lg bg-purple-400 float-animation-delay">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="py-16 bg-dark">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <p class="text-center text-gray-400 text-sm uppercase tracking-wider mb-10 font-semibold">
                    Miles de personas ya conectan a través de JoinMe
                </p>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 items-center text-center">
                    <div class="text-white">
                        <div class="text-4xl font-bold mb-2">{{ \App\Models\User::count() }}+</div>
                        <div class="text-gray-400 text-sm">Miembros</div>
                    </div>
                    <div class="text-white">
                        <div class="text-4xl font-bold mb-2">{{ \App\Models\Conversation::count() }}+</div>
                        <div class="text-gray-400 text-sm">Conversaciones</div>
                    </div>
                    <div class="text-white">
                        <div class="text-4xl font-bold mb-2">{{ \App\Models\Topic::count() }}+</div>
                        <div class="text-gray-400 text-sm">Temas</div>
                    </div>
                    <div class="text-white">
                        <div class="text-4xl font-bold mb-2">4.8/5</div>
                        <div class="text-gray-400 text-sm">Valoración</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- What Advantages Section -->
        <section id="ventajas" class="py-24 bg-cream">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-16 items-start">
                    <!-- Left - Title & Description -->
                    <div>
                        <h2 class="font-serif text-5xl lg:text-6xl text-gray-900 mb-8 leading-tight">
                            ¿Por Qué Elegir<br>
                            <span class="gradient-text">JoinMe</span><br>
                            Para Conectar?
                        </h2>
                        <p class="text-gray-600 text-lg mb-8 leading-relaxed">
                            Somos más que una plataforma de conversaciones. Facilitamos conexiones reales entre personas con intereses comunes, ya sea online o presencial, creando comunidades auténticas y significativas.
                        </p>
                        <a href="{{ route('register') }}" class="inline-flex items-center bg-gray-900 text-white px-8 py-4 rounded-full hover:bg-gray-800 transition font-semibold">
                            ÚNETE AHORA
                        </a>
                    </div>

                    <!-- Right - Features Grid -->
                    <div class="grid sm:grid-cols-2 gap-8">
                        <!-- Feature 1 -->
                        <div class="text-center">
                            <div class="icon-circle-lg bg-primary mx-auto mb-6">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Únete en segundos</h3>
                            <p class="text-gray-600 leading-relaxed">
                                Encuentra conversaciones sobre tus intereses y únete al instante. Sin complicaciones, solo conexiones reales.
                            </p>
                            <a href="{{ route('conversations.index') }}" class="text-primary font-semibold mt-4 inline-block hover:underline">Explorar →</a>
                        </div>

                        <!-- Feature 2 -->
                        <div class="text-center">
                            <div class="icon-circle-lg bg-secondary mx-auto mb-6">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Comunidad diversa</h3>
                            <p class="text-gray-600 leading-relaxed">
                                Conoce personas de todo el mundo con intereses similares a los tuyos en cualquier tema.
                            </p>
                            <a href="{{ route('register') }}" class="text-primary font-semibold mt-4 inline-block hover:underline">Únete →</a>
                        </div>

                        <!-- Feature 3 -->
                        <div class="text-center">
                            <div class="icon-circle-lg bg-accent mx-auto mb-6">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Online o presencial</h3>
                            <p class="text-gray-600 leading-relaxed">
                                Elige cómo quieres conectar: videollamadas online o encuentros presenciales en tu ciudad.
                            </p>
                            <a href="#como-funciona" class="text-primary font-semibold mt-4 inline-block hover:underline">Ver cómo →</a>
                        </div>

                        <!-- Feature 4 -->
                        <div class="text-center">
                            <div class="icon-circle-lg bg-purple-500 mx-auto mb-6">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Confianza y seguridad</h3>
                            <p class="text-gray-600 leading-relaxed">
                                Sistema de valoraciones y verificación de perfiles para crear una comunidad segura y confiable.
                            </p>
                            <a href="#testimonios" class="text-primary font-semibold mt-4 inline-block hover:underline">Ver opiniones →</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Take Control Section -->
        <section class="py-24 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    <!-- Left - Content -->
                    <div>
                        <h2 class="font-serif text-5xl lg:text-6xl text-gray-900 mb-8 leading-tight">
                            Crea y gestiona<br>
                            tus propias<br>
                            <span class="gradient-text">conversaciones</span>
                        </h2>
                        <p class="text-gray-600 text-lg mb-8 leading-relaxed">
                            No solo puedes unirte a conversaciones, también puedes crear las tuyas propias y construir tu comunidad. Gestiona participantes, modera el contenido y cultiva relaciones duraderas.
                        </p>

                        <ul class="space-y-4 mb-8">
                            <li class="flex items-center gap-3">
                                <div class="w-6 h-6 rounded-full bg-secondary flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-gray-700 font-medium">Crea conversaciones públicas o privadas</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <div class="w-6 h-6 rounded-full bg-secondary flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-gray-700 font-medium">Gestiona participantes y moderación</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <div class="w-6 h-6 rounded-full bg-accent flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-gray-700 font-medium">Programa fechas y recibe recordatorios</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Right - Illustration -->
                    <div class="relative">
                        <div class="bg-purple-50 rounded-3xl p-8">
                            <!-- Placeholder for assistant illustration -->
                            <div class="space-y-4">
                                <div class="flex items-center gap-4 bg-white p-4 rounded-2xl shadow-md">
                                    <div class="w-12 h-12 rounded-full bg-purple-400"></div>
                                    <div class="flex-1 h-4 bg-gray-200 rounded"></div>
                                    <div class="icon-circle bg-secondary">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                </div>

                                <div class="flex items-center gap-4 bg-white p-4 rounded-2xl shadow-md">
                                    <div class="w-12 h-12 rounded-full bg-pink-400"></div>
                                    <div class="flex-1 h-4 bg-gray-200 rounded"></div>
                                    <div class="icon-circle bg-accent">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                </div>

                                <div class="flex items-center gap-4 bg-white p-4 rounded-2xl shadow-md">
                                    <div class="w-12 h-12 rounded-full bg-blue-400"></div>
                                    <div class="flex-1 h-4 bg-gray-200 rounded"></div>
                                    <div class="icon-circle bg-primary">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works -->
        <section id="como-funciona" class="py-24 bg-cream">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-20">
                    <h2 class="font-serif text-5xl lg:text-6xl text-gray-900 mb-6">¿Cómo Funciona?</h2>
                    <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                        Conectar con personas nunca fue tan fácil. Solo tres pasos te separan de conversaciones increíbles
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-12">
                    <!-- Step 1 -->
                    <div class="text-center relative">
                        <div class="absolute -top-6 left-1/2 transform -translate-x-1/2 icon-circle bg-accent z-10">
                            <span class="text-white font-bold text-xl">1</span>
                        </div>

                        <div class="bg-white rounded-3xl p-8 pt-12 shadow-lg hover:shadow-xl transition">
                            <!-- Mockup illustration -->
                            <div class="mb-6 bg-gradient-to-br from-purple-900 to-purple-600 rounded-2xl p-6 h-48 flex items-center justify-center">
                                <div class="text-white text-4xl">🔍</div>
                            </div>

                            <h3 class="font-serif text-2xl text-gray-900 mb-3">Explora Temas</h3>
                            <p class="text-gray-600 leading-relaxed">
                                Navega por cientos de conversaciones sobre tecnología, arte, negocios, salud y más. Encuentra tu tribu.
                            </p>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="text-center relative">
                        <div class="absolute -top-6 left-1/2 transform -translate-x-1/2 icon-circle bg-primary z-10">
                            <span class="text-white font-bold text-xl">2</span>
                        </div>

                        <div class="bg-white rounded-3xl p-8 pt-12 shadow-lg hover:shadow-xl transition">
                            <!-- Mockup illustration -->
                            <div class="mb-6 bg-gradient-to-br from-yellow-100 to-purple-100 rounded-2xl p-6 h-48 flex flex-col items-center justify-center space-y-2">
                                <div class="bg-white rounded-lg px-6 py-3 shadow-md">
                                    <span class="text-gray-600 font-semibold">Únete o Crea</span>
                                </div>
                                <div class="w-24 h-3 bg-secondary rounded-full"></div>
                            </div>

                            <h3 class="font-serif text-2xl text-gray-900 mb-3">Únete o Crea</h3>
                            <p class="text-gray-600 leading-relaxed">
                                Participa en conversaciones existentes o crea tu propia sala y atrae a personas afines.
                            </p>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="text-center relative">
                        <div class="absolute -top-6 left-1/2 transform -translate-x-1/2 icon-circle bg-secondary z-10">
                            <span class="text-white font-bold text-xl">3</span>
                        </div>

                        <div class="bg-white rounded-3xl p-8 pt-12 shadow-lg hover:shadow-xl transition">
                            <!-- Mockup illustration -->
                            <div class="mb-6 rounded-2xl p-4 h-48 grid grid-cols-2 gap-2">
                                <div class="bg-gradient-to-br from-orange-400 to-red-500 rounded-xl"></div>
                                <div class="bg-gradient-to-br from-blue-400 to-cyan-500 rounded-xl"></div>
                                <div class="bg-gradient-to-br from-purple-400 to-pink-500 rounded-xl"></div>
                                <div class="bg-gradient-to-br from-green-400 to-emerald-500 rounded-xl"></div>
                            </div>

                            <h3 class="font-serif text-2xl text-gray-900 mb-3">Conecta y Crece</h3>
                            <p class="text-gray-600 leading-relaxed">
                                Conoce personas increíbles, intercambia ideas y construye relaciones que duran más allá de una conversación.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section id="testimonios" class="py-24 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    <div>
                        <h2 class="font-serif text-5xl lg:text-6xl text-gray-900 mb-8 leading-tight">
                            Lo Que Dicen<br>
                            Nuestros<br>
                            <span class="gradient-text">Miembros</span>
                        </h2>
                        <a href="{{ route('conversations.index') }}" class="inline-flex items-center text-primary font-semibold text-lg hover:underline">
                            Únete a la comunidad →
                        </a>
                    </div>

                    <div class="bg-primary rounded-3xl p-10 text-white shadow-2xl">
                        <div class="flex items-center gap-2 mb-6">
                            <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>

                        <p class="text-lg leading-relaxed mb-8">
                            "JoinMe cambió completamente la forma en que conecto con personas. He encontrado mi comunidad de emprendedores tech, participado en conversaciones increíbles y hasta formé alianzas de negocio. La plataforma es intuitiva y las personas son auténticas."
                        </p>

                        <div>
                            <h4 class="font-bold text-xl">María González</h4>
                            <p class="text-purple-200">Fundadora de TechStart</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-24 bg-dark">
            <div class="max-w-4xl mx-auto text-center px-4">
                <h2 class="font-serif text-4xl md:text-5xl text-white mb-6">Únete a miles de personas hoy</h2>
                <p class="text-gray-400 text-lg mb-10 max-w-2xl mx-auto">
                    Comienza a conectar con personas que comparten tus intereses. Es gratis, rápido y sin compromisos.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 max-w-xl mx-auto justify-center">
                    <a href="{{ route('register') }}" class="bg-primary text-white px-10 py-4 rounded-full hover:opacity-90 transition font-semibold text-lg whitespace-nowrap shadow-lg">
                        CREAR CUENTA GRATIS
                    </a>
                    <a href="{{ route('conversations.index') }}" class="bg-transparent border-2 border-white text-white px-10 py-4 rounded-full hover:bg-white hover:text-dark transition font-semibold text-lg whitespace-nowrap">
                        EXPLORAR CONVERSACIONES
                    </a>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-dark text-gray-400 py-16 border-t border-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-4 gap-12 mb-12">
                    <div>
                        <h3 class="text-white font-serif text-2xl mb-4">JoinMe</h3>
                        <p class="text-sm leading-relaxed mb-6">
                            Conecta a través de conversaciones significativas. Descubre personas, comparte ideas y construye comunidad.
                        </p>
                        <div class="flex gap-4">
                            <a href="#" class="w-10 h-10 rounded-full bg-gray-800 hover:bg-primary flex items-center justify-center transition" aria-label="Facebook">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                            <a href="#" class="w-10 h-10 rounded-full bg-gray-800 hover:bg-primary flex items-center justify-center transition" aria-label="Twitter">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                            </a>
                            <a href="#" class="w-10 h-10 rounded-full bg-gray-800 hover:bg-primary flex items-center justify-center transition" aria-label="Instagram">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/></svg>
                            </a>
                            <a href="#" class="w-10 h-10 rounded-full bg-gray-800 hover:bg-primary flex items-center justify-center transition" aria-label="LinkedIn">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                            </a>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-white font-semibold mb-4">Plataforma</h4>
                        <ul class="space-y-3 text-sm">
                            <li><a href="{{ route('conversations.index') }}" class="hover:text-white transition">Explorar conversaciones</a></li>
                            <li><a href="#ventajas" class="hover:text-white transition">Ventajas</a></li>
                            <li><a href="#como-funciona" class="hover:text-white transition">Cómo funciona</a></li>
                            <li><a href="#testimonios" class="hover:text-white transition">Testimonios</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-white font-semibold mb-4">Soporte</h4>
                        <ul class="space-y-3 text-sm">
                            <li><a href="#" class="hover:text-white transition">Centro de ayuda</a></li>
                            <li><a href="#" class="hover:text-white transition">Guía de inicio</a></li>
                            <li><a href="#" class="hover:text-white transition">Normas de comunidad</a></li>
                            <li><a href="#" class="hover:text-white transition">Contacto</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-white font-semibold mb-4">Legal</h4>
                        <ul class="space-y-3 text-sm">
                            <li><a href="#" class="hover:text-white transition">Términos de uso</a></li>
                            <li><a href="#" class="hover:text-white transition">Privacidad</a></li>
                            <li><a href="#" class="hover:text-white transition">Cookies</a></li>
                            <li><a href="#" class="hover:text-white transition">Seguridad</a></li>
                        </ul>
                    </div>
                </div>

                <div class="border-t border-gray-800 pt-8 text-center text-sm">
                    <p>&copy; {{ date('Y') }} JoinMe. Todos los derechos reservados. Hecho con ❤️ para conectar personas.</p>
                </div>
            </div>
        </footer>
    </body>
</html>
