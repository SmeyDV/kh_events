<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Your E-Ticket') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
          <div class="border-4 border-dashed border-gray-300 p-6 rounded-lg">

            <div class="text-center mb-6">
              <h1 class="text-3xl font-bold text-gray-800">{{ $booking->event->title }}</h1>
              <p class="text-lg text-gray-600">This is your official ticket.</p>
            </div>

            <div class="flex justify-between items-center mb-6">
              <div>
                <p class="text-sm text-gray-500">Event Date</p>
                <p class="font-bold">{{ $booking->event->start_date->format('l, F j, Y') }}</p>
                <p>{{ $booking->event->start_date->format('g:i A') }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500 text-right">Venue</p>
                <p class="font-bold text-right">{{ $booking->event->venue }}</p>
              </div>
            </div>

            <div class="my-6 border-t border-dashed border-gray-300"></div>

            <div class="flex justify-between items-center mb-6">
              <div>
                <p class="text-sm text-gray-500">Booked By</p>
                <p class="font-bold">{{ $booking->user->name }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500 text-right">Quantity</p>
                <p class="font-bold text-right">{{ $booking->quantity }} {{ Str::plural('Ticket', $booking->quantity) }}</p>
              </div>
            </div>

            <div class="my-6 border-t border-dashed border-gray-300"></div>

            <div class="text-center">
              <p class="text-sm text-gray-500 mb-2">Scan this QR code at the entrance</p>
              {{-- In a real application, you would generate a QR code with the booking ID --}}
              <div class="flex justify-center">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ route('bookings.show', $booking) }}" alt="QR Code">
              </div>
              <p class="mt-2 text-xs text-gray-500 font-mono">Booking ID: {{ $booking->id }}</p>
            </div>

          </div>
        </div>
      </div>
      <div class="mt-4 text-center">
        <a href="{{ route('bookings.index') }}" class="text-sm text-gray-600 hover:text-gray-900">&larr; Back to my bookings</a>
      </div>
    </div>
  </div>
</x-app-layout>