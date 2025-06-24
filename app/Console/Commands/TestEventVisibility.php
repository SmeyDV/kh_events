<?php

namespace App\Console\Commands;

use App\Models\Event;
use Illuminate\Console\Command;

class TestEventVisibility extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:test-visibility';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test event visibility and debug published events';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== Event Visibility Test ===');

        // Test 1: Total events
        $totalEvents = Event::count();
        $this->info("Total events in database: {$totalEvents}");

        // Test 2: Published events
        $publishedEvents = Event::published()->count();
        $this->info("Published events: {$publishedEvents}");

        // Test 3: Show event details
        if ($totalEvents > 0) {
            $this->info("\nEvent Details:");
            Event::all(['id', 'title', 'status', 'start_date', 'organizer_id'])->each(function ($event) {
                $this->line("ID: {$event->id}, Title: {$event->title}, Status: {$event->status}, Start Date: {$event->start_date}");
            });
        }

        // Test 4: Test cached method
        $this->info("\nTesting cached getPublishedEvents method:");
        try {
            $cachedEvents = Event::getPublishedEvents();
            $this->info("Cached published events count: " . $cachedEvents->count());

            if ($cachedEvents->count() > 0) {
                $this->info("First cached event: " . $cachedEvents->first()->title);
            }
        } catch (\Exception $e) {
            $this->error("Error with cached method: " . $e->getMessage());
        }

        // Test 5: Test upcoming events
        $this->info("\nTesting upcoming events:");
        try {
            $upcomingEvents = Event::getUpcomingEvents();
            $this->info("Upcoming events count: " . $upcomingEvents->count());
        } catch (\Exception $e) {
            $this->error("Error with upcoming events: " . $e->getMessage());
        }

        $this->info("\n=== Test Complete ===");
    }
}
