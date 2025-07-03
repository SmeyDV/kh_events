<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Category;
use App\Models\User;
use App\Models\Role;
use App\Models\City;
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
        $cities = City::all();

        if ($cities->isEmpty()) {
            $this->command->error('No cities found. Please run CitySeeder first.');
            return;
        }

        // Large pool of event templates
        $eventTemplates = [
            [
                'title' => 'Summer Music Festival',
                'description' => 'A day of live music and fun in the sun.',
                'venue' => 'Central Park',
                'capacity' => 200,
                'ticket_price' => 25.00,
                'category' => 'Music',
                'duration' => 6,
            ],
            [
                'title' => 'Gourmet Food Expo',
                'description' => 'Taste the best food and drinks from local chefs.',
                'venue' => 'City Convention Center',
                'capacity' => 150,
                'ticket_price' => 15.00,
                'category' => 'Food & Drink',
                'duration' => 4,
            ],
            [
                'title' => 'Charity Run for Hope',
                'description' => 'Join us for a 5K run to support local charities.',
                'venue' => 'Riverside Park',
                'capacity' => 300,
                'ticket_price' => 0.00,
                'category' => 'Charity & Causes',
                'duration' => 2,
            ],
            [
                'title' => 'Artisan Craft Fair',
                'description' => 'Discover unique handmade crafts and gifts.',
                'venue' => 'Old Town Square',
                'capacity' => 100,
                'ticket_price' => 5.00,
                'category' => 'Hobbies',
                'duration' => 5,
            ],
            [
                'title' => 'Tech Innovators Meetup',
                'description' => 'Network with tech enthusiasts and startups.',
                'venue' => 'Innovation Hub',
                'capacity' => 80,
                'ticket_price' => 10.00,
                'category' => 'Tech',
                'duration' => 3,
            ],
            [
                'title' => 'Wellness Yoga Retreat',
                'description' => 'A relaxing day of yoga and mindfulness.',
                'venue' => 'Green Valley Retreat',
                'capacity' => 50,
                'ticket_price' => 20.00,
                'category' => 'Hobbies',
                'duration' => 8,
            ],
            [
                'title' => 'Jazz Under the Stars',
                'description' => 'An intimate evening of smooth jazz music.',
                'venue' => 'Rooftop Garden',
                'capacity' => 75,
                'ticket_price' => 30.00,
                'category' => 'Music',
                'duration' => 4,
            ],
            [
                'title' => 'Street Food Festival',
                'description' => 'Experience authentic street food from around the world.',
                'venue' => 'Market Square',
                'capacity' => 500,
                'ticket_price' => 8.00,
                'category' => 'Food & Drink',
                'duration' => 6,
            ],
            [
                'title' => 'Digital Marketing Summit',
                'description' => 'Learn the latest trends in digital marketing.',
                'venue' => 'Business Center',
                'capacity' => 120,
                'ticket_price' => 50.00,
                'category' => 'Tech',
                'duration' => 8,
            ],
            [
                'title' => 'Photography Workshop',
                'description' => 'Master the art of portrait photography.',
                'venue' => 'Studio 101',
                'capacity' => 25,
                'ticket_price' => 40.00,
                'category' => 'Hobbies',
                'duration' => 4,
            ],
            [
                'title' => 'Wine Tasting Evening',
                'description' => 'Sample fine wines from local vineyards.',
                'venue' => 'Wine Bar & Lounge',
                'capacity' => 60,
                'ticket_price' => 35.00,
                'category' => 'Food & Drink',
                'duration' => 3,
            ],
            [
                'title' => 'Startup Pitch Competition',
                'description' => 'Watch emerging startups pitch their ideas.',
                'venue' => 'Tech Incubator',
                'capacity' => 100,
                'ticket_price' => 15.00,
                'category' => 'Tech',
                'duration' => 4,
            ],
            [
                'title' => 'Community Art Exhibition',
                'description' => 'Showcase of local artists and their work.',
                'venue' => 'Art Gallery',
                'capacity' => 80,
                'ticket_price' => 0.00,
                'category' => 'Charity & Causes',
                'duration' => 6,
            ],
            [
                'title' => 'Rock Concert Series',
                'description' => 'High-energy rock performances by local bands.',
                'venue' => 'Music Venue',
                'capacity' => 300,
                'ticket_price' => 40.00,
                'category' => 'Music',
                'duration' => 5,
            ],
            [
                'title' => 'Cooking Masterclass',
                'description' => 'Learn professional cooking techniques.',
                'venue' => 'Culinary School',
                'capacity' => 30,
                'ticket_price' => 60.00,
                'category' => 'Food & Drink',
                'duration' => 3,
            ],
            [
                'title' => 'Gaming Tournament',
                'description' => 'Compete in the ultimate gaming championship.',
                'venue' => 'Gaming Arena',
                'capacity' => 150,
                'ticket_price' => 20.00,
                'category' => 'Hobbies',
                'duration' => 8,
            ],
            [
                'title' => 'Environmental Awareness Day',
                'description' => 'Learn about sustainable living practices.',
                'venue' => 'Community Center',
                'capacity' => 200,
                'ticket_price' => 0.00,
                'category' => 'Charity & Causes',
                'duration' => 4,
            ],
            [
                'title' => 'AI & Machine Learning Conference',
                'description' => 'Explore the future of artificial intelligence.',
                'venue' => 'Tech Conference Hall',
                'capacity' => 250,
                'ticket_price' => 75.00,
                'category' => 'Tech',
                'duration' => 6,
            ],
            [
                'title' => 'Classical Music Recital',
                'description' => 'An elegant evening of classical performances.',
                'venue' => 'Concert Hall',
                'capacity' => 120,
                'ticket_price' => 45.00,
                'category' => 'Music',
                'duration' => 3,
            ],
            [
                'title' => 'Meditation & Mindfulness Workshop',
                'description' => 'Find inner peace through guided meditation.',
                'venue' => 'Wellness Center',
                'capacity' => 40,
                'ticket_price' => 25.00,
                'category' => 'Hobbies',
                'duration' => 2,
            ],
            [
                'title' => 'International Food Festival',
                'description' => 'Taste cuisines from around the globe.',
                'venue' => 'Festival Grounds',
                'capacity' => 1000,
                'ticket_price' => 12.00,
                'category' => 'Food & Drink',
                'duration' => 8,
            ],
            [
                'title' => 'Children\'s Charity Gala',
                'description' => 'Supporting underprivileged children in our community.',
                'venue' => 'Grand Ballroom',
                'capacity' => 180,
                'ticket_price' => 100.00,
                'category' => 'Charity & Causes',
                'duration' => 4,
            ],
            [
                'title' => 'Blockchain & Crypto Summit',
                'description' => 'Understanding the future of digital currency.',
                'venue' => 'Financial District Center',
                'capacity' => 150,
                'ticket_price' => 85.00,
                'category' => 'Tech',
                'duration' => 6,
            ],
            [
                'title' => 'Indie Music Showcase',
                'description' => 'Discover emerging independent artists.',
                'venue' => 'Underground Club',
                'capacity' => 90,
                'ticket_price' => 18.00,
                'category' => 'Music',
                'duration' => 4,
            ],
            [
                'title' => 'Fitness Bootcamp Challenge',
                'description' => 'Push your limits in this intense workout session.',
                'venue' => 'Outdoor Training Ground',
                'capacity' => 50,
                'ticket_price' => 30.00,
                'category' => 'Hobbies',
                'duration' => 2,
            ],
            [
                'title' => 'Craft Beer Festival',
                'description' => 'Sample the finest craft beers from local breweries.',
                'venue' => 'Brewery District',
                'capacity' => 400,
                'ticket_price' => 22.00,
                'category' => 'Food & Drink',
                'duration' => 5,
            ],
            [
                'title' => 'Book Reading & Discussion',
                'description' => 'Join us for literary discussions and author readings.',
                'venue' => 'Public Library',
                'capacity' => 60,
                'ticket_price' => 0.00,
                'category' => 'Charity & Causes',
                'duration' => 2,
            ],
            [
                'title' => 'Mobile App Development Workshop',
                'description' => 'Build your first mobile application.',
                'venue' => 'Code Academy',
                'capacity' => 35,
                'ticket_price' => 65.00,
                'category' => 'Tech',
                'duration' => 6,
            ],
            [
                'title' => 'Electronic Dance Music Night',
                'description' => 'Dance the night away to the best EDM beats.',
                'venue' => 'Nightclub',
                'capacity' => 250,
                'ticket_price' => 28.00,
                'category' => 'Music',
                'duration' => 6,
            ],
            [
                'title' => 'Pottery Making Class',
                'description' => 'Create beautiful ceramic pieces with your hands.',
                'venue' => 'Art Studio',
                'capacity' => 20,
                'ticket_price' => 35.00,
                'category' => 'Hobbies',
                'duration' => 3,
            ],
        ];

        $dayOffset = 5; // Start events 5 days from now
        $eventIndex = 0;

        // Create 5 events for each organizer
        foreach ($organizers as $organizer) {
            for ($i = 0; $i < 5; $i++) {
                $template = $eventTemplates[$eventIndex % count($eventTemplates)];
                $category = $categories->firstWhere('name', $template['category']);
                $randomCity = $cities->random(); // Pick a random city for each event

                if ($category) {
                    Event::create([
                        'organizer_id' => $organizer->id,
                        'category_id' => $category->id,
                        'title' => $template['title'],
                        'description' => $template['description'],
                        'venue' => $template['venue'],
                        'city_id' => $randomCity->id,
                        'start_date' => now()->addDays($dayOffset),
                        'end_date' => now()->addDays($dayOffset)->addHours($template['duration']),
                        'capacity' => $template['capacity'],
                        'ticket_price' => $template['ticket_price'],
                        'status' => 'published',
                    ]);

                    $dayOffset += rand(3, 7); // Space events 3-7 days apart
                    $eventIndex++;
                }
            }

            $this->command->info("Created 5 events for organizer: {$organizer->name}");
        }

        $this->command->info("Total events created: " . ($organizers->count() * 5));
    }
}
