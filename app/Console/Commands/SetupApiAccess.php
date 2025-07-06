<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class SetupApiAccess extends Command
{
  protected $signature = 'setup:api-access';
  protected $description = 'Set up API access for external dashboard';

  public function handle()
  {
    $this->info('Setting up API access for external dashboard...');

    // Check if API service user already exists
    $existingUser = User::where('email', 'api@kh-events.local')->first();

    if ($existingUser) {
      $this->warn('API service user already exists.');
      $user = $existingUser;
    } else {
      // Create API service user
      $organizerRole = Role::where('slug', 'organizer')->first();

      if (!$organizerRole) {
        $this->error('Organizer role not found. Please run migrations first.');
        return 1;
      }

      $user = User::create([
        'name' => 'API Service User',
        'email' => 'api@kh-events.local',
        'password' => Hash::make('api-service-key-2024'),
        'role_id' => $organizerRole->id,
        'email_verified_at' => now(),
      ]);

      $this->info("API service user created with ID: {$user->id}");
    }

    // Create or regenerate API token
    $user->tokens()->delete(); // Remove old tokens
    $token = $user->createToken('External Dashboard API Access', ['*']);

    $this->info('API Token created successfully!');
    $this->line('');
    $this->line('=== API SETUP COMPLETE ===');
    $this->line('Base URL: ' . config('app.url') . '/api/v1');
    $this->line('Token: ' . $token->plainTextToken);
    $this->line('');
    $this->line('Use this token in your external dashboard Authorization header:');
    $this->line('Authorization: Bearer ' . $token->plainTextToken);
    $this->line('');
    $this->warn('IMPORTANT: Save this token securely. It cannot be retrieved again.');

    return 0;
  }
}
