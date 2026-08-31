<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BorrowingController extends Controller
{
    /**
     * Display patron active loans and borrowing history.
     */
    public function index(Request $request): View
    {
        $popularBooks = Book::query()
            ->with(['authors', 'categories', 'copies'])
            ->whereHas('copies', fn ($q) => $q->where('status', 'available'))
            ->orderByDesc('ratings_count')
            ->take(3)
            ->get();

        return view('user.borrowings', [
            'user' => $request->user(),
            'borrowings' => collect(),
            'popularBooks' => $popularBooks,
        ]);
    }
}
