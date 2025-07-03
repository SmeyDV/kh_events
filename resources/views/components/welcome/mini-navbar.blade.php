@props(['tabs' => []])

<nav class="w-full bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
  <div class="max-w-7xl mx-auto px-6 lg:px-8">
    <div class="flex justify-center space-x-4 py-4">
      @php
      // Show only first 8 tabs to prevent horizontal scrolling
      $visibleTabs = array_slice($tabs, 0, 8);
      @endphp
      @foreach($visibleTabs as $tab)
      <a href="{{ isset($tab['slug']) ? route('events.index', ['category' => $tab['slug']]) : '#' }}"
        class="text-sm lg:text-base font-medium whitespace-nowrap px-2 pb-1 border-b-2 transition-colors duration-200 {{ $tab['active'] ? 'border-blue-600 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400' }}"
        @if(isset($tab['type']) && $tab['type']==='category' )
        data-category-id="{{ $tab['id'] }}"
        data-category-slug="{{ $tab['slug'] }}"
        @endif>
        {{ $tab['label'] }}
      </a>
      @endforeach

      @if(count($tabs) > 8)
      <!-- More dropdown for remaining tabs -->
      <div class="relative group">
        <button class="text-sm lg:text-base font-medium whitespace-nowrap px-2 pb-1 border-b-2 border-transparent text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 transition-colors duration-200">
          More
          <svg class="inline-block w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
          </svg>
        </button>

        <!-- Dropdown menu -->
        <div class="absolute top-full left-0 mt-1 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
          <div class="py-2">
            @foreach(array_slice($tabs, 8) as $tab)
            <a href="{{ isset($tab['slug']) ? route('events.index', ['category' => $tab['slug']]) : '#' }}"
              class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400"
              @if(isset($tab['type']) && $tab['type']==='category' )
              data-category-id="{{ $tab['id'] }}"
              data-category-slug="{{ $tab['slug'] }}"
              @endif>
              {{ $tab['label'] }}
            </a>
            @endforeach
          </div>
        </div>
      </div>
      @endif
    </div>
  </div>
</nav>