<x-organizer-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Event') }}: {{ $event->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-bold mb-6">Update Event Details</h3>

                    {{-- Displaying Validation Errors --}}
                    @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300 rounded-lg">
                        <strong class="font-bold">Whoops!</strong> There were some problems with your input.
                        <ul class="mt-2 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('organizer.events.update', $event) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <!-- Event Title -->
                        <div>
                            <x-input-label for="title" :value="__('Event Title')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $event->title)" required autofocus />
                        </div>

                        <!-- Event Description -->
                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="5" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('description', $event->description) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Start Date -->
                            <div>
                                <x-input-label for="start_date" :value="__('Start Date & Time')" />
                                <x-text-input id="start_date" class="block mt-1 w-full" type="datetime-local" name="start_date" :value="old('start_date', $event->start_date->format('Y-m-d\TH:i'))" required />
                            </div>

                            <!-- End Date -->
                            <div>
                                <x-input-label for="end_date" :value="__('End Date & Time')" />
                                <x-text-input id="end_date" class="block mt-1 w-full" type="datetime-local" name="end_date" :value="old('end_date', $event->end_date->format('Y-m-d\TH:i'))" required />
                            </div>
                        </div>

                        <!-- Venue -->
                        <div>
                            <x-input-label for="venue" :value="__('Venue / Location')" />
                            <x-text-input id="venue" class="block mt-1 w-full" type="text" name="venue" :value="old('venue', $event->venue)" required />
                        </div>
                        <!-- City -->
                        <div>
                            <x-input-label for="city" :value="__('City')" />
                            <select id="city" name="city" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">Select a city</option>
                                @foreach(config('app.kh_cities') as $city)
                                <option value="{{ $city }}" {{ old('city', $event->city) == $city ? 'selected' : '' }}>{{ $city }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Capacity -->
                            <div>
                                <x-input-label for="capacity" :value="__('Capacity (Number of tickets)')" />
                                <x-text-input id="capacity" class="block mt-1 w-full" type="number" name="capacity" :value="old('capacity', $event->capacity)" required min="1" />
                            </div>

                            <!-- Ticket Price -->
                            <div>
                                <x-input-label for="ticket_price" :value="__('Ticket Price ($)')" />
                                <x-text-input id="ticket_price" class="block mt-1 w-full" type="number" name="ticket_price" :value="old('ticket_price', $event->ticket_price)" required min="0" step="0.01" />
                            </div>
                        </div>

                        <!-- Category -->
                        <div>
                            <x-input-label for="category_id" :value="__('Category')" />
                            <select id="category_id" name="category_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $event->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                        </div>

                        <!-- Image Upload -->
                        <div>
                            <x-input-label for="image" :value="__('Update Event Poster / Image')" />
                            @if($event->image_path)
                            <div class="my-2">
                                <p class="text-sm text-gray-600 dark:text-gray-400">Current Image:</p>
                                <img src="{{ Storage::url($event->image_path) }}" alt="Current Event Image" class="rounded-md w-48 h-auto mt-1">
                            </div>
                            @endif
                            <input id="image" class="block mt-1 w-full text-sm text-gray-900 dark:text-gray-300 border border-gray-300 dark:border-gray-700 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none" type="file" name="image">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Upload a new image to replace the current one.</p>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('organizer.my-events') }}" class="text-gray-600 dark:text-gray-400 hover:underline mr-4">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Update Event') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-organizer-layout>