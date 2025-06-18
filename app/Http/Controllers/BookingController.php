<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
  /**
   * Show the booking form for an event.
   */
  public function create(Event $event): View|RedirectResponse
  {
    // Check if user is authenticated
    if (!Auth::check()) {
      return redirect()->route('login')->with('error', 'Please login to book tickets.');
    }

    // Check if event is available for booking
    if (!$event->isAvailableForBooking()) {
      return redirect()->route('events.show', $event)->with('error', 'This event is not available for booking.');
    }

    // Check if user has already booked this event
    $existingBooking = Auth::user()->bookings()->where('event_id', $event->id)->first();
    if ($existingBooking) {
      return redirect()->route('events.show', $event)->with('error', 'You have already booked this event.');
    }

    return view('bookings.create', compact('event'));
  }

  /**
   * Store a new booking.
   */
  public function store(Request $request, Event $event): RedirectResponse
  {
    // Check if user is authenticated
    if (!Auth::check()) {
      return redirect()->route('login')->with('error', 'Please login to book tickets.');
    }

    // Validate the request
    $validated = $request->validate([
      'quantity' => 'required|integer|min:1|max:10',
    ]);

    // Check if event is available for booking
    if (!$event->isAvailableForBooking()) {
      return redirect()->route('events.show', $event)->with('error', 'This event is not available for booking.');
    }

    // Check if user has already booked this event
    $existingBooking = Auth::user()->bookings()->where('event_id', $event->id)->first();
    if ($existingBooking) {
      return redirect()->route('events.show', $event)->with('error', 'You have already booked this event.');
    }

    // Check if enough tickets are available
    if ($event->capacity !== null && $event->remaining_tickets < $validated['quantity']) {
      return back()->withErrors(['quantity' => 'Not enough tickets available. Only ' . $event->remaining_tickets . ' tickets left.']);
    }

    // Calculate total amount
    $totalAmount = $event->ticket_price ? $event->ticket_price * $validated['quantity'] : 0;

    try {
      DB::beginTransaction();

      // Create the booking
      $booking = Auth::user()->bookings()->create([
        'event_id' => $event->id,
        'quantity' => $validated['quantity'],
        'total_amount' => $totalAmount,
        'status' => 'pending',
        'payment_status' => $totalAmount > 0 ? 'pending' : 'paid', // Free events are automatically paid
      ]);

      // If it's a free event, automatically confirm the booking
      if ($totalAmount === 0) {
        $booking->update([
          'status' => 'confirmed',
          'payment_status' => 'paid',
        ]);
      }

      DB::commit();

      if ($totalAmount > 0) {
        // Redirect to payment page (we'll implement this later)
        return redirect()->route('bookings.payment', $booking)->with('success', 'Booking created successfully. Please complete your payment.');
      } else {
        // Free event - booking is confirmed
        return redirect()->route('bookings.show', $booking)->with('success', 'Booking confirmed successfully!');
      }
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->with('error', 'An error occurred while creating your booking. Please try again.');
    }
  }

  /**
   * Display the specified booking.
   */
  public function show(Booking $booking): View
  {
    // Check if user owns this booking or is admin
    if (Auth::id() !== $booking->user_id && !Auth::user()->isAdmin()) {
      abort(403);
    }

    return view('bookings.show', compact('booking'));
  }

  /**
   * Display user's bookings.
   */
  public function index(): View
  {
    $bookings = Auth::user()->bookings()->with('event')->latest()->paginate(10);

    return view('bookings.index', compact('bookings'));
  }

  /**
   * Cancel a booking.
   */
  public function cancel(Booking $booking): RedirectResponse
  {
    // Check if user owns this booking or is admin
    if (Auth::id() !== $booking->user_id && !Auth::user()->isAdmin()) {
      abort(403);
    }

    // Check if booking can be cancelled
    if (!$booking->isPending() && !$booking->isConfirmed()) {
      return back()->with('error', 'This booking cannot be cancelled.');
    }

    $booking->update([
      'status' => 'cancelled',
      'payment_status' => 'refunded',
    ]);

    return redirect()->route('bookings.index')->with('success', 'Booking cancelled successfully.');
  }
}
