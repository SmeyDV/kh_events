<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $query = Event::where('status', 'published');

    if ($request->has('search')) {
      $searchTerm = $request->input('search');
      $query->where('title', 'like', '%' . $searchTerm . '%')
        ->orWhere('description', 'like', '%' . $searchTerm . '%');
    }

    $cities = Event::whereNotNull('city')->distinct()->pluck('city');
    if ($request->filled('city')) {
      $query->where('city', $request->input('city'));
    }
    $events = $query->paginate(10)->withQueryString();
    return view('events.index', compact('events', 'cities'));
  }

  /**
   * Display the specified resource.
   */
  public function show(Event $event)
  {
    // Ensure the event is published or the user is authorized to see it
    // For now, we assume if it's accessed publicly, it should be published.
    if ($event->status !== 'published') {
      // Or handle as you see fit, maybe show for admins/organizers
      abort(404);
    }
    return view('events.show', compact('event'));
  }
}
