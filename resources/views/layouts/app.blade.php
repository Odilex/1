<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Serene Heights Hotel') - Hotel Reservation System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <nav class="bg-blue-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold">Serene Heights Hotel</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('rooms.index') }}" class="hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium">Rooms</a>
                    <a href="{{ route('guests.index') }}" class="hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium">Guests</a>
                    <a href="{{ route('reservations.index') }}" class="hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium">Reservations</a>
                    <a href="{{ route('payments.index') }}" class="hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium">Payments</a>
                    <a href="{{ route('dashboard.index') }}" class="hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-gray-800 text-white py-4 mt-8">
        <div class="max-w-7xl mx-auto text-center">
            <p>&copy; 2026 Serene Heights Hotel. All rights reserved.</p>
        </div>
    </footer>

@stack('scripts')
</body>
</html>
