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

        <!-- Acceptance Form -->
        <form action="{{ route('rsvp.submit', ['seat_category' => 'vvip', 'token' => $attendee->token]) }}" method="POST" class="mb-4">
            @csrf
            <input type="hidden" name="action" value="accept">
            <button
                type="submit"
                class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded transition duration-300"
            >
                Accept
            </button>
        </form>

        <!-- Rejection Form -->
        <form action="{{ route('rsvp.submit', ['seat_category' => 'vvip', 'token' => $attendee->token]) }}" method="POST">
            @csrf
            <input type="hidden" name="action" value="reject">
            <button
                type="submit"
                class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded transition duration-300"
            >
                Reject
            </button>
        </form>
    </div>
</div>

