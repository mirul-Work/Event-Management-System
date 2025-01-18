<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-14 bg-gradient-to-r from-purple-800 to-pink-800 min-h-screen flex flex-col items-center justify-center font-sans">
    <header class="text-center mb-12">
        <h1 class="text-5xl text-white">Event Management System</h1>
        <p class="text-lg  text-white mt-4">Welcome! Who are you?</p>
    </header>

    <main class="grid grid-cols-1 sm:grid-cols-2 gap-6 w-full max-w-4xl px-4">
        <!-- Admin Card -->
        <div class="bg-purple-700 text-white rounded-lg shadow-2xl transform hover:scale-105 transition duration-300">
            <div class="p-6 text-center">
                <h2 class="text-2xl font-bold mb-2">Admin</h2>
                <p class="text-sm mb-4">Manage the entire system</p>
                <a href="{{ route('login') }}"
                   class="inline-block bg-purple-900 text-white font-semibold rounded-full px-6 py-2 hover:bg-purple-800 transition">
                   Go to Admin
                </a>
            </div>
        </div>

        <!-- Organizer Card -->
        <div class="bg-pink-600 text-white rounded-lg shadow-2xl transform hover:scale-105 transition duration-300">
            <div class="p-6 text-center">
                <h2 class="text-2xl font-bold mb-2">Organizer</h2>
                <p class="text-sm mb-4">Organize events effortlessly</p>
                <a href="{{ route('login') }}"
                   class="inline-block bg-pink-900 text-white font-semibold rounded-full px-6 py-2 hover:bg-pink-800 transition">
                   Go to Organizer
                </a>
            </div>
        </div>
    </main>

    <footer class="mt-12 text-white text-sm">
        <p>&copy; {{ date('Y') }} Event Management System. All rights reserved.</p>
    </footer>
</body>
</html>
