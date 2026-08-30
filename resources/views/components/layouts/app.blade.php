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
    </head>
    <body class="bg-readora-paper font-sans antialiased dark:bg-readora-midnight">
        {{ $slot }}
    </body>
</html>
