@extends('layouts/dashboard-admin')

@section('dashboard-return')
<a class="navbar-brand ms-3" href="{{route('admin.dashboard')}}">Event Management System</a>

@endsection
@section('title')
    <title>Admin Dashboard</title>
@endsection


@section('role')
<p class="text-center fw-bold">{{ Auth::user()->name }} (Admin)</p>
@endsection
@section('side-bar-ul')


@endsection


@section('content-dashboard')
<h1>Edit Event</h1>

<form action="{{ route('admin.events.update', $event->event_id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $event->name) }}" required>
        @error('name')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="date">Date:</label>
        <input type="date" name="date" id="date" class="form-control" value="{{ old('date', $event->date) }}" required>
        @error('date')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="location">Location:</label>
        <input type="location" name="location" id="location" class="form-control" value="{{ old('location', $event->location) }}">
        @error('location')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group">
        <label for="status">Status:</label>
        <input type="status" name="status" id="status" class="form-control" value="{{ old('status', $event->status) }}">
        @error('status')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>


    <button type="submit" class="btn btn-success">Create Event</button>
    <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">Cancel</a>
</form>
</form>
@endsection
