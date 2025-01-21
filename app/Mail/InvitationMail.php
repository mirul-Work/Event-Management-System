<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $attendee;
    public $rsvpLink;

    public function __construct($event, $attendee, $rsvpLink)
    {
        $this->event = $event;
        $this->attendee = $attendee;
        $this->rsvpLink = $rsvpLink;
    }

    public function build()
    {
        return $this->subject('You’re Invited!')
            ->view('emails.invitation');
    }
}
