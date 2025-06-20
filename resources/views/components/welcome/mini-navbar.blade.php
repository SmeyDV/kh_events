<nav class="w-full bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
  <div class="max-w-7xl mx-auto px-6 lg:px-8">
    <div class="flex space-x-6 overflow-x-auto py-4">
      @php
      $tabs = [
      ['label' => 'All', 'active' => true],
      ['label' => 'For you', 'active' => false],
      ['label' => 'Online', 'active' => false],
      ['label' => 'Today', 'active' => false],
      ['label' => 'This weekend', 'active' => false],
      ['label' => 'Free', 'active' => false],
      ['label' => 'Music', 'active' => false],
      ['label' => 'Food & Drink', 'active' => false],
      ['label' => 'Charity & Causes', 'active' => false],
      ];
      @endphp
      @foreach($tabs as $tab)
      <a href="#" class="text-base font-medium whitespace-nowrap px-2 pb-1 border-b-2 transition-colors duration-200 {{ $tab['active'] ? 'border-blue-600 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400' }}">
        {{ $tab['label'] }}
      </a>
      @endforeach
    </div>
  </div>
</nav>