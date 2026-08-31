<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\ReadingHistory;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Store or update a patron review for a specific book.
     */
    public function store(Request $request, Book $book): RedirectResponse
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'title' => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', 'string', 'max:5000'],
        ]);

        $user = $request->user();

        $review = Review::updateOrCreate(
            [
                'user_id' => $user->id,
                'book_id' => $book->id,
            ],
            [
                'rating' => $validated['rating'],
                'title' => $validated['title'] ?? null,
                'content' => $validated['content'] ?? null,
                'status' => Review::STATUS_APPROVED, // Default approved for direct public engagement
            ]
        );

        // Recalculate book average rating & count
        $review->updateBookAggregates();

        // Record reading history activity
        ReadingHistory::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'action' => 'reviewed',
            'created_at' => now(),
        ]);

        return back()->with('status', 'Your review and rating have been posted successfully!');
    }

    /**
     * Delete the patron's own review.
     */
    public function destroy(Request $request, Review $review): RedirectResponse
    {
        $user = $request->user();

        if ($review->user_id !== $user->id && ! $user->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $book = $review->book;
        $review->delete();

        // Recalculate remaining ratings
        $approvedReviews = $book->reviews()->where('status', Review::STATUS_APPROVED);
        $count = $approvedReviews->count();
        $avg = $count > 0 ? round($approvedReviews->avg('rating'), 2) : 4.00;

        $book->update([
            'average_rating' => $avg,
            'ratings_count' => $count,
        ]);

        return back()->with('status', 'Your review has been removed.');
    }
}
