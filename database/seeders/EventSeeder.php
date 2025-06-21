<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Category;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the organizer role
        $organizerRole = Role::where('slug', 'organizer')->first();

        // Get all users with the organizer role
        $organizers = User::where('role_id', $organizerRole->id)->get();

        // If there are no organizers, you might want to create one or stop the seeder
        if ($organizers->isEmpty()) {
            $this->command->info('No organizers found. Skipping event seeding.');
            return;
        }

        $categories = Category::all();

        $events = [
            [
                'title' => 'Summer Music Festival',
                'description' => 'A day of live music and fun in the sun.',
                'venue' => 'Central Park',
                'city' => 'Phnom Penh',
                'start_date' => now()->addDays(10),
                'end_date' => now()->addDays(10)->addHours(6),
                'capacity' => 200,
                'ticket_price' => 25.00,
                'category' => 'Music',
            ],
            [
                'title' => 'Gourmet Food Expo',
                'description' => 'Taste the best food and drinks from local chefs.',
                'venue' => 'City Convention Center',
                'city' => 'Phnom Penh',
                'start_date' => now()->addDays(15),
                'end_date' => now()->addDays(15)->addHours(4),
                'capacity' => 150,
                'ticket_price' => 15.00,
                'category' => 'Food & Drink',
            ],
            [
                'title' => 'Charity Run for Hope',
                'description' => 'Join us for a 5K run to support local charities.',
                'venue' => 'Riverside Park',
                'city' => 'Phnom Penh',
                'start_date' => now()->addDays(20),
                'end_date' => now()->addDays(20)->addHours(2),
                'capacity' => 300,
                'ticket_price' => 0.00,
                'category' => 'Charity & Causes',
            ],
            [
                'title' => 'Artisan Craft Fair',
                'description' => 'Discover unique handmade crafts and gifts.',
                'venue' => 'Old Town Square',
                'city' => 'Phnom Penh',
                'start_date' => now()->addDays(25),
                'end_date' => now()->addDays(25)->addHours(5),
                'capacity' => 100,
                'ticket_price' => 5.00,
                'category' => 'Hobbies',
            ],
            [
                'title' => 'Tech Innovators Meetup',
                'description' => 'Network with tech enthusiasts and startups.',
                'venue' => 'Innovation Hub',
                'city' => 'Phnom Penh',
                'start_date' => now()->addDays(30),
                'end_date' => now()->addDays(30)->addHours(3),
                'capacity' => 80,
                'ticket_price' => 10.00,
                'category' => 'Tech',
            ],
            [
                'title' => 'Wellness Yoga Retreat',
                'description' => 'A relaxing day of yoga and mindfulness.',
                'venue' => 'Green Valley Retreat',
                'city' => 'Phnom Penh',
                'start_date' => now()->addDays(35),
                'end_date' => now()->addDays(35)->addHours(8),
                'capacity' => 50,
                'ticket_price' => 20.00,
                'category' => 'Hobbies',
            ],
        ];

        foreach ($events as $eventData) {
            $category = $categories->firstWhere('name', $eventData['category']);
            $organizer = $organizers->random(); // Assign a random organizer

            if ($organizer && $category) {
                Event::create([
                    'organizer_id' => $organizer->id,
                    'category_id' => $category->id,
                    'title' => $eventData['title'],
                    'description' => $eventData['description'],
                    'venue' => $eventData['venue'],
                    'city' => $eventData['city'],
                    'start_date' => $eventData['start_date'],
                    'end_date' => $eventData['end_date'],
                    'capacity' => $eventData['capacity'],
                    'ticket_price' => $eventData['ticket_price'],
                    'status' => 'published',
                    'image_path' => null,
                ]);
            }
        }
    }
}
