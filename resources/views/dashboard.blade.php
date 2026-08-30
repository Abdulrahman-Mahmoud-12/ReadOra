<x-layouts.app title="Patron Dashboard — ReadOra">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Header Section --}}
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Welcome, {{ $user->name }}</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage your active borrowings, reading list, and discovery recommendations.</p>
            </div>
            <div class="flex items-center gap-3">
                <x-button href="/" variant="outline" size="sm">Explore Books</x-button>
                @if($user->isAdmin())
                    <x-button href="{{ route('admin.dashboard') }}" variant="secondary" size="sm">Admin Portal</x-button>
                @endif
            </div>
        </div>

        {{-- Metrics Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-navy-900 rounded-xl p-6 border border-gray-200 dark:border-navy-800 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Currently Borrowed</span>
                    <span class="p-2 bg-navy-50 dark:bg-navy-800 text-navy-600 dark:text-gold-400 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    </span>
                </div>
                <div class="mt-4">
                    <span class="text-2xl font-bold text-gray-900 dark:text-white">0</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">Active loans</span>
                </div>
            </div>

            <div class="bg-white dark:bg-navy-900 rounded-xl p-6 border border-gray-200 dark:border-navy-800 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Saved Favorites</span>
                    <span class="p-2 bg-navy-50 dark:bg-navy-800 text-navy-600 dark:text-gold-400 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                    </span>
                </div>
                <div class="mt-4">
                    <span class="text-2xl font-bold text-gray-900 dark:text-white">0</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">Saved books</span>
                </div>
            </div>

            <div class="bg-white dark:bg-navy-900 rounded-xl p-6 border border-gray-200 dark:border-navy-800 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Read</span>
                    <span class="p-2 bg-navy-50 dark:bg-navy-800 text-navy-600 dark:text-gold-400 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </span>
                </div>
                <div class="mt-4">
                    <span class="text-2xl font-bold text-gray-900 dark:text-white">0</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">History count</span>
                </div>
            </div>

            <div class="bg-white dark:bg-navy-900 rounded-xl p-6 border border-gray-200 dark:border-navy-800 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Account Role</span>
                    <span class="p-2 bg-navy-50 dark:bg-navy-800 text-navy-600 dark:text-gold-400 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    </span>
                </div>
                <div class="mt-4">
                    <span class="text-2xl font-bold capitalize text-gold-500">{{ $user->role }}</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">Patron status</span>
                </div>
            </div>
        </div>

        {{-- Activity Sections --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-white dark:bg-navy-900 rounded-xl p-6 border border-gray-200 dark:border-navy-800 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Active Borrowings</h2>
                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                    <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-navy-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    <p class="text-sm">You do not have any active book loans currently.</p>
                </div>
            </div>

            <div class="bg-white dark:bg-navy-900 rounded-xl p-6 border border-gray-200 dark:border-navy-800 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">AI Library Assistant</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                    ReadOra AI assistant is ready to help you discover authors, summarize themes, and find books matching your interests.
                </p>
                <div class="rounded-lg bg-navy-50 dark:bg-navy-800/50 p-4 border border-navy-100 dark:border-navy-800">
                    <p class="text-xs text-navy-800 dark:text-gold-300 font-medium">Assistant Status: Active (Role-Aware Patron Mode)</p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
