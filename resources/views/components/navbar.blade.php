<nav class="sticky top-0 z-50 border-b border-gray-200 bg-white/80 backdrop-blur-md dark:border-navy-800 dark:bg-navy-900/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            {{-- Logo --}}
            <a href="/" class="flex items-center gap-2">
                <x-logo class="h-8" />
            </a>

            {{-- Desktop Navigation --}}
            <div class="hidden md:flex items-center gap-6">
                <a href="/" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-navy-600 dark:hover:text-gold-400 transition-colors">Home</a>
                <a href="/books" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-navy-600 dark:hover:text-gold-400 transition-colors">Books</a>
            </div>

            {{-- Right side --}}
            <div class="flex items-center gap-3">
                <x-theme-toggle />

                @auth
                    <a href="/dashboard" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-navy-600 dark:hover:text-gold-400 transition-colors">Dashboard</a>
                @else
                    <a href="/login" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-navy-600 dark:hover:text-gold-400 transition-colors">Login</a>
                    <x-button href="/register" size="sm">Register</x-button>
                @endauth

                {{-- Mobile menu button --}}
                <button type="button" class="md:hidden text-gray-500 dark:text-gray-400" data-mobile-menu-toggle aria-controls="mobile-menu" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
            </div>
        </div>

        <div id="mobile-menu" class="hidden border-t border-gray-200 py-4 dark:border-navy-800 md:hidden">
            <div class="flex flex-col gap-3">
                <a href="/" class="text-sm font-medium text-gray-700 transition-colors hover:text-navy-600 dark:text-gray-300 dark:hover:text-gold-400">Home</a>
                <a href="/books" class="text-sm font-medium text-gray-700 transition-colors hover:text-navy-600 dark:text-gray-300 dark:hover:text-gold-400">Books</a>
                @auth
                    <a href="/dashboard" class="text-sm font-medium text-gray-700 transition-colors hover:text-navy-600 dark:text-gray-300 dark:hover:text-gold-400">Dashboard</a>
                @else
                    <a href="/login" class="text-sm font-medium text-gray-700 transition-colors hover:text-navy-600 dark:text-gray-300 dark:hover:text-gold-400">Login</a>
                    <x-button href="/register" size="sm" class="w-fit">Register</x-button>
                @endauth
            </div>
        </div>
    </div>
</nav>
