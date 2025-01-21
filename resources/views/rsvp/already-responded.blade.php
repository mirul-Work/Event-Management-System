<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">


<div class="min-h-screen bg-gray-50 flex flex-col items-center justify-center px-4">
    <!-- Flash Messages -->
    @if (session('success'))
        <div class="mb-4 w-full max-w-lg bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 w-full max-w-lg bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    @endif

    <!-- RSVP Card -->
    <div class="bg-white shadow-lg rounded-lg p-6 w-full max-w-lg">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">You already   <span class="{{ $attendee->status === 'accepted' ? 'text-green-500' : ($attendee->status === 'rejected' ? 'text-red-500' : 'text-gray-500') }}">
            {{ strtoupper($attendee->status) }}
        </span>

            for {{$attendee->seat_category}} Seat
         </h1>

        <h1 class="text-2xl font-bold text-gray-800 mb-4">Organizer : {{strtoupper($attendee->user->name)}} </h1>

        <h2 class="text-2xl font-bold text-gray-800 mb-4">RSVP for {{strtoupper($attendee->seat_category)}} Seat</h2>
        <div class="mb-4">
            <p class=" font-bold text-black">
                <span class="font-bold text-black">Hello</span> {{ ucfirst($attendee->name )}}
            </p>
        <div class="mb-4">
            <p class="text-black">
                <span class="font-bold text-black">Event:</span> {{ $attendee->event->name }}
            </p>
            <p class="text-black">
                <span class="font-bold text-black">Your Seat Category:</span> {{strtoupper($attendee->seat_category)}}
            </p>
        </div>

        </div>
    </div>
</div>

