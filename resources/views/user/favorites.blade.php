<x-layouts.app title="Saved Favorites — ReadOra">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        {{-- Header --}}
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <nav class="mb-2 flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                    <a href="{{ route('dashboard') }}" class="hover:text-navy-600 dark:hover:text-gold-400">Dashboard</a>
                    <span>/</span>
                    <span class="text-gray-900 dark:text-white font-medium">Favorites</span>
                </nav>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    Saved Favorites
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Keep track of books you love, want to read later, or reference frequently.
                </p>
            </div>
            <x-button href="{{ route('books.index') }}" variant="primary" size="sm">
                Explore Catalog
            </x-button>
        </div>

        {{-- Empty State --}}
        @if($favoriteBooks->isEmpty())
            <div class="mb-12">
                <x-empty-state
                    title="Your reading list is empty"
                    description="You haven't saved any books to your favorites yet. Browse our curated library catalog and save titles to build your reading wishlist."
                    actionText="Browse Library Catalog"
                    :actionUrl="route('books.index')"
                >
                    <x-slot:icon>
                        <svg class="w-7 h-7 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                    </x-slot:icon>
                </x-empty-state>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
                @foreach($favoriteBooks as $book)
                    <div class="relative group">
                        <x-book-card :book="$book" />
                        <form method="POST" action="{{ route('favorites.toggle', $book) }}" class="absolute top-3 right-3 z-10">
                            @csrf
                            <button
                                type="submit"
                                title="Remove from favorites"
                                class="p-1.5 rounded-full bg-white/90 dark:bg-navy-900/90 shadow text-rose-500 hover:text-rose-700 dark:hover:text-rose-400 hover:scale-110 transition-transform"
                            >
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>

            @if($favoriteBooks->hasPages())
                <div class="mb-12">
                    {{ $favoriteBooks->links() }}
                </div>
            @endif
        @endif

        {{-- Suggested Reading Section --}}
        @if($suggestedBooks->isNotEmpty())
            <div class="pt-8 border-t border-gray-200 dark:border-navy-800">
                <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Suggested for Your Wishlist</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400">High-rated classical literature you might enjoy saving.</p>
                    </div>
                    <a href="{{ route('books.index') }}" class="text-xs font-semibold text-gold-600 dark:text-gold-400 hover:underline">
                        View All →
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    @foreach($suggestedBooks as $suggested)
                        <x-book-card :book="$suggested" />
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
