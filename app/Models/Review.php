<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    public const STATUS_APPROVED = 'approved';

    public const STATUS_PENDING = 'pending';

    public const STATUS_REJECTED = 'rejected';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'book_id',
        'rating',
        'title',
        'content',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'rating' => 'integer',
        ];
    }

    /**
     * The patron who wrote the review.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The book being reviewed.
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Scope query to approved reviews only.
     */
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Scope query to pending reviews only.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Recalculate and update the aggregate ratings on the associated book.
     */
    public function updateBookAggregates(): void
    {
        $book = $this->book;
        if (! $book) {
            return;
        }

        $approvedReviews = $book->reviews()->where('status', self::STATUS_APPROVED);
        $count = $approvedReviews->count();

        if ($count > 0) {
            $avg = $approvedReviews->avg('rating');
            $book->update([
                'average_rating' => round($avg, 2),
                'ratings_count' => $count,
            ]);
        } else {
            $book->update([
                'average_rating' => 4.00,
                'ratings_count' => 0,
            ]);
        }
    }
}
