<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased h-full bg-gray-100 dark:bg-gray-900">
    <div class="flex min-h-full">
        <!-- Kolom Kiri untuk Branding -->
        <div
            class="hidden lg:flex lg:w-1/2 xl:w-2/3 items-center justify-center bg-gradient-to-br from-indigo-600 to-blue-500 p-12 text-white text-center">
            <div class="max-w-md">

                <a href="/">
                    {{-- Logo Anda akan tampil di sini, pastikan komponennya ada --}}
                    @if(setting('app_logo'))
                    <img src="{{ asset('storage/' . setting('app_logo')) }}" alt="Logo" class="w-24 h-24 mx-auto text-white fill-current">
                    @else
                    <x-application-logo class="w-24 h-24 mx-auto text-white fill-current" />
                    @endif
                </a>
                <h1 class="mt-6 text-4xl font-bold tracking-tight">{{ setting('app_name', config('app.name')) }}</h1>
                <p class="mt-4 text-lg text-indigo-100">Solusi Point of Sale modern untuk bisnis Anda. Cepat, andal, dan
                    mudah digunakan.</p>
            </div>
        </div>

        <!-- Kolom Kanan untuk Form -->
        <div class="flex flex-1 flex-col justify-center px-4 py-12 sm:px-6 lg:flex-none lg:px-20 xl:px-24">
            <div class="mx-auto w-full max-w-sm lg:w-96">
                {{-- Logo untuk tampilan mobile --}}
                <div class="lg:hidden mb-8 text-center">
                    <a href="/">
                        <x-application-logo class="w-20 h-20 mx-auto text-gray-700 dark:text-gray-300 fill-current" />
                    </a>
                </div>

                {{-- Slot ini akan diisi oleh form login atau register --}}
                {{ $slot }}
            </div>
        </div>
    </div>
</body>

</html>