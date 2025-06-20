@props(['categories'])

<section class="w-full py-20">
  <div class="max-w-7xl mx-auto px-6 lg:px-8">
    <h2 class="text-3xl font-bold text-center text-gray-900 dark:text-white mb-12">Browse By Category</h2>
    @php
    $categories = [
    ['name' => 'Music', 'image' => 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?q=80&w=2070'],
    ['name' => 'Food & Drink', 'image' => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?q=80&w=2070'],
    ['name' => 'Charity & Causes', 'image' => 'https://images.unsplash.com/photo-1593113598332-cd288d649433?q=80&w=2070'],
    ['name' => 'Hobbies', 'image' => 'https://images.unsplash.com/photo-1487958449943-2429e8be8625?q=80&w=2070'],
    ];
    @endphp
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
      @foreach($categories as $category)
      <a href="#" class="block group relative">
        <div class="overflow-hidden rounded-lg">
          <img src="{{ $category['image'] }}&auto=format&fit=crop&h=400" alt="{{ $category['name'] }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-500">
        </div>
        <div class="absolute inset-0 bg-black/40 rounded-lg flex items-center justify-center p-4">
          <h3 class="text-2xl font-bold text-white tracking-wider text-center">{{ $category['name'] }}</h3>
        </div>
      </a>
      @endforeach
    </div>
  </div>
</section>