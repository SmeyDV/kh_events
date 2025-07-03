<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class EventController extends Controller
{
    /**
     * Display a listing of all events (public).
     */
    public function index(Request $request): JsonResponse
    {
        $query = Event::with(['category', 'organizer:id,name', 'images'])
            ->where('status', 'published');

        // Filtering by city
        if ($request->has('city_id') && $request->city_id != '') {
            $query->where('city_id', $request->city_id);
        }

        // Filtering by category
        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        // Searching by keyword
        if ($request->has('q') && $request->q != '') {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->q . '%')
                    ->orWhere('description', 'like', '%' . $request->q . '%');
            });
        }

        $events = $query->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $events
        ]);
    }

    /**
     * Display organizer's events.
     */
    public function organizerEvents(): JsonResponse
    {
        $events = Auth::user()->events()
            ->with(['category', 'bookings'])
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $events
        ]);
    }

    /**
     * Store a newly created event.
     */
    public function store(Request $request): JsonResponse
    {
        try {
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

            // Remove images from validated data as we'll handle them separately
            $images = $validated['images'] ?? [];
            unset($validated['images']);

            $event = Event::create($validated);

            // Handle multiple image uploads
            if (!empty($images)) {
                foreach ($images as $image) {
                    $imagePath = $image->store('event_images', 'public');
                    $event->images()->create([
                        'image_path' => $imagePath,
                        'is_primary' => $event->images()->count() === 0 // First image is primary
                    ]);
                }
            }

            $event->load(['category', 'organizer:id,name', 'city', 'images']);

            return response()->json([
                'success' => true,
                'message' => 'Event created successfully',
                'data' => $event
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Display the specified event (public).
     */
    public function show(string $id): JsonResponse
    {
        $event = Event::with(['category', 'organizer:id,name', 'images'])
            ->where('status', 'published')
            ->find($id);

        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $event
        ]);
    }

    /**
     * Display event details for organizer.
     */
    public function organizerEventDetails(string $id): JsonResponse
    {
        $event = Event::with(['bookings.user', 'category'])
            ->where('organizer_id', Auth::id())
            ->find($id);

        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found or unauthorized'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $event
        ]);
    }

    /**
     * Update the specified event.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $event = Event::where('organizer_id', Auth::id())->find($id);

        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found or unauthorized'
            ], 404);
        }

        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string|min:10',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'venue' => 'required|string|max:255',
                'city_id' => 'required|exists:cities,id',
                'capacity' => 'required|integer|min:1',
                'ticket_price' => 'nullable|numeric|min:0|max:999999.99',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'category_id' => 'required|exists:categories,id',
            ]);

            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($event->image_path) {
                    Storage::disk('public')->delete($event->image_path);
                }
                $validated['image_path'] = $request->file('image')->store('event_images', 'public');
            }

            $event->update($validated);
            $event->load(['category', 'organizer:id,name']);

            return response()->json([
                'success' => true,
                'message' => 'Event updated successfully',
                'data' => $event
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Remove the specified event.
     */
    public function destroy(string $id): JsonResponse
    {
        $event = Event::where('organizer_id', Auth::id())->find($id);

        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found or unauthorized'
            ], 404);
        }

        // Check if event has bookings
        if ($event->bookings()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete event with existing bookings'
            ], 400);
        }

        // Delete associated images from storage
        if ($event->images) {
            foreach ($event->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }
            // Images will be deleted from db by cascade
        }

        $event->delete();

        return response()->json([
            'success' => true,
            'message' => 'Event and associated images deleted successfully'
        ]);
    }

    /**
     * Upload event image.
     */
    public function uploadImage(Request $request, string $id): JsonResponse
    {
        $event = Event::where('organizer_id', Auth::id())->find($id);

        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found or unauthorized'
            ], 404);
        }

        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            // Delete old image if exists
            if ($event->image_path) {
                Storage::disk('public')->delete($event->image_path);
            }

            $imagePath = $request->file('image')->store('event_images', 'public');
            $event->update(['image_path' => $imagePath]);

            return response()->json([
                'success' => true,
                'message' => 'Image uploaded successfully',
                'image_url' => Storage::url($imagePath)
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Get events by category.
     */
    public function byCategory(string $categoryId): JsonResponse
    {
        $events = Event::with(['category', 'organizer:id,name', 'images'])
            ->where('category_id', $categoryId)
            ->where('status', 'published')
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $events
        ]);
    }

    /**
     * Get events by city.
     */
    public function byCity(string $cityId): JsonResponse
    {
        $events = Event::with(['category', 'organizer:id,name', 'images'])
            ->where('city_id', $cityId)
            ->where('status', 'published')
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $events
        ]);
    }

    /**
     * Search for events (public).
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->input('q');

        if (!$query) {
            return $this->index($request); // Return paginated results if no query
        }

        $events = Event::with(['category', 'organizer:id,name', 'images'])
            ->where('status', 'published')
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('venue', 'like', "%{$query}%")
                    ->orWhereHas('city', function ($cityQuery) use ($query) {
                        $cityQuery->where('name', 'like', "%{$query}%");
                    });
            })
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $events
        ]);
    }

    /**
     * Get organizer dashboard stats.
     */
    public function organizerDashboard(): JsonResponse
    {
        $stats = [
            'total_events' => Auth::user()->events()->count(),
            'published_events' => Auth::user()->events()->where('status', 'published')->count(),
            'draft_events' => Auth::user()->events()->where('status', 'draft')->count(),
            'total_bookings' => Auth::user()->events()->withCount('bookings')->get()->sum('bookings_count'),
            'total_revenue' => Auth::user()->events()->with('bookings')->get()->sum(function ($event) {
                return $event->bookings->sum('total_amount');
            }),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Get event statistics.
     */
    public function eventStats(string $id): JsonResponse
    {
        $event = Event::with(['bookings'])
            ->where('organizer_id', Auth::id())
            ->find($id);

        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found or unauthorized'
            ], 404);
        }

        $stats = [
            'total_bookings' => $event->bookings->count(),
            'confirmed_bookings' => $event->bookings->where('status', 'confirmed')->count(),
            'pending_bookings' => $event->bookings->where('status', 'pending')->count(),
            'cancelled_bookings' => $event->bookings->where('status', 'cancelled')->count(),
            'total_revenue' => $event->bookings->where('status', 'confirmed')->sum('total_amount'),
            'capacity_utilization' => round(($event->bookings->where('status', 'confirmed')->sum('quantity') / $event->capacity) * 100, 2),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
