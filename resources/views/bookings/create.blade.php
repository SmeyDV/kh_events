<x-main-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      Book Tickets for {{ $event->title }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
      @if (session('error'))
      <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
      </div>
      @endif

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Event Details -->
        <div class="lg:col-span-2">
          <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
              <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Event Details</h3>

              @if($event->images->isNotEmpty())
              <img src="{{ Storage::url($event->images->first()->image_path) }}" alt="{{ $event->title }}" class="w-full h-64 object-cover rounded-lg mb-6">
              @endif

              <div class="space-y-4">
                <div>
                  <h4 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $event->title }}</h4>
                  <p class="text-gray-600 dark:text-gray-400 mt-2">{{ $event->description }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Date & Time</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $event->start_date->format('F d, Y g:i A') }}</p>
                  </div>
                  <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Venue</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $event->venue }}</p>
                  </div>
                  <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Price</p>
                    <p class="font-medium text-gray-900 dark:text-white">
                      @if ($event->ticket_price)
                      ${{ number_format($event->ticket_price, 2) }}
                      @else
                      Free
                      @endif
                    </p>
                  </div>
                  <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Available Tickets</p>
                    <p class="font-medium text-gray-900 dark:text-white">
                      @if ($event->capacity)
                      {{ $event->remaining_tickets }} of {{ $event->capacity }}
                      @else
                      Unlimited
                      @endif
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Booking Form -->
        <div class="lg:col-span-1">
          <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
              <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Book Your Tickets</h3>

              <form method="POST" action="{{ route('bookings.store', $event) }}" class="space-y-6">
                @csrf

                <div>
                  <x-input-label for="ticket_type" :value="__('Ticket Type')" />
                  <select id="ticket_type" name="ticket_type" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                    @foreach($event->tickets as $ticket)
                    <option value="{{ $ticket->id }}" data-price="{{ $ticket->price }}" data-available="{{ $ticket->quantity }}">
                      {{ ucfirst(str_replace('_', ' ', $ticket->type)) }} - ${{ number_format($ticket->price, 2) }} ({{ $ticket->quantity }} available)
                    </option>
                    @endforeach
                  </select>
                  <x-input-error :messages="$errors->get('ticket_type')" class="mt-2" />
                </div>

                <div>
                  <x-input-label for="quantity" :value="__('Number of Tickets')" />
                  <select id="quantity" name="quantity" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                    <!-- Options will be populated by JS -->
                  </select>
                  <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                </div>

                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                  <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600 dark:text-gray-400">Price per ticket:</span>
                    <span class="font-medium text-gray-900 dark:text-white" id="price-per-ticket-display"></span>
                  </div>
                  <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600 dark:text-gray-400">Quantity:</span>
                    <span class="font-medium text-gray-900 dark:text-white" id="quantity-display">1</span>
                  </div>
                  <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600 dark:text-gray-400">Available:</span>
                    <span class="font-medium text-gray-900 dark:text-white" id="available-display"></span>
                  </div>
                  <hr class="my-2 border-gray-300 dark:border-gray-600">
                  <div class="flex justify-between items-center">
                    <span class="text-lg font-semibold text-gray-900 dark:text-white">Total:</span>
                    <span class="text-lg font-bold text-red-600 dark:text-red-400" id="total-amount"></span>
                  </div>
                </div>

                <div class="flex items-center justify-between">
                  <a href="{{ route('events.show', $event) }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                    ← Back to Event
                  </a>
                  <x-primary-button type="submit" class="bg-red-600 hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:ring-red-500">
                    {{ __('Book Now') }}
                  </x-primary-button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const ticketTypeSelect = document.getElementById('ticket_type');
      const quantitySelect = document.getElementById('quantity');
      const priceDisplay = document.getElementById('price-per-ticket-display');
      const availableDisplay = document.getElementById('available-display');
      const quantityDisplay = document.getElementById('quantity-display');
      const totalAmountDisplay = document.getElementById('total-amount');

      function updateForm() {
        const selectedOption = ticketTypeSelect.options[ticketTypeSelect.selectedIndex];
        const price = parseFloat(selectedOption.getAttribute('data-price'));
        const available = parseInt(selectedOption.getAttribute('data-available'));
        priceDisplay.textContent = price > 0 ? '$' + price.toFixed(2) : 'Free';
        availableDisplay.textContent = available;

        // Update quantity options
        quantitySelect.innerHTML = '';
        for (let i = 1; i <= Math.min(10, available); i++) {
          const opt = document.createElement('option');
          opt.value = i;
          opt.textContent = i + ' ' + (i === 1 ? 'ticket' : 'tickets');
          quantitySelect.appendChild(opt);
        }
        quantityDisplay.textContent = quantitySelect.value;
        updateTotal();
      }

      function updateTotal() {
        const selectedOption = ticketTypeSelect.options[ticketTypeSelect.selectedIndex];
        const price = parseFloat(selectedOption.getAttribute('data-price'));
        const quantity = parseInt(quantitySelect.value);
        quantityDisplay.textContent = quantity;
        let total = price * quantity;
        totalAmountDisplay.textContent = total > 0 ? '$' + total.toFixed(2) : 'Free';
      }

      ticketTypeSelect.addEventListener('change', updateForm);
      quantitySelect.addEventListener('change', updateTotal);

      // Initialize on page load
      updateForm();
    });
  </script>
</x-main-layout>