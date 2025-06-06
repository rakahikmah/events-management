<?php

namespace App\Console\Commands;

use App\Notifications\EventReminderNotification;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Symfony\Contracts\EventDispatcher\Event;


class SendEventReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-event-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command Event Reminders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        logger("memulai scheduler");
        $events = \App\Models\Event::with('attendees.user')
            ->whereBetween('start_date', [now(), now()->addDays()])
            ->get();

        $eventCount = $events->count();
        $eventLabel = Str::plural('event', $eventCount);

        $this->info("Sending reminders for {$eventCount} {$eventLabel}");
        $events->each(function ($event) {
            $event->attendees->each(function ($attendee) use ($event) {
                $attendee->user->notify(new EventReminderNotification($event));
            });
        });


        $this->info('Reminder notifications sent successfullyl');
        logger("mengakhiri scheduler");
    }
}
