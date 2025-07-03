<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OrganizerSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Get the organizer role
    $organizerRole = Role::where('slug', 'organizer')->first();

    if (!$organizerRole) {
      $this->command->error('Organizer role not found. Please run RoleSeeder first.');
      return;
    }

    $organizers = [
      [
        'name' => 'EventMaster Pro',
        'email' => 'contact@eventmasterpro.com',
        'password' => Hash::make('password'),
      ],
      [
        'name' => 'Stellar Events Co.',
        'email' => 'info@stellarevents.com',
        'password' => Hash::make('password'),
      ],
      [
        'name' => 'Pinnacle Productions',
        'email' => 'hello@pinnacleproductions.com',
        'password' => Hash::make('password'),
      ],
      [
        'name' => 'Urban Experiences Ltd',
        'email' => 'team@urbanexperiences.com',
        'password' => Hash::make('password'),
      ],
      [
        'name' => 'Elite Event Solutions',
        'email' => 'support@eliteeventsolutions.com',
        'password' => Hash::make('password'),
      ],
      [
        'name' => 'Creative Gatherings Inc',
        'email' => 'bookings@creativegatherings.com',
        'password' => Hash::make('password'),
      ],
    ];

    foreach ($organizers as $organizerData) {
      User::firstOrCreate([
        'email' => $organizerData['email']
      ], [
        'name' => $organizerData['name'],
        'email' => $organizerData['email'],
        'password' => $organizerData['password'],
        'role_id' => $organizerRole->id,
        'email_verified_at' => now(),
      ]);
    }

    $this->command->info('Created ' . count($organizers) . ' organizer users.');
  }
}
