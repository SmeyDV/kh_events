<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Http\Controllers\Controller;

class EventController extends Controller
{
  // List all pending events for approval
  public function index(): View
  {
    $pendingEvents = Event::where('status', 'draft')->with('organizer')->latest()->get();
    return view('admin.events.index', compact('pendingEvents'));
  }

  // Approve an event
  public function approve($id)
  {
    $event = Event::findOrFail($id);
    $event->status = 'published';
    $event->save();
    return Redirect::back()->with('success', 'Event approved!');
  }

  // Reject an event
  public function reject($id)
  {
    $event = Event::findOrFail($id);
    $event->status = 'rejected';
    $event->save();
    return Redirect::back()->with('success', 'Event rejected!');
  }
}
