<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use App\Services\EventService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Http\Controllers\Controller;

class EventController extends Controller
{
  protected EventService $eventService;

  public function __construct(EventService $eventService)
  {
    $this->eventService = $eventService;
  }

  /**
   * List all pending events for approval.
   */
  public function index(): View
  {
    $pendingEvents = Event::where('status', 'draft')
      ->with(['organizer', 'category'])
      ->latest()
      ->paginate(15);

    return view('admin.events.index', compact('pendingEvents'));
  }

  /**
   * Display the specified event.
   */
  public function show(Event $event): View
  {
    $event->load(['organizer', 'category', 'bookings.user']);
    $stats = $this->eventService->getEventStats($event);

    return view('admin.events.show', compact('event', 'stats'));
  }

  /**
   * Approve an event.
   */
  public function approve(Event $event)
  {
    try {
      $this->eventService->approveEvent($event);
      return Redirect::back()->with('success', 'Event approved successfully!');
    } catch (\Exception $e) {
      return Redirect::back()->with('error', 'Failed to approve event: ' . $e->getMessage());
    }
  }

  /**
   * Reject an event.
   */
  public function reject(Event $event)
  {
    try {
      $this->eventService->rejectEvent($event);
      return Redirect::back()->with('success', 'Event rejected successfully!');
    } catch (\Exception $e) {
      return Redirect::back()->with('error', 'Failed to reject event: ' . $e->getMessage());
    }
  }
}
