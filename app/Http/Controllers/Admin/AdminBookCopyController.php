<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookCopy;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminBookCopyController extends Controller
{
    public function __construct(
        protected AuditLogger $auditLogger
    ) {}

    /**
     * Display list of physical copies in library inventory.
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();
        $status = $request->string('status')->trim()->toString();
        $bookId = $request->integer('book_id');

        $copies = BookCopy::query()
            ->with(['book', 'activeBorrowing.user'])
            ->when($search !== '', function ($q) use ($search) {
                $q->where('barcode', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhereHas('book', fn ($bq) => $bq->where('title', 'like', "%{$search}%"));
            })
            ->when($status !== '', fn ($q) => $q->where('status', $status))
            ->when($bookId > 0, fn ($q) => $q->where('book_id', $bookId))
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        $books = Book::orderBy('title')->get();

        return view('admin.copies.index', [
            'copies' => $copies,
            'books' => $books,
            'search' => $search,
            'status' => $status,
            'bookId' => $bookId,
        ]);
    }

    /**
     * Add a new physical copy to an existing book.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'book_id' => ['required', 'exists:books,id'],
            'barcode' => ['required', 'string', 'max:50', 'unique:book_copies,barcode'],
            'location' => ['nullable', 'string', 'max:100'],
            'condition' => ['required', 'string', 'in:new,good,fair,damaged,maintenance'],
        ]);

        $copy = BookCopy::create([
            'book_id' => $validated['book_id'],
            'barcode' => strtoupper(trim($validated['barcode'])),
            'status' => BookCopy::STATUS_AVAILABLE,
            'location' => $validated['location'] ?? 'Main Stacks',
            'condition' => $validated['condition'],
            'acquisition_date' => now(),
        ]);

        $this->auditLogger->log('copy.created', $copy, null, $copy->toArray());

        return back()->with('status', "Physical copy \"{$copy->barcode}\" added to inventory.");
    }

    /**
     * Update copy status/condition.
     */
    public function update(Request $request, BookCopy $copy): RedirectResponse
    {
        $validated = $request->validate([
            'location' => ['nullable', 'string', 'max:100'],
            'condition' => ['required', 'string', 'in:new,good,fair,damaged,maintenance'],
            'status' => ['required', 'string', 'in:available,borrowed,reserved,lost,damaged,maintenance'],
        ]);

        $oldValues = $copy->toArray();
        $copy->update($validated);

        $this->auditLogger->log('copy.updated', $copy, $oldValues, $copy->toArray());

        return back()->with('status', "Copy \"{$copy->barcode}\" updated.");
    }

    /**
     * Remove physical copy from inventory.
     */
    public function destroy(BookCopy $copy): RedirectResponse
    {
        if ($copy->status === BookCopy::STATUS_BORROWED) {
            return back()->with('error', "Cannot remove copy \"{$copy->barcode}\" because it is currently checked out.");
        }

        $oldData = $copy->toArray();
        $barcode = $copy->barcode;
        $copy->delete();

        $this->auditLogger->log('copy.deleted', null, $oldData, null);

        return back()->with('status', "Copy \"{$barcode}\" removed from inventory.");
    }
}
