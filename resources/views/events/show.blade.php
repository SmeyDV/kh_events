<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ $event->title }}
    </h2>
  </x-slot>

  <div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
      <div class="h-64 bg-cover bg-center" style="background-image: url('{{ $event->image_path ? asset('storage/' . $event->image_path) : 'https://via.placeholder.com/800x400' }}');">
      </div>
      <div class="p-6 md:p-8">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-gray-100 mb-2">{{ $event->title }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mb-6">Organized by <span class="font-semibold">{{ $event->organizer->name }}</span></p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
          <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-2">Event Details</h2>
            <div class="text-gray-700 dark:text-gray-300 space-y-2">
              <p><span class="font-semibold">Date:</span> {{ $event->start_date->format('F j, Y, g:i a') }} - {{ $event->end_date->format('F j, Y, g:i a') }}</p>
              <p><span class="font-semibold">Venue:</span> {{ $event->venue }}, {{ $event->city }}</p>
              <p><span class="font-semibold">Category:</span> {{ $event->category->name }}</p>
            </div>
          </div>
          <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-2">Tickets</h2>
            <div class="text-gray-700 dark:text-gray-300 space-y-2">
              <p><span class="font-semibold">Price:</span> {{ $event->ticket_price > 0 ? '$' . number_format($event->ticket_price, 2) : 'Free' }}</p>
              <p><span class="font-semibold">Capacity:</span> {{ $event->capacity ?? 'Not specified' }}</p>
              <p><span class="font-semibold">Tickets Left:</span> {{ $event->getRemainingTicketsAttribute() ?? 'N/A' }}</p>
            </div>
          </div>
        </div>

        <div class="prose max-w-none text-gray-800 dark:text-gray-200 mb-8">
          {!! nl2br(e($event->description)) !!}
        </div>

        <div class="text-center">
          @if($event->isAvailableForBooking())
          <a href="{{ route('bookings.create', $event) }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg transition duration-300">
            Book Your Spot
          </a>
          @else
          <p class="text-red-500 font-semibold">Booking is currently unavailable for this event.</p>
          @endif
        </div>
      </div>
    </div>
  </div>
</x-app-layout>