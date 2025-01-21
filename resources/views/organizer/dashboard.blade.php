@extends('layouts.dashboard-organizer')

@section('dashboard-return')
    <a class="navbar-brand ms-3" href="{{ route('admin.dashboard') }}">Event Management System</a>
@endsection

@section('title')
    <title>Organizer Dashboard</title>
@endsection

@section('role')
<p class="text-center font-bold text-gray-700">{{ Auth::user()->name }} </p>
@endsection

@section('side-bar-ul')
    <!-- You can add sidebar links here if needed -->
@endsection

@section('content-dashboard')
    <!-- Dashboard Content -->
    <div class="container py-6">
        <h3 class="mb-6 text-2xl font-semibold">Organizer Dashboard</h3>

        <!-- Success, Info, and Error Messages -->
        @if(session('success'))
            <div class="alert alert-success mb-4 p-4 bg-green-100 text-green-800 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info mb-4 p-4 bg-blue-100 text-blue-800 rounded-lg">
                {{ session('info') }}
            </div>
        @endif

        @if(session('errors'))
            <div class="alert alert-danger mb-4 p-4 bg-red-100 text-red-800 rounded-lg">
                {{ session('errors') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Manage Users Card -->
            <div class="card text-white bg-blue-600 rounded-lg shadow-lg">
                <div class="card-body p-6">
                    <h5 class="text-xl font-semibold">Manage Profile</h5>
                    <p class="text-sm mt-2">Go to your profile page</p>
                    <a href="{{ route('organizer.profile') }}" class="mt-4 inline-block px-4 py-2 bg-white text-blue-600 rounded-lg">Manage Profile</a>
                </div>
            </div>

            <!-- Manage Organizers Card -->
            <div class="card text-white bg-yellow-500 rounded-lg shadow-lg">
                <div class="card-body p-6">
                    <h5 class="text-xl font-semibold">Total Attendees : {{ $attendees->count() }}</h5>
                    <p class="text-sm mt-2">Go to Attendees</p>
                    <a href="{{route('organizer.attendees.index',['user_id' => Auth::user()->id])}}" class="mt-4 inline-block px-4 py-2 bg-white text-yellow-600 rounded-lg">Manage Attendees</a>
                </div>
            </div>

            <!-- Manage Events Card -->
            <div class="card text-white bg-green-600 rounded-lg shadow-lg">
                <div class="card-body p-6">
                    <h5 class="text-xl font-semibold">Total Event: {{ $events->count() }}</h5>
                    <p class="text-sm mt-2">Go to Events</p>
                    <a href="{{ route('organizer.events.index') }}" class="mt-4 inline-block px-4 py-2 bg-white text-green-600 rounded-lg">Manage Events</a>
                </div>
            </div>
        </div>
    </div>
@endsection
