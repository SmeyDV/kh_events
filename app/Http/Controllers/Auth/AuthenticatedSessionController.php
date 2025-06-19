<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        if (!$user || !$user->role) {
            Log::error('User or role not found after login', [
                'user_exists' => !!$user,
                'user_id' => $user ? $user->id : null,
                'role_exists' => $user ? !!$user->role : null,
                'role_id' => $user ? $user->role_id : null
            ]);
            return redirect()->route('dashboard');
        }

        Log::info('User logged in', [
            'user_id' => $user->id,
            'email' => $user->email,
            'role_id' => $user->role_id,
            'role' => [
                'id' => $user->role->id,
                'name' => $user->role->name,
                'slug' => $user->role->slug
            ]
        ]);

        $roleSlug = $user->role->slug;
        $route = 'dashboard';

        switch ($roleSlug) {
            case 'admin':
                $route = 'admin.dashboard';
                break;
            case 'organizer':
                $route = 'organizer.dashboard';
                break;
        }

        Log::info('Redirecting user', [
            'role_slug' => $roleSlug,
            'route' => $route
        ]);

        return redirect()->route($route);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
