<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'ReadOra' }}</title>
    <script>
        (function() {
            const t = localStorage.getItem('readora-theme');
            const dark = t === 'dark' || (!t || t === 'system') && window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (dark) document.documentElement.classList.add('dark');
        })();
    </script>
    @vite(['resources/js/app.js'])
    {{ $head ?? '' }}
</head>
<body class="min-h-screen flex flex-col items-center justify-center bg-gray-50 dark:bg-navy-950 text-gray-900 dark:text-gray-100 antialiased">
    <div class="w-full max-w-md px-6">
        <div class="mb-8 flex justify-center">
            <a href="/">
                <x-logo class="h-12" />
            </a>
        </div>

        <div class="bg-white dark:bg-navy-900 rounded-2xl shadow-xl p-8 border border-gray-200 dark:border-navy-800">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
