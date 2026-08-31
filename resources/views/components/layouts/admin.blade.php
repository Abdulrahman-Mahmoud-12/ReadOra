@props([
    'title' => 'Administration Portal — ReadOra',
    'header' => 'Administration Overview',
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    <script>
        (function() {
            const t = localStorage.getItem('readora-theme');
            const dark = t === 'dark' || (!t || t === 'system') && window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (dark) document.documentElement.classList.add('dark');
        })();
    </script>
    @vite(['resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100 text-gray-900 antialiased dark:bg-navy-950 dark:text-gray-100 flex flex-col">
    <div class="flex flex-1 min-h-screen">
        {{-- Desktop Sidebar --}}
        <aside class="hidden lg:flex lg:flex-col lg:w-64 bg-navy-900 dark:bg-navy-950 border-r border-navy-800 text-gray-300">
            <div class="flex items-center h-16 px-6 border-b border-navy-800">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                    <x-logo class="h-8" variant="dark" />
                    <span class="text-[10px] font-bold uppercase tracking-widest px-2 py-0.5 rounded bg-gold-500/20 text-gold-300 border border-gold-500/30">Admin</span>
                </a>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto text-sm">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-gold-500 text-navy-950 font-bold' : 'hover:bg-navy-800 text-gray-300 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                    Dashboard
                </a>

                <div class="pt-4 pb-1 text-[11px] font-semibold uppercase tracking-wider text-gray-400 px-3">Circulation & Feedback</div>
                <a href="{{ route('admin.circulations.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.circulations.*') ? 'bg-gold-500 text-navy-950 font-bold' : 'hover:bg-navy-800 text-gray-300 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                    Circulation Desk
                </a>
                <a href="{{ route('admin.reviews.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.reviews.*') ? 'bg-gold-500 text-navy-950 font-bold' : 'hover:bg-navy-800 text-gray-300 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
                    Patron Reviews
                </a>

                <div class="pt-4 pb-1 text-[11px] font-semibold uppercase tracking-wider text-gray-400 px-3">Catalog Management</div>
                <a href="{{ route('admin.books.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.books.*') ? 'bg-gold-500 text-navy-950 font-bold' : 'hover:bg-navy-800 text-gray-300 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    Books
                </a>
                <a href="{{ route('admin.copies.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.copies.*') ? 'bg-gold-500 text-navy-950 font-bold' : 'hover:bg-navy-800 text-gray-300 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                    Book Copies
                </a>
                <a href="{{ route('admin.authors.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.authors.*') ? 'bg-gold-500 text-navy-950 font-bold' : 'hover:bg-navy-800 text-gray-300 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    Authors
                </a>
                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.categories.*') ? 'bg-gold-500 text-navy-950 font-bold' : 'hover:bg-navy-800 text-gray-300 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                    Categories
                </a>
                <a href="{{ route('admin.publishers.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.publishers.*') ? 'bg-gold-500 text-navy-950 font-bold' : 'hover:bg-navy-800 text-gray-300 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    Publishers
                </a>

                <div class="pt-4 pb-1 text-[11px] font-semibold uppercase tracking-wider text-gray-400 px-3">System & Users</div>
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-gold-500 text-navy-950 font-bold' : 'hover:bg-navy-800 text-gray-300 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    Users & Roles
                </a>
                <a href="{{ route('admin.audit-logs.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.audit-logs.*') ? 'bg-gold-500 text-navy-950 font-bold' : 'hover:bg-navy-800 text-gray-300 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    Audit Trail
                </a>
            </nav>

            <div class="p-4 border-t border-navy-800 flex items-center justify-between text-xs">
                <a href="{{ route('home') }}" class="text-gold-400 hover:underline flex items-center gap-1">
                    ← Library Front
                </a>
                <x-theme-toggle />
            </div>
        </aside>

        {{-- Main Container --}}
        <div class="flex-1 flex flex-col min-w-0">
            {{-- Top Navbar --}}
            <header class="h-16 bg-white dark:bg-navy-900 border-b border-gray-200 dark:border-navy-800 flex items-center justify-between px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-3">
                    <h1 class="text-lg font-bold text-gray-900 dark:text-white truncate">{{ $header }}</h1>
                </div>

                <div class="flex items-center gap-4">
                    <a href="{{ route('books.index') }}" class="hidden sm:inline-flex text-xs font-semibold text-gray-600 dark:text-gray-300 hover:text-gold-500">
                        View Catalog
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">
                            Sign Out
                        </button>
                    </form>
                </div>
            </header>

            {{-- Flash Messages --}}
            @if(session('status'))
                <div class="mx-6 mt-6 p-4 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                    {{ session('status') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mx-6 mt-6 p-4 rounded-lg bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                    {{ session('error') }}
                </div>
            @endif

            {{-- Main Content Area --}}
            <main class="flex-1 p-6 lg:p-8">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
