<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});

// Public API routes (no authentication required)
Route::prefix('v1')->group(function () {

  // API Authentication routes
  Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/admin-login', [AuthController::class, 'adminLogin']); // Admin login for external dashboard
  });

  // Public event routes
  Route::prefix('events')->group(function () {
    Route::get('/', [EventController::class, 'index']); // Get all events
    Route::get('/{id}', [EventController::class, 'show']); // Get single event
    Route::get('/category/{categoryId}', [EventController::class, 'byCategory']); // Events by category
    Route::get('/city/{city}', [EventController::class, 'byCity']); // Events by city
    Route::get('/search', [EventController::class, 'search']); // Search events
  });
});

// Protected API routes (Sanctum token authentication)
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {

  // Auth user routes
  Route::prefix('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
  });

  // Admin API routes - for external admin dashboard
  // Note: These routes are protected by Sanctum auth but don't require specific role
  // since admin functionality is handled by external dashboard
  Route::prefix('admin')->group(function () {

    // Users management
    Route::prefix('users')->group(function () {
      Route::get('/', [AdminController::class, 'users']); // Get all users
      Route::get('/{id}', [AdminController::class, 'userDetails']); // Get user details
      Route::put('/{id}', [AdminController::class, 'updateUser']); // Update user
      Route::delete('/{id}', [AdminController::class, 'deleteUser']); // Delete user
      Route::put('/{id}/status', [AdminController::class, 'toggleUserStatus']); // Ban/Unban user
    });

    // Events management
    Route::prefix('events')->group(function () {
      Route::get('/', [AdminController::class, 'allEvents']); // Get all events (admin view)
      Route::get('/{id}', [AdminController::class, 'eventDetails']); // Get event details
      Route::put('/{id}', [AdminController::class, 'updateEvent']); // Update event
      Route::delete('/{id}', [AdminController::class, 'deleteEvent']); // Delete event
      Route::post('/{id}/approve', [AdminController::class, 'approveEvent']); // Approve event
      Route::post('/{id}/reject', [AdminController::class, 'rejectEvent']); // Reject event
      Route::post('/{id}/feature', [AdminController::class, 'featureEvent']); // Feature event
    });

    // Categories management
    Route::prefix('categories')->group(function () {
      Route::get('/', [AdminController::class, 'categories']); // Get all categories
      Route::post('/', [AdminController::class, 'createCategory']); // Create category
      Route::put('/{id}', [AdminController::class, 'updateCategory']); // Update category
      Route::delete('/{id}', [AdminController::class, 'deleteCategory']); // Delete category
    });

    // Bookings management
    Route::prefix('bookings')->group(function () {
      Route::get('/', [AdminController::class, 'allBookings']); // Get all bookings
      Route::get('/{id}', [AdminController::class, 'bookingDetails']); // Get booking details
      Route::put('/{id}/status', [AdminController::class, 'updateBookingStatus']); // Update booking status
    });

    // Analytics and reports
    Route::prefix('analytics')->group(function () {
      Route::get('/dashboard', [AdminController::class, 'dashboardStats']); // Dashboard stats
      Route::get('/revenue', [AdminController::class, 'revenueStats']); // Revenue analytics
      Route::get('/users', [AdminController::class, 'userStats']); // User analytics
      Route::get('/events', [AdminController::class, 'eventStats']); // Event analytics
    });

    // System settings
    Route::prefix('settings')->group(function () {
      Route::get('/', [AdminController::class, 'systemSettings']); // Get settings
      Route::put('/', [AdminController::class, 'updateSettings']); // Update settings
    });
  });

  // Organizer routes (role-based access)
  Route::prefix('organizer')->middleware('role:organizer')->group(function () {

    // Event management
    Route::prefix('events')->group(function () {
      Route::get('/', [EventController::class, 'organizerEvents']); // Organizer's events
      Route::post('/', [EventController::class, 'store']); // Create event
      Route::get('/{id}', [EventController::class, 'organizerEventDetails']); // Event details for organizer
      Route::put('/{id}', [EventController::class, 'update']); // Update event
      Route::delete('/{id}', [EventController::class, 'destroy']); // Delete event
      Route::post('/{id}/upload-image', [EventController::class, 'uploadImage']); // Upload event image
    });

    // Analytics for organizers
    Route::prefix('analytics')->group(function () {
      Route::get('/dashboard', [EventController::class, 'organizerDashboard']);
      Route::get('/event/{id}/stats', [EventController::class, 'eventStats']);
    });
  });
});
