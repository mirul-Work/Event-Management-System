<?php
namespace App\Http\Controllers;

use App\Models\Events;
use App\Models\Attendee;
use App\Mail\SendInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class InvitationController extends Controller
{
    public function sendInvitation(Request $request, $eventId)
    {
        $event = Events::findOrFail($eventId);
        $attendees = Attendee::where('events_id', $event->id)->get();

        foreach ($attendees as $attendee) {
            // Send invitation email for each attendee
            Mail::to($attendee->user->email)->send(new SendInvitation($attendee));
        }

        return redirect()->route('organizer.events.index')->with('success', 'Invitations sent successfully.');
    }
}
