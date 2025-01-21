@extends('layouts/dashboard-organizer')

@section('dashboard-return')
<a class="navbar-brand ms-3" href="{{route('organizer.dashboard')}}">Event Management System</a>

@endsection
@section('title')
    <title>Organizer Profile</title>
@endsection
@section('role')
<p class="text-center font-bold text-gray-700">{{ Auth::user()->name }} </p>

@endsection

@section('side-bar-ul')
@endsection

@section('content-dashboard')


    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('organizer.profile.update') }}" method="POST" class="max-w-2xl mx-auto p-4 bg-white rounded-lg shadow-md">
        @csrf

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" class="form-control mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" id="name" name="name" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" class="form-control mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" id="email" name="email" value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" class="form-control mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" id="password" name="password" placeholder="Leave empty if not changing">
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
            <input type="password" class="form-control mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" id="password_confirmation" name="password_confirmation">
        </div>

        <button type="submit" class="btn btn-warning">Update Profile</button>
    </form>
@endsection
