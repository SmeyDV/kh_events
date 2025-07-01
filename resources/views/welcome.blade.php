<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth dark">

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

        @include('layouts.navigation')

        <x-welcome.hero-section />

        <x-welcome.categories-section />

        <x-welcome.mini-navbar />

        <x-welcome.event-section :upcomingEvents="$upcomingEvents" />

        <x-welcome.organizer-cta />

        <x-layout.footer />

    </div>
</body>

</html>