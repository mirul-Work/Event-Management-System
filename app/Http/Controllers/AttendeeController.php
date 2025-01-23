<?php

namespace App\Http\Controllers;

use App\Imports\AttendeesImport;
use App\Models\Events;
use App\Models\Attendee;
use App\Mail\InvitationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class AttendeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $max_view = 10;

        $user = auth()->user(); // Get the authenticated user

        if ($user->role == 'admin') {
            // Admin can see all events, even pending ones
            $attendees = Attendee::with('event')->paginate($max_view); // Eager load 'event'
            return view('admin.events.index', compact('events'));
        } elseif ($user->role == 'organizer') {
            // Organizer can only see their own events
            $attendees = Attendee::where('user_id', $user->id)->with('event')->paginate($max_view); // Eager load 'event'
            return view('organizer.attendees.index', compact('attendees'));
        } else {
            // Handle the case for a regular user if needed
            $attendees = [];
        }

        return abort(401);
    }


    /**
     * Search attendees based on query.
     */
    public function search(Request $request)
    {
        // Get the search query
        $searchQuery = $request->input('search');

        // Fetch attendees matching the search query
        // Searching in attendee name, email, phone number, and event name
        $attendees = Attendee::where('name', 'LIKE', "%{$searchQuery}%")
            ->orWhere('email', 'LIKE', "%{$searchQuery}%")
            ->orWhere('seat_category', 'LIKE', "%{$searchQuery}%")
            ->orWhere('phone_number', 'LIKE', "%{$searchQuery}%")
            ->orWhereHas('event', function ($query) use ($searchQuery) {
                $query->where('name', 'LIKE', "%{$searchQuery}%");
            })
            ->paginate(10); // Paginate results

        // Check if request is AJAX
        if ($request->ajax()) {
            return response()->json([
                'attendees' => $attendees->items(), // Return only the attendees array
            ]);
        }

        // Return to the attendees page with the search results
        return view('organizer.attendees.index', compact('attendees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Events $event)
    {
        return view('organizer.attendees.create', compact('event'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Events $event)
    {
        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:attendees,email,NULL,id,events_id,' . $event->id,
            'phone_number' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|max:15',
            'seat_category' => 'required|in:regular,vip,vvip',
        ]);

        // Check seat availability based on category
        $availableSeats = 0;

        switch ($validated['seat_category']) {
            case 'vip':
                $availableSeats = $event->vip_seat_available;
                break;
            case 'regular':
                $availableSeats = $event->regular_seat_available;
                break;
            case 'vvip':
                $availableSeats = $event->vvip_seat_available;
                break;
        }

        // If no available seats, show error
        if ($availableSeats <= 0) {
            return redirect()->back()->with('error', 'No seats available for this category.');
        }

        // Generate a unique token for the attendee
        $token = bin2hex(random_bytes(16));

        // Save the attendee to the database
        $attendee = Attendee::create([
            'events_id' => $event->id,
            'user_id' => $event->user_id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'seat_category' => $validated['seat_category'],
            'token' => $token,
            'status' => 'pending',
        ]);

        // Decrease the available seats for the selected category
        if ($validated['seat_category'] === 'vip') {
            $event->vip_seat_available--;
        } elseif ($validated['seat_category'] === 'regular') {
            $event->regular_seat_available--;
        } elseif ($validated['seat_category'] === 'vvip') {
            $event->vvip_seat_available--;
        }

        // Decrease total available seats
        $event->available_seats--;

        // Save the updated event data
        $event->save();

        // Redirect with RSVP link
        return redirect()->route('organizer.events.index', $event->id)
            ->with('success', "Attendee added successfully! RSVP link: http://127.0.0.1:8000/rsvp/{$attendee->seat_category}/{$token}");
    }

    public function showImportForm($eventId)
    {
        $event = Events::findOrFail($eventId); // Retrieve the event by its ID

        return view('organizer.attendees.import', compact('event'));
    }

    /**
     * Handle the file import.
     */

    public function import(Request $request, $eventId)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx',
        ]);

        $event = Events::findOrFail($eventId);
        $user = Auth::user();  // Get the authenticated user

        $attendees = Excel::toCollection(null, $request->file('file'));
        $sheet = $attendees->first();
        $dataRows = $sheet->slice(1);

        $duplicateEmails = [];  // Array to store emails that were skipped

        foreach ($dataRows as $row) {
            // Generate a secure token using random_bytes (32-character hexadecimal string)
            $token = bin2hex(random_bytes(16));  // 16 bytes = 32 characters in hex

            // Check if the email already exists
            $existingAttendee = Attendee::where('email', $row[1])->first();  // Check if email exists

            if ($existingAttendee) {
                // If the email already exists, log the duplicate and skip inserting
                $duplicateEmails[] = $row[1];  // Store the duplicated email
                continue;  // Skip this row and move to the next one
            }

            // Insert the attendee data and include the generated token
            Attendee::create([
                'name' => $row[0], // First column
                'email' => $row[1], // Second column
                'phone_number' => $row[2], // Third column
                'seat_category' => $row[3], // Fourth column
                'events_id' => $event->id, // Associate with the event
                'user_id' => $user->id, // Associate with the authenticated user (organizer)
                'token' => $token, // Provide the generated token
            ]);
        }

        // If there were duplicate emails, generate a message to notify the user
        if (!empty($duplicateEmails)) {
            $duplicateEmailsList = implode(', ', $duplicateEmails);  // Join the emails with commas
            return redirect()->back()->with('warning', "The following emails were duplicated and ignored: $duplicateEmailsList");
        }

        return redirect()->back()->with('success', 'Attendees imported successfully!');
    }
    /**
     * Display the specified resource.
     */
    public function showEventAttendees(string $id)
    {
        $pending = 'pending';
        $accepted = 'accepted';
        $rejected = 'rejected';

        // Fetch the event by ID
        $events = Events::findOrFail($id);

        // Get all attendees for the event (pagination)
        $attendees = Attendee::where('events_id', $id)->paginate(10);

        // Get attendees with 'pending' status (pagination)
        $attendees_pending = Attendee::where('events_id', $id)->where('status', $pending)->paginate(10);

        // Get attendees with 'accepted' status (pagination)
        $attendees_accepted = Attendee::where('events_id', $id)->where('status', $accepted)->paginate(10);

        // Get attendees with 'rejected' status (pagination)
        $attendees_rejected = Attendee::where('events_id', $id)->where('status', $rejected)->paginate(10);

        // Return the view with the necessary data
        return view('organizer.events.show-attendees', compact(
            'attendees',
            'attendees_pending',
            'attendees_accepted',
            'attendees_rejected',
            'events'
        ));
    }

    public function destroy($attendeeId)
    {
        // Find the attendee by ID
        $attendee = Attendee::find($attendeeId);

        // If the attendee exists
        if ($attendee) {
            // Get the event related to this attendee
            $event = $attendee->event; // assuming you have a relationship between Attendee and Event

            // Delete the attendee
            $attendee->delete();

            // Recalculate available seats after deletion
            $this->recalculateAvailableSeats($event);

            // Redirect back to the same page with a success message
            return redirect()->back()->with('success', 'Attendee deleted and available seats updated successfully.');
        } else {
            // If the attendee does not exist, redirect back with an error message
            return redirect()->back()->with('error', 'Attendee not found.');
        }
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



    public function sendEmailAll($eventId)
    {
        // Find the event
        $event = Events::find($eventId);

        // Get all attendees of the event who haven't received an email yet
        $attendees = Attendee::where('events_id', $eventId)
            ->where('email_sent', 'no')
            ->get();

        // Check if there are attendees to send emails to
        if ($attendees->isEmpty()) {
            return redirect()->back()->with('info', 'No attendees need email notifications.');
        }

        // Loop through the attendees and send the email
        foreach ($attendees as $attendee) {
            try {
                // Send the email
                $rsvpLink = url("/rsvp/{$attendee->seat_category}/{$attendee->token}");
                Mail::to($attendee->email)->send(new InvitationMail($event, $attendee, $rsvpLink));

                // Mark email as sent
                $attendee->email_sent = true;
                $attendee->save();
            } catch (\Exception $e) {
                // If an error occurs, continue to the next attendee
                continue;
            }
        }

        // Return success message after sending emails
        return redirect()->back()->with('success', 'Emails sent successfully to all pending attendees.');
    }


    public function sendEmail(Events $event, Attendee $attendee)
    {
        // Ensure the event is correctly passed
        $rsvpLink = url("/rsvp/{$attendee->seat_category}/{$attendee->token}");

        // Send the email
        Mail::to($attendee->email)->send(new InvitationMail($event, $attendee, $rsvpLink));
        // Update the email_sent flag
        $attendee->email_sent = true;
        $attendee->save();
        // Redirect back with success message
        return redirect()->route('organizer.attendees.showEventAttendees', $event->id)
            ->with('success', "Invitation email sent to {$attendee->email} successfully!");
    }
}
