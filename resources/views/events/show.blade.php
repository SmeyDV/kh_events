<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ $event->title }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          <div class="container mx-auto px-4">
            @if($event->image_path)
            <img src="{{ asset('storage/' . $event->image_path) }}" alt="{{ $event->title }}" class="w-full h-96 object-cover rounded-lg mb-6">
            @endif

            <h1 class="text-4xl font-bold mb-4">{{ $event->title }}</h1>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div class="md:col-span-2">
                <p class="text-gray-700 text-lg">
                  {{ $event->description }}
                </p>
              </div>

              <div class="space-y-4">
                <div class="bg-gray-100 p-4 rounded-lg">
                  <h3 class="font-bold text-lg mb-2">Event Details</h3>
                  <p><strong>Date:</strong> {{ $event->start_date->format('M d, Y') }} - {{ $event->end_date->format('M d, Y') }}</p>
                  <p><strong>Venue:</strong> {{ $event->venue }}</p>
                  <p><strong>Price:</strong> {{ $event->ticket_price > 0 ? '$' . number_format($event->ticket_price, 2) : 'Free' }}</p>
                  <p><strong>Capacity:</strong> {{ $event->capacity }}</p>
                </div>

                @auth
                @if($event->capacity > 0)
                <a href="{{ route('bookings.create', $event) }}" class="w-full text-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded block">
                  Book Your Ticket
                </a>
                @else
                <p class="text-center bg-red-500 text-white font-bold py-3 px-4 rounded">Sold Out</p>
                @endif
                @else
                <a href="{{ route('login') }}" class="w-full text-center bg-gray-500 hover:bg-gray-700 text-white font-bold py-3 px-4 rounded block">
                  Log in to Book
                </a>
                @endauth
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>