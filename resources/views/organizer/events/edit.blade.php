@extends('layouts/dashboard-organizer')

@section('dashboard-return')
    <a class="navbar-brand ms-3" href="{{ route('organizer.dashboard') }}">Event Management System</a>
@endsection
@section('title')
    <title>Organizer Dashboard</title>
@endsection

@section('role')
    {{-- <p class="text-center fw-bold">Organizer</p> --}}
    <p class="text-center font-bold text-gray-700">{{ Auth::user()->name }} </p>
@endsection

@section('side-bar-ul')
@endsection


@section('content-dashboard')
    <h1 class="text-2xl font-bold py-2 ">Edit Event</h1>

    <form action="{{ route('organizer.events.update', $event->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Event Name -->
        <div class="form-group">
            <label for="name">Event Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $event->name) }}"
                required>
        </div>

        <!-- Event Location -->
        <div class="form-group">
            <label for="location">Event Location</label>
            <input type="text" name="location" id="location" class="form-control"
                value="{{ old('location', $event->location) }}" required>
        </div>

        <!-- Event Date -->
        <div class="form-group">
            <label for="event_date">Event Date</label>
            <input type="date" name="date" id="date" class="form-control" value="{{ old('date', $event->date) }}"
                required>
            @error('date')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="regular_seats">Regular Seats</label>
            <input type="number" name="regular_seats" id="regular_seats" class="form-control"
                value="{{ old('regular_seats', $event->regular_seats) }}" required>
            @error('regular_seats')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="vip_seats">VIP Seats</label>
            <input type="number" name="vip_seats" id="vip_seats" class="form-control"
                value="{{ old('vip_seats', $event->vip_seats) }}" required>
            @error('vip_seats')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="vvip_seats">VVIP Seats</label>
            <input type="number" name="vvip_seats" id="vvip_seats" class="form-control"
                value="{{ old('vvip_seats', $event->vvip_seats) }}" required>
            @error('vvip_seats')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <!-- Hidden input for total seats -->
        <input type="hidden" name="total_seats" id="total_seats">
        <!-- Status (Hidden for Organizer) -->
        <input type="hidden" name="status" value="{{ $event->status }}">
        <div class="form-group mt-3">
            <button type="submit" class="btn btn-warning">Update Event</button>
            <a href="{{ route('organizer.events.index') }}" class="btn btn-primary">Cancel</a>
        </div>
    </form>

    <script>
        // JavaScript to calculate total seats
        document.getElementById('regular_seats').addEventListener('input', updateTotalSeats);
        document.getElementById('vip_seats').addEventListener('input', updateTotalSeats);
        document.getElementById('vvip_seats').addEventListener('input', updateTotalSeats);


        function updateTotalSeats() {
            let regularSeats = parseInt(document.getElementById('vip_seats').value) || 0;
            let vipSeats = parseInt(document.getElementById('vip_seats').value) || 0;
            let vvipSeats = parseInt(document.getElementById('vvip_seats').value) || 0;
            let totalSeats = vipSeats + vipSeats + vvipSeats;
            document.getElementById('total_seats').value = totalSeats;
        }
    </script>
@endsection
