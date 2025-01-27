<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\EventScheduler;

class SendEventReminders extends Command
{
    protected $signature = 'events:send-reminders';
    protected $description = 'Send reminders to attendees to RSVP or to remind about an upcoming event';

    protected $eventScheduler;

    public function __construct(EventScheduler $eventScheduler)
    {
        parent::__construct();
        $this->eventScheduler = $eventScheduler;
    }

    public function handle()
    {
        $this->eventScheduler->scheduleReminders();
        $this->info('Event reminders sent successfully!');
    }
}
