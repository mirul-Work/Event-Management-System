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
    <div class="mb-3">
        {{-- <!-- Search Bar -->
        <div class="float-left">
            <input type="text" class="form-control" id="attendeesSearch" placeholder="Search attendees" oninput="searchAttendees()">
        </div>

        <!-- Button to Send All Emails -->
        <div class="text-right">
            <form action="" method="POST" class="inline-block">
                @csrf
                <button type="submit" class="btn btn-success">Send All Emails</button>
            </form>
        </div> --}}
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
                            <a href="{{ url('/rsvp/' . $attendee->seat_category . '/' . $attendee->token) }}" class="text-blue-600 hover:underline">
                                {{ url('/rsvp/' . $attendee->seat_category . '/' . $attendee->token) }}
                            </a>
                        </td>
                        <td class="py-2 px-4">
                            <span class="px-2 py-1 text-md font-bold  {{ $attendee->status === 'accepted' ? 'text-green-500' : 'text-red-500' }}">
                                {{ ucfirst($attendee->status) }}
                            </span>
                        </td>
                        <td class="py-2 px-4">
                            <span class="px-2 py-1 text-md font-bold  {{ $attendee->email_sent ? 'text-green-500' : 'text-red-500' }}">
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


    <script>
        function searchAttendees() {
            let query = document.getElementById('attendeesSearch').value;

            // Use AJAX to send search query to the controller
            fetch(`/organizer/attendees/search?q=${query}`)
                .then(response => response.json())
                .then(data => {
                    // Clear the current table rows
                    document.getElementById('attendeesBody').innerHTML = '';

                    // Add new rows based on search results
                    data.attendees.forEach(attendee => {
                        let row = document.createElement('tr');

                        row.innerHTML = `
                            <td class="py-2 px-4">${attendee.event_name}</td>
                            <td class="py-2 px-4">${attendee.id}</td>
                            <td class="py-2 px-4">${attendee.name}</td>
                            <td class="py-2 px-4">${attendee.email}</td>
                            <td class="py-2 px-4">${attendee.seat_category}</td>
                            <td class="py-2 px-4">
                                <a href="/rsvp/${attendee.seat_category}/${attendee.token}" class="text-blue-600 hover:underline">${attendee.rsvp_link}</a>
                            </td>
                            <td class="py-2 px-4">
                                <span class="px-2 py-1 text-sm font-semibold rounded ${attendee.status === 'accepted' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'}">
                                    ${attendee.status}
                                </span>
                            </td>
                            <td class="py-2 px-4">
                                <span class="px-2 py-1 text-sm font-semibold rounded ${attendee.email_sent ? 'bg-green-500 text-white' : 'bg-red-500 text-white'}">
                                    ${attendee.email_sent ? 'Yes' : 'No'}
                                </span>
                            </td>
                        `;
                        document.getElementById('attendeesBody').appendChild(row);
                    });
                })
                .catch(error => console.error('Error fetching attendees:', error));
        }
    </script>
