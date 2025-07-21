<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TicketController extends Controller
{
  // Get all tickets (optionally filter by event)
  public function index(Request $request): JsonResponse
  {
    $query = Ticket::query();
    if ($request->has('event_id')) {
      $query->where('event_id', $request->event_id);
    }
    $tickets = $query->get();
    return response()->json($tickets);
  }

  // Store a new ticket
  public function store(Request $request): JsonResponse
  {
    $validated = $request->validate([
      'event_id' => 'required|exists:events,id',
      'type' => 'required|in:normal,early_birds,premium',
      'price' => 'required|numeric|min:0',
      'quantity' => 'nullable|integer|min:0',
    ]);
    $ticket = Ticket::create($validated);
    return response()->json($ticket, 201);
  }

  // Show a single ticket
  public function show($id): JsonResponse
  {
    $ticket = Ticket::findOrFail($id);
    return response()->json($ticket);
  }

  // Update a ticket
  public function update(Request $request, $id): JsonResponse
  {
    $ticket = Ticket::findOrFail($id);
    $validated = $request->validate([
      'type' => 'sometimes|in:normal,early_birds,premium',
      'price' => 'sometimes|numeric|min:0',
      'quantity' => 'nullable|integer|min:0',
    ]);
    $ticket->update($validated);
    return response()->json($ticket);
  }

  // Delete a ticket
  public function destroy($id): JsonResponse
  {
    $ticket = Ticket::findOrFail($id);
    $ticket->delete();
    return response()->json(['message' => 'Ticket deleted successfully']);
  }
}
