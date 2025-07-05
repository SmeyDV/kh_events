<x-main-layout>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    @php
    // Category image mapping
    $categoryImages = [
    'music' => 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?q=80&w=2070',
    'food-drink' => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?q=80&w=2070',
    'charity-causes' => 'https://images.unsplash.com/photo-1593113598332-cd288d649433?q=80&w=2070',
    'hobbies' => 'https://images.unsplash.com/photo-1487958449943-2429e8be8625?q=80&w=2070',
    'tech' => 'https://images.unsplash.com/photo-1518709268805-4e9042af2176?q=80&w=2070',
    'fashion' => 'https://images.unsplash.com/photo-1445205170230-053b83016050?q=80&w=2070',
    'sports' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?q=80&w=2070',
    'health-wellness' => 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?q=80&w=2070',
    'education' => 'https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?q=80&w=2070',
    'arts-culture' => 'https://images.unsplash.com/photo-1513475382585-d06e58bcb0e0?q=80&w=2070',
    'business-networking' => 'https://images.unsplash.com/photo-1515187029135-18ee286d815b?q=80&w=2070',
    'travel-adventure' => 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?q=80&w=2070',
    ];
    @endphp

    <!-- Hero Section -->
    <div class="relative h-96 overflow-hidden mt-20">
      <img src="{{ $categoryImages[$category->slug] ?? 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=2070' }}&auto=format&fit=crop&h=600"
        alt="{{ $category->name }}"
        class="w-full h-full object-cover">
      <div class="absolute inset-0 bg-black/50"></div>
      <div class="absolute inset-0 flex items-center justify-center">
        <div class="text-center text-white">
          <nav class="flex justify-center mb-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
              <li>
                <a href="{{ route('home') }}" class="text-white/80 hover:text-white">Home</a>
              </li>
              <li>
                <svg class="w-6 h-6 text-white/60" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
              </li>
              <li>
                <a href="{{ route('categories.index') }}" class="text-white/80 hover:text-white">Categories</a>
              </li>
              <li>
                <svg class="w-6 h-6 text-white/60" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
              </li>
              <li class="text-white">{{ $category->name }}</li>
            </ol>
          </nav>
          <h1 class="text-4xl md:text-6xl font-black mb-4">{{ $category->name }} Events</h1>
          <p class="text-xl text-white/90 max-w-2xl mx-auto">
            Discover amazing {{ strtolower($category->name) }} events happening across Cambodia
          </p>
        </div>
      </div>
    </div>

    <!-- Statistics -->
    <div class="bg-white dark:bg-gray-800 border-b dark:border-gray-700">
      <div class="max-w-7xl mx-auto px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="text-center">
            <div class="text-3xl font-bold text-red-600 dark:text-red-400">{{ $totalEvents }}</div>
            <div class="text-gray-600 dark:text-gray-300">Total Events</div>
          </div>
          <div class="text-center">
            <div class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $upcomingEvents }}</div>
            <div class="text-gray-600 dark:text-gray-300">Upcoming Events</div>
          </div>
          <div class="text-center">
            <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $cities->count() }}</div>
            <div class="text-gray-600 dark:text-gray-300">Cities</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white dark:bg-gray-800 border-b dark:border-gray-700">
      <div class="max-w-7xl mx-auto px-6 lg:px-8 py-6">
        <form action="{{ route('categories.show', $category->slug) }}" method="GET" class="flex flex-col md:flex-row gap-4">
          <div class="flex-1">
            <label for="search" class="sr-only">Search events</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
              </div>
              <input type="search"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search {{ $category->name }} events..."
                class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-red-500 focus:border-red-500">
            </div>
          </div>
          <div class="md:w-48">
            <select name="city_id"
              class="block w-full py-3 px-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-red-500 focus:border-red-500">
              <option value="">All Cities</option>
              @foreach($cities as $city)
              <option value="{{ $city->id }}" @if(request('city_id')==$city->id) selected @endif>
                {{ $city->name }}
              </option>
              @endforeach
            </select>
          </div>
          <button type="submit"
            class="md:w-32 bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-300">
            Search
          </button>
        </form>
      </div>
    </div>

    <!-- Events Grid -->
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-12">
      @if($events->count() > 0)
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        @foreach($events as $event)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden">
          <div class="relative">
            @if($event->images->count() > 0)
            <img src="{{ asset('storage/' . $event->images->first()->image_path) }}"
              alt="{{ $event->title }}"
              class="w-full h-48 object-cover">
            @else
            <div class="w-full h-48 bg-gradient-to-br from-red-400 to-red-600 flex items-center justify-center">
              <svg class="w-16 h-16 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
            </div>
            @endif
            <div class="absolute top-4 left-4">
              <span class="bg-red-600 text-white px-3 py-1 rounded-full text-xs font-semibold">
                {{ $event->category->name }}
              </span>
            </div>
          </div>
          <div class="p-6">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 line-clamp-2">
              {{ $event->title }}
            </h3>
            <div class="space-y-2 text-sm text-gray-600 dark:text-gray-300">
              <div class="flex items-center">
                <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                {{ $event->start_date->format('M d, Y') }}
              </div>
              <div class="flex items-center">
                <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                {{ $event->city->name ?? 'Location TBA' }}
              </div>
              <div class="flex items-center">
                <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                {{ $event->organizer->name }}
              </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center">
              <div class="text-2xl font-bold text-red-600 dark:text-red-400">
                @if($event->price > 0)
                ${{ number_format($event->price, 2) }}
                @else
                Free
                @endif
              </div>
              <a href="{{ route('events.show', $event) }}"
                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors duration-300">
                View Details
              </a>
            </div>
          </div>
        </div>
        @endforeach
      </div>

      <!-- Pagination -->
      <div class="mt-12">
        {{ $events->appends(request()->query())->links() }}
      </div>
      @else
      <div class="text-center py-16">
        <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">No Events Found</h3>
        <p class="text-gray-600 dark:text-gray-300 mb-8">
          There are no {{ strtolower($category->name) }} events available at the moment.
        </p>
        <a href="{{ route('categories.index') }}"
          class="inline-flex items-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors duration-300">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
          </svg>
          Browse Other Categories
        </a>
      </div>
      @endif
    </div>
</x-main-layout>