@props(['upcomingEvents'])

@if($upcomingEvents->isNotEmpty())
<section class="w-full py-20 bg-gray-50 dark:bg-gray-950/50">
  <div class="max-w-7xl mx-auto px-6 lg:px-8">
    <h2 class="text-3xl font-bold text-center text-gray-900 dark:text-white mb-12">Upcoming Events</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      @foreach($upcomingEvents as $event)
      <div class="bg-white dark:bg-gray-800/50 backdrop-blur-sm border border-gray-200 dark:border-gray-700/50 overflow-hidden shadow-2xl rounded-2xl transform hover:-translate-y-2 transition-transform duration-300 ease-in-out group">
        <a href="{{ route('events.show', $event) }}" class="block">
          <div class="overflow-hidden">
            <img src="{{ $event->image_path ? Storage::url($event->image_path) : 'https://placehold.co/600x400/1e293b/ffffff?text=Event' }}" alt="{{ $event->title }}" class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-500">
          </div>
          <div class="p-6">
            <p class="text-sm text-red-500 dark:text-red-400 font-bold">{{ $event->start_date->format('D, M d, Y - h:i A') }}</p>
            <h3 class="text-xl font-bold mt-2 text-gray-900 dark:text-white truncate group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors">{{ $event->title }}</h3>
            <p class="text-sm text-gray-500 mt-1">By {{ $event->organizer->name }}</p>
            <p class="text-gray-600 dark:text-gray-400 mt-2 flex items-center">
              <svg class="w-4 h-4 mr-2 text-gray-500 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
              </svg>
              <span class="truncate">{{ $event->venue }}</span>
            </p>
          </div>
        </a>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif