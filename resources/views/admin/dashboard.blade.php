<x-layouts.admin title="Admin Dashboard">
    <x-slot:sidebar>
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg bg-navy-800 text-gold-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
            Dashboard
        </a>
        <a href="/" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg text-gray-300 hover:bg-navy-800 hover:text-white transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Public Site
        </a>
    </x-slot:sidebar>

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Administration Overview</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">System management, analytics, and circulation controls.</p>
            </div>
            <div class="flex items-center gap-3">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-button type="submit" variant="outline" size="sm">Sign Out</x-button>
                </form>
            </div>
        </div>

        {{-- Admin Stats Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white dark:bg-navy-900 rounded-xl p-6 border border-gray-200 dark:border-navy-800 shadow-sm">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Users</span>
                <div class="mt-3">
                    <span class="text-3xl font-bold text-gray-900 dark:text-white">1</span>
                    <span class="text-xs text-emerald-600 dark:text-emerald-400 ml-2 font-medium">Registered</span>
                </div>
            </div>

            <div class="bg-white dark:bg-navy-900 rounded-xl p-6 border border-gray-200 dark:border-navy-800 shadow-sm">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Catalog Books</span>
                <div class="mt-3">
                    <span class="text-3xl font-bold text-gray-900 dark:text-white">0</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">Phase 3 Import</span>
                </div>
            </div>

            <div class="bg-white dark:bg-navy-900 rounded-xl p-6 border border-gray-200 dark:border-navy-800 shadow-sm">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Borrowings</span>
                <div class="mt-3">
                    <span class="text-3xl font-bold text-gray-900 dark:text-white">0</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">Circulation</span>
                </div>
            </div>

            <div class="bg-white dark:bg-navy-900 rounded-xl p-6 border border-gray-200 dark:border-navy-800 shadow-sm">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Admin Role</span>
                <div class="mt-3">
                    <span class="text-2xl font-bold text-gold-500 capitalize">{{ $user->role }}</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">Superuser</span>
                </div>
            </div>
        </div>

        {{-- Admin Operations Card --}}
        <div class="bg-white dark:bg-navy-900 rounded-xl p-6 border border-gray-200 dark:border-navy-800 shadow-sm">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">ReadOra Administration System</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                You are authenticated with full administrative privileges. Catalog management, circulation logs, AI administrative context, and audit tracking are active.
            </p>
        </div>
    </div>
</x-layouts.admin>
