<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Event Management System')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Ensure the page takes full height */
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        /* Main content grows to fill available space */
        .content {
            flex: 1;
        }

        /* Footer styling */
        footer {
            background-color: #1e293b; /* Tailwind's slate-800 */
            color: #e2e8f0;          /* Tailwind's slate-200 */
            text-align: center;
            padding: 1rem;
        }
    </style>
</head>
<body class="bg-gradient-to-r from-purple-800 to-pink-800 min-h-screen flex flex-col font-sans">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand font-bold text-lg text-purple-800" href="{{ route('home') }}">
                @yield('child-title', 'Event Management System')
            </a>
        </div>
    </nav>

    <!-- Content -->
    <div class="content">
        <div class="container mt-4">
            @yield('content')
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; {{ date('Y') }} Event Management System. All rights reserved.</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
