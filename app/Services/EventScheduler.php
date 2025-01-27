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
        $pendingRsvps = Attendee::where('status', 'pending')->get();

        foreach ($pendingRsvps as $rsvp) {
            Notification::send($rsvp, new EventReminder($rsvp, 'pending')); // Send to Attendee model
            $rsvp->update(['last_reminder_sent_at' => Carbon::now()]);
        }
    }

    private function remindAcceptedRsvps()
    {
        // Send reminders to attendees who have accepted the RSVP
        $acceptedRsvps = Attendee::where('status', 'accepted')->get();
        foreach ($acceptedRsvps as $attendee) {
            $attendee->notify(new EventReminder($attendee, 'accepted'));
            $attendee->update(['last_reminder_sent_at' => now()]);
        }
    }
}
