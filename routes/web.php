<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
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

// Public list of all events and single event view
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');


// --- AUTHENTICATED ROUTES ---
// Routes that require a user to be logged in and verified.

Route::middleware(['auth', 'verified'])->group(function () {

    // General authenticated user routes
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

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

require __DIR__ . '/auth.php';
