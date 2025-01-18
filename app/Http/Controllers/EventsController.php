<?php
namespace App\Http\Controllers;


use App\Models\Seat;
use App\Models\User;
use App\Models\Events;
use App\Models\Attendee;

use App\Mail\SendInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class EventsController extends Controller
{
    //show all event index
    public function index()
{
    $max_view = 10;  // Change this to the desired number of events per page
    $user = auth()->user(); // Get the authenticated user

    if ($user->role == 'admin') {
        // Admin can see all events, even pending ones
        $events = Events::paginate($max_view); // Apply pagination directly on the query
        return view('admin.events.index', compact('events'));

    } elseif ($user->role == 'organizer') {
        // Organizer can only see their own events
        $events = Events::where('user_id', $user->id)->paginate($max_view); // Paginate based on organizer's events
        return view('organizer.events.index', compact('events'));

    } else {
        // Handle the case for a regular user (e.g., redirect them or show an error message)
        return abort(403, 'Unauthorized'); // Or you could return a custom view or message
    }
}

    // Show the form to create an event
    public function create()
    {
        return view('organizer.events.create');
    }

    // Store the new event created by the organizer
    // public function store(Request $request)
    // {
    //     // Validate the request data
    //     $validatedData = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'date' => 'required|after:today|date',
    //         'location' => 'required|string|max:255',
    //         'status' => 'required|in:pending', // Only 'pending' status for now
    //         'regular_seats' => 'required|integer|min:0',
    //         'vip_seats' => 'required|integer|min:0',
    //         'vvip_seats' => 'required|integer|min:0',
    //     ]);

    //     // Calculate total seats
    //     $totalSeats = $validatedData['vip_seats'] + $validatedData['vvip_seats'];

    //     // Create the event with the total seats
    //     $event = Events::create([
    //         'name' => $validatedData['name'],
    //         'date' => $validatedData['date'],
    //         'location' => $validatedData['location'],
    //         'status' => $validatedData['status'],
    //         'regular_seats' => $validatedData['regular_seats'],
    //         'vip_seats' => $validatedData['vip_seats'],
    //         'vvip_seats' => $validatedData['vvip_seats'],
    //         'total_seats' => $totalSeats, // Store total seats in the database
    //         'user_id' => auth()->id(), // Ensure the logged-in user is the organizer
    //     ]);

    //     // Generate Seat Links (Regular, VIP, VVIP)
    //     // $this->generateSeatLinks($event);

    //     // Flash message for success
    //     session()->flash('success', 'Event created successfully!');

    //     return redirect()->route('organizer.events.index');
    // }


    public function store(Request $request)
    {
        // Validate the input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'vip_seats' => 'required|integer|min:0',
            'regular_seats' => 'required|integer|min:0',
            'vvip_seats' => 'required|integer|min:0',
            'date' => 'required|after:today|date',
            'location' => 'required|string|max:255',
            'status' => 'required|in:pending', // Only 'pending' status for now
        ]);
        $totalSeats = $validated['vip_seats'] + $validated['vvip_seats'];
        // Create the event
        $event = Events::create([
            'user_id' => auth()->id(),
            'name' => $validated['name'],
            'date' => $validated['date'],
            'location' => $validated['location'],
            'status' => $validated['status'],
            'regular_seats' => $validated['regular_seats'],
            'vip_seats' => $validated['vip_seats'],
            'vvip_seats' => $validated['vvip_seats'],
            'regular_seat_available' => $validated['regular_seats'],
            'vip_seat_available' => $validated['vip_seats'],
            'vvip_seat_available' => $validated['vvip_seats'],
            'available_seats' => $totalSeats,
            'total_seats' => $totalSeats, // Store total seats in the database
        ]);



        // // Generate seats for each category
        // $this->generateSeats($event->id, 'vip', $validated['vip_seats']);
        // $this->generateSeats($event->id, 'regular', $validated['regular_seats']);
        // $this->generateSeats($event->id, 'vvip', $validated['vvip_seats']);

        return redirect()->route('organizer.events.index')
            ->with('success', 'Event created with seats and  availability successfully!');
    }

    private function generateSeats($eventId, $category, $quantity)
    {
        for ($i = 1; $i <= $quantity; $i++) {
            Seat::create([
                'events_id' => $eventId,
                'seat_category' => $category,
                'seat_number' => strtoupper($category) . '-' . $i,
                'status' => 'available',
            ]);
        }
    }


    public function edit($eventId)
    {
        // Find the event using the event ID
        $event = Events::findOrFail($eventId);

        // Check if the authenticated user is the organizer of the event
        if (auth()->id() !== $event->user_id) {
            // If the user is not the organizer, show an error or redirect
            session()->flash('error', 'You are not authorized to edit this event.');
            return redirect()->route('organizer.events.index');
        }

        // Return the edit view with the event data
        return view('organizer.events.edit', compact('event'));
    }

    public function update(Request $request, $eventId)
    {
        // Find the event using the event ID
        $event = Events::findOrFail($eventId);

        // Check if the authenticated user is the organizer of the event
        if (auth()->id() !== $event->user_id) {
            session()->flash('error', 'You are not authorized to update this event.');
            return redirect()->route('organizer.events.index');
        }

        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|after:today|date',
            'location' => 'required|string|max:255',
            'regular_seats' => 'required|integer|min:0',
            'vip_seats' => 'required|integer|min:0',
            'vvip_seats' => 'required|integer|min:0',
        ]);

        // Calculate the total seats after the update
        $totalSeats = $validated['regular_seats'] + $validated['vip_seats'] + $validated['vvip_seats'];

        // Update the event with the new data
        $event->update([
            'name' => $validated['name'],
            'date' => $validated['date'],
            'location' => $validated['location'],
            'regular_seats' => $validated['regular_seats'],
            'vip_seats' => $validated['vip_seats'],
            'vvip_seats' => $validated['vvip_seats'],
            'total_seats' => $totalSeats,
            'regular_seat_available' => $validated['regular_seats'],
            'vip_seat_available' => $validated['vip_seats'],
            'vvip_seat_available' => $validated['vvip_seats'],
            'available_seats' => $totalSeats,
        ]);

        // Recalculate the available seats based on the number of attendees
        $this->recalculateAvailableSeats($event);

        session()->flash('success', 'Event updated successfully!');
        return redirect()->route('organizer.events.index');
    }

    private function recalculateAvailableSeats($event)
{
    // Get the number of attendees for each category, regardless of their status
    $regularAttendees = Attendee::where('events_id', $event->id)
        ->where('seat_category', 'regular')
        ->count();
    $vipAttendees = Attendee::where('events_id', $event->id)
        ->where('seat_category', 'vip')
        ->count();
    $vvipAttendees = Attendee::where('events_id', $event->id)
        ->where('seat_category', 'vvip')
        ->count();

    // Calculate available seats for each category
    $regularAvailable = $event->regular_seats - $regularAttendees;
    $vipAvailable = $event->vip_seats - $vipAttendees;
    $vvipAvailable = $event->vvip_seats - $vvipAttendees;

    // Calculate the total available seats
    $totalAvailable = $regularAvailable + $vipAvailable + $vvipAvailable;

    // Update the event with the recalculated values
    $event->update([
        'regular_seat_available' => $regularAvailable,
        'vip_seat_available' => $vipAvailable,
        'vvip_seat_available' => $vvipAvailable,
        'available_seats' => $totalAvailable,
    ]);
}





    public function destroy(Request $request, $id)
    {
        $event = Events::findOrFail($id);

        // Check if the user is an admin or the organizer who created the event
        if (Auth::guard('admin')->check()) {
            // Admin can delete any event
            $event->delete();
            return redirect()->route('admin.events.index')->with('success', 'Event deleted successfully!');
        }

        // Organizer can only delete events they created
        if (Auth::guard('organizer')->check() && $event->user_id === Auth::id()) {
            // Make sure the event belongs to the logged-in organizer
            $event->delete();
            return redirect()->route('organizer.events.index')->with('success', 'Event deleted successfully!');
        }

        // If neither admin nor organizer, deny access
        return redirect()->route('admin.events.index')->with('error', 'You do not have permission to delete this event.');
    }



    // Show attendees for a specific event

    public function show(string $id)
    {
        // $attendees = Attendee::where('events_id', $event->id)->get();
        $events = request('events_id');
        $attendees = Attendee::where('events_id', $id)->get();
        return view('organizer.events.show-attendees', compact('attendees', 'events'));

    }
    // public function sendInvitations(Request $request, $events_id)
    // {
    //     // Retrieve the event
    //     $event = Events::with('attendees.user')->findOrFail($events_id);

    //     // Loop through attendees and send email
    //     foreach ($event->attendees as $attendee) {
    //         // Generate RSVP link
    //         $rsvpLink = route('events.rsvp', [
    //             'events_id' => $event->id,
    //             'token' => $attendee->token,
    //         ]);

    //         // Send the email
    //         Mail::to($attendee->user->email)->send(new SendInvitation($event_id, $rsvp_link));
    //     }

    //     return redirect()->route('organizer.events.show', $event->id)
    //                      ->with('success', 'Invitations have been sent to attendees.');
    // }


    // public function uploadAttendees(Request $request, $eventId)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'csv_file' => 'required|mimes:csv,txt|max:10240', // File validation (CSV)
    //     ]);

    //     if ($validator->fails()) {
    //         return redirect()->back()->withErrors($validator)->withInput();
    //     }

    //     // Load CSV file
    //     $file = $request->file('csv_file');
    //     $csvData = array_map('str_getcsv', file($file));

    //     // Assuming the CSV has headers and each row contains an email address
    //     foreach ($csvData as $row) {
    //         $email = $row[0]; // Assuming email is the first column in the CSV file

    //         // Check if the email exists in the users table
    //         $user = User::where('email', $email)->first();

    //         if ($user) {
    //             // Add the attendee
    //             Attendee::create([
    //                 'event_id' => $eventId,
    //                 'user_id' => $user->id,
    //                 'seat_category' => 'regular', // Default seat category (can be modified)
    //                 'status' => 'pending',
    //             ]);
    //         }
    //     }

    //     return redirect()->route('events.show', ['event' => $eventId])
    //         ->with('success', 'Attendees uploaded successfully.');
    // }


    public function uploadAttendees(Request $request, Events $event)
    {
        $file = $request->file('attendees_csv');
        $data = array_map('str_getcsv', file($file->getRealPath()));

        foreach ($data as $row) {
            // Example: [email, seat_category]
            $user = User::where('email', $row[0])->first();

            if ($user) {
                Attendee::create([
                    'event_id' => $event->id,
                    'user_id' => $user->id,
                    'seat_category' => $row[1], // Ensure 'regular', 'vip', or 'vvip'
                    'status' => 'pending',
                ]);
            }
        }

        return redirect()->route('events.show', $event->id)->with('success', 'Attendees uploaded successfully.');
    }



    // public function handleRSVP($eventId, $token)
    // {
    //     $attendee = Attendee::where('event_id', $eventId)
    //         ->where('token', $token)
    //         ->first();

    //     if (!$attendee) {
    //         abort(404, 'Invalid RSVP link');
    //     }

    //     // Show RSVP page where attendee can accept or reject the invitation
    //     return view('events.rsvp', compact('attendee'));
    // }

    // public function submitRSVP(Request $request, $eventId, $token)
    // {
    //     $attendee = Attendee::where('event_id', $eventId)
    //         ->where('token', $token)
    //         ->first();

    //     if (!$attendee) {
    //         abort(404, 'Invalid RSVP link');
    //     }

    //     $attendee->status = $request->response === 'accepted' ? 'accepted' : 'rejected';
    //     $attendee->save();

    //     return redirect()->route('home')->with('success', 'Your RSVP has been submitted.');
    // }

    // public function rsvp(Request $request, Events $event)
    // {
    //     $attendee = Attendee::where('events_id', $event->id)
    //         ->where('user_id', auth()->id())
    //         ->first();

    //     if (!$attendee) {
    //         return redirect()->route('organizer.events.index')->with('error', 'Attendee not found.');
    //     }

    //     // Validate RSVP link matches the assigned seat type
    //     if ($request->seat_category !== $attendee->seat_category) {
    //         return redirect()->route('organizer.events.index')->with('error', 'Invalid RSVP link for your seat category.');
    //     }

    //     // Update RSVP status
    //     $attendee->status = $request->input('status');
    //     $attendee->save();

    //     return redirect()->route('organizer.events.show', $event->id)->with('success', 'RSVP updated successfully.');
    // }


}
