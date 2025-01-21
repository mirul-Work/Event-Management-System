@extends('layouts/dashboard-organizer')

@section('dashboard-return')
    <a class="navbar-brand ms-3 " href="{{ route('organizer.dashboard') }}">Event Management
        System</a>
@endsection

@section('title')
    <title>Organizer Dashboard</title>

@endsection

@section('role')
    <p class="text-center font-bold text-gray-700">{{ Auth::user()->name }} </p>
@endsection

@section('side-bar-ul')
    <!-- Sidebar content for organizer -->
@endsection

@section('content-dashboard')
    <h1 class="text-2xl font-bold mb-4 text-gray-800">Total Events: {{ $events->total() }}</h1>
    <a href="{{ route('organizer.events.create') }}" class="btn btn-sm btn-warning">New
        Event</a>

    {{-- Total Attendees Count --}}


    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Add New Event Button and Search Form -->
    <div class="flex flex-wrap items-center justify-between mb-4">


        <!-- Search Form -->
        <form class="d-flex mt-3 md:mt-0" method="GET" action="{{ route('organizer.events.index') }}">
            <input class="form-control me-2" type="search" name="search" placeholder="Search events..."
                aria-label="Search" value="{{ request('search') }}">
            <button class="btn btn-outline-secondary" type="submit">Search</button>
        </form>
    </div>

    <!-- Events Table -->
    @if ($events->isEmpty())
        <p class="text-gray-500 italic">No events found. Create your first event!</p>
    @else
        @php
            $totalSeats = $events->sum('total_seats');
        @endphp

        <!-- Responsive Table -->
        <div class="overflow-auto bg-white shadow-md rounded-lg ">

            <table id="attendeesTable" class="table-auto min-w-full border-collapse border border-gray-200">
                <thead class="bg-gray-100 text-gray-800">
                    <tr class="border border-gray-300">
                        <th class="px-4 py-2">#</th>
                        <th class="px-4 py-2">Name</th>
                        <th class="min-w-40 px-4 py-2">Date</th>
                        <th class="px-4 py-2">Location</th>
                        <th class="px-4 py-2">Regular Seat</th>
                        <th class="px-4 py-2">VIP Seat</th>
                        <th class="px-4 py-2">VVIP Seat</th>
                        <th class="px-4 py-2">Total Available Seat</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($events as $index => $event)
                        <tr class="border border-gray-300">
                            <td class="px-4 py-2">{{ $index + 1 }}</td>
                            <td class="px-4 py-2">{{ $event->name }}</td>
                            <td class="px-4 py-2">{{ $event->date }}</td>
                            <td class="px-4 py-2">{{ $event->location }}</td>
                            <td class="px-4 py-2">{{ $event->regular_seat_available }} / {{ $event->regular_seats }}</td>
                            <td class="px-4 py-2">{{ $event->vip_seat_available }} / {{ $event->vip_seats }}</td>
                            <td class="px-4 py-2">{{ $event->vvip_seat_available }}/ {{ $event->vvip_seats }}</td>
                            <td class="px-4 py-2">{{ $event->available_seats }} / {{ $event->total_seats }}</td>
                            <td class="py-2 px-4">
                                <span
                                    class="px-2 py-1 text-md font-bold
                                {{ $event->status === 'approved' ? 'text-green-500' : 'text-red-500' }}">
                                    {{ ucfirst($event->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-2 space-x-1">


                                <div class="dropdown">
                                    <button
                                        class="text-amber-500 dropdown-toggle"
                                        type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="bi bi-plus-circle-fill"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <!-- Delete event form -->
                                        <li>
                                            <form action="{{ route('organizer.events.destroy', $event->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 dropdown-item"
                                                        onclick="return confirm('Are you sure you want to delete this event?')">
                                                    <i class="bi bi-trash-fill"></i> Delete
                                                </button>
                                            </form>
                                        </li>

                                        <!-- Edit event link -->
                                        <li>
                                            <a class="text-amber-500 dropdown-item" href="{{ route('organizer.events.edit', $event->id) }}">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </a>
                                        </li>

                                        <!-- Create attendee link -->
                                        <li>
                                            <a class="text-blue-500 dropdown-item" href="{{ route('organizer.attendees.create', $event->id) }}">
                                                <i class="bi bi-person-plus-fill"></i> Create Attendee
                                            </a>
                                        </li>

                                        <!-- List attendees link -->
                                        <li>
                                            <a class="text-amber-500 dropdown-item" href="{{ route('organizer.attendees.showEventAttendees', $event->id) }}">
                                                <i class="bi bi-person-lines-fill"></i> List Attendees
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>

                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
        <div class="mt-4">
            {{-- {{ $events->links() }} --}}
            {{ $events->appends(request()->query())->links() }}

        </div>
    @endif
@endsection
