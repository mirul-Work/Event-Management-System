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
    public function index(Request $request)
    {
        $max_view = 10; // Number of events per page
        $user = auth()->user(); // Get the authenticated user

        $query = Events::query();

        if ($user->role == 'admin') {
            // Admin can see all events
            $query = $query;
        } elseif ($user->role == 'organizer') {
            // Organizer sees only their events
            $query = $query->where('user_id', $user->id);
        } else {
            // Handle unauthorized users
            return abort(403, 'Unauthorized');
        }

        // Apply search filters
        if ($request->has('search') && $request->search) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Paginate the results
        $events = $query->paginate($max_view);

        // Return the correct view based on the user's role
        $view = $user->role == 'admin' ? 'admin.events.index' : 'organizer.events.index';
        return view($view, compact('events'));
    }

    // Show the form to create an event
    public function create()
    {
        return view('organizer.events.create');
    }



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


}
