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
    @if ($events->total() === 0)
        <div class="alert alert-info">No Event Found.{{ session('info') }}</div>
    @else
        <!-- Search Form -->
        <div class="mb-3">
            <form method="GET" action="{{ route('admin.events.index') }}">
                <input type="text" name="search" class="form-control"
                    placeholder="Search by Event Name or Organizer Name" value="{{ request('search') }}" />
            </form>
        </div>

        <div class="overflow-auto bg-white shadow-md rounded-lg ">

            <table class="table-auto min-w-full border-collapse border border-gray-200">
                <thead class="bg-gray-100 text-gray-800">
                    <tr class="border border-gray-300">
                        <th class="px-4 py-2">Event Name</th>
                        <th class="px-4 py-2">Organizer Name</th>
                        <th class="px-4 py-2">Date</th>
                        <th class="px-4 py-2">Location</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($events as $event)
                        <tr>
                            <td class="px-4 py-2">{{ $event->name }}</td>
                            <td class="px-4 py-2">{{ $event->organizer->name }}</td>
                            <td class="px-4 py-2">{{ $event->date }}</td>
                            <td class="px-4 py-2">{{ $event->location }}</td>
                            <td class="py-2 px-4">
                                <span
                                    class="px-2 py-1 text-md font-bold
                                {{ $event->status === 'approved' ? 'text-green-500' : 'text-red-500' }}">
                                    {{ ucfirst($event->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-2">
                                @if ($event->status !== 'approved')
                                    <form action="{{ route('admin.events.approve', $event->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.events.disapprove', $event->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm">Disapprove</button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this event?')">Delete</button>
                                </form>
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
