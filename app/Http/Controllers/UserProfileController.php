<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserProfileController extends Controller
{
    public function myProfile(): View
    {
        // Now returns the default edit view, passing the authenticated user
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    public function show(User $user): View
    {
        return view('users.profile', [
            'user' => $user,
        ]);
    }
}
