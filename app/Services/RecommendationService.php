<?php

namespace App\Services;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class RecommendationService
{
    public const WEIGHT_CATEGORY = 3.0;

    public const WEIGHT_AUTHOR = 2.5;

    public const WEIGHT_RATING = 1.0;

    public const WEIGHT_AVAILABILITY = 0.5;

    /**
     * Generate personalized book recommendations for a patron or popular titles for guests.
     *
     * @return Collection<int, Book>
     */
    public function getRecommendationsForUser(?User $user, int $limit = 6): Collection
    {
        if (! $user) {
            return $this->getDefaultRecommendations($limit);
        }

        // 1. Extract interacted book IDs (favorites, active borrowings, reading history)
        $favoriteBookIds = $user->favorites()->pluck('book_id')->toArray();
        $borrowedBookIds = $user->borrowings()->with('bookCopy')->get()
            ->pluck('bookCopy.book_id')
            ->filter()
            ->unique()
            ->toArray();

        $interactedBookIds = array_unique(array_merge($favoriteBookIds, $borrowedBookIds));

        // Cold start fallback: If patron has no activity, return top-rated popular catalog books
        if (empty($interactedBookIds)) {
            return $this->getDefaultRecommendations($limit);
        }

        // 2. Build Category and Author affinity weight profiles
        $interactedBooks = Book::with(['categories', 'authors'])
            ->whereIn('id', $interactedBookIds)
            ->get();

        $categoryAffinity = [];
        $authorAffinity = [];

        foreach ($interactedBooks as $book) {
            // Favorited books give +2 weight, borrowed books give +3 weight
            $weight = in_array($book->id, $borrowedBookIds, true) ? 3.0 : 2.0;

            foreach ($book->categories as $category) {
                $categoryAffinity[$category->id] = ($categoryAffinity[$category->id] ?? 0) + $weight;
            }

            foreach ($book->authors as $author) {
                $authorAffinity[$author->id] = ($authorAffinity[$author->id] ?? 0) + $weight;
            }
        }

        // 3. Fetch candidate books (excluding books currently borrowed by user)
        $activeBorrowedBookIds = $user->activeBorrowings()->with('bookCopy')->get()
            ->pluck('bookCopy.book_id')
            ->filter()
            ->unique()
            ->toArray();

        $candidates = Book::query()
            ->with(['authors', 'categories', 'copies'])
            ->whereNotIn('id', $activeBorrowedBookIds)
            ->get();

        // 4. Calculate content-based recommendation score for each candidate book
        $scoredBooks = $candidates->map(function (Book $book) use ($categoryAffinity, $authorAffinity, $favoriteBookIds) {
            $score = 0.0;
            $reasons = [];

            // Category match score
            $matchedCategories = [];
            foreach ($book->categories as $cat) {
                if (isset($categoryAffinity[$cat->id])) {
                    $score += $categoryAffinity[$cat->id] * self::WEIGHT_CATEGORY;
                    $matchedCategories[] = $cat->name;
                }
            }
            if (! empty($matchedCategories)) {
                $reasons[] = 'Matches your interest in '.implode(', ', array_slice($matchedCategories, 0, 2));
            }

            // Author match score
            $matchedAuthors = [];
            foreach ($book->authors as $author) {
                if (isset($authorAffinity[$author->id])) {
                    $score += $authorAffinity[$author->id] * self::WEIGHT_AUTHOR;
                    $matchedAuthors[] = $author->name;
                }
            }
            if (! empty($matchedAuthors)) {
                $reasons[] = 'By author you read: '.implode(', ', array_slice($matchedAuthors, 0, 2));
            }

            // Quality & Popularity score (0 to 5 normalized rating + log count)
            $rating = (float) $book->average_rating;
            $score += $rating * self::WEIGHT_RATING;

            // Availability bonus
            if ($book->isAvailable()) {
                $score += self::WEIGHT_AVAILABILITY;
            }

            // Slight demotion for already favorited books so new discoveries rank higher
            if (in_array($book->id, $favoriteBookIds, true)) {
                $score *= 0.7;
            }

            $book->recommendation_score = round($score, 2);
            $book->recommendation_reason = ! empty($reasons) ? $reasons[0] : 'Top-rated in library collection';

            return $book;
        });

        // 5. Sort by calculated score descending and take requested limit
        $topBooks = $scoredBooks->sortByDesc('recommendation_score')->take($limit)->values();

        return new Collection($topBooks->all());
    }

    /**
     * Find similar books based on category and author similarity.
     *
     * @return Collection<int, Book>
     */
    public function getSimilarBooks(Book $book, int $limit = 4): Collection
    {
        $categoryIds = $book->categories->pluck('id')->toArray();
        $authorIds = $book->authors->pluck('id')->toArray();

        $similar = Book::query()
            ->with(['authors', 'categories', 'copies'])
            ->where('id', '!=', $book->id)
            ->where(function ($query) use ($categoryIds, $authorIds) {
                if (! empty($categoryIds)) {
                    $query->whereHas('categories', fn ($q) => $q->whereIn('categories.id', $categoryIds));
                }
                if (! empty($authorIds)) {
                    $query->orWhereHas('authors', fn ($q) => $q->whereIn('authors.id', $authorIds));
                }
            })
            ->orderByDesc('average_rating')
            ->take($limit)
            ->get();

        // Fallback to top-rated books if no category/author overlaps found
        if ($similar->count() < $limit) {
            $needed = $limit - $similar->count();
            $fallback = Book::query()
                ->with(['authors', 'categories', 'copies'])
                ->where('id', '!=', $book->id)
                ->whereNotIn('id', $similar->pluck('id'))
                ->orderByDesc('average_rating')
                ->take($needed)
                ->get();

            $similar = $similar->concat($fallback);
        }

        return $similar;
    }

    /**
     * Get default catalog recommendations for cold start or guest users.
     *
     * @return Collection<int, Book>
     */
    public function getDefaultRecommendations(int $limit = 6): Collection
    {
        return Book::query()
            ->with(['authors', 'categories', 'copies'])
            ->whereHas('copies', fn ($q) => $q->where('status', 'available'))
            ->orderByDesc('average_rating')
            ->orderByDesc('ratings_count')
            ->take($limit)
            ->get()
            ->each(function (Book $book) {
                $book->recommendation_score = (float) $book->average_rating;
                $book->recommendation_reason = 'Curated library masterpiece';
            });
    }
}
