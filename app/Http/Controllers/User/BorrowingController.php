<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrowing;
use App\Services\BorrowingService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BorrowingController extends Controller
{
    public function __construct(
        protected BorrowingService $borrowingService
    ) {}

    /**
     * Display patron active loans and borrowing history.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $activeBorrowings = Borrowing::query()
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->with(['bookCopy.book.authors', 'bookCopy.book.categories'])
            ->orderBy('due_at')
            ->get();

        $pastBorrowings = Borrowing::query()
            ->where('user_id', $user->id)
            ->where('status', '!=', 'active')
            ->with(['bookCopy.book.authors'])
            ->orderByDesc('returned_at')
            ->paginate(10);

        $popularBooks = Book::query()
            ->with(['authors', 'categories', 'copies'])
            ->whereHas('copies', fn ($q) => $q->where('status', 'available'))
            ->orderByDesc('ratings_count')
            ->take(3)
            ->get();

        return view('user.borrowings', [
            'user' => $user,
            'activeBorrowings' => $activeBorrowings,
            'pastBorrowings' => $pastBorrowings,
            'popularBooks' => $popularBooks,
        ]);
    }

    /**
     * Borrow an available copy of a book.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'book_id' => ['required', 'exists:books,id'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        $book = Book::findOrFail($validated['book_id']);

        try {
            $borrowing = $this->borrowingService->borrow(
                $request->user(),
                $book,
                $validated['notes'] ?? null
            );

            return redirect()->route('borrowings.index')
                ->with('status', "Successfully checked out \"{$book->title}\". Due date: {$borrowing->due_at->toFormattedDateString()}.");
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Return a borrowed book copy.
     */
    public function returnBook(Request $request, Borrowing $borrowing): RedirectResponse
    {
        if ($borrowing->user_id !== $request->user()->id && ! $request->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $this->borrowingService->returnBook($borrowing);

            return redirect()->route('borrowings.index')
                ->with('status', 'Book successfully marked as returned.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Renew an active loan.
     */
    public function renew(Request $request, Borrowing $borrowing): RedirectResponse
    {
        if ($borrowing->user_id !== $request->user()->id && ! $request->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $this->borrowingService->renew($borrowing);

            return redirect()->route('borrowings.index')
                ->with('status', "Loan successfully renewed until {$borrowing->due_at->toFormattedDateString()}.");
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
