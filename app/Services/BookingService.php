<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class BookingService
{
  /**
   * Create a new booking.
   */
  public function createBooking(Event $event, User $user, int $quantity): Booking
  {
    return DB::transaction(function () use ($event, $user, $quantity) {
      // Recheck capacity within transaction
      if ($event->capacity && $event->remaining_tickets < $quantity) {
        throw new \Exception('Not enough tickets available.');
      }

      $totalAmount = ($event->ticket_price ?? 0) * $quantity;

      return $event->bookings()->create([
        'user_id' => $user->id,
        'quantity' => $quantity,
        'total_amount' => $totalAmount,
        'status' => 'confirmed',
        'payment_status' => 'pending',
        'booking_date' => now(),
      ]);
    });
  }

  /**
   * Cancel a booking.
   */
  public function cancelBooking(Booking $booking): Booking
  {
    return DB::transaction(function () use ($booking) {
      // Check if booking can be cancelled
      if ($booking->event->start_date->diffInDays(now()) < 1) {
        throw new \Exception('Cannot cancel booking within 24 hours of the event.');
      }

      if ($booking->status === 'cancelled') {
        throw new \Exception('Booking is already cancelled.');
      }

      $booking->update(['status' => 'cancelled']);
      return $booking;
    });
  }

  /**
   * Get user's bookings.
   */
  public function getUserBookings(User $user, int $perPage = 10)
  {
    return $user->bookings()
      ->with(['event.organizer', 'event.category'])
      ->latest('booking_date')
      ->paginate($perPage);
  }

  /**
   * Get booking statistics for an event.
   */
  public function getEventBookingStats(Event $event): array
  {
    return [
      'total_bookings' => $event->bookings()->count(),
      'confirmed_bookings' => $event->bookings()->where('status', 'confirmed')->count(),
      'cancelled_bookings' => $event->bookings()->where('status', 'cancelled')->count(),
      'pending_bookings' => $event->bookings()->where('status', 'pending')->count(),
      'total_revenue' => $event->bookings()->where('status', 'confirmed')->sum('total_amount'),
      'average_booking_size' => $event->bookings()->where('status', 'confirmed')->avg('quantity'),
    ];
  }

  /**
   * Check if user can book event.
   */
  public function canUserBookEvent(Event $event, User $user): bool
  {
    // Check if event is available for booking
    if (!$event->isAvailableForBooking()) {
      return false;
    }

    // Check if user already has a booking for this event
    if ($event->bookings()->where('user_id', $user->id)->where('status', 'confirmed')->exists()) {
      return false;
    }

    return true;
  }

  /**
   * Get booking by ID with authorization check.
   */
  public function getBookingWithAuth(Booking $booking, User $user): ?Booking
  {
    if ($booking->user_id !== $user->id && !$user->isAdmin()) {
      return null;
    }

    return $booking->load(['event.organizer', 'event.category']);
  }
}
