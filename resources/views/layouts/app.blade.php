<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="{{ asset('fonts/figtree.css') }}">

    <!-- Chart.js -->
    <script src="{{ asset('js/chart.js') }}"></script>
    
    @stack('scripts')


    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    
</head>
<body 
    transition-colors duration-300 bg-white text-gray-900"
    x-data="{ sidebarOpen: true }"
>

    <!-- Sidebar Toggle Button -->
    <button @click="sidebarOpen = !sidebarOpen"
        class="fixed top-4 left-4 z-50 text-3xl text-blue-600 bg-transparent px-3 py-2">
        &#9776;
    </button>

    <!-- Livewire Navigation -->
    <livewire:layout.navigation />

    <!-- Dynamic Sidebar -->
    <livewire:layout.sidebar />

    <!-- Main Content -->
    <main class="flex-1 min-h-screen pt-20 px-6 md:ml-72">
        @if (isset($header))
            <header class="fixed top-0 left-0 w-full bg-white shadow z-30">
                <div class="max-w-7xl mx-auto py-3 px-4 sm:px-6 lg:px-8 flex items-center space-x-4">
                    <img src="{{ asset('pru30.png') }}" alt="PLA" class="h-6 w-auto">
                </div>
            </header>
        @endif

        <!-- Page Content -->
        {{ $slot }}

        <!-- Footer -->
        <footer class="mt-12 text-center text-sm text-gray-500">
            &copy; 2025 Database Administrator Dashboard — DBA-PLA Prudential.
        </footer>

        <!-- Debugbar -->
        @if (config('app.debug'))
            {!! Debugbar::render() !!}
        @endif
    </main>
</body>
</html>
