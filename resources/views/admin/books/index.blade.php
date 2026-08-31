<x-layouts.admin title="Books Management — ReadOra Admin" header="Catalog Books Management">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <form method="GET" action="{{ route('admin.books.index') }}" class="flex flex-wrap gap-2">
            <input
                type="text"
                name="search"
                value="{{ $search }}"
                placeholder="Search title, ISBN..."
                class="px-3 py-1.5 rounded-lg border border-gray-300 dark:border-navy-700 bg-white dark:bg-navy-900 text-xs text-gray-900 dark:text-white"
            />
            <select name="category_id" class="px-3 py-1.5 rounded-lg border border-gray-300 dark:border-navy-700 bg-white dark:bg-navy-900 text-xs text-gray-900 dark:text-white">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $categoryId === $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-3 py-1.5 bg-navy-900 dark:bg-gold-500 text-white dark:text-navy-950 font-bold rounded-lg text-xs hover:opacity-90">
                Search
            </button>
        </form>

        <a href="{{ route('admin.books.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-gold-500 hover:bg-gold-600 text-navy-950 font-bold rounded-lg text-xs shadow-sm transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Add New Book
        </a>
    </div>

    {{-- Books Table --}}
    <div class="rounded-lg border border-gray-200 bg-white shadow-sm dark:border-navy-800 dark:bg-navy-900 overflow-hidden">
        @if($books->isEmpty())
            <div class="p-8 text-center text-gray-400 text-xs">No books found in catalog.</div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50 dark:bg-navy-950 text-gray-500 dark:text-gray-400 uppercase tracking-wider text-[10px]">
                        <tr>
                            <th class="px-4 py-3">Book</th>
                            <th class="px-4 py-3">Authors</th>
                            <th class="px-4 py-3">Categories</th>
                            <th class="px-4 py-3">ISBN</th>
                            <th class="px-4 py-3">Copies</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-navy-800">
                        @foreach($books as $book)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-navy-800/40">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        @if($book->cover_image_path)
                                            <img src="{{ $book->cover_image_path }}" alt="" class="w-8 h-12 object-cover rounded shadow-sm shrink-0" onerror="this.style.display='none'" />
                                        @endif
                                        <div>
                                            <a href="{{ route('books.show', $book->slug) }}" target="_blank" class="font-bold text-gray-900 dark:text-white hover:text-gold-500">
                                                {{ $book->title }}
                                            </a>
                                            <span class="text-[10px] text-gray-400 block">{{ $book->publisher?->name ?? 'Independent' }} • {{ $book->publication_year }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                    {{ $book->authors->pluck('name')->join(', ') ?: 'Unknown' }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($book->categories as $cat)
                                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-gold-500/10 text-gold-600 dark:text-gold-400 border border-gold-500/20">{{ $cat->name }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-4 py-3 font-mono text-[11px] text-gray-500">
                                    {{ $book->isbn_13 ?? $book->isbn_10 ?? '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('admin.copies.index', ['book_id' => $book->id]) }}" class="font-bold text-navy-600 dark:text-gold-400 hover:underline">
                                        {{ $book->copies->count() }} ({{ $book->availableCopiesCount() }} Avail)
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('admin.books.edit', $book) }}" class="text-xs font-semibold text-navy-600 dark:text-gold-400 hover:underline">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.books.destroy', $book) }}" onsubmit="return confirm('Are you sure you want to delete this book?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-gray-100 dark:border-navy-800">
                {{ $books->links() }}
            </div>
        @endif
    </div>
</x-layouts.admin>
