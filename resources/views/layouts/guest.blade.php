<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Kasir App') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">

<div class="min-h-screen flex">

    <!-- ================= LEFT SIDE (BRANDING) ================= -->
    <div class="hidden md:flex w-1/2 bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-600 text-white items-center justify-center p-10">

        <div class="text-center">
            <div class="text-5xl mb-6">💳</div>

            <h1 class="text-4xl font-bold mb-4">
                {{ config('app.name', 'Sistem Kasir') }}
            </h1>

            <p class="text-lg opacity-90">
                Sistem kasir modern untuk mengelola penjualan,
                stok barang, dan laporan keuangan secara realtime.
            </p>

            <div class="mt-8 text-sm opacity-75">
                © {{ date('Y') }} - All Rights Reserved
            </div>
        </div>

    </div>

    <!-- ================= RIGHT SIDE (FORM) ================= -->
    <div class="flex w-full md:w-1/2 items-center justify-center bg-gray-100">

        <div class="w-full max-w-md p-8">

            <!-- Logo -->
            <div class="text-center mb-6">
                <div class="text-4xl mb-2">🏪</div>
                <h2 class="text-2xl font-semibold text-gray-700">
                    Login Kasir
                </h2>
            </div>

            <!-- Card -->
            <div class="bg-white shadow-xl rounded-2xl p-6">
                {{ $slot }}
            </div>

        </div>

    </div>

</div>

</body>
</html>
