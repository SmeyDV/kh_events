<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $userRole = Role::where('name', 'user')->first();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role_id' => $userRole ? $userRole->id : 1,
        ]);

        $this->call([
            // RoleSeeder::class, // Removed to avoid duplicate role errors
            EventSeeder::class,
            CategorySeeder::class,
        ]);
    }
}
