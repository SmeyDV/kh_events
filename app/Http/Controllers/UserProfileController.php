<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserProfileController extends Controller
{
  /**
   * Display the user's profile page.
   */
  public function show(User $user): View
  {
    // Get user's events if they are an organizer
    $userEvents = collect();
    if ($user->isOrganizer()) {
      $userEvents = $user->events()
        ->orderBy('start_date', 'desc')
        ->take(6)
        ->get();
    }

    // Get upcoming events for display
    $upcomingEvents = Event::where('start_date', '>', now())
      ->orderBy('start_date', 'asc')
      ->take(3)
      ->get();

    return view('users.profile', compact('user', 'userEvents', 'upcomingEvents'));
  }

  /**
   * Display the authenticated user's own profile page.
   */
  public function myProfile(): View
  {
    $user = auth()->user();

    // Get user's events if they are an organizer
    $userEvents = collect();
    if ($user->isOrganizer()) {
      $userEvents = $user->events()
        ->orderBy('start_date', 'desc')
        ->take(6)
        ->get();
    }

    // Get upcoming events for display
    $upcomingEvents = Event::where('start_date', '>', now())
      ->orderBy('start_date', 'asc')
      ->take(3)
      ->get();

    return view('users.profile', compact('user', 'userEvents', 'upcomingEvents'));
  }
}
