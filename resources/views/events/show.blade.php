<x-app-layout>
  <x-slot name="header">
    <div class="flex justify-between items-center">
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ $event->title }}
      </h2>
      <div class="flex gap-4">
        @if (Auth::user() && (Auth::user()->isAdmin() || (Auth::user()->isOrganizer() && $event->user_id === Auth::user()->id)))
        <a href="{{ route('events.edit', $event) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
          {{ __('Edit Event') }}
        </a>
        <form action="{{ route('events.destroy', $event) }}" method="POST" class="inline">
          @csrf
          @method('DELETE')
          <button type="submit" onclick="return confirm('Are you sure you want to delete this event?')" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            {{ __('Delete Event') }}
          </button>
        </form>
        @endif
      </div>
    </div>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      @if (session('success'))
      <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
      </div>
      @endif

      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        @if ($event->image_path)
        <div class="w-full h-96 relative">
          <img src="{{ Storage::url($event->image_path) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
        </div>
        @endif
        <div class="p-6">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2">
              <div class="prose dark:prose-invert max-w-none">
                <h1 class="text-3xl font-bold mb-4">{{ $event->title }}</h1>
                <p class="text-gray-600 dark:text-gray-400 whitespace-pre-line">{{ $event->description }}</p>
              </div>
            </div>
            <div class="space-y-6">
              <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                <h3 class="text-lg font-semibold mb-4">Event Details</h3>
                <div class="space-y-4">
                  <div>
                    <p class="text-gray-500 dark:text-gray-400">Date & Time</p>
                    <p class="font-medium">{{ $event->start_date->format('F d, Y g:i A') }}</p>
                  </div>
                  <div>
                    <p class="text-gray-500 dark:text-gray-400">Venue</p>
                    <p class="font-medium">{{ $event->venue }}</p>
                  </div>
                  <div>
                    <p class="text-gray-500 dark:text-gray-400">Price</p>
                    <p class="font-medium">
                      @if ($event->ticket_price)
                      ${{ number_format($event->ticket_price, 2) }}
                      @else
                      Free
                      @endif
                    </p>
                  </div>
                  <div>
                    <p class="text-gray-500 dark:text-gray-400">Available Tickets</p>
                    <p class="font-medium">
                      @if ($event->capacity)
                      @if ($event->isSoldOut())
                      <span class="text-red-600 dark:text-red-400 font-semibold">Sold Out</span>
                      @else
                      {{ $event->remaining_tickets }} of {{ $event->capacity }} remaining
                      @endif
                      @else
                      Unlimited
                      @endif
                    </p>
                  </div>
                  <div>
                    <p class="text-gray-500 dark:text-gray-400">Organizer</p>
                    <p class="font-medium">
                      <a href="{{ route('users.show', $event->organizer) }}" class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 hover:underline">
                        {{ $event->organizer->name }}
                      </a>
                    </p>
                  </div>
                </div>

                <!-- Booking Button -->
                @auth
                @if ($event->isAvailableForBooking())
                @php
                $existingBooking = auth()->user()->bookings()->where('event_id', $event->id)->first();
                @endphp

                @if ($existingBooking)
                <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                  <p class="text-blue-800 dark:text-blue-200 text-sm">
                    You have already booked this event.
                    <a href="{{ route('bookings.show', $existingBooking) }}" class="font-medium underline">View your booking</a>
                  </p>
                </div>
                @else
                <div class="mt-6">
                  <a href="{{ route('bookings.create', $event) }}" class="w-full inline-flex justify-center items-center px-6 py-3 bg-red-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Book Tickets
                  </a>
                </div>
                @endif
                @else
                <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg">
                  <p class="text-gray-600 dark:text-gray-400 text-sm">
                    @if ($event->isSoldOut())
                    This event is sold out.
                    @elseif ($event->isCancelled())
                    This event has been cancelled.
                    @elseif (!$event->isPublished())
                    This event is not yet published.
                    @else
                    This event has already passed.
                    @endif
                  </p>
                </div>
                @endif
                @else
                <div class="mt-6">
                  <a href="{{ route('login') }}" class="w-full inline-flex justify-center items-center px-6 py-3 bg-red-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Login to Book Tickets
                  </a>
                </div>
                @endauth
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>