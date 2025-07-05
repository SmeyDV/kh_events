<x-main-layout>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-red-600 to-red-800 text-white py-20 pt-32">
      <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-6xl font-black mb-6">Event Categories</h1>
        <p class="text-xl md:text-2xl text-red-100 max-w-3xl mx-auto">
          Discover amazing events across all categories in Cambodia
        </p>
      </div>
    </div>

    <!-- Categories Grid -->
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-16">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        @foreach($categories as $category)
        @php
        // Category image mapping
        $categoryImages = [
        'music' => 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?q=80&w=2070',
        'food-drink' => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?q=80&w=2070',
        'charity-causes' => 'https://images.unsplash.com/photo-1593113598332-cd288d649433?q=80&w=2070',
        'hobbies' => 'https://images.unsplash.com/photo-1487958449943-2429e8be8625?q=80&w=2070',
        'tech' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRuJdag7QfvljZJrsJbdx5PnBf7C79Fd7tG-w&s',
        'fashion' => 'https://images.unsplash.com/photo-1445205170230-053b83016050?q=80&w=2070',
        'sports' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?q=80&w=2070',
        'health-wellness' => 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?q=80&w=2070',
        'education' => 'https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?q=80&w=2070',
        'arts-culture' => 'https://images.unsplash.com/photo-1513475382585-d06e58bcb0e0?q=80&w=2070',
        'business-networking' => 'https://images.unsplash.com/photo-1515187029135-18ee286d815b?q=80&w=2070',
        'travel-adventure' => 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?q=80&w=2070',
        ];
        @endphp

        <a href="{{ route('categories.show', $category->slug) }}" class="group bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden">
          <div class="relative">
            <img src="{{ $categoryImages[$category->slug] ?? 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=2070' }}&auto=format&fit=crop&h=300"
              alt="{{ $category->name }}"
              class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-500">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
            <div class="absolute bottom-4 left-4 right-4">
              <h3 class="text-2xl font-bold text-white mb-2">{{ $category->name }}</h3>
              <div class="flex items-center text-white/80">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span>{{ $category->events_count }} events</span>
              </div>
            </div>
          </div>
          <div class="p-6">
            <div class="flex items-center justify-between">
              <span class="text-red-600 dark:text-red-400 font-semibold">Explore {{ $category->name }}</span>
              <svg class="w-5 h-5 text-gray-400 group-hover:text-red-600 group-hover:translate-x-1 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
              </svg>
            </div>
          </div>
      </div>
      @endforeach
    </div>
  </div>
  </div>
</x-main-layout>