<x-layouts.admin title="Edit Book — ReadOra Admin" header="Edit Catalog Book">
    <div class="max-w-4xl rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-navy-800 dark:bg-navy-900">
        <form method="POST" action="{{ route('admin.books.update', $book) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                {{-- Title --}}
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">Book Title *</label>
                    <input type="text" name="title" value="{{ old('title', $book->title) }}" required class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-sm text-gray-900 dark:text-white" />
                    @error('title') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Subtitle --}}
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">Subtitle</label>
                    <input type="text" name="subtitle" value="{{ old('subtitle', $book->subtitle) }}" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-sm text-gray-900 dark:text-white" />
                </div>

                {{-- Authors --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">Authors * (Hold Ctrl for multi-select)</label>
                    <select name="authors[]" multiple required class="w-full h-32 px-3 py-2 rounded-lg border border-gray-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-xs text-gray-900 dark:text-white">
                        @php $selectedAuthors = old('authors', $book->authors->pluck('id')->toArray()); @endphp
                        @foreach($authors as $author)
                            <option value="{{ $author->id }}" {{ in_array($author->id, $selectedAuthors) ? 'selected' : '' }}>{{ $author->name }}</option>
                        @endforeach
                    </select>
                    @error('authors') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Categories --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">Categories * (Hold Ctrl for multi-select)</label>
                    <select name="categories[]" multiple required class="w-full h-32 px-3 py-2 rounded-lg border border-gray-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-xs text-gray-900 dark:text-white">
                        @php $selectedCategories = old('categories', $book->categories->pluck('id')->toArray()); @endphp
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ in_array($category->id, $selectedCategories) ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('categories') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Publisher --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">Publisher</label>
                    <select name="publisher_id" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-xs text-gray-900 dark:text-white">
                        <option value="">Independent Publishing</option>
                        @foreach($publishers as $pub)
                            <option value="{{ $pub->id }}" {{ old('publisher_id', $book->publisher_id) == $pub->id ? 'selected' : '' }}>{{ $pub->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- ISBN-13 --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">ISBN-13</label>
                    <input type="text" name="isbn_13" value="{{ old('isbn_13', $book->isbn_13) }}" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-xs text-gray-900 dark:text-white" />
                </div>

                {{-- ISBN-10 --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">ISBN-10</label>
                    <input type="text" name="isbn_10" value="{{ old('isbn_10', $book->isbn_10) }}" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-xs text-gray-900 dark:text-white" />
                </div>

                {{-- Publication Year --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">Publication Year</label>
                    <input type="number" name="publication_year" value="{{ old('publication_year', $book->publication_year) }}" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-xs text-gray-900 dark:text-white" />
                </div>

                {{-- Page Count --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">Page Count</label>
                    <input type="number" name="page_count" value="{{ old('page_count', $book->page_count) }}" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-xs text-gray-900 dark:text-white" />
                </div>

                {{-- Language --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">Language</label>
                    <input type="text" name="language" value="{{ old('language', $book->language) }}" required class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-xs text-gray-900 dark:text-white" />
                </div>

                {{-- Cover Image URL --}}
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">Cover Image URL</label>
                    <input type="url" name="cover_image_path" value="{{ old('cover_image_path', $book->cover_image_path) }}" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-xs text-gray-900 dark:text-white" />
                </div>

                {{-- Description --}}
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">Description / Synopsis</label>
                    <textarea name="description" rows="4" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-xs text-gray-900 dark:text-white">{{ old('description', $book->description) }}</textarea>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-navy-800">
                <a href="{{ route('admin.books.index') }}" class="px-4 py-2 border border-gray-300 dark:border-navy-700 rounded-lg text-xs font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-navy-800">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2 bg-gold-500 hover:bg-gold-600 text-navy-950 font-bold rounded-lg text-xs shadow-sm transition-colors">
                    Update Book Metadata
                </button>
            </div>
        </form>
    </div>
</x-layouts.admin>
