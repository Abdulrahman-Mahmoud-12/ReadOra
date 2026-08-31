<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookController extends Controller
{
    public function __construct(
        protected RecommendationService $recommendationService
    ) {}

    /**
     * Display a paginated, faceted, searchable, filterable catalog of books.
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();
        $selectedCategories = array_filter((array) $request->input('categories', $request->input('category') ? [$request->input('category')] : []));
        $selectedAuthors = array_filter((array) $request->input('authors', $request->input('author') ? [$request->input('author')] : []));
        $selectedPublishers = array_filter((array) $request->input('publishers', $request->input('publisher') ? [$request->input('publisher')] : []));
        $selectedLanguages = array_filter((array) $request->input('languages', $request->input('language') ? [$request->input('language')] : []));
        $availability = $request->string('availability')->trim()->toString();
        $minRating = $request->float('min_rating');
        $era = $request->string('era')->trim()->toString();
        $sort = $request->string('sort', 'rating_desc')->trim()->toString();

        $query = Book::query()
            ->with(['publisher', 'authors', 'categories', 'copies'])
            ->when($search !== '', fn ($q) => $q->search($search))
            ->when(! empty($selectedCategories), function ($q) use ($selectedCategories) {
                $q->whereHas('categories', fn ($catQuery) => $catQuery->whereIn('slug', $selectedCategories));
            })
            ->when(! empty($selectedAuthors), function ($q) use ($selectedAuthors) {
                $q->whereHas('authors', fn ($authorQuery) => $authorQuery->whereIn('slug', $selectedAuthors));
            })
            ->when(! empty($selectedPublishers), function ($q) use ($selectedPublishers) {
                $q->whereHas('publisher', fn ($pubQuery) => $pubQuery->whereIn('slug', $selectedPublishers));
            })
            ->when(! empty($selectedLanguages), function ($q) use ($selectedLanguages) {
                $q->whereIn('language', $selectedLanguages);
            })
            ->when($minRating > 0, function ($q) use ($minRating) {
                $q->where('average_rating', '>=', $minRating);
            })
            ->when($era !== '', function ($q) use ($era) {
                match ($era) {
                    '2020s' => $q->where('publication_year', '>=', 2020),
                    '2010s' => $q->whereBetween('publication_year', [2010, 2019]),
                    '2000s' => $q->whereBetween('publication_year', [2000, 2009]),
                    '1900-1999' => $q->whereBetween('publication_year', [1900, 1999]),
                    'classic' => $q->where('publication_year', '<', 1900),
                    default => $q,
                };
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

        // Facets data
        $categories = Category::query()
            ->withCount('books')
            ->orderBy('name')
            ->get();

        $authors = Author::query()
            ->whereHas('books')
            ->withCount('books')
            ->orderByDesc('books_count')
            ->take(15)
            ->get();

        $publishers = Publisher::query()
            ->whereHas('books')
            ->withCount('books')
            ->orderByDesc('books_count')
            ->take(10)
            ->get();

        $availableLanguages = Book::query()
            ->select('language')
            ->distinct()
            ->pluck('language')
            ->filter()
            ->values();

        return view('books.index', [
            'books' => $books,
            'categories' => $categories,
            'authors' => $authors,
            'publishers' => $publishers,
            'availableLanguages' => $availableLanguages,
            'filters' => [
                'search' => $search,
                'categories' => $selectedCategories,
                'authors' => $selectedAuthors,
                'publishers' => $selectedPublishers,
                'languages' => $selectedLanguages,
                'min_rating' => $minRating > 0 ? $minRating : null,
                'era' => $era,
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

        $relatedBooks = $this->recommendationService->getSimilarBooks($book, 4);

        return view('books.show', [
            'book' => $book,
            'relatedBooks' => $relatedBooks,
        ]);
    }
}
