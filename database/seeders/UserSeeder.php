<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing roles
        $adminRole = Role::where('slug', 'admin')->first();
        $organizerRole = Role::where('slug', 'organizer')->first();
        $userRole = Role::where('slug', 'user')->first();

        // Create admin user
        User::firstOrCreate([
            'email' => 'admin@khevents.com'
        ], [
            'name' => 'Admin User',
            'email' => 'admin@khevents.com',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
            'email_verified_at' => now(),   
        ]);

        // Create organizer user
        User::firstOrCreate([
            'email' => 'organizer@khevents.com'
        ], [
            'name' => 'Event Organizer',
            'email' => 'organizer@khevents.com',
            'password' => Hash::make('password'),
            'role_id' => $organizerRole->id,
            'email_verified_at' => now(),
        ]);

        // Create regular user
        User::firstOrCreate([
            'email' => 'user@khevents.com'
        ], [
            'name' => 'Regular User',
            'email' => 'user@khevents.com',
            'password' => Hash::make('password'),
            'role_id' => $userRole->id,
            'email_verified_at' => now(),
        ]);
    }
}
