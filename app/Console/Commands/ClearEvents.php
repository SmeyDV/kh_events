<?php

namespace App\Console\Commands;

use App\Models\Event;
use Illuminate\Console\Command;

class ClearEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:clear {--force : Force deletion without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all events from the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $eventCount = Event::count();

        if ($eventCount === 0) {
            $this->info('No events found in the database.');
            return;
        }

        if (!$this->option('force')) {
            if (!$this->confirm("Are you sure you want to delete all {$eventCount} events?")) {
                $this->info('Operation cancelled.');
                return;
            }
        }

        // Delete events (this will also delete related bookings due to foreign key constraints)
        Event::query()->delete();

        $this->info("Successfully deleted {$eventCount} events from the database.");
    }
}
