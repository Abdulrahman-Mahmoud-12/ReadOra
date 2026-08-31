<x-layouts.app :title="$book->title . ' — ReadOra'">
    @php
        $availableCopies = $book->availableCopiesCount();
        $isAvailable = $book->isAvailable();
        $authorsString = $book->authors->pluck('name')->join(', ') ?: 'Unknown Author';
        $rating = (float) $book->average_rating;
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        {{-- Breadcrumbs --}}
        <nav class="mb-6 flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
            <a href="/" class="hover:text-navy-600 dark:hover:text-gold-400 transition-colors">Home</a>
            <span>/</span>
            <a href="{{ route('books.index') }}" class="hover:text-navy-600 dark:hover:text-gold-400 transition-colors">Catalog</a>
            <span>/</span>
            <span class="text-gray-900 dark:text-white font-medium truncate max-w-xs sm:max-w-md">{{ $book->title }}</span>
        </nav>

        {{-- Main Book Profile --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 mb-12">
            {{-- Left Column: Book Spine Cover & Actions --}}
            <div class="lg:col-span-4 flex flex-col gap-6">
                {{-- Book Cover Display --}}
                <div class="relative mx-auto w-full max-w-sm overflow-hidden rounded-lg border border-navy-800 shadow-2xl bg-navy-950">
                    @if($book->cover_image_path)
                        <div class="relative aspect-[3/4] w-full overflow-hidden bg-navy-900">
                            <img
                                src="{{ $book->cover_image_path }}"
                                alt="{{ $book->title }}"
                                class="w-full h-full object-cover shadow-inner"
                                onerror="this.parentElement.style.display='none'; this.parentElement.nextElementSibling.classList.remove('hidden');"
                            />
                        </div>
                    @endif

                    {{-- Stylized Fallback Book Spine Cover --}}
                    <div class="{{ $book->cover_image_path ? 'hidden' : '' }} relative flex aspect-[3/4] w-full flex-col justify-between overflow-hidden bg-gradient-to-br from-navy-950 via-navy-900 to-navy-800 p-6 sm:p-8">
                        {{-- Decorative pattern --}}
                        <div class="absolute inset-0 opacity-15">
                            <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <pattern id="show-pattern" width="24" height="24" patternUnits="userSpaceOnUse">
                                        <path d="M 24 0 L 0 0 0 24" fill="none" stroke="currentColor" stroke-width="0.5" class="text-gold-400" />
                                    </pattern>
                                </defs>
                                <rect width="100%" height="100%" fill="url(#show-pattern)" />
                            </svg>
                        </div>

                        {{-- Left decorative spine --}}
                        <div class="absolute left-0 top-0 bottom-0 w-3 bg-gradient-to-r from-gold-600 to-gold-400/40 border-r border-gold-400/30"></div>

                        <div class="relative pl-3">
                            <div class="flex flex-wrap gap-1.5 mb-3">
                                @foreach($book->categories as $category)
                                    <x-badge variant="gold" size="sm">
                                        {{ $category->name }}
                                    </x-badge>
                                @endforeach
                            </div>

                            <h1 class="text-2xl sm:text-3xl font-bold text-white leading-tight">
                                {{ $book->title }}
                            </h1>
                            @if($book->subtitle)
                                <p class="text-sm text-gold-300 font-medium mt-1">
                                    {{ $book->subtitle }}
                                </p>
                            @endif
                        </div>

                        <div class="relative pl-3 pt-6 border-t border-navy-700/80">
                            <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Author</p>
                            <p class="text-base text-white font-medium mt-0.5">
                                {{ $authorsString }}
                            </p>
                            @if($book->publisher)
                                <p class="text-xs text-gray-400 mt-2">Published by <span class="text-gray-300 font-medium">{{ $book->publisher->name }}</span></p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Action Panel Card --}}
                <div class="mx-auto w-full max-w-sm rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-navy-800 dark:bg-navy-900">
                    <div class="mb-4 flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 pb-4 dark:border-navy-800">
                        <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Library Status</span>
                        <x-badge :variant="$isAvailable ? 'available' : 'borrowed'" size="md">
                            {{ $isAvailable ? "{$availableCopies} Available" : 'Currently Unavailable' }}
                        </x-badge>
                    </div>

                    @php
                        $user = auth()->user();
                        $hasActiveLoan = $user ? $user->activeBorrowings()->whereHas('bookCopy', fn($q) => $q->where('book_id', $book->id))->exists() : false;
                        $isFavorited = $book->isFavoritedBy($user);
                    @endphp

                    <div class="space-y-3">
                        @auth
                            @if($hasActiveLoan)
                                <div class="w-full py-2.5 px-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 rounded-lg text-emerald-800 dark:text-emerald-300 text-xs font-semibold flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                    Currently in Your Loans
                                </div>
                                <x-button
                                    href="{{ route('borrowings.index') }}"
                                    variant="outline"
                                    size="md"
                                    class="w-full justify-center"
                                >
                                    View in Circulation
                                </x-button>
                            @elseif($isAvailable)
                                <form method="POST" action="{{ route('borrowings.store') }}">
                                    @csrf
                                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                                    <button
                                        type="submit"
                                        class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-lg text-sm font-semibold transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 bg-gradient-to-r from-navy-800 to-navy-950 hover:from-navy-700 hover:to-navy-900 text-white shadow-md hover:shadow-lg focus:ring-navy-500 border border-gold-500/20"
                                    >
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                        Borrow This Book
                                    </button>
                                </form>
                            @else
                                <button
                                    disabled
                                    class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-lg text-sm font-semibold bg-gray-100 dark:bg-navy-800 text-gray-400 dark:text-gray-500 cursor-not-allowed border border-gray-200 dark:border-navy-700"
                                >
                                    No Copies Available
                                </button>
                            @endif

                            <form method="POST" action="{{ route('favorites.toggle', $book) }}">
                                @csrf
                                <button
                                    type="submit"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium transition-colors border {{ $isFavorited ? 'border-rose-300 dark:border-rose-800 bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400' : 'border-gray-300 dark:border-navy-700 bg-white dark:bg-navy-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-navy-700' }}"
                                >
                                    <svg class="w-4 h-4 mr-2 {{ $isFavorited ? 'fill-current text-rose-500' : 'text-rose-500' }}" fill="{{ $isFavorited ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                                    {{ $isFavorited ? 'Favorited' : 'Save to Favorites' }}
                                </button>
                            </form>

                            {{-- Reading Shelves Dropdown Menu --}}
                            <div class="relative" id="shelves-menu-container">
                                <button
                                    type="button"
                                    onclick="document.getElementById('shelves-dropdown').classList.toggle('hidden')"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg text-xs font-semibold border border-gold-400/40 bg-gold-500/10 text-gold-700 dark:text-gold-300 hover:bg-gold-500/20 transition-colors"
                                >
                                    <svg class="w-4 h-4 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                                    Add to Reading Shelf
                                    <svg class="w-3.5 h-3.5 ml-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </button>

                                <div id="shelves-dropdown" class="hidden absolute left-0 right-0 top-full mt-1.5 bg-white dark:bg-navy-900 border border-gray-200 dark:border-navy-700 rounded-xl shadow-xl z-30 p-2 space-y-1 text-xs">
                                    <div class="px-2 py-1 text-[10px] font-bold uppercase tracking-wider text-gray-400">Select Shelf / List</div>
                                    @foreach($userReadingLists as $shelf)
                                        @php $inShelf = $shelf->hasBook($book); @endphp
                                        <div class="flex items-center justify-between px-2 py-1.5 rounded-lg hover:bg-gray-50 dark:hover:bg-navy-800 transition-colors">
                                            <span class="font-medium text-gray-800 dark:text-gray-200 truncate {{ $inShelf ? 'text-gold-600 dark:text-gold-400 font-bold' : '' }}">
                                                {{ $shelf->name }}
                                            </span>

                                            @if($inShelf)
                                                <form method="POST" action="{{ route('reading-lists.books.remove', [$shelf, $book]) }}" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-[10px] font-bold text-rose-600 dark:text-rose-400 hover:underline">
                                                        Remove
                                                    </button>
                                                </form>
                                            @else
                                                <form method="POST" action="{{ route('reading-lists.books.add', [$shelf, $book]) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-[10px] font-bold text-navy-800 dark:text-gold-400 hover:underline">
                                                        + Add
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    @endforeach

                                    <div class="pt-1.5 border-t border-gray-100 dark:border-navy-800 mt-1">
                                        <a href="{{ route('reading-lists.index') }}" class="block text-center text-[10px] font-bold text-gold-600 dark:text-gold-400 hover:underline">
                                            Manage All Shelves →
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="p-3 bg-navy-50 dark:bg-navy-950 rounded-xl border border-navy-100 dark:border-navy-800 text-xs text-gray-600 dark:text-gray-400">
                                <span class="font-semibold text-navy-800 dark:text-gold-300">Patron Loan Limit:</span> 14 days standard borrowing period with online renewal.
                            </div>
                        @else
                            <a
                                href="{{ route('login') }}"
                                class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-lg text-sm font-semibold bg-gradient-to-r from-navy-800 to-navy-950 hover:from-navy-700 hover:to-navy-900 text-white shadow-md"
                            >
                                Sign in to Borrow
                            </a>
                            <a
                                href="{{ route('login') }}"
                                class="w-full inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium border border-gray-300 dark:border-navy-700 bg-white dark:bg-navy-800 text-gray-700 dark:text-gray-300"
                            >
                                <svg class="w-4 h-4 mr-2 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                                Sign in to Favorite
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

            {{-- Right Column: Metadata, Description & Copies Inventory --}}
            <div class="lg:col-span-8 flex flex-col gap-8">
                {{-- Metadata Grid Card --}}
                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-navy-800 dark:bg-navy-900 sm:p-8">
                    <div class="flex flex-wrap items-center justify-between gap-4 pb-6 border-b border-gray-100 dark:border-navy-800">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $book->title }}</h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">By <span class="font-semibold text-gray-900 dark:text-white">{{ $authorsString }}</span></p>
                        </div>

                        {{-- Rating Summary --}}
                        <div class="flex items-center gap-2 bg-gold-50 dark:bg-gold-900/30 px-3.5 py-2 rounded-xl border border-gold-200 dark:border-gold-800/50">
                            <svg class="w-5 h-5 text-gold-500 fill-current" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <div class="text-left">
                                <span class="text-sm font-bold text-gray-900 dark:text-white">{{ number_format($rating, 1) }}</span>
                                <span class="text-[10px] text-gray-500 dark:text-gray-400 block">{{ number_format($book->ratings_count) }} ratings</span>
                            </div>
                        </div>
                    </div>

                    {{-- Bibliographic Specs --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 py-6 border-b border-gray-100 dark:border-navy-800 text-xs">
                        <div>
                            <span class="text-gray-400 block">Publication Year</span>
                            <span class="font-bold text-gray-900 dark:text-white text-sm mt-0.5 block">
                                {{ $book->publication_year ? ($book->publication_year > 0 ? $book->publication_year : abs($book->publication_year) . ' BCE') : 'N/A' }}
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-400 block">Page Count</span>
                            <span class="font-bold text-gray-900 dark:text-white text-sm mt-0.5 block">
                                {{ $book->page_count ? $book->page_count . ' pages' : 'N/A' }}
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-400 block">Language</span>
                            <span class="font-bold text-gray-900 dark:text-white text-sm mt-0.5 block uppercase">
                                {{ $book->language }}
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-400 block">Edition</span>
                            <span class="font-bold text-gray-900 dark:text-white text-sm mt-0.5 block truncate">
                                {{ $book->edition ?: 'Standard Edition' }}
                            </span>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="pt-6">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-gray-900 dark:text-white mb-2">Synopsis</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                            {{ $book->description ?: 'No synopsis available for this public-domain record.' }}
                        </p>
                    </div>

                    {{-- AI Book Insights & Key Takeaways Generator --}}
                    <div class="mt-6 pt-6 border-t border-gray-100 dark:border-navy-800">
                        <div class="rounded-xl border border-gold-500/30 bg-gold-500/5 p-5">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-gold-400 to-gold-600 text-navy-950 font-extrabold text-xs flex items-center justify-center shrink-0 shadow-md">
                                        AI
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold uppercase tracking-wider text-gold-700 dark:text-gold-400">ReadOra AI Deep Book Insights</h4>
                                        <p class="text-xs text-gray-600 dark:text-gray-300">Generate structured synopsis, key takeaways & discussion questions powered by OpenRouter.</p>
                                    </div>
                                </div>

                                <button
                                    type="button"
                                    id="generate-ai-insights-btn"
                                    onclick="generateAiInsights({{ $book->id }})"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-gold-500 hover:bg-gold-600 text-navy-950 font-bold rounded-lg text-xs shadow-sm transition-all shrink-0"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                    <span>Generate AI Insights</span>
                                </button>
                            </div>

                            {{-- AI Insights Output Container --}}
                            <div id="ai-insights-container" class="hidden mt-5 pt-4 border-t border-gold-500/20">
                                <div id="ai-insights-loading" class="hidden py-6 text-center text-xs text-gold-600 dark:text-gold-400 font-semibold space-y-2">
                                    <div class="inline-block w-6 h-6 border-2 border-gold-500 border-t-transparent rounded-full animate-spin"></div>
                                    <p>ReadOra AI is analyzing this title and synthesizing key takeaways...</p>
                                </div>
                                <div id="ai-insights-content" class="text-xs leading-relaxed text-gray-800 dark:text-gray-200 whitespace-pre-line space-y-3"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Identifiers & Source --}}
                    @if($book->isbn_10 || $book->isbn_13 || $book->source_identifier)
                        <div class="mt-6 pt-4 border-t border-gray-100 dark:border-navy-800 flex flex-wrap gap-4 text-xs text-gray-500 dark:text-gray-400">
                            @if($book->isbn_13)
                                <span>ISBN-13: <strong class="text-gray-700 dark:text-gray-300">{{ $book->isbn_13 }}</strong></span>
                            @endif
                            @if($book->isbn_10)
                                <span>ISBN-10: <strong class="text-gray-700 dark:text-gray-300">{{ $book->isbn_10 }}</strong></span>
                            @endif
                            @if($book->source_identifier)
                                <span>Catalog ID: <strong class="text-gray-700 dark:text-gray-300">{{ $book->source_identifier }}</strong></span>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- Physical Copies Inventory Table --}}
                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-navy-800 dark:bg-navy-900 sm:p-8">
                    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Physical Library Copies</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Inventory locations and real-time shelf status.</p>
                        </div>
                        <span class="text-xs font-semibold px-3 py-1 bg-navy-50 dark:bg-navy-800 rounded-lg text-navy-700 dark:text-gold-300">
                            {{ $book->copies->count() }} Total Units
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-gray-50 dark:bg-navy-950 text-gray-500 dark:text-gray-400 uppercase tracking-wider text-[10px]">
                                <tr>
                                    <th class="px-4 py-3 rounded-l-lg">Barcode</th>
                                    <th class="px-4 py-3">Location</th>
                                    <th class="px-4 py-3">Condition</th>
                                    <th class="px-4 py-3">Acquired</th>
                                    <th class="px-4 py-3 rounded-r-lg text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-navy-800">
                                @forelse($book->copies as $copy)
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-navy-800/40 transition-colors">
                                        <td class="px-4 py-3 font-mono font-semibold text-gray-900 dark:text-white">
                                            {{ $copy->barcode }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                                            {{ $copy->location ?: 'Main Stacks' }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300 capitalize">
                                            {{ $copy->condition ?: 'Good' }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-500 dark:text-gray-400">
                                            {{ $copy->acquisition_date ? $copy->acquisition_date->format('M d, Y') : 'Catalog Record' }}
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <x-badge :variant="$copy->status === 'available' ? 'available' : ($copy->status === 'borrowed' ? 'borrowed' : 'default')" size="sm">
                                                {{ ucfirst($copy->status) }}
                                            </x-badge>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                                            No physical copies currently registered for this title.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                </div>

                {{-- Patron Reviews & Ratings Breakdown Section --}}
                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-navy-800 dark:bg-navy-900 sm:p-8">
                    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-gray-100 dark:border-navy-800 pb-6">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Patron Reviews & Ratings</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Community feedback and ratings for this edition.</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ number_format($book->average_rating, 1) }}</span>
                            <div>
                                <div class="flex text-gold-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= round($book->average_rating) ? 'fill-current' : 'text-gray-300 dark:text-navy-700' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                    @endfor
                                </div>
                                <span class="text-[11px] text-gray-500 dark:text-gray-400">{{ number_format($book->ratings_count) }} ratings</span>
                            </div>
                        </div>
                    </div>

                    {{-- Rating Distribution Progress Bars --}}
                    <div class="mb-8 grid grid-cols-1 md:grid-cols-12 gap-8 items-center bg-gray-50 dark:bg-navy-950 p-5 rounded-xl">
                        <div class="md:col-span-7 space-y-2 text-xs">
                            @foreach($ratingDistribution as $star => $data)
                                <div class="flex items-center gap-3">
                                    <span class="w-12 font-bold text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ $star }} ★</span>
                                    <div class="flex-1 h-2.5 bg-gray-200 dark:bg-navy-800 rounded-full overflow-hidden">
                                        <div class="h-full bg-gold-500 rounded-full transition-all duration-500" style="width: {{ $data['percentage'] }}%"></div>
                                    </div>
                                    <span class="w-10 text-right text-[11px] text-gray-500 dark:text-gray-400 font-mono">{{ $data['percentage'] }}%</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="md:col-span-5 text-center md:border-l border-gray-200 dark:border-navy-800 md:pl-6">
                            @auth
                                <p class="text-xs text-gray-600 dark:text-gray-300 mb-2">
                                    {{ $userReview ? 'You rated this book:' : 'Have you read this book?' }}
                                </p>
                                @if($userReview)
                                    <div class="inline-flex items-center gap-1 text-gold-500 font-bold mb-2">
                                        {{ $userReview->rating }} / 5 Stars ★
                                    </div>
                                @endif
                                <a href="#review-form" class="inline-block px-4 py-2 bg-gold-500 hover:bg-gold-600 text-navy-950 font-bold rounded-lg text-xs shadow-sm transition-colors">
                                    {{ $userReview ? 'Edit Your Review' : 'Write a Patron Review' }}
                                </a>
                            @else
                                <p class="text-xs text-gray-600 dark:text-gray-300 mb-3">Sign in to share your thoughts with fellow readers.</p>
                                <a href="{{ route('login') }}" class="px-4 py-2 bg-navy-900 dark:bg-gold-500 text-white dark:text-navy-950 font-bold rounded-lg text-xs shadow-sm">
                                    Sign In to Review
                                </a>
                            @endauth
                        </div>
                    </div>

                    {{-- Review Submission Form --}}
                    @auth
                        <div id="review-form" class="mb-8 rounded-xl border border-gray-200 dark:border-navy-800 p-5 bg-white dark:bg-navy-900">
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-3">
                                {{ $userReview ? 'Update Your Review' : 'Write a Review' }}
                            </h4>
                            <form method="POST" action="{{ route('reviews.store', $book) }}" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-1">Your Rating *</label>
                                    <div class="flex items-center gap-4 text-xs">
                                        @for($star = 5; $star >= 1; $star--)
                                            <label class="flex items-center gap-1 cursor-pointer">
                                                <input
                                                    type="radio"
                                                    name="rating"
                                                    value="{{ $star }}"
                                                    {{ old('rating', $userReview?->rating ?? 5) == $star ? 'checked' : '' }}
                                                    required
                                                    class="text-gold-500"
                                                >
                                                <span class="font-bold text-gray-800 dark:text-gray-200">{{ $star }} ★</span>
                                            </label>
                                        @endfor
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-1">Review Title</label>
                                    <input
                                        type="text"
                                        name="title"
                                        value="{{ old('title', $userReview?->title) }}"
                                        placeholder="Summarize your review in a sentence..."
                                        class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-navy-700 bg-gray-50 dark:bg-navy-950 text-xs text-gray-900 dark:text-white"
                                    />
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-1">Review Commentary</label>
                                    <textarea
                                        name="content"
                                        rows="3"
                                        placeholder="What did you like or dislike about this book? Who would you recommend it to?"
                                        class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-navy-700 bg-gray-50 dark:bg-navy-950 text-xs text-gray-900 dark:text-white"
                                    >{{ old('content', $userReview?->content) }}</textarea>
                                </div>

                                <div class="flex items-center justify-between pt-2">
                                    @if($userReview)
                                        <button
                                            type="submit"
                                            form="delete-review-form"
                                            onclick="return confirm('Delete your review?');"
                                            class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline"
                                        >
                                            Delete Review
                                        </button>
                                    @else
                                        <span></span>
                                    @endif

                                    <button type="submit" class="px-5 py-2 bg-gold-500 hover:bg-gold-600 text-navy-950 font-bold rounded-lg text-xs shadow-sm transition-colors">
                                        {{ $userReview ? 'Update Review' : 'Submit Review' }}
                                    </button>
                                </div>
                            </form>

                            @if($userReview)
                                <form id="delete-review-form" method="POST" action="{{ route('reviews.destroy', $userReview) }}" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @endif
                        </div>
                    @endauth

                    {{-- Approved Reviews List --}}
                    <div class="space-y-4">
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white">
                            Community Reviews ({{ $book->approvedReviews->count() }})
                        </h4>

                        @forelse($book->approvedReviews as $rev)
                            <div class="p-4 rounded-xl bg-gray-50 dark:bg-navy-950 border border-gray-100 dark:border-navy-800 space-y-2">
                                <div class="flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-navy-900 text-gold-400 font-bold text-xs flex items-center justify-center border border-navy-700">
                                            {{ strtoupper(substr($rev->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <strong class="text-xs text-gray-900 dark:text-white block leading-tight">{{ $rev->user->name }}</strong>
                                            <span class="text-[10px] text-gray-400">{{ $rev->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>

                                    <div class="flex items-center text-gold-400 text-xs font-bold gap-0.5">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-3.5 h-3.5 {{ $i <= $rev->rating ? 'fill-current' : 'text-gray-300 dark:text-navy-700' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                        @endfor
                                    </div>
                                </div>

                                @if($rev->title)
                                    <h5 class="text-xs font-bold text-gray-900 dark:text-white pt-1">{{ $rev->title }}</h5>
                                @endif

                                @if($rev->content)
                                    <p class="text-xs text-gray-700 dark:text-gray-300 leading-relaxed">{{ $rev->content }}</p>
                                @endif
                            </div>
                        @empty
                            <p class="text-xs text-gray-400 py-6 text-center">No community reviews yet for this title. Be the first patron to review it!</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Related Books Section --}}
        @if($relatedBooks->isNotEmpty())
            <div class="border-t border-gray-200 pt-8 dark:border-navy-800">
                <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Related Books in Collection</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Works sharing related themes, genres, or classifications.</p>
                    </div>
                    <a href="{{ route('books.index', ['category' => $book->categories->first()?->slug]) }}" class="text-xs font-semibold text-gold-600 dark:text-gold-400 hover:underline">
                        Browse Category →
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                    @foreach($relatedBooks as $related)
                        <x-book-card :book="$related" />
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <script>
        async function generateAiInsights(bookId) {
            const btn = document.getElementById('generate-ai-insights-btn');
            const container = document.getElementById('ai-insights-container');
            const loading = document.getElementById('ai-insights-loading');
            const content = document.getElementById('ai-insights-content');

            container.classList.remove('hidden');
            loading.classList.remove('hidden');
            content.innerHTML = '';
            btn.disabled = true;
            btn.classList.add('opacity-60', 'cursor-not-allowed');

            try {
                const response = await fetch(`/books/${bookId}/ai-insights`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();
                loading.classList.add('hidden');

                if (data.success && data.insights) {
                    let formatted = data.insights
                        .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
                        .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                        .replace(/^\s*•\s*(.*)$/gm, '• $1');
                    content.innerHTML = formatted;
                } else {
                    content.innerHTML = '<p class="text-rose-500">Could not generate AI insights at this time. Please try again later.</p>';
                }
            } catch (err) {
                loading.classList.add('hidden');
                content.innerHTML = '<p class="text-rose-500">Network error while connecting to AI assistant.</p>';
            } finally {
                btn.disabled = false;
                btn.classList.remove('opacity-60', 'cursor-not-allowed');
            }
        }
    </script>
</x-layouts.app>
