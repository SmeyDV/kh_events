<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\Organizer\DashboardController as OrganizerDashboardController;
use App\Http\Controllers\Organizer\EventController as OrganizerEventController;
use App\Http\Controllers\Organizer\ProfileController as OrganizerProfileController;
use App\Http\Controllers\User\BookingController;
use App\Http\Controllers\User\ProfileController as UserProfileController;
use App\Models\Event;
use App\Models\Category;
use App\Models\City;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

// --- PUBLIC ROUTES ---
Route::get('/', function () {
    // Temporarily bypass caching for debugging
    $upcomingEvents = Event::published()
        ->upcoming()
        ->with(['organizer', 'category', 'images'])
        ->orderBy('start_date', 'asc')
        ->take(6)
        ->get();

    // Get additional events for "Other events you may like" section
    $otherEvents = Event::published()
        ->upcoming()
        ->with(['organizer', 'category', 'images'])
        ->whereNotIn('id', $upcomingEvents->pluck('id'))
        ->orderBy('start_date', 'asc')
        ->take(8)
        ->get();

    $categories = Category::all();

    // Create dynamic tabs with categories from database
    $tabs = [
        ['label' => 'All', 'active' => true, 'type' => 'filter'],
        ['label' => 'For you', 'active' => false, 'type' => 'filter'],
        ['label' => 'Online', 'active' => false, 'type' => 'filter'],
        ['label' => 'Today', 'active' => false, 'type' => 'filter'],
        ['label' => 'This weekend', 'active' => false, 'type' => 'filter'],
        ['label' => 'Free', 'active' => false, 'type' => 'filter'],
    ];

    // Add categories to tabs
    foreach ($categories as $category) {
        $tabs[] = [
            'label' => $category->name,
            'active' => false,
            'type' => 'category',
            'slug' => $category->slug,
            'id' => $category->id
        ];
    }

    $organizerCta = [
        'title' => 'Make your own event',
        'description' => 'Got a show, a party, or a workshop to share? Join our community of organizers and bring your event to life on our platform.',
        'button' => [
            'label' => 'Create Your Event',
            'url' => route('register.organizer'),
        ],
    ];

    $cities = City::orderBy('name')->get();

    return view('welcome', compact('upcomingEvents', 'otherEvents', 'categories', 'tabs', 'organizerCta', 'cities'));
})->name('home');

// Public event listing and detail pages
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

// Category pages
Route::get('/categories', [App\Http\Controllers\CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category:slug}', [App\Http\Controllers\CategoryController::class, 'show'])->name('categories.show');

// --- AUTHENTICATED ROUTES ---
Route::middleware(['auth', 'verified'])->group(function () {

    // Default dashboard for standard users
    Route::middleware('role:user')->group(function () {
        Route::get('/profile', [UserProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [UserProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [UserProfileController::class, 'destroy'])->name('profile.destroy');

        // Booking routes for users
        Route::get('/events/{event}/book', [BookingController::class, 'create'])->name('bookings.create');
        Route::post('/events/{event}/book', [BookingController::class, 'store'])->name('bookings.store');
        Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
        Route::delete('/bookings/{booking}', [BookingController::class, 'cancel'])->name('bookings.cancel');
    });

    // --- ORGANIZER ROUTES ---
    Route::prefix('organizer')->name('organizer.')->middleware(['role:organizer'])->group(function () {
        Route::get('/dashboard', [OrganizerDashboardController::class, 'organizer'])->name('dashboard');
        Route::get('/myevents', [OrganizerEventController::class, 'myEvents'])->name('my-events');

        // Organizer Profile
        Route::get('/profile', [OrganizerProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [OrganizerProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [OrganizerProfileController::class, 'destroy'])->name('profile.destroy');

        // Event Management Routes
        Route::get('/events/create', [OrganizerEventController::class, 'create'])->name('events.create');
        Route::post('/events', [OrganizerEventController::class, 'store'])->name('events.store');
        Route::get('/events/{event}', [OrganizerEventController::class, 'show'])->name('events.show');
        Route::get('/events/{event}/edit', [OrganizerEventController::class, 'edit'])->name('events.edit');
        Route::patch('/events/{event}', [OrganizerEventController::class, 'update'])->name('events.update');
        Route::delete('/events/{event}', [OrganizerEventController::class, 'destroy'])->name('events.destroy');
    });
});

require __DIR__ . '/auth.php';
