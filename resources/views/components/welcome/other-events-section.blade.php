@props(['otherEvents'])

<section class="w-full py-20 bg-gray-900 dark:bg-gray-800">
  <div class="max-w-7xl mx-auto px-6 lg:px-8">
    <h2 class="text-3xl font-bold text-center text-white mb-12">Other events you may like</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      @foreach($otherEvents as $event)
      <a href="{{ route('events.show', $event) }}" class="group">
        <div class="bg-white dark:bg-gray-700 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 group-hover:transform group-hover:-translate-y-1">
          <div class="relative">
            @if($event->images && $event->images->count() > 0)
            <img src="{{ asset('storage/' . $event->images->first()->image_path) }}"
              alt="{{ $event->title }}"
              class="w-full h-32 object-cover group-hover:scale-105 transition-transform duration-300">
            @else
            <div class="w-full h-32 bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
              <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
            </div>
            @endif
          </div>

          <div class="p-4">
            <h3 class="font-semibold text-gray-900 dark:text-white text-sm mb-2 line-clamp-2 group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors duration-300">{{ $event->title }}</h3>

            <div class="text-xs text-gray-600 dark:text-gray-300 mb-1">
              {{ $event->start_date->format('l • g:i A') }}
            </div>

            <div class="text-xs text-gray-500 dark:text-gray-400 mb-3">
              {{ $event->location ?? 'Location TBA' }}
            </div>

            <div class="text-sm font-semibold text-gray-900 dark:text-white">
              @if($event->ticket_price && $event->ticket_price > 0)
              From ${{ number_format($event->ticket_price, 2) }}
              @else
              Free
              @endif
            </div>

            @if($event->organizer)
            <div class="text-xs text-gray-500 dark:text-gray-400 mt-2">
              {{ $event->organizer->name }}
            </div>
            @endif
          </div>
        </div>
      </a>
      @endforeach
    </div>

    @if($otherEvents->count() >= 8)
    <div class="text-center mt-12">
      <div class="inline-flex items-center px-6 py-3 bg-white hover:bg-gray-100 text-gray-900 font-semibold rounded-lg transition-colors duration-300 cursor-pointer">
        <span>See more</span>
      </div>
    </div>
    @endif
  </div>
</section>