<?php

namespace App\Http\Controllers\Organizer;

use App\Models\Event;
use App\Models\Category;
use App\Models\City;
use App\Models\EventImage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class EventController extends Controller
{
  /**
   * Display a listing of the organizer's events.
   */
  public function myEvents(): View
  {
    $events = Auth::user()->events()
      ->with(['category', 'bookings'])
      ->latest()
      ->paginate(9);

    return view('organizer.events.myevents', compact('events'));
  }

  /**
   * Show the form for creating a new event.
   */
  public function create(): View
  {
    $categories = Category::all();
    $cities = City::all();
    return view('organizer.events.create', compact('categories', 'cities'));
  }

  /**
   * Store a newly created event in storage.
   */
  public function store(Request $request): RedirectResponse
  {
    $validated = $request->validate([
      'title' => 'required|string|max:255',
      'description' => 'required|string|min:10',
      'start_date' => 'required|date|after:now',
      'end_date' => 'required|date|after:start_date',
      'venue' => 'required|string|max:255',
      'city_id' => 'required|exists:cities,id',
      'capacity' => 'required|integer|min:1',
      'ticket_price' => 'nullable|numeric|min:0|max:999999.99',
      'images' => 'nullable|array',
      'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
      'category_id' => 'required|exists:categories,id',
    ]);

    $validated['organizer_id'] = Auth::id();
    $validated['status'] = 'draft';

    $event = Event::create($validated);

    if ($request->hasFile('images')) {
      foreach ($request->file('images') as $image) {
        $path = $image->store('event_images', 'public');
        $event->images()->create(['image_path' => $path]);
      }
    }

    return redirect()
      ->route('organizer.my-events')
      ->with('success', 'Event created successfully. It is now pending admin approval.');
  }

  /**
   * Display the specified event.
   */
  public function show(Event $event): View
  {
    // Ensure the organizer can only view their own events
    if ($event->organizer_id !== Auth::id()) {
      abort(403, 'Unauthorized action.');
    }

    // Eager load bookings and users to prevent N+1 queries
    $event->load(['bookings.user', 'category']);

    return view('organizer.events.show', compact('event'));
  }

  /**
   * Show the form for editing the specified event.
   */
  public function edit(Event $event): View
  {
    // Ensure the organizer can only edit their own events
    if ($event->organizer_id !== Auth::id()) {
      abort(403, 'Unauthorized action.');
    }

    $categories = Category::all();
    $cities = City::all();
    return view('organizer.events.edit', compact('event', 'categories', 'cities'));
  }

  /**
   * Update the specified event in storage.
   */
  public function update(Request $request, Event $event): RedirectResponse
  {
    // Ensure the organizer can only update their own events
    if ($event->organizer_id !== Auth::id()) {
      abort(403, 'Unauthorized action.');
    }

    $validated = $request->validate([
      'title' => 'required|string|max:255',
      'description' => 'required|string|min:10',
      'start_date' => 'required|date',
      'end_date' => 'required|date|after:start_date',
      'venue' => 'required|string|max:255',
      'city_id' => 'required|exists:cities,id',
      'capacity' => 'required|integer|min:1',
      'ticket_price' => 'nullable|numeric|min:0|max:999999.99',
      'images' => 'nullable|array',
      'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
      'category_id' => 'required|exists:categories,id',
    ]);

    $event->update($validated);

    if ($request->hasFile('images')) {
      foreach ($request->file('images') as $image) {
        $path = $image->store('event_images', 'public');
        $event->images()->create(['image_path' => $path]);
      }
    }

    return redirect()
      ->route('organizer.my-events')
      ->with('success', 'Event updated successfully.');
  }

  /**
   * Remove the specified event from storage.
   */
  public function destroy(Event $event): RedirectResponse
  {
    // Ensure the organizer can only delete their own events
    if ($event->organizer_id !== Auth::id()) {
      abort(403, 'Unauthorized action.');
    }

    // Check if event has bookings
    if ($event->bookings()->exists()) {
      return redirect()
        ->route('organizer.my-events')
        ->with('error', 'Cannot delete event with existing bookings.');
    }

    // Delete images from storage
    foreach ($event->images as $image) {
      Storage::disk('public')->delete($image->image_path);
    }

    $event->delete();

    return redirect()
      ->route('organizer.my-events')
      ->with('success', 'Event deleted successfully.');
  }
}
