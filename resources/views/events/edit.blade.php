<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Edit Event') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          <form method="POST" action="{{ route('events.update', $event) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div>
              <x-input-label for="title" :value="__('Event Title')" />
              <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $event->title)" required autofocus />
              <x-input-error :messages="$errors->get('title')" class="mt-2" />
            </div>

            <!-- Description -->
            <div>
              <x-input-label for="description" :value="__('Description')" />
              <textarea id="description" name="description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" required>{{ old('description', $event->description) }}</textarea>
              <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            <!-- Venue -->
            <div>
              <x-input-label for="venue" :value="__('Venue')" />
              <x-text-input id="venue" class="block mt-1 w-full" type="text" name="venue" :value="old('venue', $event->venue)" required />
              <x-input-error :messages="$errors->get('venue')" class="mt-2" />
            </div>

            <!-- Start Date -->
            <div>
              <x-input-label for="start_date" :value="__('Start Date and Time')" />
              <x-text-input id="start_date" class="block mt-1 w-full" type="datetime-local" name="start_date" :value="old('start_date', $event->start_date->format('Y-m-d\TH:i'))" required />
              <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
            </div>

            <!-- End Date -->
            <div>
              <x-input-label for="end_date" :value="__('End Date and Time')" />
              <x-text-input id="end_date" class="block mt-1 w-full" type="datetime-local" name="end_date" :value="old('end_date', $event->end_date->format('Y-m-d\TH:i'))" required />
              <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
            </div>

            <!-- Capacity -->
            <div>
              <x-input-label for="capacity" :value="__('Capacity (optional)')" />
              <x-text-input id="capacity" class="block mt-1 w-full" type="number" name="capacity" :value="old('capacity', $event->capacity)" min="1" />
              <x-input-error :messages="$errors->get('capacity')" class="mt-2" />
            </div>

            <!-- Ticket Price -->
            <div>
              <x-input-label for="ticket_price" :value="__('Ticket Price (optional)')" />
              <x-text-input id="ticket_price" class="block mt-1 w-full" type="number" name="ticket_price" :value="old('ticket_price', $event->ticket_price)" min="0" step="0.01" />
              <x-input-error :messages="$errors->get('ticket_price')" class="mt-2" />
            </div>

            <!-- Current Image -->
            @if($event->image_path)
            <div>
              <p class="mb-2">Current Image:</p>
              <img src="{{ Storage::url($event->image_path) }}" alt="Event image" class="w-48 h-48 object-cover rounded-lg">
            </div>
            @endif

            <!-- Image -->
            <div>
              <x-input-label for="image" :value="__('New Event Image (optional)')" />
              <input id="image" name="image" type="file" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
              <x-input-error :messages="$errors->get('image')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
              <x-secondary-button onclick="window.history.back()" type="button" class="mr-4">
                {{ __('Cancel') }}
              </x-secondary-button>
              <x-primary-button>
                {{ __('Update Event') }}
              </x-primary-button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>