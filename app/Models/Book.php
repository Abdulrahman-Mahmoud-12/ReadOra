<?php

namespace App\Models;

use Database\Factories\BookFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'publisher_id',
    'title',
    'subtitle',
    'slug',
    'isbn_10',
    'isbn_13',
    'description',
    'language',
    'publication_year',
    'edition',
    'page_count',
    'cover_image_path',
    'average_rating',
    'ratings_count',
    'source',
    'source_identifier',
    'source_url',
    'metadata',
])]
class Book extends Model
{
    /** @use HasFactory<BookFactory> */
    use HasFactory;

    /**
     * Get the publisher for the book.
     */
    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class);
    }

    /**
     * Get the authors who wrote the book.
     */
    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class)->withTimestamps();
    }

    /**
     * Get the categories assigned to the book.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }

    /**
     * Get the physical copies of the book.
     */
    public function copies(): HasMany
    {
        return $this->hasMany(BookCopy::class);
    }

    /**
     * Get available physical copies of the book.
     */
    public function availableCopies(): HasMany
    {
        return $this->copies()->where('status', BookCopy::STATUS_AVAILABLE);
    }

    /**
     * Get favorites records for this book.
     *
     * @return HasMany<Favorite>
     */
    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Users who favorited this book.
     *
     * @return BelongsToMany<User>
     */
    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    /**
     * Check if a specific user favorited this book.
     */
    public function isFavoritedBy(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        return $this->favorites()->where('user_id', $user->id)->exists();
    }

    /**
     * Get reading history records for this book.
     *
     * @return HasMany<ReadingHistory>
     */
    public function readingHistories(): HasMany
    {
        return $this->hasMany(ReadingHistory::class);
    }

    /**
     * Scope a query to records matching the catalog search term.
     */
    public function scopeSearch(Builder $query, string $term): Builder
    {
        $term = trim($term);

        if ($term === '') {
            return $query;
        }

        return $query->where(function (Builder $query) use ($term): void {
            $query->where('title', 'like', "%{$term}%")
                ->orWhere('subtitle', 'like', "%{$term}%")
                ->orWhere('isbn_10', $term)
                ->orWhere('isbn_13', $term)
                ->orWhereHas('authors', fn (Builder $query): Builder => $query->where('name', 'like', "%{$term}%"))
                ->orWhereHas('categories', fn (Builder $query): Builder => $query->where('name', 'like', "%{$term}%"));
        });
    }

    /**
     * Count available physical copies without loading them.
     */
    public function availableCopiesCount(): int
    {
        return $this->availableCopies()->count();
    }

    /**
     * Determine whether at least one copy can be borrowed.
     */
    public function isAvailable(): bool
    {
        return $this->availableCopies()->exists();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'average_rating' => 'decimal:2',
            'metadata' => 'array',
        ];
    }
}
