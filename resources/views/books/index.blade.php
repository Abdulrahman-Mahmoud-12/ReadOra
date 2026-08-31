<x-layouts.app title="Library Catalog & Discovery — ReadOra">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        {{-- Page Title Banner --}}
        <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4 border-b border-gray-200 dark:border-navy-800 pb-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gold-500/10 text-gold-600 dark:text-gold-400 text-xs font-semibold uppercase tracking-wider mb-2">
                    Digital Collection Catalog
                </div>
                <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white">
                    Explore Books
                </h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 max-w-xl">
                    Discover literature, computer science, philosophy, and classical manuscripts with real-time copy availability.
                </p>
            </div>

            <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 font-medium">
                Showing <span class="font-bold text-gray-900 dark:text-white">{{ $books->firstItem() ?? 0 }}</span>–<span class="font-bold text-gray-900 dark:text-white">{{ $books->lastItem() ?? 0 }}</span> of <span class="font-bold text-gray-900 dark:text-white">{{ $books->total() }}</span> catalog titles
            </div>
        </div>

        {{-- Main 2-Column Discovery Layout --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            {{-- Left Column: Facet Filters Sidebar --}}
            <aside class="lg:col-span-4 xl:col-span-3">
                <div class="sticky top-24 rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-navy-800 dark:bg-navy-900 space-y-6">
                    <div class="flex items-center justify-between border-b border-gray-100 dark:border-navy-800 pb-3">
                        <h2 class="text-sm font-bold uppercase tracking-wider text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-4 h-4 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
                            Faceted Filters
                        </h2>
                        @if($filters['search'] || !empty($filters['categories']) || !empty($filters['authors']) || !empty($filters['publishers']) || !empty($filters['languages']) || $filters['min_rating'] || $filters['era'] || $filters['availability'])
                            <a href="{{ route('books.index') }}" class="text-[11px] font-semibold text-rose-600 dark:text-rose-400 hover:underline">
                                Reset All
                            </a>
                        @endif
                    </div>

                    <form method="GET" action="{{ route('books.index') }}" class="space-y-6 text-xs">
                        {{-- Preserved sort --}}
                        <input type="hidden" name="sort" value="{{ $filters['sort'] }}">

                        {{-- Search Keyword --}}
                        <div>
                            <label class="block text-[11px] font-bold uppercase text-gray-500 dark:text-gray-400 mb-1.5">Keywords / ISBN</label>
                            <div class="relative">
                                <input
                                    type="text"
                                    name="search"
                                    value="{{ $filters['search'] }}"
                                    placeholder="Title, author, ISBN..."
                                    class="w-full pl-8 pr-3 py-2 rounded-lg border border-gray-300 dark:border-navy-700 bg-gray-50 dark:bg-navy-950 text-gray-900 dark:text-white text-xs"
                                />
                                <svg class="w-4 h-4 text-gray-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            </div>
                        </div>

                        {{-- Shelf Availability --}}
                        <div class="pt-2 border-t border-gray-100 dark:border-navy-800">
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input
                                    type="checkbox"
                                    name="availability"
                                    value="available"
                                    {{ $filters['availability'] === 'available' ? 'checked' : '' }}
                                    class="w-4 h-4 rounded border-gray-300 text-gold-500 focus:ring-gold-400 dark:border-navy-700 dark:bg-navy-950"
                                >
                                <span class="font-semibold text-gray-800 dark:text-gray-200">Available on Shelves Only</span>
                            </label>
                        </div>

                        {{-- Rating Range --}}
                        <div class="pt-2 border-t border-gray-100 dark:border-navy-800">
                            <label class="block text-[11px] font-bold uppercase text-gray-500 dark:text-gray-400 mb-2">Minimum Rating</label>
                            <div class="space-y-1.5">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="min_rating" value="" {{ empty($filters['min_rating']) ? 'checked' : '' }} class="text-gold-500">
                                    <span class="text-gray-700 dark:text-gray-300">Any Rating</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="min_rating" value="4.5" {{ $filters['min_rating'] == 4.5 ? 'checked' : '' }} class="text-gold-500">
                                    <span class="text-gray-700 dark:text-gray-300">★ 4.5 & higher</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="min_rating" value="4.0" {{ $filters['min_rating'] == 4.0 ? 'checked' : '' }} class="text-gold-500">
                                    <span class="text-gray-700 dark:text-gray-300">★ 4.0 & higher</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="min_rating" value="3.5" {{ $filters['min_rating'] == 3.5 ? 'checked' : '' }} class="text-gold-500">
                                    <span class="text-gray-700 dark:text-gray-300">★ 3.5 & higher</span>
                                </label>
                            </div>
                        </div>

                        {{-- Publication Era / Decade --}}
                        <div class="pt-2 border-t border-gray-100 dark:border-navy-800">
                            <label class="block text-[11px] font-bold uppercase text-gray-500 dark:text-gray-400 mb-1.5">Publication Era</label>
                            <select name="era" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-navy-700 bg-gray-50 dark:bg-navy-950 text-gray-900 dark:text-white text-xs">
                                <option value="">All Eras</option>
                                <option value="2020s" {{ $filters['era'] === '2020s' ? 'selected' : '' }}>2020s (Modern)</option>
                                <option value="2010s" {{ $filters['era'] === '2010s' ? 'selected' : '' }}>2010s</option>
                                <option value="2000s" {{ $filters['era'] === '2000s' ? 'selected' : '' }}>2000s</option>
                                <option value="1900-1999" {{ $filters['era'] === '1900-1999' ? 'selected' : '' }}>20th Century (1900–1999)</option>
                                <option value="classic" {{ $filters['era'] === 'classic' ? 'selected' : '' }}>Classics (Before 1900)</option>
                            </select>
                        </div>

                        {{-- Language Facet --}}
                        @if($availableLanguages->count() > 1)
                            <div class="pt-2 border-t border-gray-100 dark:border-navy-800">
                                <label class="block text-[11px] font-bold uppercase text-gray-500 dark:text-gray-400 mb-2">Language</label>
                                <div class="space-y-1.5 max-h-32 overflow-y-auto">
                                    @foreach($availableLanguages as $lang)
                                        <label class="flex items-center justify-between gap-2 cursor-pointer pr-1">
                                            <div class="flex items-center gap-2">
                                                <input
                                                    type="checkbox"
                                                    name="languages[]"
                                                    value="{{ $lang }}"
                                                    {{ in_array($lang, $filters['languages']) ? 'checked' : '' }}
                                                    class="w-3.5 h-3.5 rounded border-gray-300 text-gold-500 focus:ring-gold-400 dark:border-navy-700 dark:bg-navy-950"
                                                >
                                                <span class="text-gray-700 dark:text-gray-300 uppercase">{{ $lang }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Categories Facet --}}
                        <div class="pt-2 border-t border-gray-100 dark:border-navy-800">
                            <label class="block text-[11px] font-bold uppercase text-gray-500 dark:text-gray-400 mb-2">Categories / Genres</label>
                            <div class="space-y-1.5 max-h-48 overflow-y-auto pr-1">
                                @foreach($categories as $cat)
                                    <label class="flex items-center justify-between gap-2 cursor-pointer">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <input
                                                type="checkbox"
                                                name="categories[]"
                                                value="{{ $cat->slug }}"
                                                {{ in_array($cat->slug, $filters['categories']) ? 'checked' : '' }}
                                                class="w-3.5 h-3.5 rounded border-gray-300 text-gold-500 focus:ring-gold-400 dark:border-navy-700 dark:bg-navy-950 shrink-0"
                                            >
                                            <span class="text-gray-700 dark:text-gray-300 truncate">{{ $cat->name }}</span>
                                        </div>
                                        <span class="text-[10px] text-gray-400 font-mono shrink-0">({{ $cat->books_count }})</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Filter Action Buttons --}}
                        <div class="pt-4 border-t border-gray-100 dark:border-navy-800 flex gap-2">
                            <button type="submit" class="w-full py-2 bg-gold-500 hover:bg-gold-600 text-navy-950 font-bold rounded-lg text-xs shadow-sm transition-colors text-center">
                                Apply Filters
                            </button>
                        </div>
                    </form>
                </div>
            </aside>

            {{-- Right Column: Results Grid & Sort Toolbar --}}
            <main class="lg:col-span-8 xl:col-span-9 space-y-6">
                {{-- Top Sort & Search Summary Toolbar --}}
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-navy-800 dark:bg-navy-900 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    {{-- Active Search Query Feedback --}}
                    <div class="text-xs text-gray-600 dark:text-gray-300">
                        @if($filters['search'])
                            <span>Results matching <strong class="text-navy-900 dark:text-gold-400">"{{ $filters['search'] }}"</strong></span>
                        @else
                            <span>Browsing all curated titles</span>
                        @endif
                    </div>

                    {{-- Sort Dropdown --}}
                    <form method="GET" action="{{ route('books.index') }}" class="flex items-center gap-2">
                        {{-- Preserved query params --}}
                        @if($filters['search']) <input type="hidden" name="search" value="{{ $filters['search'] }}"> @endif
                        @if($filters['availability']) <input type="hidden" name="availability" value="{{ $filters['availability'] }}"> @endif
                        @if($filters['era']) <input type="hidden" name="era" value="{{ $filters['era'] }}"> @endif
                        @if($filters['min_rating']) <input type="hidden" name="min_rating" value="{{ $filters['min_rating'] }}"> @endif
                        @foreach($filters['categories'] as $c) <input type="hidden" name="categories[]" value="{{ $c }}"> @endforeach
                        @foreach($filters['languages'] as $l) <input type="hidden" name="languages[]" value="{{ $l }}"> @endforeach

                        <label for="sort_select" class="text-xs font-semibold text-gray-500 dark:text-gray-400 whitespace-nowrap">Sort By:</label>
                        <select
                            id="sort_select"
                            name="sort"
                            onchange="this.form.submit()"
                            class="py-1.5 px-3 rounded-lg border border-gray-300 dark:border-navy-700 bg-gray-50 dark:bg-navy-950 text-gray-900 dark:text-white text-xs focus:ring-2 focus:ring-gold-400"
                        >
                            <option value="rating_desc" {{ $filters['sort'] === 'rating_desc' ? 'selected' : '' }}>Highest Rated</option>
                            <option value="popular" {{ $filters['sort'] === 'popular' ? 'selected' : '' }}>Most Popular</option>
                            <option value="year_desc" {{ $filters['sort'] === 'year_desc' ? 'selected' : '' }}>Newest Published</option>
                            <option value="year_asc" {{ $filters['sort'] === 'year_asc' ? 'selected' : '' }}>Oldest Published</option>
                            <option value="title_asc" {{ $filters['sort'] === 'title_asc' ? 'selected' : '' }}>Title (A–Z)</option>
                            <option value="title_desc" {{ $filters['sort'] === 'title_desc' ? 'selected' : '' }}>Title (Z–A)</option>
                        </select>
                    </form>
                </div>

                {{-- Active Filter Tags --}}
                @if($filters['search'] || !empty($filters['categories']) || !empty($filters['languages']) || $filters['min_rating'] || $filters['era'] || $filters['availability'])
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Active:</span>

                        @if($filters['search'])
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-navy-100 dark:bg-navy-800 text-navy-800 dark:text-gold-300 text-xs font-medium">
                                "{{ $filters['search'] }}"
                                <a href="{{ route('books.index', array_merge(request()->query(), ['search' => ''])) }}" class="hover:text-rose-500">×</a>
                            </span>
                        @endif

                        @if($filters['availability'] === 'available')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 text-xs font-medium">
                                In Library Only
                                <a href="{{ route('books.index', array_merge(request()->query(), ['availability' => ''])) }}" class="hover:text-rose-500">×</a>
                            </span>
                        @endif

                        @if($filters['min_rating'])
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 text-xs font-medium">
                                ★ {{ $filters['min_rating'] }}+
                                <a href="{{ route('books.index', array_merge(request()->query(), ['min_rating' => ''])) }}" class="hover:text-rose-500">×</a>
                            </span>
                        @endif

                        @if($filters['era'])
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-navy-100 dark:bg-navy-800 text-navy-800 dark:text-gold-300 text-xs font-medium">
                                Era: {{ $filters['era'] }}
                                <a href="{{ route('books.index', array_merge(request()->query(), ['era' => ''])) }}" class="hover:text-rose-500">×</a>
                            </span>
                        @endif

                        @foreach($filters['categories'] as $catSlug)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-gold-500/10 text-gold-700 dark:text-gold-300 border border-gold-500/30 text-xs font-medium">
                                {{ $categories->firstWhere('slug', $catSlug)?->name ?? $catSlug }}
                                <a href="{{ route('books.index', array_merge(request()->query(), ['categories' => array_diff($filters['categories'], [$catSlug])])) }}" class="hover:text-rose-500">×</a>
                            </span>
                        @endforeach

                        <a href="{{ route('books.index') }}" class="text-xs text-rose-600 dark:text-rose-400 hover:underline font-semibold ml-2">
                            Reset All
                        </a>
                    </div>
                @endif

                {{-- Books Grid / Empty State --}}
                @if($books->isNotEmpty())
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($books as $book)
                            <x-book-card :book="$book" />
                        @endforeach
                    </div>

                    {{-- Pagination Links --}}
                    <div class="mt-8">
                        {{ $books->links() }}
                    </div>
                @else
                    <x-empty-state
                        title="No books match your criteria"
                        description="Try broadening your search term, clearing some facet filters, or browsing the full catalog."
                        actionText="Clear All Filters"
                        :actionUrl="route('books.index')"
                    />
                @endif
            </main>
        </div>
    </div>
</x-layouts.app>
