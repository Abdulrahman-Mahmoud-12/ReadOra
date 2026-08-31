<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Category;
use App\Models\Publisher;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminBookController extends Controller
{
    public function __construct(
        protected AuditLogger $auditLogger
    ) {}

    /**
     * Display list of books in administrative catalog.
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();
        $categoryId = $request->integer('category_id');

        $books = Book::query()
            ->with(['publisher', 'authors', 'categories', 'copies'])
            ->when($search !== '', fn ($q) => $q->search($search))
            ->when($categoryId > 0, fn ($q) => $q->whereHas('categories', fn ($cq) => $cq->where('categories.id', $categoryId)))
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('admin.books.index', [
            'books' => $books,
            'categories' => $categories,
            'search' => $search,
            'categoryId' => $categoryId,
        ]);
    }

    /**
     * Show form for creating a new book.
     */
    public function create(): View
    {
        return view('admin.books.create', [
            'publishers' => Publisher::orderBy('name')->get(),
            'authors' => Author::orderBy('name')->get(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created book in the catalog.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'publisher_id' => ['nullable', 'exists:publishers,id'],
            'publisher_name' => ['nullable', 'string', 'max:255'],
            'authors' => ['required', 'array', 'min:1'],
            'authors.*' => ['exists:authors,id'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['exists:categories,id'],
            'isbn_10' => ['nullable', 'string', 'max:20', 'unique:books,isbn_10'],
            'isbn_13' => ['nullable', 'string', 'max:20', 'unique:books,isbn_13'],
            'publication_year' => ['nullable', 'integer', 'between:-3000,2100'],
            'page_count' => ['nullable', 'integer', 'min:1'],
            'edition' => ['nullable', 'string', 'max:100'],
            'language' => ['required', 'string', 'max:10'],
            'cover_image_path' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'initial_copies' => ['required', 'integer', 'min:1', 'max:20'],
        ]);

        // Resolve publisher if provided by name
        $publisherId = $validated['publisher_id'] ?? null;
        if (! $publisherId && ! empty($validated['publisher_name'])) {
            $pub = Publisher::firstOrCreate(
                ['slug' => Str::slug($validated['publisher_name'])],
                ['name' => trim($validated['publisher_name'])]
            );
            $publisherId = $pub->id;
        }

        // Generate unique slug
        $baseSlug = Str::slug($validated['title']);
        $slug = $baseSlug;
        $count = 1;
        while (Book::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$count}";
            $count++;
        }

        $book = Book::create([
            'publisher_id' => $publisherId,
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? null,
            'slug' => $slug,
            'isbn_10' => $validated['isbn_10'] ?? null,
            'isbn_13' => $validated['isbn_13'] ?? null,
            'publication_year' => $validated['publication_year'] ?? null,
            'page_count' => $validated['page_count'] ?? null,
            'edition' => $validated['edition'] ?? '1st Edition',
            'language' => strtolower($validated['language']),
            'cover_image_path' => $validated['cover_image_path'] ?? null,
            'description' => $validated['description'] ?? null,
            'average_rating' => 4.50,
            'ratings_count' => 1,
            'source' => 'Admin Direct Entry',
        ]);

        $book->authors()->sync($validated['authors']);
        $book->categories()->sync($validated['categories']);

        // Generate initial physical copies
        $copiesCount = (int) $validated['initial_copies'];
        $cleanPrefix = strtoupper(substr(preg_replace('/[^A-Z0-9]/i', '', $book->title), 0, 5));
        for ($i = 1; $i <= $copiesCount; $i++) {
            BookCopy::create([
                'book_id' => $book->id,
                'barcode' => sprintf('READ-%s-%03d-%02d', $cleanPrefix, $book->id, $i),
                'status' => BookCopy::STATUS_AVAILABLE,
                'location' => 'Main Stacks, Shelf A-1',
                'condition' => 'new',
                'acquisition_date' => now(),
            ]);
        }

        $this->auditLogger->log('book.created', $book, null, $book->toArray());

        return redirect()->route('admin.books.index')
            ->with('status', "Book \"{$book->title}\" successfully added with {$copiesCount} physical copies.");
    }

    /**
     * Show form for editing a book.
     */
    public function edit(Book $book): View
    {
        $book->load(['publisher', 'authors', 'categories']);

        return view('admin.books.edit', [
            'book' => $book,
            'publishers' => Publisher::orderBy('name')->get(),
            'authors' => Author::orderBy('name')->get(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    /**
     * Update an existing book in the catalog.
     */
    public function update(Request $request, Book $book): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'publisher_id' => ['nullable', 'exists:publishers,id'],
            'authors' => ['required', 'array', 'min:1'],
            'authors.*' => ['exists:authors,id'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['exists:categories,id'],
            'isbn_10' => ['nullable', 'string', 'max:20', 'unique:books,isbn_10,'.$book->id],
            'isbn_13' => ['nullable', 'string', 'max:20', 'unique:books,isbn_13,'.$book->id],
            'publication_year' => ['nullable', 'integer', 'between:-3000,2100'],
            'page_count' => ['nullable', 'integer', 'min:1'],
            'edition' => ['nullable', 'string', 'max:100'],
            'language' => ['required', 'string', 'max:10'],
            'cover_image_path' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
        ]);

        $oldValues = $book->toArray();

        $book->update([
            'publisher_id' => $validated['publisher_id'] ?? null,
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? null,
            'isbn_10' => $validated['isbn_10'] ?? null,
            'isbn_13' => $validated['isbn_13'] ?? null,
            'publication_year' => $validated['publication_year'] ?? null,
            'page_count' => $validated['page_count'] ?? null,
            'edition' => $validated['edition'] ?? 'Standard Edition',
            'language' => strtolower($validated['language']),
            'cover_image_path' => $validated['cover_image_path'] ?? null,
            'description' => $validated['description'] ?? null,
        ]);

        $book->authors()->sync($validated['authors']);
        $book->categories()->sync($validated['categories']);

        $this->auditLogger->log('book.updated', $book, $oldValues, $book->toArray());

        return redirect()->route('admin.books.index')
            ->with('status', "Book \"{$book->title}\" successfully updated.");
    }

    /**
     * Delete a book from the catalog.
     */
    public function destroy(Book $book): RedirectResponse
    {
        // Safety check: Prevent deletion if any copy is actively checked out
        $hasActiveLoans = $book->copies()->whereHas('activeBorrowing')->exists();
        if ($hasActiveLoans) {
            return back()->with('error', "Cannot delete \"{$book->title}\" because one or more copies are currently checked out.");
        }

        $oldData = $book->toArray();
        $title = $book->title;
        $book->delete();

        $this->auditLogger->log('book.deleted', null, $oldData, null);

        return redirect()->route('admin.books.index')
            ->with('status', "Book \"{$title}\" was successfully deleted.");
    }
}
