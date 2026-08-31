<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    /**
     * Display patron saved favorites collection.
     */
    public function index(Request $request): View
    {
        $suggestedBooks = Book::query()
            ->with(['authors', 'categories', 'copies'])
            ->orderByDesc('average_rating')
            ->take(3)
            ->get();

        return view('user.favorites', [
            'user' => $request->user(),
            'favorites' => collect(),
            'suggestedBooks' => $suggestedBooks,
        ]);
    }
}
