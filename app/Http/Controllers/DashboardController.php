<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected RecommendationService $recommendationService
    ) {}

    /**
     * Display the patron user dashboard with live catalog metrics and recommendations.
     */
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        $totalBooks = Book::query()->count();
        $availableBooks = Book::query()->whereHas('copies', fn ($q) => $q->where('status', 'available'))->count();
        $categoriesCount = Category::query()->count();

        $activeLoansCount = $user->activeBorrowings()->count();
        $favoritesCount = $user->favorites()->count();
        $returnedLoansCount = $user->borrowings()->where('status', 'returned')->count();

        // Content-based intelligent recommendations
        $recommendedBooks = $this->recommendationService->getRecommendationsForUser($user, 4);

        $recentlyAdded = Book::query()
            ->with(['authors', 'categories', 'copies'])
            ->latest('id')
            ->take(4)
            ->get();

        $popularCategories = Category::query()
            ->withCount('books')
            ->orderByDesc('books_count')
            ->take(6)
            ->get();

        return view('dashboard', [
            'user' => $user,
            'totalBooks' => $totalBooks,
            'availableBooks' => $availableBooks,
            'categoriesCount' => $categoriesCount,
            'activeLoansCount' => $activeLoansCount,
            'favoritesCount' => $favoritesCount,
            'returnedLoansCount' => $returnedLoansCount,
            'recommendedBooks' => $recommendedBooks,
            'recentlyAdded' => $recentlyAdded,
            'popularCategories' => $popularCategories,
        ]);
    }
}
