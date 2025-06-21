<div x-show="sidebarOpen"
  x-transition:enter="transition ease-out duration-300"
  x-transition:enter-start="opacity-0 transform -translate-x-full"
  x-transition:enter-end="opacity-100 transform translate-x-0"
  x-transition:leave="transition ease-in duration-200"
  x-transition:leave-start="opacity-100 transform translate-x-0"
  x-transition:leave-end="opacity-0 transform -translate-x-full"
  class="fixed inset-y-0 left-0 z-30 w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 md:relative md:translate-x-0 md:opacity-100">

  <aside class="w-full h-full">
    <div class="p-4 flex justify-between items-center">
      <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Organizer Menu</h2>
      <button @click="sidebarOpen = false" class="md:hidden text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 focus:outline-none transition-colors duration-200">
        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M6 18L18 6M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>
      </button>
    </div>
    <nav class="mt-4">
      <a href="{{ route('organizer.dashboard') }}" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
        <span class="mx-4 font-medium">Dashboard</span>
      </a>
      <a href="{{ route('organizer.my-events') }}" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
        <span class="mx-4 font-medium">My Events</span>
      </a>
      <a href="{{ route('organizer.events.create') }}" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
        <span class="mx-4 font-medium">Create Event</span>
      </a>
      <a href="{{ route('organizer.profile.edit') }}" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
        <span class="mx-4 font-medium">Profile</span>
      </a>
    </nav>
  </aside>
</div>