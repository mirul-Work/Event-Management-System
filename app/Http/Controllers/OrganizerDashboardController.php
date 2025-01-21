<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Events;
use App\Models\Attendee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class OrganizerDashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id(); // Get the currently authenticated user's ID

        // Filter attendees and events by the user's ID
        $attendees = Attendee::where('user_id', $userId)->get();
        $events = Events::where('user_id', $userId)->get();

        return view('organizer.dashboard', compact('attendees', 'events'));
    }

    // public function showProfile(){
    //     return view('organizer.profile.reset-password');

    // }


    // Show the profile page for the organizer
    public function showProfile()
    {
        return view('organizer.profile', ['user' => Auth::user()]);
    }

    // Update the organizer profile
    public function updateProfile(Request $request)
    {
        // Validate the incoming data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'nullable|confirmed|min:8', // Password is optional for update
        ]);

        $user = Auth::user();

        // Update the user's name and email
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];

        // Update password if it's provided
        if ($request->filled('password')) {
            $user->password = bcrypt($validatedData['password']);
        }

        $user->save();

        return redirect()->route('organizer.profile')->with('success', 'Profile updated successfully.');
    }


    //version 2

    public function indexEvents()
    {
        // Fetch events where the organizer is the logged-in user
        $events = Events::where('user_id', auth()->id())->get();

        return view('organizer.events.index', compact('events'));
    }


    // Show the event creation form for organizers
    public function create()
    {
        return view('organizer.events.create');
    }

    // Store a new event in the database (only for organizers)
    public function storeEvent(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'status' => 'required|in:pending,approved,rejected',
            'event_date' => 'required|date',  // Ensure the event_date is present and valid
        ]);

        // Create the event and save it
        Events::create([
            'name' => $request->name,
            'location' => $request->location,
            'event_date' => $request->event_date,  // Ensure event_date is passed correctly
            'status' => $request->status,
            'user_id' => auth()->id(),  // Logged-in user
        ]);

        return redirect()->route('organizer.events.index')->with('success', 'Event created successfully.');
    }

    public function editEvent($id)
    {
        $event = Events::findOrFail($id);

        // Ensure only the organizer who created the event can edit it
        if ($event->user_id != auth()->id()) {
            return redirect()->route('organizer.events.index')->with('error', 'You are not authorized to edit this event.');
        }

        return view('organizer.events.edit', compact('event'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'event_date' => 'required|date',
        ]);

        $event = Events::findOrFail($id);

        // Ensure only the organizer who created the event can update it
        if ($event->user_id != auth()->id()) {
            return redirect()->route('organizer.events.index')->with('error', 'You are not authorized to update this event.');
        }

        $event->update([
            'name' => $validated['name'],
            'location' => $validated['location'],
            'event_date' => $validated['event_date'],
            'status' => $event->status, // Do not change the status from the organizer view
        ]);

        return redirect()->route('organizer.events.index')->with('success', 'Event updated successfully!');
    }

    public function destroy($id)
    {
        $event = Events::findOrFail($id);

        // Ensure only the organizer who created the event can delete it
        if ($event->user_id != auth()->id()) {
            return redirect()->route('organizer.events.index')->with('error', 'You are not authorized to delete this event.');
        }

        $event->delete();

        return redirect()->route('organizer.events.index')->with('success', 'Event deleted successfully!');
    }


}





