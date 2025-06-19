<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminEventController;
use App\Models\Event;
use Illuminate\Support\Facades\Route;

// --- PUBLIC ROUTES ---
Route::get('/', function () {
    $upcomingEvents = Event::where('status', 'published')
        ->where('start_date', '>', now())
        ->orderBy('start_date', 'asc')
        ->take(3)
        ->get();
    return view('welcome', ['upcomingEvents' => $upcomingEvents]);
})->name('home');

// Public event listing and detail pages
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->where('event', '[0-9]+')->name('events.show');


// --- AUTHENTICATED ROUTES ---
Route::middleware(['auth', 'verified'])->group(function () {

    // Default dashboard for standard users
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // User profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Booking routes
    Route::get('/events/{event}/book', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/events/{event}/book', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::delete('/bookings/{booking}', [BookingController::class, 'cancel'])->name('bookings.cancel');

    // --- ORGANIZER ROUTES ---
    Route::prefix('organizer')->name('organizer.')->middleware(['role:organizer'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'organizer'])->name('dashboard');
        Route::get('/myevents', [EventController::class, 'myEvents'])->name('my-events');

        // Simplified Event Management Routes
        Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
        Route::post('/events', [EventController::class, 'store'])->name('events.store');
        Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
        Route::patch('/events/{event}', [EventController::class, 'update'])->name('events.update');
        Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
    });

    // --- ADMIN ROUTES ---
    Route::prefix('admin')->name('admin.')->middleware(['role:admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
        Route::get('/users', [DashboardController::class, 'users'])->name('users');
        Route::get('/events', [AdminEventController::class, 'index'])->name('events');
        Route::post('/events/{id}/approve', [AdminEventController::class, 'approve'])->name('events.approve');
        Route::post('/events/{id}/reject', [AdminEventController::class, 'reject'])->name('events.reject');
    });

    // Routes accessible by both admin and organizer
    Route::middleware(['role:admin,organizer'])->group(function () {
        Route::get('/reports', [DashboardController::class, 'reports'])->name('reports');
    });
});


require __DIR__ . '/auth.php';
