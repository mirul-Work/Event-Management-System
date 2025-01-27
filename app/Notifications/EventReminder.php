<?php

namespace App\Notifications;

use App\Models\Attendee;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class EventReminder extends Notification
{
    use Queueable;

    public $attendee;
    public $status;

    /**
     * Create a new notification instance.
     *
     * @param  \App\Models\Attendee  $attendee
     * @param  string  $status
     * @return void
     */
    public function __construct(Attendee $attendee, string $status)
    {
        $this->attendee = $attendee;
        $this->status = $status;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $event = $this->attendee->event;
        $seat = $this->attendee->seat_category;
        $token = $this->attendee->token;

        $message = new MailMessage;

        if ($this->status === 'pending') {
            $message->subject('Please RSVP for the '.$event->name.' event!')
                ->line('You have not yet responded to the event invitation for "' . $event->name . '" at '. $event->date->format('h:i A') .' Please click below to respond.')
                ->action('RSVP Now', url('/rsvp/' . $seat . '/' . $token));
        } elseif ($this->status === 'accepted') {
            $message->subject('Event Reminder: "' . $event->name . '" is Tomorrow!')
                ->line('This is a reminder that you have accepted the invitation to attend the event tomorrow at ' . $event->date->format('h:i A') . '.');
        }

        return $message;
    }
}
