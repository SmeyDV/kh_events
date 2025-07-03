<main class="h-screen flex items-center justify-center relative overflow-hidden">
  <!-- Photo Slider Background -->
  <div class="absolute inset-0 z-0">
    <!-- Slide 1 -->
    <div class="slide absolute inset-0 opacity-100 transition-opacity duration-1000 ease-in-out">
      <img src="https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover" alt="Music concert">
      <div class="absolute inset-0 bg-black/40"></div>
    </div>
    <!-- Slide 2 -->
    <div class="slide absolute inset-0 opacity-0 transition-opacity duration-1000 ease-in-out">
      <img src="https://images.unsplash.com/photo-1514525253161-7a46d19cd819?q=80&w=2074&auto=format&fit=crop" class="w-full h-full object-cover" alt="Live music performance">
      <div class="absolute inset-0 bg-black/40"></div>
    </div>
    <!-- Slide 3 -->
    <div class="slide absolute inset-0 opacity-0 transition-opacity duration-1000 ease-in-out">
      <img src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover" alt="Event gathering">
      <div class="absolute inset-0 bg-black/40"></div>
    </div>
    <!-- Slide 4 -->
    <div class="slide absolute inset-0 opacity-0 transition-opacity duration-1000 ease-in-out">
      <img src="https://images.unsplash.com/photo-1506157786151-b8491531f063?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover" alt="Festival crowd">
      <div class="absolute inset-0 bg-black/40"></div>
    </div>
    <!-- Slide 5 -->
    <div class="slide absolute inset-0 opacity-0 transition-opacity duration-1000 ease-in-out">
      <img src="https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?q=80&w=2074&auto=format&fit=crop" class="w-full h-full object-cover" alt="Concert lights">
      <div class="absolute inset-0 bg-black/40"></div>
    </div>
  </div>

  <!-- Slider Indicators -->
  <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-20 flex space-x-3">
    <button class="indicator w-3 h-3 rounded-full bg-white/50 hover:bg-white/80 transition-colors duration-300 active" data-slide="0"></button>
    <button class="indicator w-3 h-3 rounded-full bg-white/50 hover:bg-white/80 transition-colors duration-300" data-slide="1"></button>
    <button class="indicator w-3 h-3 rounded-full bg-white/50 hover:bg-white/80 transition-colors duration-300" data-slide="2"></button>
    <button class="indicator w-3 h-3 rounded-full bg-white/50 hover:bg-white/80 transition-colors duration-300" data-slide="3"></button>
    <button class="indicator w-3 h-3 rounded-full bg-white/50 hover:bg-white/80 transition-colors duration-300" data-slide="4"></button>
  </div>

  <!-- Content Overlay -->
  <div class="relative z-10 text-center max-w-4xl mx-auto px-4 py-24">
    <div class="mb-6">
      <span class="inline-block px-4 py-2 bg-red-600/90 text-white text-sm font-semibold rounded-full backdrop-blur-sm">
        🎉 Cambodia's Premier Event Platform
      </span>
    </div>
    <h1 class="text-5xl md:text-7xl lg:text-8xl font-black text-white leading-tight tracking-tighter drop-shadow-2xl">
      Discover Amazing <br>
      <span class="text-red-400 bg-gradient-to-r from-red-400 to-red-600 bg-clip-text text-transparent">Events in Cambodia</span>
    </h1>
    <p class="mt-8 text-xl md:text-2xl text-white/90 max-w-3xl mx-auto drop-shadow-lg leading-relaxed">
      From vibrant festivals in Siem Reap to intimate concerts in Phnom Penh. Join thousands discovering unforgettable experiences across Cambodia.
    </p>

    <!-- Action Buttons -->
    <div class="mt-12 flex flex-col sm:flex-row justify-center gap-4">
      <a href="{{ route('events.index') }}" class="group inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-white bg-gradient-to-r from-red-600 to-red-700 rounded-full shadow-xl hover:shadow-2xl hover:from-red-700 hover:to-red-800 transform hover:scale-105 transition-all duration-300 backdrop-blur-sm">
        <svg class="w-5 h-5 mr-2 group-hover:animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
        Explore Events
      </a>
      <a href="{{ route('register.organizer') }}" class="group inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-white bg-white/20 border-2 border-white/30 rounded-full shadow-xl hover:bg-white/30 hover:border-white/50 transform hover:scale-105 transition-all duration-300 backdrop-blur-sm">
        <svg class="w-5 h-5 mr-2 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        Create Event
      </a>
    </div>

    <!-- Quick Stats -->
    <div class="mt-16 grid grid-cols-2 md:grid-cols-4 gap-6 max-w-2xl mx-auto">
      <div class="text-center bg-white/10 backdrop-blur-sm rounded-lg py-4 px-3">
        <div class="text-2xl font-bold text-white">500+</div>
        <div class="text-sm text-white/80">Events</div>
      </div>
      <div class="text-center bg-white/10 backdrop-blur-sm rounded-lg py-4 px-3">
        <div class="text-2xl font-bold text-white">50+</div>
        <div class="text-sm text-white/80">Organizers</div>
      </div>
      <div class="text-center bg-white/10 backdrop-blur-sm rounded-lg py-4 px-3">
        <div class="text-2xl font-bold text-white">25</div>
        <div class="text-sm text-white/80">Cities</div>
      </div>
      <div class="text-center bg-white/10 backdrop-blur-sm rounded-lg py-4 px-3">
        <div class="text-2xl font-bold text-white">10K+</div>
        <div class="text-sm text-white/80">Attendees</div>
      </div>
    </div>
  </div>

  <!-- Navigation Arrows -->
  <button id="prevSlide" class="absolute left-6 top-1/2 transform -translate-y-1/2 z-20 w-12 h-12 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center backdrop-blur-sm transition-all duration-300 group">
    <svg class="w-6 h-6 text-white group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
    </svg>
  </button>
  <button id="nextSlide" class="absolute right-6 top-1/2 transform -translate-y-1/2 z-20 w-12 h-12 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center backdrop-blur-sm transition-all duration-300 group">
    <svg class="w-6 h-6 text-white group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
    </svg>
  </button>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      let currentSlide = 0;
      const slides = document.querySelectorAll('.slide');
      const indicators = document.querySelectorAll('.indicator');
      const totalSlides = slides.length;
      let slideInterval;

      // Function to show specific slide
      function showSlide(index) {
        // Hide all slides
        slides.forEach(slide => slide.style.opacity = '0');
        indicators.forEach(indicator => indicator.classList.remove('active'));

        // Show current slide
        slides[index].style.opacity = '1';
        indicators[index].classList.add('active');

        currentSlide = index;
      }

      // Function to go to next slide
      function nextSlide() {
        const next = (currentSlide + 1) % totalSlides;
        showSlide(next);
      }

      // Function to go to previous slide
      function prevSlide() {
        const prev = (currentSlide - 1 + totalSlides) % totalSlides;
        showSlide(prev);
      }

      // Auto-play functionality
      function startAutoPlay() {
        slideInterval = setInterval(nextSlide, 5000); // Change slide every 5 seconds
      }

      function stopAutoPlay() {
        clearInterval(slideInterval);
      }

      // Event listeners for navigation arrows
      document.getElementById('nextSlide').addEventListener('click', () => {
        stopAutoPlay();
        nextSlide();
        startAutoPlay();
      });

      document.getElementById('prevSlide').addEventListener('click', () => {
        stopAutoPlay();
        prevSlide();
        startAutoPlay();
      });

      // Event listeners for indicators
      indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => {
          stopAutoPlay();
          showSlide(index);
          startAutoPlay();
        });
      });

      // Pause on hover
      const heroSection = document.querySelector('main');
      heroSection.addEventListener('mouseenter', stopAutoPlay);
      heroSection.addEventListener('mouseleave', startAutoPlay);

      // Start the slider
      startAutoPlay();

      // Add active class to first indicator
      indicators[0].classList.add('active');
    });
  </script>

  <style>
    .indicator.active {
      background-color: rgba(255, 255, 255, 0.9);
      transform: scale(1.2);
    }

    .slide {
      transition: opacity 1000ms ease-in-out;
    }
  </style>
</main>