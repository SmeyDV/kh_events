<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a new booking and payment via API.
     */
    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'ticket_id' => 'required|exists:tickets,id',
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        $event = Event::findOrFail($request->event_id);
        $ticket = Ticket::where('event_id', $event->id)->where('id', $request->ticket_id)->firstOrFail();
        $quantity = $request->quantity;
        $total = $ticket->price * $quantity;

        // Check capacity and ticket quantity
        if ($event->capacity && $event->remaining_tickets < $quantity) {
            return response()->json(['success' => false, 'message' => 'Not enough tickets available.'], 422);
        }
        if ($ticket->quantity < $quantity) {
            return response()->json(['success' => false, 'message' => 'Not enough tickets of this type available.'], 422);
        }

        DB::beginTransaction();
        try {
            // Create booking
            $booking = Booking::create([
                'user_id' => Auth::id() ?? 1, // For API testing, fallback to user 1 if not authenticated
                'event_id' => $event->id,
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
                'user_id' => $booking->user_id,
                'event_id' => $event->id,
                'ticket_id' => $ticket->id,
                'quantity' => $quantity,
                'total' => $total,
                'payment_date' => now(),
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Booking and payment successful.',
                'data' => $booking->load(['event', 'ticket', 'payment'])
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
