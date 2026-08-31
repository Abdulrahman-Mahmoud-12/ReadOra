<x-layouts.app title="My Reading Shelves & Lists — ReadOra">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        {{-- Header & Create Action --}}
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-gray-200 dark:border-navy-800 pb-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gold-500/10 text-gold-600 dark:text-gold-400 text-xs font-semibold uppercase tracking-wider mb-2">
                    Patron Curations
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    My Reading Shelves & Lists
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Organize your library journey, create custom reading collections, and share curated lists.
                </p>
            </div>

            <button
                type="button"
                onclick="document.getElementById('create-list-modal').classList.remove('hidden')"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-gold-500 hover:bg-gold-600 text-navy-950 font-bold rounded-lg text-xs shadow-sm transition-colors"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Create New Shelf
            </button>
        </div>

        {{-- Reading Lists Grid --}}
        @if($readingLists->isEmpty())
            <x-empty-state
                title="No shelves found"
                description="Start organizing your library by creating your first reading shelf or list."
                actionText="Create a Shelf"
                actionUrl="javascript:document.getElementById('create-list-modal').classList.remove('hidden')"
            />
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($readingLists as $list)
                    <div class="group flex flex-col rounded-xl border border-gray-200 bg-white shadow-sm transition-all duration-300 hover:border-gold-300 hover:shadow-xl dark:border-navy-800 dark:bg-navy-900 overflow-hidden">
                        {{-- Cover Thumbnails Preview Strip --}}
                        <div class="h-32 bg-navy-950 p-3 flex items-center gap-2 overflow-hidden relative">
                            @forelse($list->books as $b)
                                <div class="h-full aspect-[2/3] bg-navy-900 rounded overflow-hidden shadow-md border border-navy-800 shrink-0">
                                    @if($b->cover_image_path)
                                        <img src="{{ $b->cover_image_path }}" alt="{{ $b->title }}" class="w-full h-full object-cover" />
                                    @else
                                        <div class="w-full h-full bg-navy-800 flex items-center justify-center text-[10px] text-gold-400 p-1 text-center font-bold">
                                            {{ substr($b->title, 0, 8) }}
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="w-full h-full flex items-center justify-center text-xs text-gray-500">
                                    Empty shelf (0 books)
                                </div>
                            @endforelse

                            <div class="absolute top-2 right-2">
                                <x-badge :variant="$list->is_public ? 'available' : 'default'" size="sm">
                                    {{ $list->is_public ? 'Public' : 'Private' }}
                                </x-badge>
                            </div>
                        </div>

                        {{-- Card Details --}}
                        <div class="p-5 flex-1 flex flex-col justify-between gap-4">
                            <div>
                                <a href="{{ route('reading-lists.show', $list->slug) }}" class="block group-hover:text-gold-600 dark:group-hover:text-gold-400 transition-colors">
                                    <h3 class="text-base font-bold text-gray-900 dark:text-white">
                                        {{ $list->name }}
                                    </h3>
                                </a>
                                @if($list->description)
                                    <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-2 mt-1 leading-relaxed">
                                        {{ $list->description }}
                                    </p>
                                @endif
                            </div>

                            <div class="flex items-center justify-between border-t border-gray-100 dark:border-navy-800 pt-3 text-xs">
                                <span class="font-medium text-gray-500 dark:text-gray-400 font-mono">
                                    {{ $list->books_count }} {{ Str::plural('title', $list->books_count) }}
                                </span>

                                <a href="{{ route('reading-lists.show', $list->slug) }}" class="inline-flex items-center gap-1 font-bold text-gold-600 dark:text-gold-400 hover:underline">
                                    View Shelf →
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Create Shelf Modal --}}
    <div id="create-list-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-navy-950/80 backdrop-blur-sm">
        <div class="w-full max-w-md bg-white dark:bg-navy-900 rounded-2xl border border-gray-200 dark:border-navy-800 p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-navy-800 pb-3">
                <h3 class="text-base font-bold text-gray-900 dark:text-white">Create New Reading Shelf</h3>
                <button type="button" onclick="document.getElementById('create-list-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 dark:hover:text-white text-lg font-bold">
                    ×
                </button>
            </div>

            <form method="POST" action="{{ route('reading-lists.store') }}" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-bold uppercase text-gray-600 dark:text-gray-300 mb-1">Shelf Name *</label>
                    <input
                        type="text"
                        name="name"
                        required
                        placeholder="e.g. Summer Sci-Fi, Favorites of 2026..."
                        class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-navy-700 bg-gray-50 dark:bg-navy-950 text-xs text-gray-900 dark:text-white"
                    />
                </div>

                <div>
                    <label class="block font-bold uppercase text-gray-600 dark:text-gray-300 mb-1">Description (Optional)</label>
                    <textarea
                        name="description"
                        rows="2"
                        placeholder="Brief summary of what this collection is about..."
                        class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-navy-700 bg-gray-50 dark:bg-navy-950 text-xs text-gray-900 dark:text-white"
                    ></textarea>
                </div>

                <div>
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_public" value="1" class="w-4 h-4 rounded text-gold-500 focus:ring-gold-400 dark:border-navy-700 dark:bg-navy-950">
                        <span class="font-semibold text-gray-800 dark:text-gray-200">Make this shelf publicly shareable via link</span>
                    </label>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-100 dark:border-navy-800">
                    <button
                        type="button"
                        onclick="document.getElementById('create-list-modal').classList.add('hidden')"
                        class="px-4 py-2 rounded-lg border border-gray-300 dark:border-navy-700 text-gray-700 dark:text-gray-300 font-semibold"
                    >
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 bg-gold-500 hover:bg-gold-600 text-navy-950 font-bold rounded-lg shadow-sm">
                        Create Shelf
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
