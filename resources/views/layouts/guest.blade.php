<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { background-color: #0d1117 !important; color: #e6edf3; }
            .bg-gray-100, [class*="bg-gray-100"] { background-color: #0d1117 !important; }
            .bg-white, [class*="bg-white"] { background-color: #161b22 !important; border: 1px solid #2a3444; }
            .text-gray-900, .text-gray-700, .text-gray-600, .text-gray-500 {
                color: #e6edf3 !important;
            }
            input, select, textarea {
                background-color: #0d1117 !important;
                color: #e6edf3 !important;
                border-color: #2a3444 !important;
            }
            input:focus, select:focus, textarea:focus {
                border-color: #8b5cf6 !important;
                box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.15) !important;
            }
            label { color: #8b949e !important; }
            .shadow-md { box-shadow: 0 4px 16px rgba(0, 0, 0, 0.4) !important; }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0" style="background-color: #0d1117;">
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 shadow-md overflow-hidden sm:rounded-lg" style="background-color: #161b22; border: 1px solid #2a3444;">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>

