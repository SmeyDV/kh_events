<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BookingController extends Controller
{
  use AuthorizesRequests;

  /**
   * Show the form for creating a new booking.
   *
   * @param  \App\Models\Event  $event
   * @return \Illuminate\View\View
   */
  public function create(Event $event)
  {
    // You might want to add logic here to check if the event is bookable
    // (e.g., it's published, has remaining capacity, etc.)
    return view('bookings.create', compact('event'));
  }

  /**
   * Store a newly created booking in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Event  $event
   * @return \Illuminate\Http\RedirectResponse
   */
  public function store(Request $request, Event $event)
  {
    // This is a simplified store method.
    // You would want to add validation, payment processing, etc.
    $request->validate([
      'quantity' => 'required|integer|min:1',
    ]);

    // Basic check for capacity
    if ($event->capacity && $event->bookings()->sum('quantity') + $request->quantity > $event->capacity) {
      return back()->with('error', 'Sorry, there are not enough tickets available.');
    }

    $total_amount = ($event->ticket_price ?? 0) * $request->quantity;

    $booking = $event->bookings()->create([
      'user_id' => Auth::id(),
      'quantity' => $request->quantity,
      'total_amount' => $total_amount,
      // Add other relevant fields like total_amount if applicable
    ]);

    return redirect()->route('bookings.show', $booking)->with('success', 'Booking successful!');
  }

  /**
   * Display a listing of the user's bookings.
   *
   * @return \Illuminate\View\View
   */
  public function index()
  {
    $bookings = Auth::user()->bookings()->with('event')->latest()->paginate(10);
    return view('bookings.index', compact('bookings'));
  }

  /**
   * Display the specified booking.
   *
   * @param  \App\Models\Booking  $booking
   * @return \Illuminate\View\View
   */
  public function show(Booking $booking)
  {
    // Add authorization check to ensure the user can view this booking
    $this->authorize('view', $booking);
    return view('bookings.show', compact('booking'));
  }

  /**
   * Cancel the specified booking.
   *
   * @param  \App\Models\Booking  $booking
   * @return \Illuminate\Http\RedirectResponse
   */
  public function cancel(Booking $booking)
  {
    // Add authorization check
    $this->authorize('delete', $booking);

    // Add logic for cancellation (e.g., check if it's cancellable)
    $booking->delete(); // Or update status to 'cancelled'

    return redirect()->route('bookings.index')->with('success', 'Booking cancelled successfully.');
  }

  // User booking logic here
}
