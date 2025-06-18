<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function admin(): View
    {
        $userCount = User::count();
        $eventCount = \App\Models\Event::count();
        $organizerCount = User::whereHas('role', function ($q) {
            $q->where('slug', 'organizer');
        })->count();
        $pendingEventCount = \App\Models\Event::where('status', 'draft')->count();

        return view('admin.dashboard', compact('userCount', 'eventCount', 'organizerCount', 'pendingEventCount'));
    }

    public function users(): View
    {
        $users = User::with('role')->paginate(10);
        return view('admin.users', compact('users'));
    }

    public function organizer(): View
    {
        return view('organizer.dashboard');
    }

    public function reports(): View
    {
        return view('reports');
    }
}
