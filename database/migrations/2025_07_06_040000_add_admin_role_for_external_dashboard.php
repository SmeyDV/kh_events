<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
  public function up(): void
  {
    // Add admin role back
    $adminRole = Role::firstOrCreate([
      'slug' => 'admin'
    ], [
      'name' => 'Admin',
      'description' => 'Administrator with full access'
    ]);

    // Create default admin user
    User::firstOrCreate([
      'email' => 'admin@kh-events.local'
    ], [
      'name' => 'System Administrator',
      'password' => Hash::make('admin123'),
      'role_id' => $adminRole->id,
      'email_verified_at' => now(),
    ]);
  }

  public function down(): void
  {
    // Remove admin users and role
    $adminRole = Role::where('slug', 'admin')->first();
    if ($adminRole) {
      User::where('role_id', $adminRole->id)->delete();
      $adminRole->delete();
    }
  }
};
