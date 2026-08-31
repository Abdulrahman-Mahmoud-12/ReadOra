<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminReviewController extends Controller
{
    public function __construct(
        protected AuditLogger $auditLogger
    ) {}

    /**
     * Display list of all patron reviews with moderation status filters.
     */
    public function index(Request $request): View
    {
        $status = $request->string('status')->trim()->toString();
        $search = $request->string('search')->trim()->toString();

        $reviews = Review::query()
            ->with(['user', 'book'])
            ->when($status !== '', fn ($q) => $q->where('status', $status))
            ->when($search !== '', function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('book', fn ($b) => $b->where('title', 'like', "%{$search}%"));
            })
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.reviews.index', [
            'reviews' => $reviews,
            'status' => $status,
            'search' => $search,
            'totalCount' => Review::count(),
            'approvedCount' => Review::where('status', Review::STATUS_APPROVED)->count(),
            'pendingCount' => Review::where('status', Review::STATUS_PENDING)->count(),
            'rejectedCount' => Review::where('status', Review::STATUS_REJECTED)->count(),
        ]);
    }

    /**
     * Update moderation status of a review (approve / reject).
     */
    public function updateStatus(Request $request, Review $review): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:approved,pending,rejected'],
        ]);

        $oldStatus = $review->status;
        $review->update(['status' => $validated['status']]);

        // Recalculate book rating aggregate
        $review->updateBookAggregates();

        $this->auditLogger->log('review.moderated', $review, ['status' => $oldStatus], ['status' => $review->status]);

        return back()->with('status', "Review status updated to {$review->status}.");
    }

    /**
     * Delete an inappropriate review completely.
     */
    public function destroy(Review $review): RedirectResponse
    {
        $oldData = $review->toArray();
        $book = $review->book;
        $review->delete();

        // Recalculate book aggregate
        if ($book) {
            $approvedReviews = $book->reviews()->where('status', Review::STATUS_APPROVED);
            $count = $approvedReviews->count();
            $avg = $count > 0 ? round($approvedReviews->avg('rating'), 2) : 4.00;

            $book->update([
                'average_rating' => $avg,
                'ratings_count' => $count,
            ]);
        }

        $this->auditLogger->log('review.deleted', null, $oldData, null);

        return back()->with('status', 'Review deleted successfully.');
    }
}
