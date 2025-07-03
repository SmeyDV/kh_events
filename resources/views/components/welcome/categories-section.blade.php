@props(['categories'])

<section class="w-full py-20">
  <div class="max-w-7xl mx-auto px-6 lg:px-8">
    <h2 class="text-3xl font-bold text-center text-gray-900 dark:text-white mb-12">Browse By Category</h2>

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

    // Take first 4 categories for display
    $displayCategories = $categories->take(4);
    @endphp

    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
      @foreach($displayCategories as $category)
      <a href="{{ route('categories.show', $category->slug) }}" class="block group relative">
        <div class="overflow-hidden rounded-lg">
          <img src="{{ $categoryImages[$category->slug] ?? 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=2070' }}&auto=format&fit=crop&h=400"
            alt="{{ $category->name }}"
            class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-500">
        </div>
        <div class="absolute inset-0 bg-black/40 rounded-lg flex items-center justify-center p-4 group-hover:bg-black/50 transition-colors duration-300">
          <h3 class="text-2xl font-bold text-white tracking-wider text-center">{{ $category->name }}</h3>
        </div>
      </a>
      @endforeach
    </div>

    @if($categories->count() > 4)
    <div class="text-center mt-8">
      <a href="{{ route('categories.index') }}" class="inline-flex items-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors duration-300">
        <span>View All Categories</span>
        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
        </svg>
      </a>
    </div>
    @endif
  </div>
</section>