<!DOCTYPE html>
<html 
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="{ 
        darkMode: localStorage.getItem('darkMode') === 'true',
        sidebarOpen: false
    }"
    x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))"
    :class="{ 'dark': darkMode }"
>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ setting('app_name', config('app.name', 'Kasir App')) }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Memuat library Chart.js di head agar siap digunakan --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .dark input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
    
    <div class="flex h-screen bg-gray-200 dark:bg-gray-800">
        
        @include('layouts.partials.sidebar')

        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-20 bg-black bg-opacity-50 transition-opacity lg:hidden" x-cloak></div>

        <!-- Main Content & Header -->
        <div class="flex flex-col flex-1 overflow-hidden">
            
            @include('layouts.partials.header')

            <!-- Main content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 dark:bg-gray-800">
                <div class="container px-6 py-8 mx-auto">
                    @if (isset($header))
                        <h3 class="mb-4 text-3xl font-medium text-gray-700 dark:text-gray-200">
                            {{ $header }}
                        </h3>
                    @endif

                    {{ $slot }}

                </div>
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
