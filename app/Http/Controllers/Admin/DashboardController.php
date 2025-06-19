<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
  // Admin dashboard logic here

  public function admin(): View
  {
    $userCount = User::count();
    $eventCount = Event::count();
    $organizerCount = User::whereHas('role', function ($q) {
      $q->where('name', 'organizer');
    })->count();
    $pendingEventCount = Event::where('status', 'draft')->count();

    return view('admin.dashboard', compact('userCount', 'eventCount', 'organizerCount', 'pendingEventCount'));
  }

  public function users(Request $request): View
  {
    $query = User::with('role');

    // Filter by search
    if ($search = $request->input('search')) {
      $query->where(function ($q) use ($search) {
        $q->where('name', 'like', "%{$search}%")
          ->orWhere('email', 'like', "%{$search}%");
      });
    }

    // Filter by role
    if ($role = $request->input('role')) {
      $query->whereHas('role', function ($q) use ($role) {
        $q->where('slug', $role);
      });
    }

    // Sort by role
    if ($request->input('sort') === 'role') {
      $direction = $request->input('direction', 'asc');
      $query->join('roles', 'users.role_id', '=', 'roles.id')
        ->orderBy('roles.name', $direction)
        ->select('users.*');
    }

    $users = $query->paginate(10)->withQueryString();

    // Get all roles for the filter dropdown
    $roles = \App\Models\Role::all();

    return view('admin.user.index', compact('users', 'roles'));
  }
}
