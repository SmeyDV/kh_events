<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>KHEVENT CAMBODIA | Your Local Event Discovery Platform</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="antialiased font-sans bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    <div class="relative min-h-screen flex flex-col">

        <!-- Header Navigation -->
        <header class="w-full py-4 px-6 fixed top-0 z-50 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-b border-gray-200/50 dark:border-white/10">
            <nav class="flex items-center justify-between max-w-7xl mx-auto">
                <div>
                    <a href="/">
                        <img src="{{ asset('images/kheventslogo.jpg') }}" alt="KHEVENT Logo" class="h-12 w-auto">
                    </a>
                </div>
                <div class="hidden sm:flex items-center space-x-6">
                    @if (Route::has('login'))
                    @auth
                    <a href="{{ route('profile') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">My Profile</a>
                    @else
                    <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log in</a>
                    @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="font-semibold text-white bg-red-600 hover:bg-red-700 px-5 py-2 rounded-lg focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500 transition-all duration-250 transform hover:scale-105">Register</a>
                    @endif
                    @endauth
                    @endif
                </div>
            </nav>
        </header>

        <!-- Hero Section -->
        <main class="flex-grow flex items-center justify-center relative overflow-hidden pt-20">
            <!-- Background Image -->
            <div class="absolute inset-0 z-0">
                <img src="https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover opacity-20" alt="Music concert background">
                <div class="absolute inset-0 bg-gradient-to-t from-white via-white/90 to-transparent dark:from-gray-900 dark:via-gray-900/90"></div>
            </div>

            <div class="relative z-10 text-center max-w-4xl mx-auto px-4 py-24">
                <h1 class="text-5xl md:text-7xl font-black text-gray-900 dark:text-white leading-tight tracking-tighter">
                    Find Your Vibe. <span class="text-red-600 dark:text-red-500">Create Your Moment.</span>
                </h1>
                <p class="mt-6 text-lg text-gray-700 dark:text-gray-300 max-w-2xl mx-auto">
                    The heart of Cambodia's events is here. Discover concerts, festivals, and unique local happenings, or launch your own event and connect with your audience.
                </p>
                <div class="mt-12 flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('events.index') }}" class="inline-block rounded-lg bg-red-600 px-8 py-4 text-center font-semibold text-white shadow-lg hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-300 dark:focus:ring-red-800 transition-transform transform hover:scale-105">
                        Explore Events
                    </a>
                    <a href="{{ route('register.organizer') }}" class="inline-block rounded-lg bg-blue-600 px-8 py-4 text-center font-semibold text-white shadow-lg hover:bg-blue-700 border border-transparent focus:outline-none focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 transition-transform transform hover:scale-105">
                        Become an Organizer
                    </a>
                </div>
            </div>
        </main>

        <!-- Upcoming Events Section -->
        @if($upcomingEvents->isNotEmpty())
        <section class="w-full py-20 bg-gray-50 dark:bg-gray-950/50">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <h2 class="text-3xl font-bold text-center text-gray-900 dark:text-white mb-12">Upcoming Events</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($upcomingEvents as $event)
                    <div class="bg-white dark:bg-gray-800/50 backdrop-blur-sm border border-gray-200 dark:border-gray-700/50 overflow-hidden shadow-2xl rounded-2xl transform hover:-translate-y-2 transition-transform duration-300 ease-in-out group">
                        <a href="{{ route('events.show', $event) }}" class="block">
                            <div class="overflow-hidden">
                                <img src="{{ $event->image_path ? Storage::url($event->image_path) : 'https://placehold.co/600x400/1e293b/ffffff?text=Event' }}" alt="{{ $event->title }}" class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-500">
                            </div>
                            <div class="p-6">
                                <p class="text-sm text-red-500 dark:text-red-400 font-bold">{{ $event->start_date->format('D, M d, Y - h:i A') }}</p>
                                <h3 class="text-xl font-bold mt-2 text-gray-900 dark:text-white truncate group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors">{{ $event->title }}</h3>
                                <p class="text-gray-600 dark:text-gray-400 mt-2 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-500 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                    </svg>
                                    <span class="truncate">{{ $event->venue }}</span>
                                </p>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        <!-- Footer -->
        <footer class="w-full p-8 bg-gray-100 dark:bg-black border-t border-gray-200 dark:border-white/10">
            <p class="text-center text-sm text-gray-500 dark:text-gray-400">
                © {{ date('Y') }} KHEVENT CAMBODIA by Reaksmey, Kibriyo & Sokpheng. All rights reserved.
            </p>
        </footer>
    </div>
</body>

</html>