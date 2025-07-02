<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ __('Join Our Community') }} - {{ config('app.name', 'Laravel') }}</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

  <script>
    // Theme handling
    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
      document.documentElement.classList.add('dark');
    } else {
      document.documentElement.classList.remove('dark')
    }
  </script>

  <!-- Scripts -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
  <div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
    <!-- Main Content -->
    <div class="max-w-6xl w-full space-y-8">
      <div class="text-center">
        <h2 class="text-4xl font-extrabold text-gray-900 dark:text-white">
          {{ __('Join Our Community') }}
        </h2>
        <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">
          {{ __('Choose how you want to get started') }}
        </p>
      </div>

      <div class="mt-12 grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-5xl mx-auto">
        <!-- User Registration Card -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-2xl border border-gray-200 dark:border-gray-700 hover:shadow-2xl transition-all duration-300 h-full transform hover:scale-105">
          <div class="p-8 h-full flex flex-col">
            <div class="text-center mb-6">
              <div class="flex justify-center mb-4">
                <div class="bg-blue-100 dark:bg-blue-900 p-4 rounded-full">
                  <svg class="h-16 w-16 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                  </svg>
                </div>
              </div>
              <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">
                {{ __('Event Attendee') }}
              </h3>
              <p class="text-base text-gray-600 dark:text-gray-400 mb-6">
                {{ __('Join events, book tickets, and connect with your community') }}
              </p>
            </div>
            <div class="flex-1">
              <ul class="text-sm text-gray-500 dark:text-gray-400 space-y-3 mb-8">
                <li class="flex items-center">
                  <svg class="h-5 w-5 text-blue-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                  </svg>
                  {{ __('Browse and book event tickets') }}
                </li>
                <li class="flex items-center">
                  <svg class="h-5 w-5 text-blue-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                  </svg>
                  {{ __('Manage your bookings') }}
                </li>
                <li class="flex items-center">
                  <svg class="h-5 w-5 text-blue-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                  </svg>
                  {{ __('Get event updates') }}
                </li>
              </ul>
            </div>
            <div class="mt-auto">
              <a href="{{ route('register') }}" class="w-full flex justify-center py-4 px-6 border border-transparent rounded-xl shadow-sm text-base font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 transition-all duration-200 transform hover:scale-105">
                {{ __('Register as User') }}
              </a>
            </div>
          </div>
        </div>

        <!-- Organizer Registration Card -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-2xl border border-gray-200 dark:border-gray-700 hover:shadow-2xl transition-all duration-300 h-full transform hover:scale-105">
          <div class="p-8 h-full flex flex-col">
            <div class="text-center mb-6">
              <div class="flex justify-center mb-4">
                <div class="bg-red-100 dark:bg-red-900 p-4 rounded-full">
                  <svg class="h-16 w-16 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                  </svg>
                </div>
              </div>
              <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">
                {{ __('Event Organizer') }}
              </h3>
              <p class="text-base text-gray-600 dark:text-gray-400 mb-6">
                {{ __('Create and manage your own events, reach your audience') }}
              </p>
            </div>
            <div class="flex-1">
              <ul class="text-sm text-gray-500 dark:text-gray-400 space-y-3 mb-8">
                <li class="flex items-center">
                  <svg class="h-5 w-5 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                  </svg>
                  {{ __('Create and publish events') }}
                </li>
                <li class="flex items-center">
                  <svg class="h-5 w-5 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                  </svg>
                  {{ __('Manage bookings and attendees') }}
                </li>
                <li class="flex items-center">
                  <svg class="h-5 w-5 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                  </svg>
                  {{ __('Track event analytics') }}
                </li>
              </ul>
            </div>
            <div class="mt-auto">
              <a href="{{ route('register.organizer') }}" class="w-full flex justify-center py-4 px-6 border border-transparent rounded-xl shadow-sm text-base font-semibold text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-300 transition-all duration-200 transform hover:scale-105">
                {{ __('Register as Organizer') }}
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Login Link -->
      <div class="text-center mt-12">
        <p class="text-base text-gray-600 dark:text-gray-400">
          {{ __('Already have an account?') }}
          <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors duration-200">
            {{ __('Sign in here') }}
          </a>
        </p>
      </div>
    </div>
  </div>
</body>

</html>