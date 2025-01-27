<?php

namespace App\Notifications;

use App\Models\Events;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class EventReminder extends Notification
{
    use Queueable;

    public $event;
    public $status;

    /**
     * Create a new notification instance.
     *
     * @param  \App\Models\Events  $event
     * @param  string  $status
     * @return void
     */
    public function __construct(Events $event, string $status)
    {
        $this->event = $event;
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
        return ['mail'];  // You can add other channels like 'database', 'slack', etc.
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $message = new MailMessage;

        if ($this->status === 'pending') {
            // Reminder for pending RSVP
            $message->subject('Please RSVP for the event!')
                ->line('You have not yet responded to the event invitation for "' . $this->event->name . '". Please click below to respond.');
        } elseif ($this->status === 'accepted') {
            // Reminder for accepted RSVP about the event tomorrow
            $message->subject('Event Reminder: "' . $this->event->name . '" is Tomorrow!')
                ->line('This is a reminder that you have accepted the invitation to attend the event tomorrow at ' . $this->event->date->format('h:i A') . '.');
        }

        $message->action('RSVP Now', url('/events/' . $this->event->id . '/rsvp'));

        return $message;
    }
}
