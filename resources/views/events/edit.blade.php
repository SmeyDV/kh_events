<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Edit Event') }}
    </h2>
  </x-slot>

  <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden">
        <div class="p-8">
          <form method="POST" action="{{ route('events.update', $event) }}" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
              <!-- Left: Event Details -->
              <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                  <svg class="w-7 h-7 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zm0 0V4m0 4v4m0 0h4m-4 0H8" />
                  </svg>
                  Event Details
                </h3>
                <div class="space-y-4">
                  <!-- Title -->
                  <div>
                    <x-input-label for="title" :value="__('Event Title')" />
                    <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $event->title)" required autofocus />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                  </div>
                  <!-- Description -->
                  <div>
                    <x-input-label for="description" :value="__('Description')" />
                    <textarea id="description" name="description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 dark:bg-gray-900 dark:text-gray-200 rounded-lg border border-gray-300 dark:border-gray-700 focus:ring-blue-500 focus:border-blue-500" required>{{ old('description', $event->description) }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                  </div>
                  <!-- Venue -->
                  <div>
                    <x-input-label for="venue" :value="__('Venue')" />
                    <x-text-input id="venue" class="block mt-1 w-full" type="text" name="venue" :value="old('venue', $event->venue)" required />
                    <x-input-error :messages="$errors->get('venue')" class="mt-2" />
                  </div>
                  <!-- Dates -->
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <x-input-label for="start_date" :value="__('Start Date & Time')" />
                      <x-text-input id="start_date" class="block mt-1 w-full" type="datetime-local" name="start_date" :value="old('start_date', $event->start_date->format('Y-m-d\TH:i'))" required />
                      <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                    </div>
                    <div>
                      <x-input-label for="end_date" :value="__('End Date & Time')" />
                      <x-text-input id="end_date" class="block mt-1 w-full" type="datetime-local" name="end_date" :value="old('end_date', $event->end_date->format('Y-m-d\TH:i'))" required />
                      <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                    </div>
                  </div>
                  <!-- Capacity & Price -->
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <x-input-label for="capacity" :value="__('Capacity (optional)')" />
                      <x-text-input id="capacity" class="block mt-1 w-full" type="number" name="capacity" :value="old('capacity', $event->capacity)" min="1" />
                      <x-input-error :messages="$errors->get('capacity')" class="mt-2" />
                    </div>
                    <div>
                      <x-input-label for="ticket_price" :value="__('Ticket Price (optional)')" />
                      <x-text-input id="ticket_price" class="block mt-1 w-full" type="number" name="ticket_price" :value="old('ticket_price', $event->ticket_price)" min="0" step="0.01" />
                      <x-input-error :messages="$errors->get('ticket_price')" class="mt-2" />
                    </div>
                  </div>
                </div>
              </div>

              <!-- Right: Event Image -->
              <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                  <svg class="w-7 h-7 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 3v4M8 3v4m-4 4h16" />
                  </svg>
                  Event Image
                </h3>
                <div class="space-y-4">
                  @if($event->image_path)
                  <div>
                    <p class="mb-2 text-gray-700 dark:text-gray-300">Current Image:</p>
                    <img src="{{ Storage::url($event->image_path) }}" alt="Event image" class="w-full h-48 object-cover rounded-lg shadow mb-4">
                  </div>
                  @endif
                  <div>
                    <x-input-label for="image" :value="__('New Event Image (optional)')" />
                    <input id="image" name="image" type="file" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" onchange="previewImage(event)" />
                    <x-input-error :messages="$errors->get('image')" class="mt-2" />
                    <div id="image-preview" class="mt-4 hidden">
                      <p class="mb-2 text-gray-700 dark:text-gray-300">Preview:</p>
                      <img id="preview-img" src="#" alt="Image preview" class="w-full h-48 object-cover rounded-lg shadow">
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 gap-4">
              <x-secondary-button onclick="window.history.back()" type="button">
                {{ __('Cancel') }}
              </x-secondary-button>
              <x-primary-button class="bg-gradient-to-r from-pink-500 to-red-500 hover:from-pink-600 hover:to-red-600 focus:ring-pink-500">
                {{ __('Update Event') }}
              </x-primary-button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    function previewImage(event) {
      const input = event.target;
      const previewDiv = document.getElementById('image-preview');
      const previewImg = document.getElementById('preview-img');
      if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
          previewImg.src = e.target.result;
          previewDiv.classList.remove('hidden');
        }
        reader.readAsDataURL(input.files[0]);
      } else {
        previewDiv.classList.add('hidden');
        previewImg.src = '#';
      }
    }
  </script>
</x-app-layout>