@props(['title' => config('app.name', 'ReadOra')])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} | {{ config('app.name', 'ReadOra') }}</title>
    <script>
        (() => {
            const theme = localStorage.getItem('readora-theme') || 'system';
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            document.documentElement.classList.toggle('dark', theme === 'dark' || (theme === 'system' && prefersDark));
            document.documentElement.dataset.theme = theme;
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{ $head ?? '' }}
</head>
<body class="min-h-screen flex flex-col items-center justify-center bg-gray-50 dark:bg-navy-950 text-gray-900 dark:text-gray-100 antialiased px-4 py-8">
    <div class="w-full max-w-md">
        <div class="mb-8 flex justify-center">
            <a href="/">
                <x-logo class="h-10" />
            </a>
        </div>

        <div class="bg-white dark:bg-navy-900 rounded-2xl shadow-xl p-8 border border-gray-200 dark:border-navy-800">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
