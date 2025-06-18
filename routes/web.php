<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\BookingController;
use App\Models\Event;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// --- PUBLIC ROUTES ---
// Accessible by everyone, including guests.

Route::get('/', function () {
    $upcomingEvents = Event::where('start_date', '>', now())
        ->orderBy('start_date', 'asc')
        ->take(3)
        ->get();

    return view('welcome', ['upcomingEvents' => $upcomingEvents]);
});

// Public list of all events
Route::get('/events', [EventController::class, 'index'])->name('events.index');

// --- AUTHENTICATED ROUTES ---
// Routes that require a user to be logged in and verified.

Route::middleware(['auth', 'verified'])->group(function () {

    // User profile routes
    Route::get('/profile', [UserProfileController::class, 'myProfile'])->name('profile');
    Route::get('/dashboard', function () {
        return redirect()->route('profile');
    })->name('dashboard');

    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Booking routes
    Route::get('/events/{event}/book', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/events/{event}/book', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::delete('/bookings/{booking}', [BookingController::class, 'cancel'])->name('bookings.cancel');

    // Organizer-only routes
    Route::middleware(['role:organizer'])->group(function () {
        Route::get('/organizer', [DashboardController::class, 'organizer'])->name('organizer.dashboard');
        Route::get('/my-events', [EventController::class, 'myEvents'])->name('events.my-events');

        // Resource controller for event CRUD actions (create, store, edit, update, destroy)
        Route::resource('events', EventController::class)->except(['index', 'show']);
    });

    // Admin-only routes
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin', [DashboardController::class, 'admin'])->name('admin.dashboard');
        Route::get('/users', [DashboardController::class, 'users'])->name('admin.users');
    });

    // Routes accessible by both admin and organizer
    Route::middleware(['role:admin,organizer'])->group(function () {
        Route::get('/reports', [DashboardController::class, 'reports'])->name('reports');
    });
});

// Public user profile pages
Route::get('/users/{user}', [UserProfileController::class, 'show'])->name('users.show');

// Single event view (with constraint to avoid conflict with /events/create)
Route::get('/events/{event}', [EventController::class, 'show'])->where('event', '[0-9]+')->name('events.show');

require __DIR__ . '/auth.php';
