<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Events;
use App\Models\Organizer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{

    public function dashboard()
     {
         // Pass any required data to the view
         return view('admin/dashboard', [
             'title' => 'Admin Dashboard', // Example data
         ]);
     }


    public function indexUsers()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
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

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
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

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }




    //Admin.event
    public function indexEvents()
    {
        $events = Events::all();
        return view('admin.events.index', compact('events'));
    }

    public function createEvent()
    {
        return view('admin.events.create');
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
    return redirect()->route('events.index')->with('success', 'Event created successfully');
    }

    public function editEvent($id)
    {
        $event = Events::findOrFail($id); // Find the event by its ID
        return view('admin.events.edit', compact('event'));

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
        return redirect()->route('admin.events.index')->with('success', 'Event updated successfully');
    }

    public function destroy($id)
    {
        $event = Events::findOrFail($id);
        $event->delete();

        // Redirect with success message
        return redirect()->route('events.index')->with('success', 'Event deleted successfully');
    }



        //Admin.Organizer
        public function indexOrganizers()
        {
            $organizers = Organizer::all();
            //$organizers = Organizer::where('organizer_id', 3)->get();

            return view('admin.organizers.index', compact('organizers'));
        }

        public function createOrganizer()
        {
            return view('admin.organizers.create');
        }

        public function storeOrganizer(Request $request)
        {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:8',
            ]);

            Organizer::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return redirect()->route('admin.organizers.index')->with('success', 'Organizer created successfully.');
        }

        public function editOrganizer($id)
        {
            $organizer = Organizer::findOrFail($id);
            return view('admin.organizers.edit', compact('organizer'));
        }

        public function updateOrganizer(Request $request, $id)
        {
            $organizer = Organizer::findOrFail($id);
            $request->validate([
                'name' => 'required|string|max:255',
                //'email' => 'required|email|unique:users,email,' . $id,
            ]);

            $organizer->update([
                'name' => $request->name,
                'password' => $request->password ? Hash::make($request->password) : $organizer->password,
            ]);

            return redirect()->route('admin.organizers.index')->with('success', 'Organizer updated successfully.');
        }

        public function deleteOrganizer($id)
        {
            $organizer = Organizer::findOrFail($id);
            $organizer->delete();

            return redirect()->route('admin.organizers.index')->with('success', 'Organizer deleted successfully.');
        }








   // public function dashboard()
   // {
   //     // Pass any required data to the view
   //     return view('admin/dashboard', [
   //         'title' => 'Admin Dashboard', // Example data
   //     ]);
   // }
//
   // public function DisplayAdmin()
   // {
   //     $data = Admin::all();
   //     return view('admin/display_admin')->with('data', $data);
   // }
//
   // public function detail($id)
   // {
//
   //     // $data = Event::where('id',$id)->first();
//
   //     if ($data = User::where('id', $id)->first()) {
   //         return view('admin/detail-users')->with('data', $data);
   //     } else if ($data = Organizer::where('organizer_id', $id)->first()) {
   //         return view('admin/detail-organizers')->with('data', $data);
   //     } else if ($data = Event::where('event_id', $id)->first()) {
   //         return view('admin/detail-events')->with('data', $data);
   //     } else {
   //         return 'Failed';
   //     }
//
//
   //     //$data = User::all();
   //     //return "<h1>Saya $id</h1>";
   // }
//
   // public function ShowRegister()
   // {
   //     // Pass any required data to the view
   //     return view('admin/register');
   // }
//
   // public function ShowLogin()
   // {
   //     // Pass any required data to the view
   //     return view('admin/login');
   // }
//
//
//
//
//
   // //register admin
   // function create_error(Request $request)
   // {
   //     Session::flash('name', $request->name);
   //     Session::flash('email', $request->email);
   //     // Validate the input
   //     $validated = $request->validate([
   //         'name' => 'required',
   //         'email' => 'required|email|unique:admins',
   //         'password' => 'required',
   //     ], [
   //         'name.required' => 'Cannot be blank',
   //         'email.required' => 'Cannot be blank',
   //         'email.email' => 'Invalid Format',
   //         'email.unique' => 'Already in use',
   //         'password.required' => 'Cannot be blank',
   //     ]);
//
   //     // Check if the admin exists
   //     $admin = Admin::where('email', $request->email)->first();
//
   //     $data = [
   //         'name' => $request->name,
   //         'email' => $request->email,
   //         'password' => Hash::make($request->password)
   //     ];
//
   //     Admin::create($data);
//
   //     $infologin = [
   //         'email' => $request->email,
   //         'password' => $request->password,
//
   //     ];
//
   //     if (Auth::attempt($infologin)) {
//
   //         // if ($admin && Hash::check($request->password, $admin->password)) {
   //         // Log the user in
   //         //   Auth::login($admin);
//
   //         // Redirect to admin dashboard
   //         return redirect()->route('admin.dashboard');
   //         //->with('success',"Successfully logged in.");
   //     } else {
   //         // If credentials are incorrect, return with an error
   //         return redirect()->route('admin.login.form')->withErrors('Invalid credentials.');
   //     }
   // }
//
   // public function login(Request $request)
   // {
   //     Session::flash('email', $request->email);
   //     // Validate the input
   //     $validated = $request->validate([
   //         'email' => 'required|email',
   //         'password' => 'required',
   //     ]);
//
   //     // Check if the admin exists
   //     $admin = Admin::where('email', $request->email)->first();
//
   //     if ($admin && Hash::check($request->password, $admin->password)) {
   //         // Log the user in
   //         Auth::login($admin);
//
   //         // Redirect to admin dashboard
   //         return redirect()->route('admin.dashboard');
   //         //->with('success',"Successfully logged in.");
   //     } else {
   //         // If credentials are incorrect, return with an error
   //         return redirect()->route('admin.login.form')->withErrors('Invalid credentials.');
   //     }
   // }
//
   // function logout()
   // {
   //     Auth::logout();
   //     return redirect('/')->with('success', 'Logged Out');
   // }
//
   // public function AdminManageUsers()
   // {
//
//
   //     $data = User::orderBy('name', 'desc')->paginate(1);
   //     return view('admin/manage-users')->with('data', $data);
   // }
//
   // public function AdminManageOrganizers()
   // {
//
   //     $data = Organizer::orderBy('name', 'desc')->paginate(5);
   //     return view('admin/manage-organizers')->with('data', $data);
   // }
//
   // public function AdminManageEvents()
   // {
//
   //     $data = Event::orderBy('name', 'desc')->paginate(1);
   //     return view('admin/manage-events')->with('data', $data);
   // }
//
//
//
//
//
//
//
//
//
//
   // /**
   //  * Display a listing of the resource.
   //  */
   // public function index()
   // {
   //     //
   // }
//
   // /**
   //  * Show the form for creating a new resource.
   //  */
   // public function create()
   // {
   // //
   // }
//
   // /**
   //  * Store a newly created resource in storage.
   //  */
   // public function store(Request $request)
   // {
   //     //
   // }
//
   // /**
   //  * Display the specified resource.
   //  */
   // public function show(string $id)
   // {
   //     //
   // }
//
   // /**
   //  * Show the form for editing the specified resource.
   //  */
   // public function edit(string $id)
   // {
   //     //
   // }
//
   // /**
   //  * Update the specified resource in storage.
   //  */
   // public function update(Request $request, string $id)
   // {
   //     //
   // }
//
   // /**
   //  * Remove the specified resource from storage.
   //  */
   // public function destroy(string $id)
   // {
   //     //
   // }
}//
//
