<main class="flex-grow flex items-center justify-center relative overflow-hidden pt-20">
  <!-- Background Image -->
  <div class="absolute inset-0 z-0">
    <img src="https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover opacity-20" alt="Music concert background">
    <div class="absolute inset-0 bg-gradient-to-t from-white via-white/90 to-transparent dark:from-gray-900 dark:via-gray-900/90"></div>
  </div>

  <div class="relative z-10 text-center max-w-4xl mx-auto px-4 py-24">
    <h1 class="text-5xl md:text-7xl font-black text-gray-900 dark:text-white leading-tight tracking-tighter">
      Find Your Vibe. <span class="text-red-600 dark:text-red-500">Create Your Moment.</span>
    </h1>
    <p class="mt-6 text-lg text-gray-700 dark:text-gray-300 max-w-2xl mx-auto">
      The heart of Cambodia's events is here. Discover concerts, festivals, and unique local happenings, or launch your own event and connect with your audience.
    </p>
    <div class="mt-12 flex flex-col sm:flex-row justify-center gap-4">
      <a href="{{ route('events.index') }}" class="inline-block rounded-lg bg-red-600 px-8 py-4 text-center font-semibold text-white shadow-lg hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-300 dark:focus:ring-red-800 transition-transform transform hover:scale-105">
        Explore Events
      </a>
      <a href="{{ route('register.organizer') }}" class="inline-block rounded-lg bg-blue-600 px-8 py-4 text-center font-semibold text-white shadow-lg hover:bg-blue-700 border border-transparent focus:outline-none focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 transition-transform transform hover:scale-105">
        Become an Organizer
      </a>
    </div>
  </div>
</main>