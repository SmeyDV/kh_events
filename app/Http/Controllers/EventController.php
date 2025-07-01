<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request): View
  {
    $filters = [];

    if ($request->filled('search')) {
      $filters['search'] = $request->input('search');
    }

    if ($request->filled('city_id')) {
      $filters['city_id'] = $request->input('city_id');
    }

    // Temporarily bypass caching for debugging
    $query = Event::published()->with(['organizer', 'category', 'city']);

    if (isset($filters['search'])) {
      $query->search($filters['search']);
    }

    if (isset($filters['city_id'])) {
      $query->inCity($filters['city_id']);
    }

    $events = $query->orderBy('start_date', 'asc')->paginate(10);
    $cities = Event::getAvailableCities();

    return view('events.index', compact('events', 'cities'));
  }

  /**
   * Display the specified resource.
   */
  public function show(Event $event): View
  {
    // Ensure the event is published or the user is authorized to see it
    if ($event->status !== 'published') {
      abort(404);
    }

    // Eager load related data to prevent N+1 queries
    $event->load(['organizer', 'category', 'bookings.user']);

    return view('events.show', compact('event'));
  }
}
