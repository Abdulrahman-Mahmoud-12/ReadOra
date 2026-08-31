<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Services\AuditLogger;
use App\Services\BorrowingService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminCirculationController extends Controller
{
    public function __construct(
        protected BorrowingService $borrowingService,
        protected AuditLogger $auditLogger
    ) {}

    /**
     * Display all circulation loans across the library system.
     */
    public function index(Request $request): View
    {
        $status = $request->string('status')->trim()->toString();
        $search = $request->string('search')->trim()->toString();

        $query = Borrowing::query()
            ->with(['user', 'bookCopy.book.authors'])
            ->when($status === 'active', fn ($q) => $q->active())
            ->when($status === 'overdue', fn ($q) => $q->overdue())
            ->when($status === 'returned', fn ($q) => $q->returned())
            ->when($search !== '', function ($q) use ($search) {
                $q->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"))
                    ->orWhereHas('bookCopy.book', fn ($b) => $b->where('title', 'like', "%{$search}%"))
                    ->orWhereHas('bookCopy', fn ($c) => $c->where('barcode', 'like', "%{$search}%"));
            })
            ->latest('id');

        $borrowings = $query->paginate(15)->withQueryString();

        return view('admin.circulations.index', [
            'borrowings' => $borrowings,
            'status' => $status,
            'search' => $search,
            'activeCount' => Borrowing::active()->count(),
            'overdueCount' => Borrowing::overdue()->count(),
            'returnedCount' => Borrowing::returned()->count(),
        ]);
    }

    /**
     * Admin check-in / return action.
     */
    public function returnBook(Request $request, Borrowing $borrowing): RedirectResponse
    {
        try {
            $this->borrowingService->returnBook($borrowing, 'Returned via Admin Circulation Desk');
            $this->auditLogger->log('circulation.returned', $borrowing, ['status' => 'active'], ['status' => 'returned']);

            return back()->with('status', 'Book successfully checked in and restored to available inventory.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Admin loan renewal override.
     */
    public function renew(Request $request, Borrowing $borrowing): RedirectResponse
    {
        try {
            $oldDue = $borrowing->due_at;
            $this->borrowingService->renew($borrowing);
            $this->auditLogger->log('circulation.renewed', $borrowing, ['due_at' => $oldDue], ['due_at' => $borrowing->due_at]);

            return back()->with('status', "Loan successfully extended until {$borrowing->due_at->toFormattedDateString()}.");
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
