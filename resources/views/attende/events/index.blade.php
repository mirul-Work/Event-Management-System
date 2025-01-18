@extends('layouts/dashboard-organizer')

@section('dashboard-return')
<a class="navbar-brand ms-3" href="{{route('organizer.dashboard')}}">Event Management System</a>

@endsection
@section('title')
<title>Manage Event</title>
@endsection


@section('role')
<p class="text-center fw-bold">Organizer</p>
@endsection
@section('side-bar-ul')

@endsection


@section('content-dashboard')

<h1>Manage Event</h1>
<a href="{{ route('organizer.events.create') }}" class="btn btn-primary">Add New Event</a>

<table class="table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Date</th>
            <th>Location</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($events as $event)
        <tr>
            <td>{{ $event->name }}</td>
            <td>{{ $event->date }}</td>
            <td>{{ $event->location }}</td>
            <td>{{ $event->status }}</td>
            <td>
                <a href="{{ route('organizer.events.edit', $event->event_id) }}" class="btn btn-warning">Edit</a>
                <form action="{{ route('organizer.events.delete', $event->event_id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
