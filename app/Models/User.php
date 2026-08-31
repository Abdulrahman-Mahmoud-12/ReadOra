<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Determine if the user is an administrator.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Determine if the user is a normal patron.
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Get all borrowings for the user.
     *
     * @return HasMany<Borrowing>
     */
    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class);
    }

    /**
     * Get active borrowings currently checked out by the user.
     *
     * @return HasMany<Borrowing>
     */
    public function activeBorrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class)->where('status', 'active');
    }

    /**
     * Get user favorites records.
     *
     * @return HasMany<Favorite>
     */
    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Get favorited books directly.
     *
     * @return BelongsToMany<Book>
     */
    public function favoriteBooks(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'favorites')->withTimestamps();
    }

    /**
     * Get user reading history entries.
     *
     * @return HasMany<ReadingHistory>
     */
    public function readingHistories(): HasMany
    {
        return $this->hasMany(ReadingHistory::class);
    }

    /**
     * Get all book reviews written by the user.
     *
     * @return HasMany<Review>
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get all reading lists and shelves owned by the user.
     *
     * @return HasMany<ReadingList>
     */
    public function readingLists(): HasMany
    {
        return $this->hasMany(ReadingList::class);
    }

    /**
     * Ensure standard shelves exist for this user.
     */
    public function ensureDefaultShelves(): void
    {
        $defaults = [
            ['name' => 'Want to Read', 'description' => 'Books I plan on reading next.', 'is_public' => false],
            ['name' => 'Currently Reading', 'description' => 'Books currently in progress.', 'is_public' => false],
            ['name' => 'Read', 'description' => 'Books I have completed.', 'is_public' => false],
        ];

        foreach ($defaults as $shelf) {
            $this->readingLists()->firstOrCreate(
                ['name' => $shelf['name']],
                ['description' => $shelf['description'], 'is_public' => $shelf['is_public']]
            );
        }
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
