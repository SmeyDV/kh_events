<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function admin(): View
    {
        $users = User::with('role')->get();
        return view('admin.dashboard', compact('users'));
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
