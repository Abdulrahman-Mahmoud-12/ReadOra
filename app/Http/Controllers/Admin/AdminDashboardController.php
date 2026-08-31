<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Borrowing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Display the administration overview dashboard with real-time library circulation metrics.
     */
    public function __invoke(Request $request): View
    {
        $totalBooks = Book::count();
        $totalCopies = BookCopy::count();
        $availableCopies = BookCopy::where('status', BookCopy::STATUS_AVAILABLE)->count();
        $borrowedCopies = BookCopy::where('status', BookCopy::STATUS_BORROWED)->count();
        $overdueCount = Borrowing::overdue()->count();
        $activeLoansCount = Borrowing::active()->count();
        $totalUsers = User::count();
        $activeBorrowers = User::whereHas('activeBorrowings')->count();

        $recentBorrowings = Borrowing::with(['user', 'bookCopy.book'])
            ->latest('id')
            ->take(8)
            ->get();

        $recentAuditLogs = AuditLog::with('actor')
            ->latest('id')
            ->take(6)
            ->get();

        return view('admin.dashboard', [
            'user' => $request->user(),
            'totalBooks' => $totalBooks,
            'totalCopies' => $totalCopies,
            'availableCopies' => $availableCopies,
            'borrowedCopies' => $borrowedCopies,
            'overdueCount' => $overdueCount,
            'activeLoansCount' => $activeLoansCount,
            'totalUsers' => $totalUsers,
            'activeBorrowers' => $activeBorrowers,
            'recentBorrowings' => $recentBorrowings,
            'recentAuditLogs' => $recentAuditLogs,
        ]);
    }
}
