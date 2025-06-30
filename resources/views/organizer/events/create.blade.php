<x-organizer-layout>
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
            <form id="event-form" enctype="multipart/form-data">
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
                                    <strong class="font-bold">Whoops!</strong> There were some problems with your input.
                                    <ul class="mt-1 list-disc list-inside text-sm">
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif

                                <!-- Success Message -->
                                <div id="success-message" class="mb-4 p-3 bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300 rounded-lg hidden">
                                    <strong class="font-bold">Success!</strong> <span id="success-text"></span>
                                </div>

                                <!-- Error Message -->
                                <div id="error-message" class="mb-4 p-3 bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300 rounded-lg hidden">
                                    <strong class="font-bold">Error!</strong> <span id="error-text"></span>
                                </div>

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
                                        <x-input-label for="city" :value="__('City')" />
                                        <select id="city" name="city" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                            <option value="">Select a city</option>
                                            @foreach(config('app.kh_cities') as $city)
                                            <option value="{{ $city }}" {{ old('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                                            @endforeach
                                        </select>
                                        <div class="text-red-500 text-sm mt-1 hidden" id="city-error"></div>
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
                                        <x-input-label for="image" :value="__('Event Poster / Image')" />
                                        <input id="image" class="block mt-1 w-full text-sm text-gray-900 dark:text-gray-300 border border-gray-300 dark:border-gray-700 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none" type="file" name="image">
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF up to 2MB.</p>
                                        <div class="text-red-500 text-sm mt-1 hidden" id="image-error"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('organizer.my-events') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:underline mr-4">
                                Cancel
                            </a>
                            <button type="submit" id="submit-btn" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Create Event') }}
                            </button>
                            <button type="button" id="loading-btn" class="hidden items-center px-4 py-2 bg-gray-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest cursor-not-allowed">
                                <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Creating...
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('event-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            // Get form elements
            const submitBtn = document.getElementById('submit-btn');
            const loadingBtn = document.getElementById('loading-btn');
            const successMessage = document.getElementById('success-message');
            const errorMessage = document.getElementById('error-message');

            // Hide previous messages
            successMessage.classList.add('hidden');
            errorMessage.classList.add('hidden');

            // Clear previous field errors
            document.querySelectorAll('[id$="-error"]').forEach(el => {
                el.classList.add('hidden');
                el.textContent = '';
            });

            // Show loading state
            submitBtn.classList.add('hidden');
            loadingBtn.classList.remove('hidden');
            loadingBtn.style.display = 'inline-flex';

            try {
                // Create FormData object
                const formData = new FormData(this);

                // Get CSRF token
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                    document.querySelector('input[name="_token"]')?.value;

                // Make API request
                const response = await fetch('/api/v1/organizer/events', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    // Show success message
                    document.getElementById('success-text').textContent = data.message;
                    successMessage.classList.remove('hidden');

                    // Reset form
                    this.reset();

                    // Redirect after 2 seconds
                    setTimeout(() => {
                        window.location.href = '{{ route("organizer.my-events") }}';
                    }, 2000);
                } else {
                    // Show error message
                    document.getElementById('error-text').textContent = data.message || 'An error occurred';
                    errorMessage.classList.remove('hidden');

                    // Show field-specific errors
                    if (data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            const errorElement = document.getElementById(field + '-error');
                            if (errorElement) {
                                errorElement.textContent = data.errors[field][0];
                                errorElement.classList.remove('hidden');
                            }
                        });
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('error-text').textContent = 'Network error occurred. Please try again.';
                errorMessage.classList.remove('hidden');
            } finally {
                // Reset button states
                submitBtn.classList.remove('hidden');
                loadingBtn.classList.add('hidden');
                loadingBtn.style.display = 'none';
            }
        });
    </script>
</x-organizer-layout>