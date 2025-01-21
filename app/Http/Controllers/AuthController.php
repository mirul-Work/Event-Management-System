<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\UserAuthVerifyRequest;

class AuthController extends Controller
{

    //register
    public function indexRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'nullable|string|max:15', // Validate phone number as nullable, with a max length            'password' => 'required|string|min:8|confirmed', // Ensure password confirmation
            'role' => 'required|in:admin,organizer,attende', // Ensure valid role
        ]);

        // Create a new user with the provided data
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password), // Encrypt the password
            'role' => $request->role, // Set the user role
        ]);

        // Log the user in immediately after registration
        Auth::guard($request->role)->login($user);

        // Redirect to the appropriate dashboard based on role
        // if ($user->role === 'admin') {
        //     return redirect('/admin/dashboard');
        // } elseif ($user->role === 'organizer') {
        return redirect('/organizer/dashboard');
        // } else {
        //     return redirect('/attende/dashboard');
        // }
    }


    //login
    public function index()
    {
        return view('auth.login');
    }


    public function verify(UserAuthVerifyRequest $request)
    {
        // dd($request->validated());
        $data = $request->validated();
        if (Auth::guard('admin')->attempt(['email' => $data['email'], 'password' => $data['password'], 'role' => 'admin'])) {
            $request->session()->regenerate();
            return redirect()->intended('/admin/dashboard')->with('success', 'Logged in as admin');
        } else if (Auth::guard('organizer')->attempt(['email' => $data['email'], 'password' => $data['password'], 'role' => 'organizer'])) {
            $request->session()->regenerate();
            return redirect()->intended('/organizer/dashboard')->with('success', 'Logged in as organizer');
        } else if (Auth::guard('attende')->attempt(['email' => $data['email'], 'password' => $data['password'], 'role' => 'attende'])) {
            $request->session()->regenerate();
            return redirect()->intended('/attende/dashboard')->with('success', 'Logged in as attendee');
        } else {
            return redirect(route('login'))->with('errors', 'Invalid Credential');
        }
    }

    public function logout()
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        } else if (Auth::guard('organizer')->check()) {
            Auth::guard('organizer')->logout();
        } else if (Auth::guard('attende')->check()) {
            Auth::guard('attende')->logout();
        }
        return redirect(route('login'))->with('info', 'Logged Out');
    }

    //forgot

    public function indexReset()
    {
        return view('auth.reset-email');  // create this view
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }
    //method POST
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
            'token' => 'required',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill(['password' => bcrypt($password)])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    public function showResetPasswordForm($token)
    {
        return view('auth.reset', ['token' => $token]);
    }
}
