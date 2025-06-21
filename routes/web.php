<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Organizer\DashboardController as OrganizerDashboardController;
use App\Http\Controllers\Organizer\EventController as OrganizerEventController;
use App\Http\Controllers\Organizer\ProfileController as OrganizerProfileController;
use App\Http\Controllers\User\BookingController;
use App\Http\Controllers\User\ProfileController as UserProfileController;
use App\Models\Event;
use App\Models\Role;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AdminRegisterController;
use Illuminate\Support\Facades\Route;

// --- PUBLIC ROUTES ---
Route::middleware(['redirect.admin'])->group(function () {
    Route::get('/', function () {
        $upcomingEvents = Event::where('status', 'published')
            ->where('start_date', '>', now())
            ->orderBy('start_date', 'asc')
            ->take(3)
            ->get();
        $categories = \App\Models\Category::all();
        $tabs = [
            ['label' => 'All', 'active' => true],
            ['label' => 'For you', 'active' => false],
            ['label' => 'Online', 'active' => false],
            ['label' => 'Today', 'active' => false],
            ['label' => 'This weekend', 'active' => false],
            ['label' => 'Free', 'active' => false],
        ];
        $organizerCta = [
            'title' => 'Make your own event',
            'description' => 'Got a show, a party, or a workshop to share? Join our community of organizers and bring your event to life on our platform.',
            'button' => [
                'label' => 'Create Your Event',
                'url' => route('register.organizer'),
            ],
        ];
        $cities = config('app.kh_cities');
        return view('welcome', [
            'upcomingEvents' => $upcomingEvents,
            'categories' => $categories,
            'tabs' => $tabs,
            'organizerCta' => $organizerCta,
            'cities' => $cities,
        ]);
    })->name('home');
});

// Public event listing and detail pages
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->where('event', '[0-9]+')->name('events.show');


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

        // Simplified Event Management Routes
        Route::get('/events/create', [OrganizerEventController::class, 'create'])->name('events.create');
        Route::post('/events', [OrganizerEventController::class, 'store'])->name('events.store');
        Route::get('/events/{event}', [OrganizerEventController::class, 'show'])->name('events.show');
        Route::get('/events/{event}/edit', [OrganizerEventController::class, 'edit'])->name('events.edit');
        Route::patch('/events/{event}', [OrganizerEventController::class, 'update'])->name('events.update');
        Route::delete('/events/{event}', [OrganizerEventController::class, 'destroy'])->name('events.destroy');
    });

    // --- ADMIN ROUTES ---
    Route::prefix('admin')->name('admin.')->middleware(['role:admin'])->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'admin'])->name('dashboard');
        Route::get('/users', [AdminDashboardController::class, 'users'])->name('users');

        // Admin Profile
        Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [AdminProfileController::class, 'destroy'])->name('profile.destroy');

        Route::get('/events', [AdminEventController::class, 'index'])->name('events');
        Route::get('/events/{event}', [AdminEventController::class, 'show'])->name('events.show');
        Route::post('/events/{id}/approve', [AdminEventController::class, 'approve'])->name('events.approve');
        Route::post('/events/{id}/reject', [AdminEventController::class, 'reject'])->name('events.reject');
    });

    // Routes accessible by both admin and organizer
    Route::middleware(['role:admin,organizer'])->group(function () {
        Route::get('/reports', [AdminDashboardController::class, 'reports'])->name('reports');
    });
});

// Secret admin registration form
Route::get('/register-admin-SECRET123', [AdminRegisterController::class, 'showForm'])->name('register.admin.secret');
Route::post('/register-admin-SECRET123', [AdminRegisterController::class, 'register'])->name('register.admin.secret.post');

require __DIR__ . '/auth.php';
