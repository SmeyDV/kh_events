<?php

namespace App\Services;

use App\Models\Event;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class EventService
{
  /**
   * Create a new event.
   */
  public function createEvent(array $data, User $organizer): Event
  {
    return DB::transaction(function () use ($data, $organizer) {
      $data['organizer_id'] = $organizer->id;
      $data['status'] = 'draft';

      if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
        $data['image_path'] = $this->storeImage($data['image']);
      }

      return Event::create($data);
    });
  }

  /**
   * Update an existing event.
   */
  public function updateEvent(Event $event, array $data): Event
  {
    return DB::transaction(function () use ($event, $data) {
      if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
        // Delete old image if exists
        if ($event->image_path) {
          $this->deleteImage($event->image_path);
        }
        $data['image_path'] = $this->storeImage($data['image']);
      }

      $event->update($data);
      return $event;
    });
  }

  /**
   * Delete an event.
   */
  public function deleteEvent(Event $event): bool
  {
    return DB::transaction(function () use ($event) {
      // Check if event has bookings
      if ($event->bookings()->exists()) {
        throw new \Exception('Cannot delete event with existing bookings.');
      }

      // Delete image if exists
      if ($event->image_path) {
        $this->deleteImage($event->image_path);
      }

      return $event->delete();
    });
  }

  /**
   * Approve an event.
   */
  public function approveEvent(Event $event): Event
  {
    $event->update(['status' => 'published']);
    return $event;
  }

  /**
   * Reject an event.
   */
  public function rejectEvent(Event $event): Event
  {
    $event->update(['status' => 'rejected']);
    return $event;
  }

  /**
   * Store event image.
   */
  private function storeImage(UploadedFile $image): string
  {
    return $image->store('event_images', 'public');
  }

  /**
   * Delete event image.
   */
  private function deleteImage(string $imagePath): bool
  {
    return Storage::disk('public')->delete($imagePath);
  }

  /**
   * Get events with filters and pagination.
   */
  public function getEventsWithFilters(array $filters = [], int $perPage = 10)
  {
    return Event::getPublishedEvents($perPage, $filters);
  }

  /**
   * Get upcoming events.
   */
  public function getUpcomingEvents(int $limit = 3)
  {
    return Event::getUpcomingEvents($limit);
  }

  /**
   * Get organizer's events.
   */
  public function getOrganizerEvents(User $organizer, int $perPage = 9)
  {
    return $organizer->events()
      ->with(['category', 'bookings'])
      ->latest()
      ->paginate($perPage);
  }

  /**
   * Check if event can be booked.
   */
  public function canBookEvent(Event $event): bool
  {
    return $event->isAvailableForBooking();
  }

  /**
   * Get event statistics.
   */
  public function getEventStats(Event $event): array
  {
    return [
      'total_bookings' => $event->bookings()->count(),
      'confirmed_bookings' => $event->bookings()->where('status', 'confirmed')->count(),
      'tickets_sold' => $event->tickets_sold,
      'remaining_tickets' => $event->remaining_tickets,
      'revenue' => $event->bookings()->where('status', 'confirmed')->sum('total_amount'),
      'is_sold_out' => $event->isSoldOut(),
    ];
  }
}
