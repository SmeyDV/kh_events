<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EventController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        // Authorization is handled in each method individually.
    }

    public function index(): View
    {
        $this->authorize('viewAny', Event::class);
        $events = Event::where('status', 'published')->paginate(9);
        // UPDATED VIEW PATH
        return view('organizer.events.index', compact('events'));
    }

    public function myEvents(): View
    {
        $this->authorize('viewAny', Event::class);
        $events = Auth::user()->events()->paginate(9);
        // UPDATED VIEW PATH
        return view('organizer.events.myevents', compact('events'));
    }

    public function create(): View
    {
        $this->authorize('create', Event::class);
        // UPDATED VIEW PATH
        return view('organizer.events.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Event::class);
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

        $validated['user_id'] = Auth::id();

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('event_images', 'public');
        }

        Event::create($validated);

        return redirect()->route('organizer.my-events')->with('success', 'Event created successfully. It is now pending admin approval.');
    }

    public function show(Event $event): View
    {
        $this->authorize('view', $event);
        // UPDATED VIEW PATH
        return view('organizer.events.show', compact('event'));
    }

    public function edit(Event $event): View
    {
        $this->authorize('update', $event);
        // UPDATED VIEW PATH
        return view('organizer.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event): RedirectResponse
    {
        $this->authorize('update', $event);
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
            $validated['image'] = $request->file('image')->store('event_images', 'public');
        }

        $event->update($validated);

        return redirect()->route('organizer.my-events')->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event): RedirectResponse
    {
        $this->authorize('delete', $event);
        $event->delete();
        return redirect()->route('organizer.my-events')->with('success', 'Event deleted successfully.');
    }
}
