<x-layouts.app :title="$readingList->name . ' — Reading Shelf'">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        {{-- Breadcrumbs & Top Bar --}}
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <nav class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                <a href="{{ route('reading-lists.index') }}" class="hover:text-gold-500">My Shelves</a>
                <span>/</span>
                <span class="text-gray-900 dark:text-white font-semibold truncate max-w-xs">{{ $readingList->name }}</span>
            </nav>

            <div class="flex items-center gap-2">
                @if($readingList->is_public)
                    <a
                        href="{{ route('reading-lists.public', $readingList->slug) }}"
                        target="_blank"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gold-500/40 bg-gold-500/10 text-gold-600 dark:text-gold-400 font-semibold text-xs hover:bg-gold-500/20"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" /></svg>
                        Share Public Link
                    </a>
                @endif

                <button
                    type="button"
                    onclick="document.getElementById('edit-shelf-modal').classList.remove('hidden')"
                    class="px-3 py-1.5 rounded-lg border border-gray-300 dark:border-navy-700 bg-white dark:bg-navy-900 text-xs font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50"
                >
                    Edit Shelf
                </button>
            </div>
        </div>

        {{-- Shelf Banner --}}
        <div class="mb-8 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-navy-800 dark:bg-navy-900 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">
                        {{ $readingList->name }}
                    </h1>
                    <x-badge :variant="$readingList->is_public ? 'available' : 'default'" size="sm">
                        {{ $readingList->is_public ? 'Public' : 'Private' }}
                    </x-badge>
                </div>
                @if($readingList->description)
                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 max-w-2xl leading-relaxed">
                        {{ $readingList->description }}
                    </p>
                @endif
            </div>

            <div class="text-xs font-mono font-bold text-gray-500 dark:text-gray-400 shrink-0 bg-gray-50 dark:bg-navy-950 px-3 py-2 rounded-lg">
                {{ $readingList->books->count() }} {{ Str::plural('book', $readingList->books->count()) }}
            </div>
        </div>

        {{-- Books Grid --}}
        @if($readingList->books->isEmpty())
            <x-empty-state
                title="This shelf is currently empty"
                description="Explore our digital collection catalog and click 'Add to Shelf' on any book."
                actionText="Explore Catalog"
                :actionUrl="route('books.index')"
            />
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($readingList->books as $book)
                    <div class="relative flex flex-col rounded-xl border border-gray-200 bg-white shadow-sm dark:border-navy-800 dark:bg-navy-900 overflow-hidden group">
                        <x-book-card :book="$book" />

                        {{-- Shelf Book Actions Overlay --}}
                        <div class="p-3 border-t border-gray-100 dark:border-navy-800 bg-gray-50 dark:bg-navy-950 flex items-center justify-between text-xs">
                            <span class="text-[11px] text-gray-500 dark:text-gray-400 truncate max-w-[12rem]">
                                {{ $book->pivot->notes ?: 'No personal notes' }}
                            </span>

                            <form method="POST" action="{{ route('reading-lists.books.remove', [$readingList, $book]) }}" onsubmit="return confirm('Remove this book from this shelf?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 dark:text-rose-400 font-bold hover:underline">
                                    Remove
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Edit Shelf Modal --}}
    <div id="edit-shelf-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-navy-950/80 backdrop-blur-sm">
        <div class="w-full max-w-md bg-white dark:bg-navy-900 rounded-2xl border border-gray-200 dark:border-navy-800 p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-navy-800 pb-3">
                <h3 class="text-base font-bold text-gray-900 dark:text-white">Edit Shelf Settings</h3>
                <button type="button" onclick="document.getElementById('edit-shelf-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 dark:hover:text-white text-lg font-bold">
                    ×
                </button>
            </div>

            <form method="POST" action="{{ route('reading-lists.update', $readingList) }}" class="space-y-4 text-xs">
                @csrf
                @method('PATCH')
                <div>
                    <label class="block font-bold uppercase text-gray-600 dark:text-gray-300 mb-1">Shelf Name *</label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $readingList->name) }}"
                        required
                        class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-navy-700 bg-gray-50 dark:bg-navy-950 text-xs text-gray-900 dark:text-white"
                    />
                </div>

                <div>
                    <label class="block font-bold uppercase text-gray-600 dark:text-gray-300 mb-1">Description</label>
                    <textarea
                        name="description"
                        rows="2"
                        class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-navy-700 bg-gray-50 dark:bg-navy-950 text-xs text-gray-900 dark:text-white"
                    >{{ old('description', $readingList->description) }}</textarea>
                </div>

                <div>
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input
                            type="checkbox"
                            name="is_public"
                            value="1"
                            {{ $readingList->is_public ? 'checked' : '' }}
                            class="w-4 h-4 rounded text-gold-500 focus:ring-gold-400 dark:border-navy-700 dark:bg-navy-950"
                        >
                        <span class="font-semibold text-gray-800 dark:text-gray-200">Public (anyone with the link can view)</span>
                    </label>
                </div>

                <div class="flex items-center justify-between pt-2 border-t border-gray-100 dark:border-navy-800">
                    <button
                        type="button"
                        onclick="if(confirm('Permanently delete this reading list?')) { document.getElementById('delete-shelf-form').submit(); }"
                        class="text-rose-600 dark:text-rose-400 font-bold hover:underline"
                    >
                        Delete Shelf
                    </button>

                    <div class="flex gap-2">
                        <button
                            type="button"
                            onclick="document.getElementById('edit-shelf-modal').classList.add('hidden')"
                            class="px-4 py-2 rounded-lg border border-gray-300 dark:border-navy-700 text-gray-700 dark:text-gray-300 font-semibold"
                        >
                            Cancel
                        </button>
                        <button type="submit" class="px-5 py-2 bg-gold-500 hover:bg-gold-600 text-navy-950 font-bold rounded-lg shadow-sm">
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>

            <form id="delete-shelf-form" method="POST" action="{{ route('reading-lists.destroy', $readingList) }}" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</x-layouts.app>
