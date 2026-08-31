<x-layouts.app title="Patron Dashboard — ReadOra">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        {{-- Header Section --}}
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gold-500/10 text-gold-600 dark:text-gold-400 text-xs font-semibold uppercase tracking-wider mb-2">
                    Patron Account
                </div>
                <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white">
                    Welcome, {{ $user->name }}
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Discover literature, track your reading preferences, and explore catalog availability.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <x-button href="{{ route('books.index') }}" variant="primary" size="sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    Browse Catalog
                </x-button>
                @if($user->isAdmin())
                    <x-button href="{{ route('admin.dashboard') }}" variant="secondary" size="sm">
                        Admin Portal
                    </x-button>
                @endif
            </div>
        </div>

        {{-- Metrics Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <x-stat-card
                title="Catalog Collection"
                :value="number_format($totalBooks)"
                subtitle="Real bibliographic works"
                variant="gold"
            >
                <x-slot:icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                </x-slot:icon>
            </x-stat-card>

            <x-stat-card
                title="Available for Loan"
                :value="number_format($availableBooks)"
                subtitle="Ready to borrow now"
                variant="success"
            >
                <x-slot:icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </x-slot:icon>
            </x-stat-card>

            <x-stat-card
                title="Subject Categories"
                :value="number_format($categoriesCount)"
                subtitle="Classified taxonomies"
                variant="purple"
            >
                <x-slot:icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                </x-slot:icon>
            </x-stat-card>

            <x-stat-card
                title="Active Borrowings"
                :value="number_format($activeLoansCount)"
                subtitle="Current active loans"
                :variant="$activeLoansCount > 0 ? 'gold' : 'default'"
            >
                <x-slot:icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                </x-slot:icon>
            </x-stat-card>
        </div>

        {{-- Recommended Books Section --}}
        <div class="mb-12">
            <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">
                        Curated Recommendations
                    </h2>
                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                        Top-rated masterpieces from our library collection.
                    </p>
                </div>
                <a href="{{ route('books.index', ['sort' => 'rating_desc']) }}" class="text-xs sm:text-sm font-semibold text-gold-600 dark:text-gold-400 hover:underline">
                    View Top Rated →
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($recommendedBooks as $book)
                    <div class="flex flex-col gap-2">
                        @if(isset($book->recommendation_reason))
                            <span class="text-[11px] font-medium text-gold-600 dark:text-gold-400 flex items-center gap-1 truncate">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" /></svg>
                                {{ $book->recommendation_reason }}
                            </span>
                        @endif
                        <x-book-card :book="$book" />
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Recently Added & Category Exploration --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-12">
            {{-- Quick Category Exploration --}}
            <div class="lg:col-span-4 flex flex-col justify-between rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-navy-800 dark:bg-navy-900">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">
                        Explore by Category
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">
                        Discover books across our most popular subjects.
                    </p>

                    <div class="space-y-2">
                        @foreach($popularCategories as $cat)
                            <a
                                href="{{ route('books.index', ['category' => $cat->slug]) }}"
                                class="group flex items-center justify-between gap-3 rounded-lg border border-transparent bg-gray-50 p-3 transition-all hover:border-gold-500/30 hover:bg-gold-500/10 dark:bg-navy-950"
                            >
                                <span class="min-w-0 text-sm font-medium text-gray-700 group-hover:text-gold-500 dark:text-gray-300">
                                    {{ $cat->name }}
                                </span>
                                <span class="shrink-0 rounded-full bg-white px-2 py-0.5 text-xs font-semibold text-gray-500 dark:bg-navy-800 dark:text-gray-400">
                                    {{ $cat->books_count }} books
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-100 dark:border-navy-800">
                    <a href="{{ route('books.index') }}" class="text-xs font-semibold text-navy-600 dark:text-gold-400 hover:underline block text-center">
                        Browse all {{ $categoriesCount }} categories →
                    </a>
                </div>
            </div>

            {{-- AI Assistant Banner / Quick Actions --}}
            <div class="lg:col-span-8 flex flex-col gap-6">
                {{-- AI Assistant Feature Card --}}
                <div class="relative overflow-hidden rounded-lg border border-navy-800 bg-gradient-to-br from-navy-900 via-navy-800 to-navy-950 p-6 text-white shadow-xl sm:p-8">
                    <div class="relative max-w-xl">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-gold-500/20 text-gold-300 border border-gold-500/30 text-xs font-semibold mb-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-gold-400 animate-pulse"></span>
                            AI Library Assistant
                        </div>

                        <h3 class="text-xl sm:text-2xl font-bold leading-snug">
                            Need inspiration on what to read next?
                        </h3>
                        <p class="mt-2 text-xs sm:text-sm text-gray-300 leading-relaxed">
                            Our intelligent library assistant can summarize classic texts, recommend authors based on your favorite genres, and answer literature questions.
                        </p>

                        <div class="mt-6 flex flex-wrap items-center gap-3">
                            <x-button href="{{ route('books.index') }}" variant="primary" size="sm">
                                Find a Book with AI
                            </x-button>
                            <x-button href="{{ route('profile.show') }}" variant="ghost" size="sm" class="text-gray-300 hover:text-white">
                                Manage Reading Profile
                            </x-button>
                        </div>
                    </div>
                </div>

                {{-- Patron Shortcuts Grid --}}
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <a href="{{ route('favorites.index') }}" class="group rounded-lg border border-gray-200 bg-white p-4 shadow-sm transition-all hover:border-gold-300 dark:border-navy-800 dark:bg-navy-900 dark:hover:border-gold-500/50">
                        <div class="w-8 h-8 rounded-lg bg-rose-50 dark:bg-rose-900/30 text-rose-500 flex items-center justify-center mb-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                        </div>
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-gold-500 transition-colors">Saved Favorites</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Your personal reading list</p>
                    </a>

                    <a href="{{ route('borrowings.index') }}" class="group rounded-lg border border-gray-200 bg-white p-4 shadow-sm transition-all hover:border-gold-300 dark:border-navy-800 dark:bg-navy-900 dark:hover:border-gold-500/50">
                        <div class="w-8 h-8 rounded-lg bg-navy-50 dark:bg-navy-800 text-navy-600 dark:text-gold-400 flex items-center justify-center mb-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        </div>
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-gold-500 transition-colors">Borrowing History</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Active loans and past returns</p>
                    </a>

                    <a href="{{ route('profile.show') }}" class="group rounded-lg border border-gray-200 bg-white p-4 shadow-sm transition-all hover:border-gold-300 dark:border-navy-800 dark:bg-navy-900 dark:hover:border-gold-500/50">
                        <div class="w-8 h-8 rounded-lg bg-gold-50 dark:bg-gold-900/30 text-gold-500 flex items-center justify-center mb-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /></svg>
                        </div>
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-gold-500 transition-colors">Patron Settings</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Profile and card credentials</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
