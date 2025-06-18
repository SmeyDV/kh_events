<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      Admin Dashboard
    </h2>
  </x-slot>
  <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow flex flex-col items-center">
          <div class="text-3xl font-bold text-blue-600 mb-2">{{ $userCount }}</div>
          <div class="text-gray-700 dark:text-gray-300">Total Users</div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow flex flex-col items-center">
          <div class="text-3xl font-bold text-green-600 mb-2">{{ $eventCount }}</div>
          <div class="text-gray-700 dark:text-gray-300">Total Events</div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow flex flex-col items-center">
          <div class="text-3xl font-bold text-pink-600 mb-2">{{ $organizerCount }}</div>
          <div class="text-gray-700 dark:text-gray-300">Total Organizers</div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow flex flex-col items-center">
          <div class="text-3xl font-bold text-yellow-600 mb-2">{{ $pendingEventCount }}</div>
          <div class="text-gray-700 dark:text-gray-300">Pending Events</div>
        </div>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <a href="{{ route('admin.users') }}" class="block bg-blue-600 hover:bg-blue-700 text-white font-semibold text-center py-6 rounded-2xl shadow transition-all text-lg">
          Manage Users
        </a>
        <a href="{{ route('events.index') }}" class="block bg-green-600 hover:bg-green-700 text-white font-semibold text-center py-6 rounded-2xl shadow transition-all text-lg">
          Manage Events
        </a>
      </div>
    </div>
  </div>
</x-app-layout>