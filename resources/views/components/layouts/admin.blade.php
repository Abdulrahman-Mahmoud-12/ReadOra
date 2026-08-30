@props(['title' => 'Admin'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} | ReadOra Admin</title>
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
<body class="min-h-screen bg-gray-100 text-gray-900 antialiased dark:bg-navy-950 dark:text-gray-100">
    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside class="hidden lg:flex lg:flex-col lg:w-64 bg-navy-900 dark:bg-navy-950 border-r border-navy-800">
            <div class="flex items-center h-16 px-6 border-b border-navy-800">
                <a href="{{ route('admin.dashboard') }}">
                    <x-logo class="h-8" variant="dark" />
                </a>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                {{ $sidebar ?? '' }}
            </nav>
            <div class="p-4 border-t border-navy-800">
                <x-theme-toggle />
            </div>
        </aside>

        {{-- Main content --}}
        <div class="flex-1 flex flex-col">
            {{-- Top bar --}}
            <header class="h-16 bg-white dark:bg-navy-900 border-b border-gray-200 dark:border-navy-800 flex items-center justify-between px-6">
                <div class="flex items-center gap-4">
                    <button type="button" class="lg:hidden text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200" aria-label="Open admin navigation">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    </button>
                    <h1 class="text-lg font-semibold">{{ $header ?? 'Dashboard' }}</h1>
                </div>
                <div class="flex items-center gap-4">
                    <x-theme-toggle />
                </div>
            </header>

            <main class="flex-1 p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
