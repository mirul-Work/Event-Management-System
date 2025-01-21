@extends('layouts.half-navigation')

@section('title', 'Event Management System')
@section('child-title', 'Event Management System')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg p-4" style="max-width: 400px; width: 100%;">
        <h3 class="text-center mb-4">Register</h3>
        <form action="{{ route('auth.register') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="phone_number" class="form-label">Phone number (optional)</label>
                <input type="text" class="form-control" id="phone_number" name="phone_number">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
            </div>

            <!-- The role field is now hidden and set to 'organizer' -->
            <input type="hidden" id="role" name="role" value="organizer">

            <!-- Submit button -->
            <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>

        <div class="text-center mt-3">
            <a href="{{ route('auth.verify') }}" class="text-decoration-none">Already have an account?</a>
        </div>
    </div>
</div>
@endsection

@section('footer')
@endsection
