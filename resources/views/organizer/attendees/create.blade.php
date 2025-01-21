@extends('layouts/dashboard-organizer')

@section('dashboard-return')
<a class="navbar-brand ms-3" href="{{route('organizer.dashboard')}}">Event Management System</a>

@endsection
@section('title')
    <title>Organizer Dashboard</title>
@endsection

@section('role')
<p class="text-center font-bold text-gray-700">{{ Auth::user()->name }} </p>


@endsection

@section('side-bar-ul')


@endsection


@section('content-dashboard')

<h1 class="text-2xl font-bold py-2 ">Add Attendee for {{ $event->name }}</h1>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if (session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif
<form action="{{ route('organizer.attendees.store',$event->id) }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="name">Attendee Name</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>

    <div class="form-group">
        <label for="email">Attendee Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="form-group">
        <label for="phone_number">Attendee Phone Number:</label>
        <input type="phone_number" name="phone_number" id="phone_number" class="form-control">
    </div>

    <div class="form-group">
        <label for="seat_category">Seat Category</label>
        <select class="form-control" id="seat_category" name="seat_category" required>
            <option value="regular">Regular</option>
            <option value="vip">VIP</option>
            <option value="vvip">VVIP</option>
        </select>
    </div>
    <div class="form-group mt-3">

    {{-- <input type="hidden" name="user_id" value="{{ auth()->id() }}"> --}}
    <button type="submit" class="btn btn-warning">Add Attendee</button>
    <a href="{{ route('organizer.events.index') }}" class="btn btn-primary">Cancel</a>
</form>
</div>

</div>
@endsection
