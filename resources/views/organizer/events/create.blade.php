<x-organizer-layout>
    <x-slot name="header">
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </x-slot>

    <div class="bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 text-white rounded-xl mx-4 sm:mx-6 lg:mx-8 mt-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h2 class="text-3xl font-bold tracking-tight">
                {{ __('Create Events') }}
            </h2>
            <p class="mt-2 text-blue-100">Manage and organize your events with ease</p>
        </div>
    </div>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('organizer.events.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    <!-- Column 1: Event Details Card -->
                    <div class="lg:col-span-2">
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900 dark:text-gray-100">
                                <h3 class="text-xl font-bold mb-6">Event Details</h3>

                                {{-- Displaying Validation Errors --}}
                                @if ($errors->any())
                                <div class="mb-4 p-3 bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300 rounded-lg">
                                    <strong class="font-bold">Error!</strong> Validation failed
                                    <ul class="mt-1 list-disc list-inside text-sm">
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif

                                {{-- Success Message --}}
                                @if (session('success'))
                                <div class="mb-4 p-3 bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300 rounded-lg">
                                    <strong class="font-bold">Success!</strong> {{ session('success') }}
                                </div>
                                @endif

                                <div class="space-y-6">
                                    <!-- Event Title -->
                                    <div>
                                        <x-input-label for="title" :value="__('Event Title')" />
                                        <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus />
                                        <div class="text-red-500 text-sm mt-1 hidden" id="title-error"></div>
                                    </div>

                                    <!-- Event Description -->
                                    <div>
                                        <x-input-label for="description" :value="__('Description')" />
                                        <textarea id="description" name="description" rows="4" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('description') }}</textarea>
                                        <div class="text-red-500 text-sm mt-1 hidden" id="description-error"></div>
                                    </div>

                                    <!-- Venue -->
                                    <div>
                                        <x-input-label for="venue" :value="__('Venue / Location')" />
                                        <x-text-input id="venue" class="block mt-1 w-full" type="text" name="venue" :value="old('venue')" required />
                                        <div class="text-red-500 text-sm mt-1 hidden" id="venue-error"></div>
                                    </div>

                                    <!-- City -->
                                    <div>
                                        <x-input-label for="city_id" :value="__('City')" />
                                        <select id="city_id" name="city_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                            <option value="">Select a city</option>
                                            @foreach($cities as $city)
                                            <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="text-red-500 text-sm mt-1 hidden" id="city_id-error"></div>
                                    </div>

                                    <!-- Category -->
                                    <div>
                                        <x-input-label for="category_id" :value="__('Category')" />
                                        <select id="category_id" name="category_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                            <option value="">Select a category</option>
                                            @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="text-red-500 text-sm mt-1 hidden" id="category_id-error"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Column 2: Logistics Card -->
                    <div class="lg:col-span-1">
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900 dark:text-gray-100">
                                <h3 class="text-xl font-bold mb-6">Scheduling & Ticketing</h3>
                                <div class="space-y-6">
                                    <div class="grid grid-cols-1 gap-6">
                                        <!-- Start Date -->
                                        <div>
                                            <x-input-label for="start_date" :value="__('Start Date & Time')" />
                                            <x-text-input id="start_date" class="block mt-1 w-full" type="datetime-local" name="start_date" :value="old('start_date')" required />
                                            <div class="text-red-500 text-sm mt-1 hidden" id="start_date-error"></div>
                                        </div>

                                        <!-- End Date -->
                                        <div>
                                            <x-input-label for="end_date" :value="__('End Date & Time')" />
                                            <x-text-input id="end_date" class="block mt-1 w-full" type="datetime-local" name="end_date" :value="old('end_date')" required />
                                            <div class="text-red-500 text-sm mt-1 hidden" id="end_date-error"></div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Capacity -->
                                        <div>
                                            <x-input-label for="capacity" :value="__('Capacity')" />
                                            <x-text-input id="capacity" class="block mt-1 w-full" type="number" name="capacity" :value="old('capacity')" required min="1" />
                                            <div class="text-red-500 text-sm mt-1 hidden" id="capacity-error"></div>
                                        </div>

                                        <!-- Ticket Price -->
                                        <div>
                                            <x-input-label for="ticket_price" :value="__('Price ($)')" />
                                            <x-text-input id="ticket_price" class="block mt-1 w-full" type="number" name="ticket_price" :value="old('ticket_price', 0)" required min="0" step="0.01" />
                                            <div class="text-red-500 text-sm mt-1 hidden" id="ticket_price-error"></div>
                                        </div>
                                    </div>

                                    <!-- Image Upload -->
                                    <div>
                                        <x-input-label for="images" :value="__('Event Images')" />
                                        <div class="relative  h-48 rounded-lg border-dashed border-2 border-blue-700 bg-gray-100 flex justify-center items-center mt-2">
                                            <div class="absolute">
                                                <div class="flex flex-col items-center">
                                                    <i class="fa fa-folder-open fa-4x text-blue-700"></i>
                                                    <span class="block text-gray-400 font-normal">Attach your files here</span>
                                                </div>
                                            </div>
                                            <input type="file" class="h-full w-full opacity-0" name="images[]" multiple>
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF up to 2MB each.</p>
                                        <div class="text-red-500 text-sm mt-1 hidden" id="images-error"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('organizer.my-events') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:underline mr-4">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Create Event') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

</x-organizer-layout>