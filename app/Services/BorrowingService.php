<?php

namespace App\Services;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Borrowing;
use App\Models\ReadingHistory;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use RuntimeException;

class BorrowingService
{
    /**
     * Maximum concurrent active loans allowed per patron.
     */
    public const MAX_ACTIVE_LOANS = 5;

    /**
     * Default loan duration in days.
     */
    public const DEFAULT_LOAN_DAYS = 14;

    /**
     * Borrow an available copy of the specified book for a patron.
     *
     * @throws RuntimeException|InvalidArgumentException
     */
    public function borrow(User $user, Book $book, ?string $notes = null): Borrowing
    {
        return DB::transaction(function () use ($user, $book, $notes) {
            // 1. Verify patron active loan count limit
            $activeCount = $user->activeBorrowings()->count();
            if ($activeCount >= self::MAX_ACTIVE_LOANS) {
                throw new RuntimeException('Borrowing limit reached. Patrons can have at most '.self::MAX_ACTIVE_LOANS.' active loans.');
            }

            // 2. Prevent patron from borrowing multiple copies of the exact same title concurrently
            $alreadyBorrowed = Borrowing::query()
                ->where('user_id', $user->id)
                ->where('status', 'active')
                ->whereHas('bookCopy', fn ($q) => $q->where('book_id', $book->id))
                ->exists();

            if ($alreadyBorrowed) {
                throw new RuntimeException('You already have an active loan for this book.');
            }

            // 3. Atomically acquire an available copy with row locking to prevent race conditions
            /** @var BookCopy|null $copy */
            $copy = BookCopy::query()
                ->where('book_id', $book->id)
                ->where('status', BookCopy::STATUS_AVAILABLE)
                ->lockForUpdate()
                ->first();

            if (! $copy) {
                throw new RuntimeException('No copies of this book are currently available for loan.');
            }

            // 4. Mark copy as borrowed
            $copy->update(['status' => BookCopy::STATUS_BORROWED]);

            // 5. Create the borrowing record
            $borrowing = Borrowing::create([
                'user_id' => $user->id,
                'book_copy_id' => $copy->id,
                'borrowed_at' => now(),
                'due_at' => now()->addDays(self::DEFAULT_LOAN_DAYS),
                'status' => 'active',
                'notes' => $notes,
            ]);

            // 6. Record reading history event
            ReadingHistory::create([
                'user_id' => $user->id,
                'book_id' => $book->id,
                'action' => 'borrowed',
            ]);

            return $borrowing;
        });
    }

    /**
     * Process return of a borrowed book copy.
     *
     * @throws RuntimeException
     */
    public function returnBook(Borrowing $borrowing, ?string $notes = null): Borrowing
    {
        return DB::transaction(function () use ($borrowing, $notes) {
            if ($borrowing->status === 'returned') {
                throw new RuntimeException('This loan has already been marked as returned.');
            }

            // 1. Lock and update borrowing record
            $borrowing->refresh();
            $borrowing->update([
                'returned_at' => now(),
                'status' => 'returned',
                'notes' => $notes ?? $borrowing->notes,
            ]);

            // 2. Lock and restore copy status to available
            /** @var BookCopy $copy */
            $copy = BookCopy::query()->where('id', $borrowing->book_copy_id)->lockForUpdate()->first();
            if ($copy) {
                $copy->update(['status' => BookCopy::STATUS_AVAILABLE]);
            }

            // 3. Record reading history event
            ReadingHistory::create([
                'user_id' => $borrowing->user_id,
                'book_id' => $copy ? $copy->book_id : $borrowing->book->id,
                'action' => 'returned',
            ]);

            return $borrowing;
        });
    }

    /**
     * Extend/renew a borrowing loan by default duration.
     *
     * @throws RuntimeException
     */
    public function renew(Borrowing $borrowing): Borrowing
    {
        if ($borrowing->status !== 'active') {
            throw new RuntimeException('Only active loans can be renewed.');
        }

        if ($borrowing->isOverdue()) {
            throw new RuntimeException('Overdue loans cannot be renewed online. Please contact library desk.');
        }

        $borrowing->update([
            'due_at' => $borrowing->due_at->addDays(self::DEFAULT_LOAN_DAYS),
        ]);

        return $borrowing;
    }
}
