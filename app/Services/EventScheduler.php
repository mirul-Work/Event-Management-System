<?php

namespace App\Services;

use App\Models\Attendee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use App\Notifications\EventReminder;

class EventScheduler
{
    public function scheduleReminders()
    {
        // Send reminders for pending RSVPs
        $this->remindPendingRsvps();

        // Send reminders for accepted RSVPs
        $this->remindAcceptedRsvps();
    }

    private function remindPendingRsvps()
    {
        $pendingRsvps = Attendee::where('status', 'pending')
            // ->whereNull('last_reminder_sent_at')
            ->get();

        foreach ($pendingRsvps as $rsvp) {
            $attendee = $rsvp->user;
            Notification::send($attendee, new EventReminder($rsvp->event, 'pending'));
            $rsvp->update(['last_reminder_sent_at' => Carbon::now()]);
        }
    }

    private function remindAcceptedRsvps()
    {
        $acceptedRsvps = Attendee::where('status', 'accepted')
            ->whereHas('event', function ($query) {
                $query->whereDate('date', '=', Carbon::tomorrow()->toDateString()); // Tomorrow's events
            })
            ->get();

        foreach ($acceptedRsvps as $rsvp) {
            $attendee = $rsvp->user;
            Notification::send($attendee, new EventReminder($rsvp->event, 'accepted'));
        }
    }
}
