<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateTestUser extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'user:create-test {email} {name} {role=organizer}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Create a test user with specified role';

  /**
   * Execute the console command.
   */
  public function handle()
  {
    $email = $this->argument('email');
    $name = $this->argument('name');
    $roleSlug = $this->argument('role');

    // Check if user already exists
    if (User::where('email', $email)->exists()) {
      $this->error("User with email {$email} already exists.");
      return 1;
    }

    // Get the role
    $role = Role::where('slug', $roleSlug)->first();
    if (!$role) {
      $this->error("Role {$roleSlug} not found.");
      $this->info("Available roles:");
      Role::all()->each(function ($role) {
        $this->line("- {$role->name} ({$role->slug})");
      });
      return 1;
    }

    // Create the user
    $user = User::create([
      'name' => $name,
      'email' => $email,
      'password' => Hash::make('password'),
      'role_id' => $role->id,
      'email_verified_at' => now(),
    ]);

    $this->info("Successfully created user:");
    $this->line("Name: {$user->name}");
    $this->line("Email: {$user->email}");
    $this->line("Role: {$role->name}");
    $this->line("Password: password");

    return 0;
  }
}
