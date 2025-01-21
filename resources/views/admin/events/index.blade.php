@extends('layouts/dashboard-admin')

@section('dashboard-return')
    <a class="navbar-brand ms-3" href="{{ route('admin.dashboard') }}">Event Management System</a>
@endsection

@section('title')
    <title>Admin Dashboard</title>
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

    @if (session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if ($events->isEmpty())
        <div class="alert alert-info">No Event Found{{ session('info') }}</div>
    @else
        <!-- Table Search Form -->
        <div class="mb-3">
            <form method="GET" action="{{ route('admin.events.index') }}">
                <input type="text" name="search" class="form-control" placeholder="Search by Event Name or Location"
                    value="{{ request('search') }}" />
            </form>
        </div>

        <!-- Table Container -->
        <div class="overflow-auto bg-white shadow-md rounded-lg">
            <table class="table-auto min-w-full border-collapse border border-gray-200">
                <thead class="bg-gray-100 text-gray-800">
                    <tr class="border border-gray-300">
                        <th class="py-2 px-4 text-left">#</th>
                        <th class="py-2 px-4 text-left">Event Name</th>
                        <th class="py-2 px-4 text-left">Organizer Name</th>
                        <th class="py-2 px-4 text-left">Date</th>
                        <th class="py-2 px-4 text-left">Location</th>
                        <th class="py-2 px-4 text-left">Regular Seat</th>
                        <th class="py-2 px-4 text-left">Vip Seat</th>
                        <th class="py-2 px-4 text-left">Vvip Seat</th>
                        <th class="py-2 px-4 text-left">Total Seat</th>
                        <th class="py-2 px-4 text-left">Location</th>
                        <th class="py-2 px-4 text-left">Status</th>
                        <th class="py-2 px-4 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($events as $index => $event)
                        <tr>
                            <td class="py-2 px-4">{{ $index + 1 }}</td>
                            <td class="py-2 px-4">{{ $event->name }}</td>
                            <td class="py-2 px-4">{{ $event->organizer->name }}</td>
                            <td class="py-2 px-4">{{ $event->date }}</td>
                            <td class="py-2 px-4">{{ $event->location }}</td>
                            <td class="px-4 py-2">{{ $event->regular_seat_available }} / {{ $event->regular_seats }}</td>
                            <td class="px-4 py-2">{{ $event->vip_seat_available }} / {{ $event->vip_seats }}</td>
                            <td class="px-4 py-2">{{ $event->vvip_seat_available }}/ {{ $event->vvip_seats }}</td>
                            <td class="px-4 py-2">{{ $event->available_seats }} / {{ $event->total_seats }}</td>

                            <td class="py-2 px-4">{{ $event->location }}</td>
                            <td class="py-2 px-4"> <span
                                    class="px-2 py-1 text-md font-bold
                                {{ $event->status === 'approved' ? 'text-green-500' : 'text-red-500' }}">
                                    {{ ucfirst($event->status) }}
                                </span></td>
                                <td class="py-2 px-4">
                                    <div class="d-flex">
                                        @if ($event->status !== 'approved')
                                            <form action="{{ route('admin.events.approve', $event->id) }}" method="POST" class="me-2">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.events.disapprove', $event->id) }}" method="POST" class="me-2">
                                                @csrf
                                                <button type="submit" class="btn btn-warning btn-sm">Disapprove</button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure you want to delete this event?')">Delete</button>
                                        </form>
                                    </div>
                                </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $events->links() }}
        </div>
    @endif
@endsection
