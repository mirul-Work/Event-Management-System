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

@section('content-dashboard')

    <h1 class="text-2xl font-bold">Total Attendees: {{ $attendees->total() }}</h1>
    <h2 class="text-2xl font-bold text-gray-500">Pending : {{ $attendees_pending->total() }}</h2>
    <h2 class="text-2xl font-bold text-green-500">Accepted : {{ $attendees_accepted->total() }}</h2>
    <h2 class="text-2xl font-bold text-red-500">Rejected : {{ $attendees_rejected->total() }}</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if (session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif

    @if ($attendees->isEmpty())
        <div class="alert alert-info">No Attendee Found</div>
    @else
        <div class="text-right py-4">
            <form action="{{ route('organizer.attendees.sendEmailAll', $attendees->first()->event->id) }}" method="POST"
                class="inline-block">
                @csrf
                <button type="submit" onclick="return confirm('Are you sure you want to send all email?')"
                    class="btn btn-warning">Send All Emails</button>
            </form>
            <a href="{{ route('organizer.attendees.import.form', $events->id) }}" type="submit"
                class="btn btn-success">Import Attendee</a>
            <a href="{{ route('organizer.attendees.create',$events->id) }}" type="submit"
                class="btn btn-danger">Create Attendee</a>
        </div>

        <div class="overflow-auto bg-white shadow-md rounded-lg">
            <table id="attendeesTable" class="min-w-full border-collapse border border-gray-200">
                <thead class="bg-gray-100 text-gray-800">
                    <tr class="border border-gray-300">
                        {{-- <th class="py-2 px-4 text-left">#</th> --}}
                        <th class="py-2 px-4 text-left">Event Name</th>
                        <th class="py-2 px-4 text-left">Attendee ID</th>
                        <th class="py-2 px-4 text-left">Name</th>
                        <th class="py-2 px-4 text-left">Email</th>
                        <th class="py-2 px-4 text-left">Phone Number</th>
                        <th class="py-2 px-4 text-left">Seat Type</th>
                        <th class="py-2 px-4 text-left">RSVP Link</th>
                        <th class="py-2 px-4 text-left">Status</th>
                        <th class="py-2 px-4 text-left">Email Sent</th>
                        <th class="py-2 px-4 text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($attendees as $attendee)
                        <tr class="border border-gray-300">
                            <td class="py-2 px-4">{{ $attendee->event->name }}</td>
                            <td class="py-2 px-4">{{ $attendee->id }}</td>
                            <td class="py-2 px-4">{{ $attendee->name }}</td>
                            <td class="py-2 px-4">{{ $attendee->email }}</td>
                            <td class="py-2 px-4">{{ $attendee->phone_number }}</td>
                            <td class="py-2 px-4 text-amber-500">{{ ucfirst($attendee->seat_category) }}</td>
                            <td class="py-2 px-4">
                                <a class="text-blue-600 hover:underline"
                                    href="{{ url('/rsvp/' . $attendee->seat_category . '/' . $attendee->token) }}">
                                    {{ url('/rsvp/' . $attendee->seat_category . '/' . $attendee->token) }}
                                </a>
                            </td>
                            <td class="py-2 px-4">
                                <span
                                    class="px-2 py-1 text-md font-bold {{ $attendee->status === 'accepted' ? 'text-green-500' : 'text-red-500' }}">
                                    {{ ucfirst($attendee->status) }}
                                </span>
                            </td>
                            <td class="py-2 px-4">
                                <span
                                    class="px-2 py-1 text-md font-bold {{ $attendee->email_sent ? 'text-green-500' : 'text-red-500' }}">
                                    {{ $attendee->email_sent ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td class="py-2 px-4">
                                <div class="flex gap-2">
                                    <div class="dropdown">
                                        <button class="text-amber-500 dropdown-toggle" type="button"
                                            id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-plus-circle-fill"></i>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                            <li>
                                                <form
                                                    action="{{ route('organizer.attendees.sendEmail', ['event' => $attendee->event->id, 'attendee' => $attendee->id]) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-blue-500 dropdown-item"
                                                        onclick="return confirm('Are you sure you want to send this email?')">
                                                        <i class="bi bi-envelope-check-fill"></i> Send Email
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <form action="{{ route('organizer.attendees.destroy', $attendee->id) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 dropdown-item"
                                                        onclick="return confirm('Are you sure you want to delete this attendee?')">
                                                        <i class="bi bi-trash-fill"></i> Delete
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
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

    <!-- DataTables initialization script -->
    <script>
        if (document.getElementById("attendeesTable") && typeof simpleDatatables.DataTable !== 'undefined') {
            const dataTable = new simpleDatatables.DataTable("#attendeesTable", {
                searchable: true,
                sortable: true,
                paging: true,
                perPage: 5,
                perPageSelect: [5, 10, 15, 20, 25],
            });
        }
    </script>
@endsection
