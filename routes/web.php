<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Public event routes
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::middleware(['auth', 'role:organizer'])->group(function () {
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
});
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

// Routes for authenticated users
Route::middleware(['auth', 'verified'])->group(function () {
    // General user dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin only routes
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin', [DashboardController::class, 'admin'])->name('admin.dashboard');
        Route::get('/users', [DashboardController::class, 'users'])->name('admin.users');
    });

    // Organizer only routes
    Route::middleware(['role:organizer'])->group(function () {
        Route::get('/organizer', [DashboardController::class, 'organizer'])->name('admin.organizer');
        Route::get('/my-events', [EventController::class, 'myEvents'])->name('events.my-events');
    });

    // Routes accessible by both admin and organizer
    Route::middleware(['role:admin,organizer'])->group(function () {
        Route::get('/reports', [DashboardController::class, 'reports'])->name('reports');
    });
});

require __DIR__ . '/auth.php';
