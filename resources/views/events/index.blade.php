<x-app-layout>
  <x-slot name="header">
    <div class="flex justify-between items-center">
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Events') }}
      </h2>
      @if (Auth::user() && Auth::user()->isOrganizer())
      <a href="{{ route('events.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
        {{ __('Create Event') }}
      </a>
      @endif
    </div>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      @if (session('success'))
      <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
      </div>
      @endif

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($events as $event)
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          @if ($event->image_path)
          <img src="{{ Storage::url($event->image_path) }}" alt="{{ $event->title }}" class="w-full h-48 object-cover">
          @endif
          <div class="p-6">
            <h3 class="text-lg font-semibold mb-2">
              <a href="{{ route('events.show', $event) }}" class="text-blue-600 hover:text-blue-800">
                {{ $event->title }}
              </a>
            </h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4">{{ Str::limit($event->description, 100) }}</p>
            <div class="flex justify-between items-center text-sm text-gray-500 dark:text-gray-400">
              <span>{{ $event->start_date->format('M d, Y') }}</span>
              <span>{{ $event->venue }}</span>
            </div>
            <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
              By <a href="{{ route('users.show', $event->organizer) }}" class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 hover:underline">
                {{ $event->organizer->name }}
              </a>
            </div>
            @if ($event->ticket_price)
            <div class="mt-2 text-green-600 font-semibold">
              ${{ number_format($event->ticket_price, 2) }}
            </div>
            @else
            <div class="mt-2 text-green-600 font-semibold">
              Free
            </div>
            @endif
          </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-8 text-gray-500 dark:text-gray-400">
          No events found.
        </div>
        @endforelse
      </div>

      <div class="mt-6">
        {{ $events->links() }}
      </div>
    </div>
  </div>
</x-app-layout>