<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
  // Organizer dashboard logic here

  public function organizer(): \Illuminate\View\View
  {
    $organizer = Auth::user();
    $totalEvents = $organizer->events()->count();
    $publishedEvents = $organizer->events()->where('status', 'published')->count();
    $pendingEvents = $organizer->events()->where('status', 'draft')->count();

    return view('organizer.dashboard', compact('totalEvents', 'publishedEvents', 'pendingEvents'));
  }
}
