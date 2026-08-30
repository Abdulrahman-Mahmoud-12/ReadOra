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
<body class="min-h-screen bg-gray-50 text-gray-900 antialiased dark:bg-navy-950 dark:text-gray-100">
    <x-navbar />

    <main class="flex-1">
        {{ $slot }}
    </main>

    <x-footer />
</body>
</html>
