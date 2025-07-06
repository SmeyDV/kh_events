<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Event;
use App\Models\Category;
use App\Models\Booking;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AdminController extends Controller
{
    // ===== USER MANAGEMENT =====

    public function users(Request $request): JsonResponse
    {
        $users = User::with('role')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->when($request->role, function ($query, $role) {
                $query->whereHas('role', function ($q) use ($role) {
                    $q->where('slug', $role);
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 15);

        return response()->json($users);
    }

    public function userDetails($id): JsonResponse
    {
        $user = User::with(['role', 'bookings.event', 'events'])->findOrFail($id);
        return response()->json($user);
    }

    public function updateUser(Request $request, $id): JsonResponse
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'role_id' => 'sometimes|exists:roles,id'
        ]);

        $user->update($request->only(['name', 'email', 'role_id']));

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user->load('role')
        ]);
    }

    public function deleteUser($id): JsonResponse
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    public function toggleUserStatus($id): JsonResponse
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => !$user->is_active]);

        return response()->json([
            'message' => 'User status updated successfully',
            'user' => $user
        ]);
    }

    // ===== EVENT MANAGEMENT =====

    public function allEvents(Request $request): JsonResponse
    {
        $events = Event::with(['organizer', 'category', 'images', 'bookings'])
            ->when($request->search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->category, function ($query, $category) {
                $query->where('category_id', $category);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 15);

        return response()->json($events);
    }

    public function eventDetails($id): JsonResponse
    {
        $event = Event::with(['organizer', 'category', 'images', 'bookings.user'])->findOrFail($id);
        return response()->json($event);
    }

    public function updateEvent(Request $request, $id): JsonResponse
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after:start_date',
            'location' => 'sometimes|string|max:255',
            'ticket_price' => 'sometimes|numeric|min:0',
            'max_attendees' => 'sometimes|integer|min:1',
            'category_id' => 'sometimes|exists:categories,id'
        ]);

        $event->update($request->only([
            'title',
            'description',
            'start_date',
            'end_date',
            'location',
            'ticket_price',
            'max_attendees',
            'category_id'
        ]));

        return response()->json([
            'message' => 'Event updated successfully',
            'event' => $event->load(['organizer', 'category'])
        ]);
    }

    public function deleteEvent($id): JsonResponse
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return response()->json(['message' => 'Event deleted successfully']);
    }

    public function approveEvent($id): JsonResponse
    {
        $event = Event::findOrFail($id);
        $event->update(['status' => 'published']);

        return response()->json([
            'message' => 'Event approved successfully',
            'event' => $event
        ]);
    }

    public function rejectEvent($id): JsonResponse
    {
        $event = Event::findOrFail($id);
        $event->update(['status' => 'rejected']);

        return response()->json([
            'message' => 'Event rejected successfully',
            'event' => $event
        ]);
    }

    public function featureEvent($id): JsonResponse
    {
        $event = Event::findOrFail($id);
        $event->update(['is_featured' => !$event->is_featured]);

        return response()->json([
            'message' => 'Event featured status updated',
            'event' => $event
        ]);
    }

    // ===== CATEGORY MANAGEMENT =====

    public function categories(Request $request): JsonResponse
    {
        $categories = Category::withCount('events')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate($request->per_page ?? 15);

        return response()->json($categories);
    }

    public function createCategory(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string'
        ]);

        $category = Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description
        ]);

        return response()->json([
            'message' => 'Category created successfully',
            'category' => $category
        ], 201);
    }

    public function updateCategory(Request $request, $id): JsonResponse
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string'
        ]);

        $category->update([
            'name' => $request->name ?? $category->name,
            'slug' => $request->name ? Str::slug($request->name) : $category->slug,
            'description' => $request->description
        ]);

        return response()->json([
            'message' => 'Category updated successfully',
            'category' => $category
        ]);
    }

    public function deleteCategory($id): JsonResponse
    {
        $category = Category::findOrFail($id);

        if ($category->events()->count() > 0) {
            return response()->json([
                'error' => 'Cannot delete category with existing events'
            ], 422);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }

    // ===== BOOKING MANAGEMENT =====

    public function allBookings(Request $request): JsonResponse
    {
        $bookings = Booking::with(['user', 'event.organizer'])
            ->when($request->search, function ($query, $search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('event', function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%");
                });
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 15);

        return response()->json($bookings);
    }

    public function bookingDetails($id): JsonResponse
    {
        $booking = Booking::with(['user', 'event.organizer'])->findOrFail($id);
        return response()->json($booking);
    }

    public function updateBookingStatus(Request $request, $id): JsonResponse
    {
        $booking = Booking::findOrFail($id);

        $request->validate([
            'status' => 'required|in:confirmed,cancelled,refunded'
        ]);

        $booking->update(['status' => $request->status]);

        return response()->json([
            'message' => 'Booking status updated successfully',
            'booking' => $booking->load(['user', 'event'])
        ]);
    }

    // ===== ANALYTICS =====

    public function dashboardStats(): JsonResponse
    {
        $stats = [
            'total_users' => User::count(),
            'total_events' => Event::count(),
            'total_bookings' => Booking::count(),
            'total_revenue' => Booking::where('status', 'confirmed')->sum('total_amount'),
            'active_events' => Event::where('status', 'published')->where('end_date', '>', now())->count(),
            'pending_events' => Event::where('status', 'pending')->count(),
            'new_users_this_month' => User::whereMonth('created_at', now()->month)->count(),
            'revenue_this_month' => Booking::where('status', 'confirmed')
                ->whereMonth('created_at', now()->month)->sum('total_amount'),
        ];

        return response()->json($stats);
    }

    public function revenueStats(Request $request): JsonResponse
    {
        $period = $request->period ?? 'month'; // day, week, month, year

        $revenue = Booking::where('status', 'confirmed')
            ->when($period === 'day', function ($query) {
                $query->whereDate('created_at', '>=', now()->subDays(30));
            })
            ->when($period === 'week', function ($query) {
                $query->whereDate('created_at', '>=', now()->subWeeks(12));
            })
            ->when($period === 'month', function ($query) {
                $query->whereDate('created_at', '>=', now()->subMonths(12));
            })
            ->when($period === 'year', function ($query) {
                $query->whereDate('created_at', '>=', now()->subYears(5));
            })
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json($revenue);
    }

    public function userStats(): JsonResponse
    {
        $stats = [
            'users_by_role' => User::selectRaw('roles.name as role, COUNT(*) as count')
                ->join('roles', 'users.role_id', '=', 'roles.id')
                ->groupBy('roles.name')
                ->get(),
            'user_registrations' => User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->where('created_at', '>=', now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
        ];

        return response()->json($stats);
    }

    public function eventStats(): JsonResponse
    {
        $stats = [
            'events_by_category' => Event::selectRaw('categories.name as category, COUNT(*) as count')
                ->join('categories', 'events.category_id', '=', 'categories.id')
                ->groupBy('categories.name')
                ->get(),
            'events_by_status' => Event::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->get(),
            'popular_events' => Event::with('category')
                ->withCount('bookings')
                ->orderBy('bookings_count', 'desc')
                ->take(10)
                ->get(),
        ];

        return response()->json($stats);
    }

    // ===== SYSTEM SETTINGS =====

    public function systemSettings(): JsonResponse
    {
        // You can implement a settings model or use config files
        $settings = [
            'site_name' => config('app.name'),
            'timezone' => config('app.timezone'),
            'currency' => 'USD', // Add to config if needed
            'commission_rate' => 0.05, // 5% commission
        ];

        return response()->json($settings);
    }

    public function updateSettings(Request $request): JsonResponse
    {
        // Implement based on your settings storage method
        return response()->json(['message' => 'Settings updated successfully']);
    }
}
