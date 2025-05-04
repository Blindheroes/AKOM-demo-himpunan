<?php

namespace App\Console\Commands;

use App\Models\Event;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AutoUpdateEventStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically update event statuses based on dates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting automatic event status update...');
        $updatedCount = 0;

        // Mark published events as completed when end date has passed
        $completedEvents = Event::where('status', 'published')
            ->where('end_date', '<', now())
            ->update(['status' => 'completed']);

        if ($completedEvents > 0) {
            $this->info("{$completedEvents} events marked as completed.");
            $updatedCount += $completedEvents;
            Log::info("{$completedEvents} events automatically marked as completed.");
        }

        // Update events that are past their registration deadline but not yet started
        // Close them for registration if registration deadline has passed
        $pastDeadlineEvents = Event::where('status', 'published')
            ->whereNotNull('registration_deadline')
            ->where('registration_deadline', '<', now())
            ->where('start_date', '>', now())
            ->get();

        foreach ($pastDeadlineEvents as $event) {
            // We're not changing the status here, just logging it
            // Could implement a "registration_closed" flag if needed in the future
            $this->line("Event '{$event->title}' has passed its registration deadline.");
            Log::info("Event ID {$event->id} ({$event->title}) has passed its registration deadline.");
        }

        // Optionally, check for events that should be automatically published
        // For example, events in pending status that are approved and approaching their start date
        $upcomingEvents = Event::where('status', 'pending')
            ->whereNotNull('approved_by')
            ->where('start_date', '>', now())
            ->where('start_date', '<', now()->addDays(7)) // Events starting within a week
            ->update(['status' => 'published']);

        if ($upcomingEvents > 0) {
            $this->info("{$upcomingEvents} pending events automatically published.");
            $updatedCount += $upcomingEvents;
            Log::info("{$upcomingEvents} events automatically published.");
        }

        if ($updatedCount == 0) {
            $this->info('No events needed status updates.');
        } else {
            $this->info("Total events updated: {$updatedCount}");
        }

        return Command::SUCCESS;
    }
}
