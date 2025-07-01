<x-organizer-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Event Details') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">

          <!-- Event Image -->
          @if($event->images->isNotEmpty())
          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-4">
            @foreach($event->images as $image)
            <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $event->title }}" class="w-full h-48 object-cover rounded-lg">
            @endforeach
          </div>
          @endif

          <h3 class="text-3xl font-bold mb-2">{{ $event->title }}</h3>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
              <p><strong>Start Date:</strong> {{ $event->start_date->format('F d, Y H:i') }}</p>
              <p><strong>End Date:</strong> {{ $event->end_date->format('F d, Y H:i') }}</p>
            </div>
            <div>
              <p><strong>Venue:</strong> {{ $event->venue }}</p>
              <p><strong>Status:</strong> <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $event->status == 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                  {{ ucfirst($event->status) }}
                </span></p>
            </div>
          </div>

          <div class="mb-4">
            <h4 class="font-bold">Description:</h4>
            <p>{{ $event->description }}</p>
          </div>

          <div class="flex justify-between items-center mt-4">
            <a href="{{ route('organizer.my-events') }}" class="text-blue-500 hover:underline">← Back to My Events</a>
            <div>
              <a href="{{ route('organizer.events.edit', $event) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">Edit</a>
            </div>
          </div>
        </div>
      </div>

      <!-- Attendee List -->
      <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          <h4 class="text-2xl font-bold mb-4">Attendee List ({{ $event->bookings->count() }}/{{$event->capacity}})</h4>
          @if($event->bookings->count() > 0)
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Name
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Email
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Booked At
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200">
                @foreach($event->bookings as $booking)
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                    {{ $booking->user->name }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $booking->user->email }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $booking->created_at->format('F d, Y') }}
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          @else
          <p>No attendees have booked this event yet.</p>
          @endif
        </div>
      </div>
    </div>
  </div>
</x-organizer-layout>