<nav class="sticky top-0 z-50 border-b border-gray-200 bg-white/80 backdrop-blur-md dark:border-navy-800 dark:bg-navy-900/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            {{-- Logo --}}
            <a href="/" class="flex items-center gap-2">
                <x-logo class="h-8" />
            </a>

            {{-- Desktop Navigation --}}
            <div class="hidden md:flex items-center gap-6">
                <a href="/" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-navy-600 dark:hover:text-gold-400 transition-colors {{ request()->routeIs('home') ? 'text-navy-600 dark:text-gold-400 font-semibold' : '' }}">Home</a>
                <a href="{{ route('books.index') }}" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-navy-600 dark:hover:text-gold-400 transition-colors {{ request()->routeIs('books.*') ? 'text-navy-600 dark:text-gold-400 font-semibold' : '' }}">Catalog</a>
                @auth
                    <a href="{{ route('favorites.index') }}" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-navy-600 dark:hover:text-gold-400 transition-colors {{ request()->routeIs('favorites.index') ? 'text-navy-600 dark:text-gold-400 font-semibold' : '' }}">Favorites</a>
                    <a href="{{ route('borrowings.index') }}" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-navy-600 dark:hover:text-gold-400 transition-colors {{ request()->routeIs('borrowings.index') ? 'text-navy-600 dark:text-gold-400 font-semibold' : '' }}">Borrowings</a>
                @endauth
            </div>

            {{-- Right side --}}
            <div class="flex items-center gap-3">
                <x-theme-toggle />

                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="text-xs font-semibold px-2.5 py-1 rounded-full bg-gold-500/20 text-gold-600 dark:text-gold-400 border border-gold-500/30 hover:bg-gold-500/30 transition-colors">Admin Portal</a>
                    @endif
                    <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-navy-600 dark:hover:text-gold-400 transition-colors {{ request()->routeIs('dashboard') ? 'text-navy-600 dark:text-gold-400 font-semibold' : '' }}">Dashboard</a>
                    <a href="{{ route('profile.show') }}" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-navy-600 dark:hover:text-gold-400 transition-colors {{ request()->routeIs('profile.*') ? 'text-navy-600 dark:text-gold-400 font-semibold' : '' }}">Profile</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <x-button type="submit" variant="ghost" size="sm">Sign Out</x-button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-navy-600 dark:hover:text-gold-400 transition-colors">Login</a>
                    <x-button href="{{ route('register') }}" size="sm">Register</x-button>
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
                <a href="{{ route('books.index') }}" class="text-sm font-medium text-gray-700 transition-colors hover:text-navy-600 dark:text-gray-300 dark:hover:text-gold-400">Catalog</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-700 transition-colors hover:text-navy-600 dark:text-gray-300 dark:hover:text-gold-400">Dashboard</a>
                    <a href="{{ route('favorites.index') }}" class="text-sm font-medium text-gray-700 transition-colors hover:text-navy-600 dark:text-gray-300 dark:hover:text-gold-400">Favorites</a>
                    <a href="{{ route('borrowings.index') }}" class="text-sm font-medium text-gray-700 transition-colors hover:text-navy-600 dark:text-gray-300 dark:hover:text-gold-400">Borrowings</a>
                    <a href="{{ route('profile.show') }}" class="text-sm font-medium text-gray-700 transition-colors hover:text-navy-600 dark:text-gray-300 dark:hover:text-gold-400">Profile</a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-gold-600 dark:text-gold-400">Admin Portal</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-button type="submit" variant="ghost" size="sm" class="w-full justify-start">Sign Out</x-button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 transition-colors hover:text-navy-600 dark:text-gray-300 dark:hover:text-gold-400">Login</a>
                    <x-button href="{{ route('register') }}" size="sm" class="w-fit">Register</x-button>
                @endauth
            </div>
        </div>
    </div>
</nav>
