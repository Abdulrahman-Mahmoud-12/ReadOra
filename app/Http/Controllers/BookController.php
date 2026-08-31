<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookController extends Controller
{
    /**
     * Display a paginated, searchable, filterable catalog of books.
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();
        $selectedCategory = $request->string('category')->trim()->toString();
        $selectedAuthor = $request->string('author')->trim()->toString();
        $availability = $request->string('availability')->trim()->toString();
        $sort = $request->string('sort', 'rating_desc')->trim()->toString();

        $query = Book::query()
            ->with(['authors', 'categories', 'copies'])
            ->when($search !== '', fn ($q) => $q->search($search))
            ->when($selectedCategory !== '', function ($q) use ($selectedCategory) {
                $q->whereHas('categories', fn ($catQuery) => $catQuery->where('slug', $selectedCategory));
            })
            ->when($selectedAuthor !== '', function ($q) use ($selectedAuthor) {
                $q->whereHas('authors', fn ($authorQuery) => $authorQuery->where('slug', $selectedAuthor));
            })
            ->when($availability === 'available', function ($q) {
                $q->whereHas('copies', fn ($copyQuery) => $copyQuery->where('status', 'available'));
            });

        // Apply sorting
        match ($sort) {
            'year_desc' => $query->orderByDesc('publication_year')->orderBy('title'),
            'year_asc' => $query->orderBy('publication_year')->orderBy('title'),
            'title_asc' => $query->orderBy('title'),
            'title_desc' => $query->orderByDesc('title'),
            'popular' => $query->orderByDesc('ratings_count')->orderByDesc('average_rating'),
            default => $query->orderByDesc('average_rating')->orderByDesc('ratings_count'),
        };

        $books = $query->paginate(12)->withQueryString();

        $categories = Category::query()
            ->withCount('books')
            ->orderBy('name')
            ->get();

        $authors = Author::query()
            ->whereHas('books')
            ->withCount('books')
            ->orderBy('name')
            ->get();

        return view('books.index', [
            'books' => $books,
            'categories' => $categories,
            'authors' => $authors,
            'filters' => [
                'search' => $search,
                'category' => $selectedCategory,
                'author' => $selectedAuthor,
                'availability' => $availability,
                'sort' => $sort,
            ],
            'totalBooksCount' => Book::query()->count(),
        ]);
    }

    /**
     * Display a specific book's bibliographic details and copy inventory.
     */
    public function show(string $slug): View
    {
        $book = Book::query()
            ->where('slug', $slug)
            ->with(['publisher', 'authors', 'categories', 'copies'])
            ->firstOrFail();

        $relatedBooks = Book::query()
            ->whereKeyNot($book->id)
            ->whereHas('categories', function ($q) use ($book) {
                $q->whereIn('categories.id', $book->categories->pluck('id'));
            })
            ->with(['authors', 'categories', 'copies'])
            ->take(4)
            ->get();

        return view('books.show', [
            'book' => $book,
            'relatedBooks' => $relatedBooks,
        ]);
    }
}
