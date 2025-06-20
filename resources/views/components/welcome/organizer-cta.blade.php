@props(['organizerCta'])
<section class="bg-red-700 dark:bg-red-800/50 my-20">
  <div class="max-w-7xl mx-auto px-6 lg:px-8 py-16 text-center">
    <h2 class="text-4xl font-black text-white leading-tight tracking-tighter">
      Make your own event
    </h2>
    <p class="mt-4 text-lg text-red-100 max-w-2xl mx-auto">
      Got a show, a party, or a workshop to share? Join our community of organizers and bring your event to life on our platform.
    </p>
    <div class="mt-8">
      <a href="{{ route('register.organizer') }}" class="inline-block rounded-lg bg-white px-8 py-4 text-center font-semibold text-red-700 shadow-lg hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-red-300 dark:focus:ring-red-800 transition-transform transform hover:scale-105">
        Create Your Event
      </a>
    </div>
  </div>
</section>