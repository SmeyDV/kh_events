<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("Hello, Admin!") }}
                </div>
            </div>

            <!-- Stats Section -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Users Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Total Users</h3>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">
                            {{ $userCount }}
                        </p>
                    </div>
                </div>

                <!-- Total Events Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Total Events</h3>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">
                           {{ $eventCount }}
                        </p>
                    </div>
                </div>

                 <!-- Total Organizers Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Total Organizers</h3>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">
                           {{ $organizerCount }}
                        </p>
                    </div>
                </div>
                
                <!-- Pending Events Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Pending Events</h3>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">
                           {{ $pendingEventCount }}
                        </p>
                        <a href="{{ route('admin.events') }}" class="mt-4 inline-block text-blue-500 hover:underline">Review Events</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
