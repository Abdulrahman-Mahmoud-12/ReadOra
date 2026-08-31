<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Borrowing extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'book_copy_id',
        'borrowed_at',
        'due_at',
        'returned_at',
        'status',
        'notes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'borrowed_at' => 'datetime',
            'due_at' => 'datetime',
            'returned_at' => 'datetime',
        ];
    }

    /**
     * The patron who borrowed the book copy.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The specific physical copy that was borrowed.
     */
    public function bookCopy(): BelongsTo
    {
        return $this->belongsTo(BookCopy::class);
    }

    /**
     * The bibliographic book entity linked through the book copy.
     */
    public function book(): HasOneThrough
    {
        return $this->hasOneThrough(
            Book::class,
            BookCopy::class,
            'id', // Foreign key on book_copies table
            'id', // Foreign key on books table
            'book_copy_id', // Local key on borrowings table
            'book_id' // Local key on book_copies table
        );
    }

    /**
     * Scope a query to only include active loans.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include overdue loans.
     */
    public function scopeOverdue(Builder $query): Builder
    {
        return $query->where('status', 'active')
            ->where('due_at', '<', now());
    }

    /**
     * Scope a query to only include returned loans.
     */
    public function scopeReturned(Builder $query): Builder
    {
        return $query->where('status', 'returned');
    }

    /**
     * Check if the borrowing is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->status === 'active' && $this->due_at->isPast();
    }

    /**
     * Calculate remaining days before due date.
     */
    public function daysRemaining(): int
    {
        if ($this->status !== 'active') {
            return 0;
        }

        return (int) now()->diffInDays($this->due_at, false);
    }
}
