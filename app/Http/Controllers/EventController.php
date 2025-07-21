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

    if ($request->filled('category_id')) {
      $filters['category_id'] = $request->input('category_id');
    }

    // Temporarily bypass caching for debugging
    $query = Event::published()->with(['organizer', 'category', 'city']);

    if (isset($filters['search'])) {
      $query->search($filters['search']);
    }

    if (isset($filters['city_id'])) {
      $query->inCity($filters['city_id']);
    }

    if (isset($filters['category_id'])) {
      $query->where('category_id', $filters['category_id']);
    }

    $events = $query->orderBy('start_date', 'asc')->paginate(10);
    $cities = Event::getAvailableCities();
    $categories = \App\Models\Category::all();

    // Load tickets for each event
    $events->load('tickets');

    return view('events.index', compact('events', 'cities', 'categories'));
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
    $event->load(['organizer', 'category', 'bookings.user', 'tickets']);

    return view('events.show', compact('event'));
  }
}
