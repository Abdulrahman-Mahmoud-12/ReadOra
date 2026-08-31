<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Borrowing;
use App\Models\Category;
use App\Models\User;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminReportController extends Controller
{
    /**
     * Display circulation analytics and reporting dashboard.
     */
    public function index(): View
    {
        $totalLoansCount = Borrowing::count();
        $activeLoansCount = Borrowing::where('status', 'active')->count();
        $overdueLoansCount = Borrowing::where('status', 'active')->where('due_at', '<', now())->count();
        $thisMonthLoansCount = Borrowing::where('borrowed_at', '>=', now()->startOfMonth())->count();

        // Top borrowed books
        $topBooks = Book::query()
            ->with(['authors', 'publisher'])
            ->withCount(['copies as borrowings_count' => function ($query) {
                $query->join('borrowings', 'book_copies.id', '=', 'borrowings.book_copy_id');
            }])
            ->orderByDesc('borrowings_count')
            ->take(6)
            ->get();

        // Most active patrons
        $topPatrons = User::query()
            ->withCount('borrowings')
            ->withCount('activeBorrowings')
            ->orderByDesc('borrowings_count')
            ->take(6)
            ->get();

        // Overdue loans
        $overdueLoans = Borrowing::query()
            ->with(['user', 'bookCopy.book'])
            ->where('status', 'active')
            ->where('due_at', '<', now())
            ->orderBy('due_at', 'asc')
            ->take(10)
            ->get();

        // Category distribution
        $categoriesStats = Category::query()
            ->withCount('books')
            ->orderByDesc('books_count')
            ->take(8)
            ->get();

        return view('admin.reports.index', [
            'totalLoansCount' => $totalLoansCount,
            'activeLoansCount' => $activeLoansCount,
            'overdueLoansCount' => $overdueLoansCount,
            'thisMonthLoansCount' => $thisMonthLoansCount,
            'topBooks' => $topBooks,
            'topPatrons' => $topPatrons,
            'overdueLoans' => $overdueLoans,
            'categoriesStats' => $categoriesStats,
        ]);
    }

    /**
     * Export full catalog of books as CSV.
     */
    public function exportBooks(): StreamedResponse
    {
        $fileName = 'readora_books_catalog_'.now()->format('Y_m_d_His').'.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            // Write UTF-8 BOM for Excel compatibility
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'ID',
                'Title',
                'Subtitle',
                'Authors',
                'Categories',
                'Publisher',
                'ISBN 10',
                'ISBN 13',
                'Publication Year',
                'Language',
                'Pages',
                'Average Rating',
                'Ratings Count',
                'Total Copies',
                'Available Copies',
            ]);

            Book::query()
                ->with(['authors', 'categories', 'publisher', 'copies'])
                ->chunk(100, function ($books) use ($handle) {
                    foreach ($books as $book) {
                        fputcsv($handle, [
                            $book->id,
                            $book->title,
                            $book->subtitle ?? '',
                            $book->authors->pluck('name')->join('; '),
                            $book->categories->pluck('name')->join('; '),
                            $book->publisher?->name ?? '',
                            $book->isbn_10 ?? '',
                            $book->isbn_13 ?? '',
                            $book->publication_year ?? '',
                            strtoupper($book->language ?? ''),
                            $book->page_count ?? '',
                            $book->average_rating,
                            $book->ratings_count,
                            $book->copies->count(),
                            $book->availableCopiesCount(),
                        ]);
                    }
                });

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Export all circulation borrowing logs as CSV.
     */
    public function exportCirculations(): StreamedResponse
    {
        $fileName = 'readora_circulations_'.now()->format('Y_m_d_His').'.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'Loan ID',
                'Patron Name',
                'Patron Email',
                'Book Title',
                'Copy Barcode',
                'Location',
                'Borrowed Date',
                'Due Date',
                'Returned Date',
                'Status',
                'Is Overdue',
            ]);

            Borrowing::query()
                ->with(['user', 'bookCopy.book'])
                ->latest('id')
                ->chunk(100, function ($loans) use ($handle) {
                    foreach ($loans as $loan) {
                        $isOverdue = $loan->status === 'active' && $loan->due_at->isPast();
                        fputcsv($handle, [
                            $loan->id,
                            $loan->user->name ?? 'Unknown',
                            $loan->user->email ?? 'Unknown',
                            $loan->bookCopy?->book?->title ?? 'N/A',
                            $loan->bookCopy?->barcode ?? 'N/A',
                            $loan->bookCopy?->location ?? 'Main Stacks',
                            $loan->borrowed_at->format('Y-m-d H:i:s'),
                            $loan->due_at->format('Y-m-d H:i:s'),
                            $loan->returned_at ? $loan->returned_at->format('Y-m-d H:i:s') : 'Active Loan',
                            ucfirst($loan->status),
                            $isOverdue ? 'YES' : 'NO',
                        ]);
                    }
                });

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Export all registered patrons as CSV.
     */
    public function exportPatrons(): StreamedResponse
    {
        $fileName = 'readora_patrons_'.now()->format('Y_m_d_His').'.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'User ID',
                'Name',
                'Email',
                'Role',
                'Active Borrowings',
                'Total Lifetime Loans',
                'Favorites Count',
                'Reviews Written',
                'Member Since',
            ]);

            User::query()
                ->withCount(['borrowings', 'activeBorrowings', 'favorites', 'reviews'])
                ->chunk(100, function ($users) use ($handle) {
                    foreach ($users as $user) {
                        fputcsv($handle, [
                            $user->id,
                            $user->name,
                            $user->email,
                            ucfirst($user->role),
                            $user->active_borrowings_count,
                            $user->borrowings_count,
                            $user->favorites_count,
                            $user->reviews_count,
                            $user->created_at->format('Y-m-d H:i:s'),
                        ]);
                    }
                });

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Export all physical copy inventory as CSV.
     */
    public function exportCopies(): StreamedResponse
    {
        $fileName = 'readora_shelf_inventory_'.now()->format('Y_m_d_His').'.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'Copy ID',
                'Barcode',
                'Book Title',
                'ISBN 13',
                'Location',
                'Condition',
                'Status',
                'Acquired Date',
            ]);

            BookCopy::query()
                ->with('book')
                ->chunk(100, function ($copies) use ($handle) {
                    foreach ($copies as $copy) {
                        fputcsv($handle, [
                            $copy->id,
                            $copy->barcode,
                            $copy->book?->title ?? 'N/A',
                            $copy->book?->isbn_13 ?? 'N/A',
                            $copy->location ?? 'Main Stacks',
                            ucfirst($copy->condition ?? 'Good'),
                            ucfirst($copy->status),
                            $copy->acquisition_date ? $copy->acquisition_date->format('Y-m-d') : 'N/A',
                        ]);
                    }
                });

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
