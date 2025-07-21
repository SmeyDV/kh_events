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
    // Validate ticket selection
    $request->validate([
      'ticket_type' => 'required|exists:tickets,id',
      'quantity' => 'required|integer|min:1|max:10',
    ]);

    $ticket = $event->tickets()->where('id', $request->ticket_type)->first();
    if (!$ticket) {
      return back()->with('error', 'Invalid ticket type selected.');
    }
    $quantity = $request->input('quantity');
    $total = $ticket->price * $quantity;

    // Check capacity and ticket quantity
    if ($event->capacity && $event->remaining_tickets < $quantity) {
      return back()->with('error', 'Sorry, not enough tickets available.');
    }
    if ($ticket->quantity < $quantity) {
      return back()->with('error', 'Sorry, not enough tickets of this type available.');
    }

    DB::beginTransaction();
    try {
      // Create booking
      $booking = $event->bookings()->create([
        'user_id' => Auth::id(),
        'ticket_id' => $ticket->id,
        'quantity' => $quantity,
        'total_amount' => $total,
        'status' => 'confirmed',
        'payment_status' => 'paid',
        'booking_date' => now(),
      ]);

      // Decrement ticket quantity
      $ticket->decrement('quantity', $quantity);

      // Create payment
      $booking->payment()->create([
        'user_id' => Auth::id(),
        'event_id' => $event->id,
        'ticket_id' => $ticket->id,
        'quantity' => $quantity,
        'total' => $total,
        'payment_date' => now(),
      ]);

      DB::commit();
      return redirect()->route('bookings.show', $booking)->with('success', 'Booking and payment successful!');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->with('error', 'An error occurred while processing your booking.');
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
    $booking->load(['event.organizer', 'event.category', 'ticket']);

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

  /**
   * Show the mock payment page.
   */
  public function payment(Request $request, Event $event)
  {
    $ticket = $event->tickets()->where('id', $request->ticket)->firstOrFail();
    $quantity = $request->quantity;
    $total = $ticket->price * $quantity;
    return view('bookings.payment', compact('event', 'ticket', 'quantity', 'total'));
  }

  /**
   * Process the mock payment, create booking and payment records, and redirect to confirmation.
   */
  public function processPayment(Request $request, Event $event)
  {
    $request->validate([
      'ticket' => 'required|exists:tickets,id',
      'quantity' => 'required|integer|min:1|max:10',
    ]);

    $ticket = $event->tickets()->where('id', $request->ticket)->firstOrFail();
    $quantity = $request->quantity;
    $total = $ticket->price * $quantity;

    // Check capacity and ticket quantity
    if ($event->capacity && $event->remaining_tickets < $quantity) {
      return back()->with('error', 'Sorry, not enough tickets available.');
    }
    if ($ticket->quantity < $quantity) {
      return back()->with('error', 'Sorry, not enough tickets of this type available.');
    }

    \DB::beginTransaction();
    try {
      // Create booking
      $booking = $event->bookings()->create([
        'user_id' => \Auth::id(),
        'ticket_id' => $ticket->id,
        'quantity' => $quantity,
        'total_amount' => $total,
        'status' => 'confirmed',
        'payment_status' => 'paid',
        'booking_date' => now(),
      ]);

      // Decrement ticket quantity
      $ticket->decrement('quantity', $quantity);

      // Create payment
      $booking->payment()->create([
        'user_id' => \Auth::id(),
        'event_id' => $event->id,
        'ticket_id' => $ticket->id,
        'quantity' => $quantity,
        'total' => $total,
        'payment_date' => now(),
      ]);

      \DB::commit();
      return redirect()->route('bookings.show', $booking)->with('success', 'Payment successful! Your tickets are confirmed.');
    } catch (\Exception $e) {
      \DB::rollBack();
      return back()->with('error', 'An error occurred while processing your payment.');
    }
  }

  // User booking logic here
}
