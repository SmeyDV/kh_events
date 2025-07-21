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
                            <x-input-label for="city_id" :value="__('City')" />
                            <select id="city_id" name="city_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">Select a city</option>
                                @foreach($cities as $city)
                                <option value="{{ $city->id }}" {{ old('city_id', $event->city_id) == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Capacity -->
                            <div>
                                <x-input-label for="capacity" :value="__('Capacity (Number of tickets)')" />
                                <x-text-input id="capacity" class="block mt-1 w-full" type="number" name="capacity" :value="old('capacity', $event->capacity)" required min="1" />
                            </div>
                        </div>

                        <!-- Ticket Types & Prices -->
                        <div class="mt-6">
                            <h4 class="font-semibold mb-2">Ticket Types & Prices</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <x-input-label for="ticket_types[normal][price]" :value="__('Normal Price ($)')" />
                                    <x-text-input id="ticket_types_normal_price" class="block mt-1 w-full" type="number" name="ticket_types[normal][price]" :value="old('ticket_types.normal.price', optional($event->tickets->where('type','normal')->first())->price)" min="0" step="0.01" required />
                                    <x-input-label for="ticket_types[normal][quantity]" :value="__('Normal Quantity')" class="mt-2" />
                                    <x-text-input id="ticket_types_normal_quantity" class="block mt-1 w-full" type="number" name="ticket_types[normal][quantity]" :value="old('ticket_types.normal.quantity', optional($event->tickets->where('type','normal')->first())->quantity)" min="0" required />
                                </div>
                                <div>
                                    <x-input-label for="ticket_types[early_birds][price]" :value="__('Early Birds Price ($)')" />
                                    <x-text-input id="ticket_types_early_birds_price" class="block mt-1 w-full" type="number" name="ticket_types[early_birds][price]" :value="old('ticket_types.early_birds.price', optional($event->tickets->where('type','early_birds')->first())->price)" min="0" step="0.01" required />
                                    <x-input-label for="ticket_types[early_birds][quantity]" :value="__('Early Birds Quantity')" class="mt-2" />
                                    <x-text-input id="ticket_types_early_birds_quantity" class="block mt-1 w-full" type="number" name="ticket_types[early_birds][quantity]" :value="old('ticket_types.early_birds.quantity', optional($event->tickets->where('type','early_birds')->first())->quantity)" min="0" required />
                                </div>
                                <div>
                                    <x-input-label for="ticket_types[premium][price]" :value="__('Premium Price ($)')" />
                                    <x-text-input id="ticket_types_premium_price" class="block mt-1 w-full" type="number" name="ticket_types[premium][price]" :value="old('ticket_types.premium.price', optional($event->tickets->where('type','premium')->first())->price)" min="0" step="0.01" required />
                                    <x-input-label for="ticket_types[premium][quantity]" :value="__('Premium Quantity')" class="mt-2" />
                                    <x-text-input id="ticket_types_premium_quantity" class="block mt-1 w-full" type="number" name="ticket_types[premium][quantity]" :value="old('ticket_types.premium.quantity', optional($event->tickets->where('type','premium')->first())->quantity)" min="0" required />
                                </div>
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
                            <x-input-label for="images" :value="__('Event Images')" />
                            <div class="mt-2 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                @foreach($event->images as $image)
                                <div class="relative group">
                                    <img src="{{ Storage::url($image->image_path) }}" alt="Event Image" class="rounded-md w-full h-24 object-cover">
                                    <div class="absolute top-0 right-0">
                                        <button type="button" class="p-1 bg-red-500 text-white rounded-full opacity-75 group-hover:opacity-100">&times;</button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="relative h-48 rounded-lg border-dashed border-2 border-blue-700 bg-gray-100 flex justify-center items-center mt-2">
                                <div class="absolute">
                                    <div class="flex flex-col items-center">
                                        <i class="fa fa-folder-open fa-4x text-blue-700"></i>
                                        <span class="block text-gray-400 font-normal">Attach your files here</span>
                                    </div>
                                </div>
                                <input type="file" class="h-full w-full opacity-0" name="images[]" multiple>
                            </div>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Upload one or more new images.</p>
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