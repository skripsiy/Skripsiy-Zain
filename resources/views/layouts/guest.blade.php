<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>XENA - {{ $title ?? 'Authentication' }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background: #C8C8C8;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <!-- Header -->
        <div class="bg-white shadow-sm">
            <div class="px-8 py-5">
                <div class="text-2xl font-bold bg-gradient-to-r from-slate-800 via-slate-600 to-slate-500 bg-clip-text text-transparent tracking-wide">
                    XENA
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="min-h-screen flex flex-col justify-center items-center pt-6 sm:pt-0">
            <div class="w-full sm:max-w-md mt-6 px-12 py-12 bg-white shadow-lg overflow-hidden sm:rounded-xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
