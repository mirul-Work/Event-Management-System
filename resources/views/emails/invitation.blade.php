<!DOCTYPE html>
<html>
<head>
    <title>You’re Invited!</title>
</head>
<body>
    <h1>You’re Invited to {{ $event->name }}</h1>
    <p>Hi {{ $attendee->name }},</p>
    <p>You have been invited to the event <strong>{{ $event->name }}</strong> as a <strong>{{ ucfirst($attendee->seat_category) }} Seater</strong>.</p>
    <p>Event Details:</p>
    <ul>
        <li>Date: {{ $event->date }}</li>
        <li>Location: {{ $event->location }}</li>
    </ul>
    <p>Please RSVP by clicking the link below:</p>
    <a href="{{ $rsvpLink }}">{{ $rsvpLink }}</a>
    <p>We look forward to seeing you there!</p>
</body>
</html>
