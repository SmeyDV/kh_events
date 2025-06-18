<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Role;
use Illuminate\Console\Command;

class AssignUserRole extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'user:assign-role {email} {role}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Assign a role to a user';

  /**
   * Execute the console command.
   */
  public function handle()
  {
    $email = $this->argument('email');
    $roleSlug = $this->argument('role');

    $user = User::where('email', $email)->first();
    if (!$user) {
      $this->error("User with email {$email} not found.");
      return 1;
    }

    $role = Role::where('slug', $roleSlug)->first();
    if (!$role) {
      $this->error("Role {$roleSlug} not found.");
      $this->info("Available roles:");
      Role::all()->each(function ($role) {
        $this->line("- {$role->name} ({$role->slug})");
      });
      return 1;
    }

    $user->update(['role_id' => $role->id]);
    $this->info("Successfully assigned role '{$role->name}' to user {$user->name} ({$user->email})");

    return 0;
  }
}
