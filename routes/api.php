<?php

use App\Http\Controllers\Api\EventController;
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

  // Public event routes
  Route::prefix('events')->group(function () {
    Route::get('/', [EventController::class, 'index']); // Get all events
    Route::get('/{id}', [EventController::class, 'show']); // Get single event
    Route::get('/category/{categoryId}', [EventController::class, 'byCategory']); // Events by category
    Route::get('/city/{city}', [EventController::class, 'byCity']); // Events by city
    Route::get('/search', [EventController::class, 'search']); // Search events
  });
});

// Protected API routes (authentication required)
Route::prefix('v1')->middleware(['web', 'auth'])->group(function () {

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
