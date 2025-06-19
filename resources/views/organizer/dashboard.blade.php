<x-organizer-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Organizer Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("Welcome, Organizer!") }}
                </div>
            </div>

            <!-- Stats Section -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Total Events Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">My Events</h3>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">
                            {{ $totalEvents }}
                        </p>
                        <a href="{{ route('organizer.my-events') }}" class="mt-4 inline-block text-blue-500 hover:underline">View My Events</a>
                    </div>
                </div>

                <!-- Published Events Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Published Events</h3>
                         <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">
                           {{ $publishedEvents }}
                        </p>
                        <p class="text-gray-500 dark:text-gray-400">Approved by admin</p>
                    </div>
                </div>
                 <!-- Pending Events Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Pending Events</h3>
                         <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">
                           {{ $pendingEvents }}
                        </p>
                        <p class="text-gray-500 dark:text-gray-400">Awaiting approval</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-organizer-layout>
