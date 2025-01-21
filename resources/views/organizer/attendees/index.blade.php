@extends('layouts/dashboard-organizer')

@section('title')
    <title>Organizer Dashboard</title>
@endsection

@section('dashboard-return')
    <a class="navbar-brand ms-3" href="{{ route('organizer.dashboard') }}">Event Management System</a>
@endsection

@section('role')
    <p class="text-center font-bold text-gray-700">{{ Auth::user()->name }} </p>
@endsection

@section('side-bar-ul')
@endsection

@section('content-dashboard')
    <h1 class="text-2xl font-bold text-gray-700">Total Attendees: {{ $attendees->total() }}</h1>

    @if ($attendees->isEmpty())
        <div class="alert alert-info">No Attendee Found{{ session('info') }}</div>
    @else
        <!-- Add New Event Button and Search Form -->
        <div class="flex flex-wrap items-center justify-between mb-4">
            <!-- Search Form -->
            <form class="d-flex mt-3 md:mt-0" method="GET" action="{{ route('organizer.attendees.search') }}">
                <input class="form-control me-2" type="search" name="search" placeholder="Search Attendees..."
                    aria-label="Search" value="{{ request('search') }}">
                <button class="btn btn-outline-secondary" type="submit">Search</button>
            </form>
        </div>

        <div class="overflow-auto bg-white shadow-md rounded-lg">
            <table id="attendeesTable" class="table-auto min-w-full border-collapse border border-gray-200">
                <thead class="bg-gray-100 text-gray-800">
                    <tr class="border border-gray-300">
                        <th class="py-2 px-4 text-left">Event Name</th>
                        <th class="py-2 px-4 text-left">Attendee ID</th>
                        <th class="py-2 px-4 text-left">Name</th>
                        <th class="py-2 px-4 text-left">Email</th>
                        <th class="py-2 px-4 text-left">Phone Number</th>
                        <th class="py-2 px-4 text-left">Seat Type</th>
                        <th class="py-2 px-4 text-left">RSVP Link</th>
                        <th class="py-2 px-4 text-left">Status</th>
                        <th class="py-2 px-4 text-left">Email Sent</th>
                    </tr>
                </thead>
                <tbody id="attendeesBody" class="divide-y divide-gray-200">
                    @foreach ($attendees as $attendee)
                        <tr class="border border-gray-300">
                            <td class="py-2 px-4">{{ $attendee->event->name }}</td>
                            <td class="py-2 px-4">{{ $attendee->id }}</td>
                            <td class="py-2 px-4">{{ $attendee->name }}</td>
                            <td class="py-2 px-4">{{ $attendee->email }}</td>
                            <td class="py-2 px-4">{{ $attendee->phone_number }}</td>
                            <td class="py-2 px-4 text-amber-500">{{ strtoupper($attendee->seat_category) }}</td>
                            <td class="py-2 px-4">
                                <a href="{{ url('/rsvp/' . $attendee->seat_category . '/' . $attendee->token) }}"
                                    class="text-blue-600 hover:underline">
                                    {{ url('/rsvp/' . $attendee->seat_category . '/' . $attendee->token) }}
                                </a>
                            </td>
                            <td class="py-2 px-4">
                                <span
                                    class="px-2 py-1 text-md font-bold  {{ $attendee->status === 'accepted' ? 'text-green-500' : 'text-red-500' }}">
                                    {{ ucfirst($attendee->status) }}
                                </span>
                            </td>
                            <td class="py-2 px-4">
                                <span
                                    class="px-2 py-1 text-md font-bold  {{ $attendee->email_sent ? 'text-green-500' : 'text-red-500' }}">
                                    {{ $attendee->email_sent ? 'Yes' : 'No' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $attendees->links() }}
        </div>
    @endif
@endsection
