<?php

namespace App\Http\Controllers\Organizer;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
  // Organizer-specific event logic goes here

  public function myEvents(): \Illuminate\View\View
  {
    $events = Auth::user()->events()->paginate(9);
    return view('organizer.events.myevents', compact('events'));
  }

  public function create(): \Illuminate\View\View
  {
    return view('organizer.events.create');
  }

  public function store(\Illuminate\Http\Request $request)
  {
    $validated = $request->validate([
      'title' => 'required|string|max:255',
      'description' => 'required|string',
      'start_date' => 'required|date',
      'end_date' => 'required|date|after_or_equal:start_date',
      'venue' => 'required|string|max:255',
      'capacity' => 'required|integer|min:1',
      'ticket_price' => 'nullable|numeric|min:0',
      'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    $validated['organizer_id'] = Auth::id();

    if ($request->hasFile('image')) {
      $validated['image_path'] = $request->file('image')->store('event_images', 'public');
    }

    \App\Models\Event::create($validated);

    return redirect()->route('organizer.my-events')->with('success', 'Event created successfully. It is now pending admin approval.');
  }

  public function edit(\App\Models\Event $event): \Illuminate\View\View
  {
    return view('organizer.events.edit', compact('event'));
  }

  public function update(\Illuminate\Http\Request $request, \App\Models\Event $event)
  {
    $validated = $request->validate([
      'title' => 'required|string|max:255',
      'description' => 'required|string',
      'start_date' => 'required|date',
      'end_date' => 'required|date|after_or_equal:start_date',
      'venue' => 'required|string|max:255',
      'capacity' => 'required|integer|min:1',
      'ticket_price' => 'nullable|numeric|min:0',
      'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    if ($request->hasFile('image')) {
      $validated['image_path'] = $request->file('image')->store('event_images', 'public');
    }

    $event->update($validated);

    return redirect()->route('organizer.my-events')->with('success', 'Event updated successfully.');
  }
}
