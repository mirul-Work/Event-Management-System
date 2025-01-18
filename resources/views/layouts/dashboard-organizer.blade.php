<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @yield('title', 'Event Management System')
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- <script src="https://code.jquery.com/jquery-3.7.1.js"></script> --}}
    {{-- <script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script> --}}
    {{-- <script src="https://cdn.datatables.net/2.2.1/js/dataTables.tailwindcss.js"></script> --}}
    {{-- <link href="https://cdn.datatables.net/2.2.1/css/dataTables.tailwindcss.css" rel="stylesheet"> --}}

    {{-- simple-datatable --}}
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .d-flex {
            display: flex;
            flex: 1;
        }

        .sidebar {
            min-height: 100vh;
            min-width: 250px;
            max-width: 250px;
            background-color: #f8f9fa;
            transition: transform 0.3s ease-in-out;
        }

        .sidebar.hidden {
            transform: translateX(-100%);
            /* Hides sidebar off-screen */
        }

        .sidebar .nav-link {
            color: #333;
        }

        .sidebar .nav-link.active {
            color: #f59e0b;
            font-weight: bold;
        }

        /* Ensure main content area scrolls */
        .flex-grow-1 {
            flex-grow: 1;
            overflow-y: auto;
            height: 100vh;
        }

        /* For smaller screens (mobile) */
        @media (max-width: 768px) {
            .sidebar {
                min-width: 200px;
                max-width: 200px;
            }

            .sidebar.hidden {
                transform: translateX(-100%);
            }
        }


        /* Set consistent row height */
        /* tr { */
        /* height: 4rem; */
        /* Adjust the height as needed */
        /* } */

        /* Ensure cells stretch evenly */
        /* td,th { */
        /* vertical-align: middle; */
        /* Align content in the middle */
        /* text-align: center; */
        /* Center text horizontally */
        /* } */
    </style>
</head>

<body class="bg-gray-100">
    <div class="d-flex">
        <!-- Sidebar -->
        <aside class="sidebar bg-white shadow-md w-64">
            <div class="p-4">
                <h5 class="text-center text-lg font-semibold text-amber-500">Organizer</h5>
                @yield('role')
                <hr class="my-2">
                @yield('side-bar-ul')
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('organizer.dashboard') }}"
                            class="nav-link {{ request()->routeIs('organizer.dashboard') ? 'active' : '' }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('organizer.profile') }}"
                            class="nav-link {{ request()->routeIs('organizer.profile') ? 'active' : '' }}">
                            Profile</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('organizer.attendees.index', ['user_id' => Auth::user()->id]) }}"
                            class="nav-link {{ request()->routeIs('organizer.attendees.index') ? 'active' : '' }}">
                            Attendees</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('organizer.events.index', ['organizer_id' => Auth::user()->id]) }}"
                            class="nav-link {{ request()->routeIs('organizer.events.index') ? 'active' : '' }}">
                            Events</a>
                    </li>
                </ul>
                <!-- Logout Button in Sidebar (Only Visible on Mobile) -->
                <div class="mt-4 block md:hidden">
                    <a href="{{ route('logout') }}" class="btn btn-danger w-full">Logout</a>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-grow-1">
            <!-- Top Navbar -->
            <nav class="navbar bg-white border-b-2">
                <div class="container mx-auto flex items-center justify-between px-4">
                    <!-- Sidebar Toggle Buttons -->
                    <div class="flex items-center">
                        <button class="btn btn-outline-warning md:hidden" id="toggleSidebarMobile">
                            ☰
                        </button>
                        <button class="btn btn-outline-warning hidden md:block" id="toggleSidebarDesktop">
                            ☰
                        </button>
                    </div>

                    <!-- Dashboard Return -->
                    <div class="flex-1 text-left">
                        @yield('dashboard-return')
                    </div>

                    <!-- Logout Button -->
                    <div class="hidden md:block">
                        <a href="{{ route('logout') }}" class="btn btn-danger text-white">Logout</a>
                    </div>
                </div>
            </nav>

            <!-- Main Content Area -->
            <div class="p-4">
                @yield('content-dashboard')

            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    {{-- jquery js --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Sidebar Toggle Script -->
    <script>
        // const toggleSidebarMobile = document.getElementById('toggleSidebarMobile');
        // const toggleSidebarDesktop = document.getElementById('toggleSidebarDesktop');
        // const sidebar = document.querySelector('.sidebar');

        // // Mobile toggle (when screen width is small)
        // toggleSidebarMobile.addEventListener('click', () => {
        //     sidebar.classList.toggle('hidden');
        // });

        // // Desktop toggle (when screen width is large)
        // toggleSidebarDesktop.addEventListener('click', () => {
        //     sidebar.classList.toggle('hidden');
        // });
        document.addEventListener('DOMContentLoaded', function() {
            const toggleSidebarMobile = document.getElementById('toggleSidebarMobile');
            const toggleSidebarDesktop = document.getElementById('toggleSidebarDesktop');
            const sidebar = document.querySelector('.sidebar');
            const sidebarLinks = document.querySelectorAll('.nav-link');

            // Restore sidebar state from sessionStorage
            if (sessionStorage.getItem('sidebarClosed') === 'true') {
                sidebar.classList.add('hidden');
            }

            // Toggle sidebar visibility for mobile
            toggleSidebarMobile.addEventListener('click', function() {
                sidebar.classList.toggle('hidden');
                sessionStorage.setItem('sidebarClosed', sidebar.classList.contains('hidden'));
            });

            // Toggle sidebar visibility for desktop
            toggleSidebarDesktop.addEventListener('click', function() {
                sidebar.classList.toggle('hidden');
                sessionStorage.setItem('sidebarClosed', sidebar.classList.contains('hidden'));
            });

            // Close sidebar and save state on link click
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    sidebar.classList.add('hidden');
                    sessionStorage.setItem('sidebarClosed', 'true');
                });
            });
        });
    </script>


</body>

</html>
