<x-app-layout>
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <!-- User Profile Header -->
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
        <div class="p-6 sm:p-8">
          <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
            <!-- User Avatar -->
            <div class="flex-shrink-0">
              <div class="w-24 h-24 bg-gradient-to-br from-red-500 to-red-700 rounded-full flex items-center justify-center text-white text-3xl font-bold">
                {{ strtoupper(substr($user->name, 0, 1)) }}
              </div>
            </div>

            <!-- User Info -->
            <div class="flex-1 min-w-0">
              <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                {{ $user->name }}
              </h1>
              <p class="text-gray-600 dark:text-gray-400 mb-2">
                {{ $user->email }}
              </p>
              <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                    @if($user->hasRole('admin')) bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                    @elseif($user->hasRole('organizer')) bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200 @endif">
                  {{ ucfirst($user->role->name ?? 'User') }}
                </span>
                <span class="text-sm text-gray-500 dark:text-gray-400">
                  Member since {{ $user->created_at->format('M Y') }}
                </span>
              </div>
            </div>

            <!-- Action Buttons -->
            @if(auth()->check() && auth()->id() === $user->id)
            <div class="flex-shrink-0">
              <a href="{{ route('profile.edit') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                Edit Profile
              </a>
            </div>
            @endif
          </div>
        </div>
      </div>

      <!-- User's Events Section (if organizer) -->
      @if($user->isOrganizer() && $userEvents->isNotEmpty())
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
        <div class="p-6 sm:p-8">
          <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
              {{ $user->name }}'s Events
            </h2>
            @if(auth()->check() && auth()->id() === $user->id)
            <a href="{{ route('events.my-events') }}"
              class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 font-medium">
              View All Events →
            </a>
            @endif
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($userEvents as $event)
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
              <a href="{{ route('events.show', $event) }}" class="block">
                <div class="aspect-video overflow-hidden">
                  <img src="{{ $event->image_path ? Storage::url($event->image_path) : 'https://placehold.co/600x400/1e293b/ffffff?text=Event' }}"
                    alt="{{ $event->title }}"
                    class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                </div>
                <div class="p-4">
                  <p class="text-sm text-red-600 dark:text-red-400 font-medium">
                    {{ $event->start_date->format('M d, Y - h:i A') }}
                  </p>
                  <h3 class="text-lg font-semibold text-gray-900 dark:text-white mt-1 line-clamp-2">
                    {{ $event->title }}
                  </h3>
                  <p class="text-sm text-gray-600 dark:text-gray-400 mt-2 flex items-center">
                    <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    {{ $event->venue }}
                  </p>
                  <div class="mt-3 flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-900 dark:text-white">
                      ${{ number_format($event->ticket_price, 2) }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($event->status === 'published') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                    @elseif($event->status === 'draft') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                    @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                      {{ ucfirst($event->status) }}
                    </span>
                  </div>
                </div>
              </a>
            </div>
            @endforeach
          </div>
        </div>
      </div>
      @endif

      <!-- Upcoming Events Section -->
      @if($upcomingEvents->isNotEmpty())
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 sm:p-8">
          <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
            Upcoming Events
          </h2>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($upcomingEvents as $event)
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
              <a href="{{ route('events.show', $event) }}" class="block">
                <div class="aspect-video overflow-hidden">
                  <img src="{{ $event->image_path ? Storage::url($event->image_path) : 'https://placehold.co/600x400/1e293b/ffffff?text=Event' }}"
                    alt="{{ $event->title }}"
                    class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                </div>
                <div class="p-4">
                  <p class="text-sm text-red-600 dark:text-red-400 font-medium">
                    {{ $event->start_date->format('M d, Y - h:i A') }}
                  </p>
                  <h3 class="text-lg font-semibold text-gray-900 dark:text-white mt-1 line-clamp-2">
                    {{ $event->title }}
                  </h3>
                  <p class="text-sm text-gray-600 dark:text-gray-400 mt-2 flex items-center">
                    <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    {{ $event->venue }}
                  </p>
                  <div class="mt-3">
                    <span class="text-sm font-medium text-gray-900 dark:text-white">
                      ${{ number_format($event->ticket_price, 2) }}
                    </span>
                  </div>
                </div>
              </a>
            </div>
            @endforeach
          </div>

          <div class="mt-8 text-center">
            <a href="{{ route('events.index') }}"
              class="inline-flex items-center px-6 py-3 bg-red-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
              View All Events
            </a>
          </div>
        </div>
      </div>
      @endif
    </div>
  </div>
</x-app-layout>