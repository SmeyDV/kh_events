<x-organizer-layout>

    <div class="bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 text-white rounded-xl mx-4 sm:mx-6 lg:mx-8 mt-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h2 class="text-3xl font-bold tracking-tight">
                {{ __('Organizer Dashboard') }}
            </h2>
            <p class="mt-2 text-blue-100">Monitor and manage your events</p>
        </div>
    </div>

    <div class="bg-gray-50 dark:bg-gray-900 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="p-8">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        {{ __("Welcome, Organizer!") }}
                    </h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Here's an overview of your events and performance.</p>
                </div>
            </div>

            <!-- Stats Section -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Total Events Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-shadow duration-300">
                    <div class="p-8">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">My Events</h3>
                        <p class="text-4xl font-bold text-blue-600 dark:text-blue-400 mb-4">
                            {{ $totalEvents }}
                        </p>
                        <a href="{{ route('organizer.my-events') }}" class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium transition-colors duration-200">
                            View My Events
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Published Events Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-shadow duration-300">
                    <div class="p-8">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Published Events</h3>
                        <p class="text-4xl font-bold text-emerald-600 dark:text-emerald-400 mb-2">
                            {{ $publishedEvents }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Approved by admin</p>
                    </div>
                </div>

                <!-- Pending Events Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-shadow duration-300">
                    <div class="p-8">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Pending Events</h3>
                        <p class="text-4xl font-bold text-amber-600 dark:text-amber-400 mb-2">
                            {{ $pendingEvents }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Awaiting approval</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-organizer-layout>