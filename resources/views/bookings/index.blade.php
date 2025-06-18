<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('My Bookings') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      @if (session('success'))
      <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
      </div>
      @endif

      @if (session('error'))
      <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
      </div>
      @endif

      @if($bookings->isEmpty())
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-center">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
          </svg>
          <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No bookings yet</h3>
          <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Start exploring events and book your tickets!</p>
          <div class="mt-6">
            <a href="{{ route('events.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
              Browse Events
            </a>
          </div>
        </div>
      </div>
      @else
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($bookings as $booking)
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          @if ($booking->event->image_path)
          <img src="{{ Storage::url($booking->event->image_path) }}" alt="{{ $booking->event->title }}" class="w-full h-48 object-cover">
          @endif
          <div class="p-6">
            <div class="flex items-center justify-between mb-2">
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                <a href="{{ route('events.show', $booking->event) }}" class="hover:text-red-600 dark:hover:text-red-400">
                  {{ $booking->event->title }}
                </a>
              </h3>
            </div>

            <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400 mb-4">
              <p>{{ $booking->event->start_date->format('M d, Y g:i A') }}</p>
              <p>{{ $booking->event->venue }}</p>
              <p>{{ $booking->quantity }} {{ Str::plural('ticket', $booking->quantity) }}</p>
              <p class="font-semibold text-gray-900 dark:text-white">
                Total: ${{ number_format($booking->total_amount, 2) }}
              </p>
            </div>

            <div class="flex items-center justify-between mb-4">
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $booking->status_badge_color }}">
                {{ ucfirst($booking->status) }}
              </span>
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $booking->payment_status_badge_color }}">
                {{ ucfirst($booking->payment_status) }}
              </span>
            </div>

            <div class="flex items-center justify-between">
              <a href="{{ route('bookings.show', $booking) }}" class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 text-sm font-medium">
                View Details
              </a>
              @if($booking->isPending() || $booking->isConfirmed())
              <form action="{{ route('bookings.cancel', $booking) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Are you sure you want to cancel this booking?')" class="text-gray-600 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 text-sm font-medium">
                  Cancel
                </button>
              </form>
              @endif
            </div>
          </div>
        </div>
        @endforeach
      </div>

      <div class="mt-6">
        {{ $bookings->links() }}
      </div>
      @endif
    </div>
  </div>
</x-app-layout>