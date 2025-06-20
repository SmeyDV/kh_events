<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Events') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
          <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold mb-6">Upcoming Events</h1>

            <form method="GET" action="" class="mb-6 flex flex-wrap gap-4 items-center">
              <select name="city" onchange="this.form.submit()" class="rounded border-gray-300 dark:bg-gray-700 dark:text-gray-100">
                <option value="">All Cities</option>
                @foreach($cities as $city)
                <option value="{{ $city }}" @if(request('city')==$city) selected @endif>{{ $city }}</option>
                @endforeach
              </select>
              <input type="search" name="search" value="{{ request('search') }}" placeholder="Search events..." class="rounded border-gray-300 dark:bg-gray-700 dark:text-gray-100" />
              <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Filter</button>
            </form>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              @forelse($events as $event)
              <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <a href="{{ route('events.show', $event) }}">
                  @if($event->image_path)
                  <img src="{{ asset('storage/' . $event->image_path) }}" alt="{{ $event->title }}" class="w-full h-48 object-cover">
                  @else
                  <img src="https://via.placeholder.com/300x200" alt="Default Event Image" class="w-full h-48 object-cover">
                  @endif
                </a>
                <div class="p-4">
                  <h2 class="text-xl font-bold mb-2">
                    <a href="{{ route('events.show', $event) }}">{{ $event->title }}</a>
                  </h2>
                  <p class="text-sm text-gray-500 mb-1">By {{ $event->organizer->name }}</p>
                  <p class="text-gray-600 mb-2">{{ $event->start_date->format('M d, Y') }}</p>
                  <p class="text-gray-500 mb-2">{{ $event->city }}</p>
                  <p class="text-gray-700">{{ Str::limit($event->description, 100) }}</p>
                  <div class="mt-4 flex justify-between items-center">
                    <span class="font-bold text-lg">{{ $event->ticket_price > 0 ? '$' . number_format($event->ticket_price, 2) : 'Free' }}</span>
                    <a href="{{ route('events.show', $event) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                      View Details
                    </a>
                  </div>
                </div>
              </div>
              @empty
              <div class="col-span-full text-center py-12">
                <h2 class="text-xl font-semibold text-gray-700">No events found</h2>
                <p class="text-gray-500 mt-2">Try adjusting your search or check back later.</p>
              </div>
              @endforelse
            </div>

            <div class="mt-8">
              {{ $events->links() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>