<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Display a listing of all payments.
     */
    public function index()
    {
        $payments = \App\Models\Payment::with('event')->orderBy('created_at', 'desc')->get();
        $payments = $payments->map(function ($payment) {
            $data = $payment->toArray();
            $data['event_title'] = $payment->event ? $payment->event->title : null;
            return $data;
        });
        return response()->json([
            'success' => true,
            'data' => $payments
        ]);
    }

    /**
     * Store a new payment via API.
     */
    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'user_id' => 'required|exists:users,id',
            'event_id' => 'required|exists:events,id',
            'ticket_id' => 'required|exists:tickets,id',
            'quantity' => 'required|integer|min:1',
            'total' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $payment = Payment::create([
                'booking_id' => $request->booking_id,
                'user_id' => $request->user_id,
                'event_id' => $request->event_id,
                'ticket_id' => $request->ticket_id,
                'quantity' => $request->quantity,
                'total' => $request->total,
                'payment_date' => now(),
            ]);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Payment successful.',
                'data' => $payment
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
     * Display the specified payment by ID.
     */
    public function show($id)
    {
        $payment = \App\Models\Payment::with('event')->find($id);
        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment not found.'
            ], 404);
        }
        $data = $payment->toArray();
        $data['event_title'] = $payment->event ? $payment->event->title : null;
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
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
