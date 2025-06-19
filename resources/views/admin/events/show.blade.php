<x-admin-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Event Details') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          <h3 class="text-2xl font-bold mb-4">{{ $event->title ?? 'Event Title' }}</h3>
          <p><strong>Date:</strong> {{ $event->start_date ?? '-' }} to {{ $event->end_date ?? '-' }}</p>
          <p><strong>Status:</strong> {{ $event->status ?? '-' }}</p>
          <p><strong>Organizer:</strong> {{ $event->organizer->name ?? '-' }}</p>
          <p><strong>Description:</strong></p>
          <div class="mb-4">{{ $event->description ?? '-' }}</div>
          <a href="{{ route('admin.events') }}" class="inline-block mt-4 text-blue-500 hover:underline">Back to Events</a>
        </div>
      </div>
    </div>
  </div>
</x-admin-layout>