<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>JoinMe - Plataforma de Conversaciones Significativas</title>
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
                            <span class="font-serif">me_eting.</span>
                        </a>
                    </div>

                    <div class="hidden md:flex items-center space-x-8">
                        <a href="#ventajas" class="text-gray-700 hover:text-primary transition font-medium">Features</a>
                        <a href="#como-funciona" class="text-gray-700 hover:text-primary transition font-medium">App</a>
                        <a href="#conversaciones" class="text-gray-700 hover:text-primary transition font-medium">Pricing</a>
                        <a href="#contacto" class="text-gray-700 hover:text-primary transition font-medium">Community</a>
                    </div>

                    <div class="flex items-center space-x-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-primary transition font-medium">Dashboard</a>
                        @else
                            <a href="{{ route('register') }}" class="bg-primary text-white px-6 py-2.5 rounded-full hover:opacity-90 transition font-semibold shadow-lg">Create Account</a>
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
                                Virtual Meeting Assistance
                            </span>
                        </div>

                        <h1 class="font-serif text-5xl lg:text-6xl xl:text-7xl text-gray-900 mb-6 leading-tight">
                            Virtual <span class="gradient-text">Meeting</span><br>
                            Platform For Online<br>
                            Conference Video
                        </h1>

                        <p class="text-gray-600 text-lg mb-8 max-w-lg leading-relaxed">
                            Making it look like readable english many desktop publishing package and web page editor now a use lorem ipsum.
                        </p>

                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center bg-primary text-white px-8 py-4 rounded-full hover:opacity-90 transition font-semibold shadow-lg text-lg">
                                GET STARTED
                            </a>
                            <a href="#demo" class="inline-flex items-center justify-center bg-gray-900 text-white px-8 py-4 rounded-full hover:bg-gray-800 transition font-semibold text-lg">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                                </svg>
                                PLAY DEMO
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
                                    <div class="text-6xl mb-2">🎥</div>
                                    <p class="text-sm">Video Conference Preview</p>
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

        <!-- Partners Logo Section -->
        <section class="py-16 bg-dark">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <p class="text-center text-gray-400 text-sm uppercase tracking-wider mb-10 font-semibold">
                    More than 5.000 from USA use our Platform
                </p>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-8 items-center">
                    <div class="text-white text-center opacity-60 hover:opacity-100 transition">
                        <div class="text-2xl font-bold">Luminous</div>
                    </div>
                    <div class="text-white text-center opacity-60 hover:opacity-100 transition">
                        <div class="text-2xl font-bold">Playersmith</div>
                    </div>
                    <div class="text-white text-center opacity-60 hover:opacity-100 transition">
                        <div class="text-2xl font-bold">Cisco Grip</div>
                    </div>
                    <div class="text-white text-center opacity-60 hover:opacity-100 transition">
                        <div class="text-2xl font-bold">Metadache</div>
                    </div>
                    <div class="text-white text-center opacity-60 hover:opacity-100 transition">
                        <div class="text-2xl font-bold">Intertech</div>
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
                            What<br>
                            Advantages Of<br>
                            Using <span class="gradient-text">Meeting?</span>
                        </h2>
                        <p class="text-gray-600 text-lg mb-8 leading-relaxed">
                            This makes a conferencing platform that advantages from the power of web. Real-time collaboration solutions with best experience and reliable ready for your things on real-time.
                        </p>
                        <a href="#" class="inline-flex items-center bg-gray-900 text-white px-8 py-4 rounded-full hover:bg-gray-800 transition font-semibold">
                            LEARN MORE
                        </a>
                    </div>

                    <!-- Right - Features Grid -->
                    <div class="grid sm:grid-cols-2 gap-8">
                        <!-- Feature 1 -->
                        <div class="text-center">
                            <div class="icon-circle-lg bg-primary mx-auto mb-6">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">One click to start</h3>
                            <p class="text-gray-600 leading-relaxed">
                                This makes conferencing platform that advantage from best quality in conferencing
                            </p>
                            <a href="#" class="text-primary font-semibold mt-4 inline-block hover:underline">Learn More →</a>
                        </div>

                        <!-- Feature 2 -->
                        <div class="text-center">
                            <div class="icon-circle-lg bg-secondary mx-auto mb-6">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Easy to customize</h3>
                            <p class="text-gray-600 leading-relaxed">
                                Can customization so you can brand and design on your own design
                            </p>
                            <a href="#" class="text-primary font-semibold mt-4 inline-block hover:underline">Learn More →</a>
                        </div>

                        <!-- Feature 3 -->
                        <div class="text-center">
                            <div class="icon-circle-lg bg-accent mx-auto mb-6">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Record & play your video</h3>
                            <p class="text-gray-600 leading-relaxed">
                                Awesome feature let you to can record and play so you can watch later
                            </p>
                            <a href="#" class="text-primary font-semibold mt-4 inline-block hover:underline">Learn More →</a>
                        </div>

                        <!-- Feature 4 -->
                        <div class="text-center">
                            <div class="icon-circle-lg bg-purple-500 mx-auto mb-6">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Integrated in social media</h3>
                            <p class="text-gray-600 leading-relaxed">
                                This makes platform that can share and embed easily on social media
                            </p>
                            <a href="#" class="text-primary font-semibold mt-4 inline-block hover:underline">Learn More →</a>
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
                            Take control of your<br>
                            business with our virtual<br>
                            assistant services
                        </h2>
                        <p class="text-gray-600 text-lg mb-8 leading-relaxed">
                            This makes a conferencing platform that advantage from the power of web. Real-time collaboration solutions with best experience and reliable ready for your thing.
                        </p>

                        <ul class="space-y-4 mb-8">
                            <li class="flex items-center gap-3">
                                <div class="w-6 h-6 rounded-full bg-secondary flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-gray-700 font-medium">Between SD video</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <div class="w-6 h-6 rounded-full bg-secondary flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-gray-700 font-medium">Crisp, Clear audio</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <div class="w-6 h-6 rounded-full bg-accent flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-gray-700 font-medium">Support for offline use</span>
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
                    <h2 class="font-serif text-5xl lg:text-6xl text-gray-900 mb-6">How It Works</h2>
                    <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                        We Made Easy For Everyone to Join And A Look To Enjoy Meeting
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
                                <div class="text-white text-4xl">🎨</div>
                            </div>

                            <h3 class="font-serif text-2xl text-gray-900 mb-3">Make A Room</h3>
                            <p class="text-gray-600 leading-relaxed">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Tincidunt enim cursus ultrices.
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
                                    <span class="text-gray-600">Share Link</span>
                                </div>
                                <div class="w-24 h-3 bg-secondary rounded-full"></div>
                            </div>

                            <h3 class="font-serif text-2xl text-gray-900 mb-3">Share Link</h3>
                            <p class="text-gray-600 leading-relaxed">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Tincidunt enim cursus ultrices.
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

                            <h3 class="font-serif text-2xl text-gray-900 mb-3">Let's Enjoy Chat</h3>
                            <p class="text-gray-600 leading-relaxed">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Tincidunt enim cursus ultrices.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section class="py-24 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    <div>
                        <h2 class="font-serif text-5xl lg:text-6xl text-gray-900 mb-8 leading-tight">
                            See How Our<br>
                            Customers Drive<br>
                            Impact
                        </h2>
                        <a href="#" class="inline-flex items-center text-primary font-semibold text-lg hover:underline">
                            Read More stories →
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
                            "How Do Account Features Works And Make You Easy And More Enjoy. Don't Worry We Know How Our Features Works. So Buy More For Today. Many Desktop Publishing Packages And Web Page Editors And Easy To Use More Enjoy More."
                        </p>

                        <div>
                            <h4 class="font-bold text-xl">Stefanie Muller</h4>
                            <p class="text-purple-200">Founder at Facebook</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-24 bg-dark">
            <div class="max-w-4xl mx-auto text-center px-4">
                <h2 class="font-serif text-4xl md:text-5xl text-white mb-6">Join 100+ users today</h2>
                <p class="text-gray-400 text-lg mb-10 max-w-2xl mx-auto">
                    This makes real platform that can be share and integrated in social media to stay connected
                </p>

                <form class="flex flex-col sm:flex-row gap-4 max-w-xl mx-auto">
                    <input
                        type="email"
                        placeholder="Enter your email"
                        class="flex-1 px-6 py-4 rounded-full border border-gray-700 bg-gray-800 text-white placeholder-gray-500 focus:outline-none focus:border-primary"
                    >
                    <a href="{{ route('register') }}" class="bg-primary text-white px-8 py-4 rounded-full hover:opacity-90 transition font-semibold whitespace-nowrap">
                        GET STARTED
                    </a>
                </form>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-dark text-gray-400 py-16 border-t border-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-4 gap-12 mb-12">
                    <div>
                        <h3 class="text-white font-serif text-2xl mb-4">me_eting.</h3>
                        <p class="text-sm leading-relaxed mb-6">
                            Virtual reality is best powerful and flexible platform for sharing engaging video.
                        </p>
                        <div class="flex gap-4">
                            <a href="#" class="w-10 h-10 rounded-full bg-gray-800 hover:bg-primary flex items-center justify-center transition">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                            <a href="#" class="w-10 h-10 rounded-full bg-gray-800 hover:bg-primary flex items-center justify-center transition">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                            </a>
                            <a href="#" class="w-10 h-10 rounded-full bg-gray-800 hover:bg-primary flex items-center justify-center transition">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14.18-.357.295-.6.295-.002 0-.003 0-.005 0l.213-3.054 5.56-5.022c.24-.213-.054-.334-.373-.121l-6.869 4.326-2.96-.924c-.64-.203-.658-.64.135-.954l11.566-4.458c.538-.196 1.006.128.832.941z"/></svg>
                            </a>
                            <a href="#" class="w-10 h-10 rounded-full bg-gray-800 hover:bg-primary flex items-center justify-center transition">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                            </a>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-white font-semibold mb-4">Company</h4>
                        <ul class="space-y-3 text-sm">
                            <li><a href="#" class="hover:text-white transition">Your Brand</a></li>
                            <li><a href="#" class="hover:text-white transition">Our Story</a></li>
                            <li><a href="#" class="hover:text-white transition">Career</a></li>
                            <li><a href="#" class="hover:text-white transition">Press</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-white font-semibold mb-4">Help</h4>
                        <ul class="space-y-3 text-sm">
                            <li><a href="#" class="hover:text-white transition">Getting Started</a></li>
                            <li><a href="#" class="hover:text-white transition">Download App</a></li>
                            <li><a href="#" class="hover:text-white transition">Affiliate Policy</a></li>
                            <li><a href="#" class="hover:text-white transition">Privacy Policy</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-white font-semibold mb-4">Links</h4>
                        <ul class="space-y-3 text-sm">
                            <li><a href="#" class="hover:text-white transition">Forum</a></li>
                            <li><a href="#" class="hover:text-white transition">Press Release</a></li>
                            <li><a href="#" class="hover:text-white transition">Newsletter</a></li>
                            <li><a href="#" class="hover:text-white transition">Testimonial</a></li>
                        </ul>
                    </div>
                </div>

                <div class="border-t border-gray-800 pt-8 text-center text-sm">
                    <p>&copy; {{ date('Y') }} me_eting. All Rights Reserved</p>
                </div>
            </div>
        </footer>
    </body>
</html>
