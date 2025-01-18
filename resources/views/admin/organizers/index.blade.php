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

    @if ($organizers->isEmpty())
        <div class="alert alert-info">No organizer found.</div>
    @else
        <!-- Search Form -->
        <div class="mb-3">
            <form method="GET" action="{{ route('admin.organizers.index') }}">
                <input type="text" name="search" class="form-control" placeholder="Search by Name or Email"
                    value="{{ request('search') }}" />
            </form>
        </div>

        <!-- Table Container -->
        <div class="overflow-auto bg-white shadow-md rounded-lg">
                <table  class="table-auto min-w-full border-collapse border border-gray-200">
                    <thead class="bg-gray-100 text-gray-800">
                        <tr class="border border-gray-300">
                            <th class="py-2 px-4 text-left">User ID</th>
                            <th class="py-2 px-4 text-left">Name</th>
                            <th class="py-2 px-4 text-left">Email</th>
                            <th class="py-2 px-4 text-left">Total Events</th>
                            <th class="py-2 px-4 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody  class="divide-y divide-gray-200">
                        @foreach ($organizers as $organizer)
                            <tr>
                                <td class="py-2 px-4">{{ $organizer->id }}</td>
                                <td class="py-2 px-4">{{ $organizer->name }}</td>
                                <td class="py-2 px-4">{{ $organizer->email }}</td>
                                <td class="py-2 px-4">{{ $organizer->events_count }}</td>
                                <td>
                                    <a href="{{ route('admin.organizers.show-events', $organizer->id) }}"
                                        class="py-2 px-4 text-amber-500"><i class="bi bi-eye-fill"></i>  Events</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    <div class="mt-4">
        {{ $organizers->links() }}
    </div>
    @endif
@endsection
