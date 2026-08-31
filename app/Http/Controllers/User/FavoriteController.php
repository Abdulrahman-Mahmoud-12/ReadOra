<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Favorite;
use App\Models\ReadingHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    /**
     * Display patron saved favorites collection.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $favoriteBooks = $user->favoriteBooks()
            ->with(['authors', 'categories', 'copies'])
            ->orderByPivot('created_at', 'desc')
            ->paginate(12);

        $suggestedBooks = Book::query()
            ->with(['authors', 'categories', 'copies'])
            ->whereNotIn('id', $user->favorites()->pluck('book_id'))
            ->orderByDesc('average_rating')
            ->take(3)
            ->get();

        return view('user.favorites', [
            'user' => $user,
            'favoriteBooks' => $favoriteBooks,
            'suggestedBooks' => $suggestedBooks,
        ]);
    }

    /**
     * Toggle a book in patron favorites collection.
     */
    public function toggle(Request $request, Book $book): JsonResponse|RedirectResponse
    {
        $user = $request->user();

        $existing = Favorite::where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $favorited = false;
            $message = "Removed \"{$book->title}\" from your favorites.";
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'book_id' => $book->id,
            ]);

            ReadingHistory::create([
                'user_id' => $user->id,
                'book_id' => $book->id,
                'action' => 'favorited',
            ]);

            $favorited = true;
            $message = "Saved \"{$book->title}\" to your favorites.";
        }

        if ($request->wantsJson()) {
            return response()->json([
                'favorited' => $favorited,
                'message' => $message,
            ]);
        }

        return back()->with('status', $message);
    }
}
