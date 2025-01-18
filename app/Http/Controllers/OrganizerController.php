<?php

namespace App\Http\Controllers;

use App\Models\Organizer;
use App\Models\Events;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OrganizerController extends Controller
{
    public function showRegisterForm()
    {
        return view('organizer.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:organizers,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Create organizer without using Auth
        Organizer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('organizer.login.form')->with('success', 'Registration successful. Please log in.');
    }

    public function showLoginForm()
    {
        return view('organizer.login');
    }

   // public function login(Request $request)
   // {
   //     $credentials = $request->validate([
   //         'email' => 'required|email',
   //         'password' => 'required',
   //     ]);
//
   //     // No Auth here, just check credentials directly
   //     $organizer = Organizer::where('email', $credentials['email'])->first();
//
   //     if ($organizer && Hash::check($credentials['password'], $organizer->password)) {
   //         return redirect()->route('organizer.dashboard', ['organizer_id' => $organizer->id]);
   //     }
//
   //     return back()->withErrors('Invalid credentials.');
   // }


   public function login(Request $request)
   {
       $credentials = $request->validate([
           'email' => 'required|email',
           'password' => 'required',
       ]);

       // Find the organizer by email
       $organizer = Organizer::where('email', $request->email)->first();

       // Check if the organizer exists and passwords match
       if ($organizer && Hash::check($request->password, $organizer->password)) {
           // Store organizer_id in the session
           session(['organizer_id' => $organizer->id]);

           // Redirect to the dashboard with organizer_id
           return redirect()->route('organizer.manage.events', ['organizer_id' => $organizer->id]);
       }

       // If authentication fails, show error
       return back()->withErrors(['email' => 'Invalid credentials.']);
   }


    public function logout()
    {
        // No need to log out if not using Auth
        return redirect()->route('organizer.login.form');
    }



    public function dashboard()
     {
         // Pass any required data to the view
         return view('organizer/dashboard', [
             'title' => 'Organizer Dashboard', // Example data
         ]);
     }


    // public function dashboard()
    // {
    //     // Retrieve the organizer_id from the session
    //     $organizer_id = session('organizer_id');

    //     // Check if organizer_id exists in session
    //     if (!$organizer_id) {
    //         return redirect()->route('organizer.login.form')->with('error', 'You need to log in first.');
    //     }

    //     // Retrieve the events that belong to this organizer
    //     $events = Event::where('organizer_id', $organizer_id)->get();

    //     return view('organizer.dashboard', compact('events'));
    // }


    public function indexEvents()
    {
        $events = Events::all();
        return view('organizer.events.index', compact('events'));
    }

    public function createEvent()
    {
        return view('organizer.events.create');
    }

    public function storeEvent(Request $request)
    {
     // Validate the request
     $validated = $request->validate([
        'name' => 'required|string|max:255',
        'date' => 'required|date',
        'location' => 'required|string|max:255',
        'status' => 'required|string|max:50',
        'organizer_id' => 'required|exists:organizers,organizer_id',
    ]);

    // Create a new event
    Events::create($validated);

    // Redirect to event listing or show message
    return redirect()->route('organizer.events.index')->with('success', 'Event created successfully');
    }

    public function editEvent($id)
    {
        $event = Events::findOrFail($id); // Find the event by its ID
        return view('organizer.events.edit', compact('event'));

    }

    public function updateEvent(Request $request, $id)
    {
          // Validate the request
          $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'status' => 'required|string|max:50',
    //        'organizer_id' => 'required|exists:organizers,organizer_id',
        ]);

        // Find the event by ID
        $event = Events::findOrFail($id);

        // Update the event
        $event->update($validated);

        // Redirect with success message
        return redirect()->route('organizer.events.index')->with('success', 'Event updated successfully');
    }

    public function destroy($id)
    {
        $event = Events::findOrFail($id);
        $event->delete();

        // Redirect with success message
        return redirect()->route('organizer.events.index')->with('success', 'Event deleted successfully');
    }

    public function indexUsers()
    {
        $users = User::all();
        return view('organizer.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('organizer.users.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('organizer.users.index')->with('success', 'User created successfully.');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('organizer.users.edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
           // 'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $user->update([
            'name' => $request->name,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        return redirect()->route('organizer.users.index')->with('success', 'User updated successfully.');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('organizer.users.index')->with('success', 'User deleted successfully.');
    }


}
