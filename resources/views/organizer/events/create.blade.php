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
    <h1 class="text-2xl font-bold py-2 ">Create New Event</h1>



    <form action="{{ route('organizer.events.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Event Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
            @error('name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="date">Event Date</label>
            <input type="date" name="date" id="date" class="form-control" value="{{ old('date') }}" required>
            @error('date')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="location">Location</label>
            <input type="text" name="location" id="location" class="form-control" value="{{ old('location') }}"
                required>
            @error('location')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <select name="status" id="status" class="form-control" required hidden>
                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            </select>
            @error('status')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="regular_seats">Regular Seats</label>
            <input type="number" name="regular_seats" id="regular_seats" class="form-control" value="{{ old('regular_seats') }}"
                required>
            @error('regular_seats')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="vip_seats">VIP Seats</label>
            <input type="number" name="vip_seats" id="vip_seats" class="form-control" value="{{ old('vip_seats') }}"
                required>
            @error('vip_seats')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="vvip_seats">VVIP Seats</label>
            <input type="number" name="vvip_seats" id="vvip_seats" class="form-control" value="{{ old('vvip_seats') }}"
                required>
            @error('vvip_seats')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <!-- Hidden input for total seats -->
        <input type="hidden" name="total_seats" id="total_seats">

        <!-- Hidden input for organizer_id based on logged-in user -->
        <input type="hidden" name="user_id" value="{{ auth()->id() }}">

        <div class="form-group mt-3">
            <button type="submit" class="btn btn-warning">Create Event</button>
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
