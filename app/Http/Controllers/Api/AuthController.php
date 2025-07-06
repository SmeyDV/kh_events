<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
  /**
   * Register a new user via API
   */
  public function register(Request $request)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
      'password' => 'required|string|min:8|confirmed',
      'role' => 'nullable|string|in:user,organizer', // Default to 'user' if not provided
    ]);

    // Get role ID
    $roleName = $request->role ?? 'user';
    $role = Role::where('slug', $roleName)->first();

    if (!$role) {
      return response()->json([
        'message' => 'Invalid role specified'
      ], 400);
    }

    $user = User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => Hash::make($request->password),
      'role_id' => $role->id,
    ]);

    // Create token with abilities based on role
    $abilities = $this->getTokenAbilities($roleName);
    $token = $user->createToken('API Token', $abilities)->plainTextToken;

    return response()->json([
      'message' => 'User registered successfully',
      'user' => [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'role' => $roleName,
      ],
      'token' => $token,
      'token_type' => 'Bearer',
    ], 201);
  }

  /**
   * Login user via API
   */
  public function login(Request $request)
  {
    $request->validate([
      'email' => 'required|email',
      'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->with('role')->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
      throw ValidationException::withMessages([
        'email' => ['The provided credentials are incorrect.'],
      ]);
    }

    // Delete old tokens
    $user->tokens()->delete();

    // Create new token with abilities based on role
    $abilities = $this->getTokenAbilities($user->role->slug);
    $token = $user->createToken('API Token', $abilities)->plainTextToken;

    return response()->json([
      'message' => 'Login successful',
      'user' => [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'role' => $user->role->slug,
      ],
      'token' => $token,
      'token_type' => 'Bearer',
    ]);
  }

  /**
   * Logout user via API
   */
  public function logout(Request $request)
  {
    $request->user()->currentAccessToken()->delete();

    return response()->json([
      'message' => 'Logged out successfully'
    ]);
  }

  /**
   * Get user profile via API
   */
  public function profile(Request $request)
  {
    $user = $request->user()->load('role');

    return response()->json([
      'user' => [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'role' => $user->role->slug,
        'email_verified_at' => $user->email_verified_at,
        'created_at' => $user->created_at,
        'updated_at' => $user->updated_at,
      ]
    ]);
  }

  /**
   * Admin login specifically for external dashboard
   */
  public function adminLogin(Request $request)
  {
    $request->validate([
      'email' => 'required|email',
      'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
      throw ValidationException::withMessages([
        'email' => ['The provided credentials are incorrect.'],
      ]);
    }

    // Check if user is admin
    if ($user->role->slug !== 'admin') {
      throw ValidationException::withMessages([
        'email' => ['Access denied. Admin privileges required.'],
      ]);
    }

    $token = $user->createToken('Admin Dashboard Access', $this->getTokenAbilities($user->role->slug));

    return response()->json([
      'message' => 'Admin login successful',
      'access_token' => $token->plainTextToken,
      'token_type' => 'Bearer',
      'user' => [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'role' => $user->role->slug,
      ]
    ]);
  }

  /**
   * Get token abilities based on user role
   */
  private function getTokenAbilities(string $role): array
  {
    switch ($role) {
      case 'admin':
        return ['*']; // All abilities
      case 'organizer':
        return [
          'events:create',
          'events:read',
          'events:update',
          'events:delete',
          'events:manage',
          'analytics:read',
        ];
      case 'user':
      default:
        return [
          'events:read',
          'bookings:create',
          'bookings:read',
          'profile:read',
          'profile:update',
        ];
    }
  }
}
