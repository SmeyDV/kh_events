<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BookingController extends Controller
{
  use AuthorizesRequests;

  /**
   * Show the form for creating a new booking.
   *
   * @param  \App\Models\Event  $event
   * @return \Illuminate\View\View
   */
  public function create(Event $event): View
  {
    // Check if event is available for booking
    if (!$event->isAvailableForBooking()) {
      abort(403, 'This event is not available for booking.');
    }

    return view('bookings.create', compact('event'));
  }

  /**
   * Store a newly created booking in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Event  $event
   * @return \Illuminate\Http\RedirectResponse
   */
  public function store(Request $request, Event $event): RedirectResponse
  {
    // Check if event is available for booking
    if (!$event->isAvailableForBooking()) {
      return back()->with('error', 'This event is not available for booking.');
    }

    $request->validate([
      'quantity' => 'required|integer|min:1|max:10',
    ]);

    $quantity = $request->input('quantity');

    // Check capacity with database transaction
    try {
      DB::beginTransaction();

      // Recheck capacity within transaction
      if ($event->capacity && $event->remaining_tickets < $quantity) {
        DB::rollBack();
        return back()->with('error', 'Sorry, there are not enough tickets available.');
      }

      $totalAmount = ($event->ticket_price ?? 0) * $quantity;

      $booking = $event->bookings()->create([
        'user_id' => Auth::id(),
        'quantity' => $quantity,
        'total_amount' => $totalAmount,
        'status' => 'confirmed',
        'booking_date' => now(),
      ]);

      DB::commit();

      return redirect()
        ->route('bookings.show', $booking)
        ->with('success', 'Booking successful!');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->with('error', 'An error occurred while processing your booking. Please try again.');
    }
  }

  /**
   * Display a listing of the user's bookings.
   *
   * @return \Illuminate\View\View
   */
  public function index(): View
  {
    $bookings = Auth::user()->bookings()
      ->with(['event.organizer', 'event.category'])
      ->latest('booking_date')
      ->paginate(10);

    return view('bookings.index', compact('bookings'));
  }

  /**
   * Display the specified booking.
   *
   * @param  \App\Models\Booking  $booking
   * @return \Illuminate\View\View
   */
  public function show(Booking $booking): View
  {
    // Add authorization check to ensure the user can view this booking
    $this->authorize('view', $booking);

    // Eager load related data
    $booking->load(['event.organizer', 'event.category']);

    return view('bookings.show', compact('booking'));
  }

  /**
   * Cancel the specified booking.
   *
   * @param  \App\Models\Booking  $booking
   * @return \Illuminate\Http\RedirectResponse
   */
  public function cancel(Booking $booking): RedirectResponse
  {
    // Add authorization check
    $this->authorize('delete', $booking);

    // Check if booking can be cancelled (e.g., not too close to event date)
    if ($booking->event->start_date->diffInDays(now()) < 1) {
      return back()->with('error', 'Cannot cancel booking within 24 hours of the event.');
    }

    // Check if booking is already cancelled
    if ($booking->status === 'cancelled') {
      return back()->with('error', 'Booking is already cancelled.');
    }

    try {
      DB::beginTransaction();

      $booking->update(['status' => 'cancelled']);

      DB::commit();

      return redirect()
        ->route('bookings.index')
        ->with('success', 'Booking cancelled successfully.');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->with('error', 'An error occurred while cancelling the booking.');
    }
  }

  // User booking logic here
}
