<x-layouts.app :title="$readingList->name . ' — Curated by ' . $readingList->user->name">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        {{-- Public Shelf Header Banner --}}
        <div class="mb-8 rounded-2xl border border-gray-200 bg-white p-6 sm:p-8 shadow-sm dark:border-navy-800 dark:bg-navy-900">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gold-500/10 text-gold-600 dark:text-gold-400 text-xs font-semibold uppercase tracking-wider mb-3">
                        Curated Patron Shelf
                    </div>
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white">
                        {{ $readingList->name }}
                    </h1>
                    @if($readingList->description)
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 max-w-2xl leading-relaxed">
                            {{ $readingList->description }}
                        </p>
                    @endif

                    <div class="mt-4 flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-navy-900 text-gold-400 font-bold text-[10px] flex items-center justify-center border border-navy-700">
                                {{ strtoupper(substr($readingList->user->name, 0, 1)) }}
                            </div>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $readingList->user->name }}</span>
                        </div>
                        <span>•</span>
                        <span>{{ $readingList->books->count() }} {{ Str::plural('book', $readingList->books->count()) }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('books.index') }}" class="px-4 py-2 bg-gold-500 hover:bg-gold-600 text-navy-950 font-bold rounded-lg text-xs shadow-sm transition-colors">
                        Explore Full Library
                    </a>
                </div>
            </div>
        </div>

        {{-- Books Grid --}}
        @if($readingList->books->isEmpty())
            <x-empty-state
                title="This shelf is empty"
                description="No books have been added to this public shelf yet."
                actionText="Browse Library"
                :actionUrl="route('books.index')"
            />
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($readingList->books as $book)
                    <x-book-card :book="$book" />
                @endforeach
            </div>
        @endif
    </div>
</x-layouts.app>
