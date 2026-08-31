<x-layouts.app title="Library Catalog — ReadOra">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        {{-- Page Header --}}
        <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gold-500/10 text-gold-600 dark:text-gold-400 text-xs font-semibold uppercase tracking-wider mb-2">
                    Digital Collection
                </div>
                <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white">
                    Library Catalog
                </h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 max-w-xl">
                    Explore our collection of {{ number_format($totalBooksCount) }} public-domain classics, literature, philosophy, and historical texts.
                </p>
            </div>

            {{-- Summary count --}}
            <div class="text-sm text-gray-500 dark:text-gray-400">
                Showing <span class="font-bold text-gray-900 dark:text-white">{{ $books->firstItem() ?? 0 }}</span> to <span class="font-bold text-gray-900 dark:text-white">{{ $books->lastItem() ?? 0 }}</span> of <span class="font-bold text-gray-900 dark:text-white">{{ $books->total() }}</span> books
            </div>
        </div>

        {{-- Search & Main Filter Bar --}}
        <form method="GET" action="{{ route('books.index') }}" class="mb-8 space-y-4">
            <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-navy-800 dark:bg-navy-900 sm:p-6">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    {{-- Search Input --}}
                    <div class="md:col-span-5 relative">
                        <label for="search" class="sr-only">Search books</label>
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>
                        <input
                            type="search"
                            name="search"
                            id="search"
                            value="{{ $filters['search'] }}"
                            placeholder="Search by title, author, category, or ISBN..."
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 dark:border-navy-700 bg-gray-50 dark:bg-navy-950 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-navy-600 dark:focus:ring-gold-400 focus:border-transparent text-sm transition-all"
                        >
                    </div>

                    {{-- Category Select --}}
                    <div class="md:col-span-3">
                        <label for="category" class="sr-only">Filter by Category</label>
                        <select
                            name="category"
                            id="category"
                            class="w-full py-2.5 px-3 rounded-xl border border-gray-300 dark:border-navy-700 bg-gray-50 dark:bg-navy-950 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-navy-600 dark:focus:ring-gold-400 focus:border-transparent transition-all"
                        >
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->slug }}" {{ $filters['category'] === $category->slug ? 'selected' : '' }}>
                                    {{ $category->name }} ({{ $category->books_count }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Sort Select --}}
                    <div class="md:col-span-2">
                        <label for="sort" class="sr-only">Sort By</label>
                        <select
                            name="sort"
                            id="sort"
                            class="w-full py-2.5 px-3 rounded-xl border border-gray-300 dark:border-navy-700 bg-gray-50 dark:bg-navy-950 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-navy-600 dark:focus:ring-gold-400 focus:border-transparent transition-all"
                        >
                            <option value="rating_desc" {{ $filters['sort'] === 'rating_desc' ? 'selected' : '' }}>Highest Rated</option>
                            <option value="popular" {{ $filters['sort'] === 'popular' ? 'selected' : '' }}>Most Popular</option>
                            <option value="year_desc" {{ $filters['sort'] === 'year_desc' ? 'selected' : '' }}>Newest Published</option>
                            <option value="year_asc" {{ $filters['sort'] === 'year_asc' ? 'selected' : '' }}>Oldest Published</option>
                            <option value="title_asc" {{ $filters['sort'] === 'title_asc' ? 'selected' : '' }}>Title (A-Z)</option>
                            <option value="title_desc" {{ $filters['sort'] === 'title_desc' ? 'selected' : '' }}>Title (Z-A)</option>
                        </select>
                    </div>

                    {{-- Submit & Clear --}}
                    <div class="md:col-span-2 flex items-center gap-2">
                        <x-button type="submit" variant="primary" class="w-full justify-center">
                            Filter
                        </x-button>
                        @if($filters['search'] || $filters['category'] || $filters['author'] || $filters['availability'] || $filters['sort'] !== 'rating_desc')
                            <a href="{{ route('books.index') }}" class="p-2.5 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white rounded-xl border border-gray-300 dark:border-navy-700 hover:bg-gray-100 dark:hover:bg-navy-800 transition-colors" title="Clear Filters">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Secondary Filters: Availability & Quick Categories --}}
                <div class="mt-4 flex flex-col gap-3 border-t border-gray-100 pt-4 text-xs dark:border-navy-800 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex min-w-0 items-center gap-4">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input
                                type="checkbox"
                                name="availability"
                                value="available"
                                {{ $filters['availability'] === 'available' ? 'checked' : '' }}
                                onchange="this.form.submit()"
                                class="w-4 h-4 rounded border-gray-300 text-gold-500 focus:ring-gold-400 dark:border-navy-700 dark:bg-navy-950"
                            >
                            <span class="font-medium text-gray-700 dark:text-gray-300">Available copies only</span>
                        </label>
                    </div>

                    {{-- Popular category chips --}}
                    <div class="flex min-w-0 flex-wrap items-center gap-1.5">
                        <span class="mr-1 shrink-0 text-gray-400">Popular:</span>
                        @foreach($categories->take(6) as $category)
                            <a
                                href="{{ route('books.index', array_merge($filters, ['category' => $filters['category'] === $category->slug ? '' : $category->slug])) }}"
                                class="max-w-full truncate rounded-full px-2.5 py-1 text-xs font-medium transition-colors {{ $filters['category'] === $category->slug ? 'bg-gold-500 text-navy-950 font-bold' : 'bg-gray-100 text-gray-700 hover:bg-gold-500/20 hover:text-gold-500 dark:bg-navy-800 dark:text-gray-300' }}"
                            >
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </form>

        {{-- Active Filters Tags --}}
        @if($filters['search'] || $filters['category'] || $filters['author'] || $filters['availability'])
            <div class="mb-6 flex flex-wrap items-center gap-2">
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Active Filters:</span>

                @if($filters['search'])
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-navy-100 dark:bg-navy-800 text-navy-800 dark:text-gold-300 text-xs font-medium">
                        Search: "{{ $filters['search'] }}"
                        <a href="{{ route('books.index', array_merge($filters, ['search' => ''])) }}" class="hover:text-rose-500">×</a>
                    </span>
                @endif

                @if($filters['category'])
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-navy-100 dark:bg-navy-800 text-navy-800 dark:text-gold-300 text-xs font-medium">
                        Category: {{ $categories->firstWhere('slug', $filters['category'])?->name ?? $filters['category'] }}
                        <a href="{{ route('books.index', array_merge($filters, ['category' => ''])) }}" class="hover:text-rose-500">×</a>
                    </span>
                @endif

                @if($filters['availability'] === 'available')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 text-xs font-medium">
                        Available in Library
                        <a href="{{ route('books.index', array_merge($filters, ['availability' => ''])) }}" class="hover:text-rose-500">×</a>
                    </span>
                @endif

                <a href="{{ route('books.index') }}" class="text-xs text-gold-600 dark:text-gold-400 hover:underline font-semibold ml-2">
                    Reset all
                </a>
            </div>
        @endif

        {{-- Books Grid / Empty State --}}
        @if($books->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($books as $book)
                    <x-book-card :book="$book" />
                @endforeach
            </div>

            {{-- Pagination Links --}}
            <div class="mt-10">
                {{ $books->links() }}
            </div>
        @else
            <x-empty-state
                title="No books match your criteria"
                description="Try refining your search term, clearing category filters, or browsing our full library catalog."
                actionText="View All Books"
                :actionUrl="route('books.index')"
            />
        @endif
    </div>
</x-layouts.app>
