<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Events;
use App\Models\Organizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminDashboardController extends Controller
{

    // Show the profile page for the admin
    public function showProfile()
    {
        return view('admin.profile', ['user' => Auth::user()]);
    }

    // Update the admin profile
    public function updateProfile(Request $request)
    {
        // Validate the incoming data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'phone_number' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|max:15',
            'password' => 'nullable|confirmed|min:8', // Password is optional for update
        ]);

        $user = Auth::user();

        // Update the user's name and email
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->phone_number = $validatedData['phone_number'] ?? null;


        // Update password if it's provided
        if ($request->filled('password')) {
            $user->password = bcrypt($validatedData['password']);
        }

        $user->save();

        return redirect()->route('admin.profile')->with('success', 'Profile updated successfully.');

    }




    public function index(Request $request)
    {
        // Get all users with their roles (assuming users have 'role' field or relation)
        $users = User::where('role', 'organizer'); // You may want to join with roles if using a Role model

        // Get all events
        $events = Events::all();

        // Return the view with users and events data
        return view('admin.dashboard', compact('users', 'events'));
    }
    public function showOrganizerEvents($id)
    {
        $max_view = 10;

        $events = Events::where('user_id', $id)->paginate($max_view);
        // Count the related events>get();

        return view('admin.organizers.show-events', compact('events'));
    }



    public function approve($eventId)
    {
        $event = Events::findOrFail($eventId);

        // Check if the event is already approved
        if ($event->status === 'accepted') {
            session()->flash('info', 'Event is already approved!');
            return redirect()->route('admin.events.index');
        }

        // Update the event status to 'approved'
        $event->update(['status' => 'approved']);

        session()->flash('success', 'Event approved successfully!');
        return redirect()->route('admin.events.index');
    }

    // Disapprove an event
    public function disapprove($eventId)
    {
        $event = Events::findOrFail($eventId);

        // Check if the event is already disapproved
        if ($event->status === 'rejected') {
            session()->flash('info', 'Event is already rejected!');
            return redirect()->route('admin.events.index');
        }

        // Update the event status to 'disapproved'
        $event->update(['status' => 'rejected']);

        session()->flash('success', 'Event rejected successfully!');
        return redirect()->route('admin.events.index');
    }



    //Admin.Organizer
    public function indexOrganizers(Request $request)
    {
        $max_view = 10;
        $search = $request->get('search');

        $organizers = User::where('role', 'organizer') // Filter by organizer role
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%$search%")
                             ->orWhere('email', 'like', "%$search%");
            })
            ->withCount('events') // Count related events
            ->paginate($max_view); // Paginate the results

        return view('admin.organizers.index', compact('organizers'));
    }

    public function deleteOrganizer($id)
    {
        $organizer = User::findOrFail($id);
        $organizer->delete();

        return redirect()->route('admin.organizers.index')->with('success', 'Organizer deleted successfully.');
    }
}
