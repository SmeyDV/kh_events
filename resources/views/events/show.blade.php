<x-main-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ $event->title }}
    </h2>
  </x-slot>

  <div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
      @if($event->images->isNotEmpty())
      <div id="main-image-container" class="mb-4">
        <img id="main-image" src="{{ asset('storage/' . $event->images->first()->image_path) }}" alt="{{ $event->title }}" class="w-full h-96 object-cover">
      </div>
      @if($event->images->count() > 1)
      <div class="px-6 md:px-8 pb-4">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-3">More Images</h3>
        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-2">
          @foreach($event->images as $image)
          <div class="cursor-pointer">
            <img src="{{ asset('storage/' . $image->image_path) }}" alt="Thumbnail for {{ $event->title }}" class="thumbnail-image w-full h-20 object-cover rounded-lg border-2 border-transparent hover:border-blue-500 transition" data-large-src="{{ asset('storage/' . $image->image_path) }}">
          </div>
          @endforeach
        </div>
      </div>
      @endif
      @else
      <div class="h-64 bg-cover bg-center" style="background-image: url('https://via.placeholder.com/800x400');">
      </div>
      @endif
      <div class="p-6 md:p-8">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-gray-100 mb-2">{{ $event->title }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mb-6">Organized by <span class="font-semibold">{{ $event->organizer->name }}</span></p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
          <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-2">Event Details</h2>
            <div class="text-gray-700 dark:text-gray-300 space-y-2">
              <p><span class="font-semibold">Date:</span> {{ $event->start_date->format('F j, Y, g:i a') }} - {{ $event->end_date->format('F j, Y, g:i a') }}</p>
              <p><span class="font-semibold">Venue:</span> {{ $event->venue }}, {{ $event->city->name }}</p>
              <p><span class="font-semibold">Category:</span> {{ $event->category->name }}</p>
            </div>
          </div>
          <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-2">Tickets</h2>
            <div class="text-gray-700 dark:text-gray-300 space-y-2">
              <p><span class="font-semibold">Capacity:</span> {{ $event->capacity ?? 'Not specified' }}</p>
              <p><span class="font-semibold">Tickets Left:</span> {{ $event->getRemainingTicketsAttribute() ?? 'N/A' }}</p>
            </div>

            @if($event->tickets->isNotEmpty())
            <div class="mt-2">
              <h3 class="font-semibold text-gray-800 dark:text-gray-200 mb-1">Ticket Types:</h3>
              <div class="space-y-1">
                @foreach($event->tickets as $ticket)
                <div class="flex justify-between items-center text-sm">
                  <span class="capitalize text-gray-800 dark:text-white">{{ str_replace('_', ' ', $ticket->type) }}</span>
                  <span class="font-semibold text-gray-800 dark:text-white">${{ number_format($ticket->price, 2) }}</span>
                </div>
                @endforeach
              </div>
            </div>
            @endif
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

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const mainImage = document.getElementById('main-image');
      const thumbnails = document.querySelectorAll('.thumbnail-image');

      if (mainImage && thumbnails.length > 0) {
        // Set the first thumbnail as active initially
        thumbnails[0].classList.add('border-blue-500');

        thumbnails.forEach(thumbnail => {
          thumbnail.addEventListener('click', function() {
            // Set the main image src to the data-large-src of the clicked thumbnail
            mainImage.src = this.dataset.largeSrc;

            // Highlight the active thumbnail
            thumbnails.forEach(t => t.classList.remove('border-blue-500'));
            this.classList.add('border-blue-500');
          });
        });
      }
    });
  </script>
</x-main-layout>