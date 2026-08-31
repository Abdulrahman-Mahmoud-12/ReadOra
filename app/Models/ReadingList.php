<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class ReadingList extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'is_public',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
        ];
    }

    /**
     * Boot model events for automatic slug generation.
     */
    protected static function booted(): void
    {
        static::creating(function (self $list): void {
            if (empty($list->slug)) {
                $baseSlug = Str::slug($list->name) ?: 'list';
                $slug = $baseSlug;
                $count = 1;

                while (static::where('user_id', $list->user_id)->where('slug', $slug)->exists()) {
                    $slug = "{$baseSlug}-{$count}";
                    $count++;
                }

                $list->slug = $slug;
            }
        });
    }

    /**
     * The patron who owns this reading list.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Books contained in this reading list.
     */
    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'book_reading_list')
            ->withPivot(['id', 'notes', 'order'])
            ->withTimestamps()
            ->orderByPivot('order', 'asc');
    }

    /**
     * Check if a specific book is in this list.
     */
    public function hasBook(Book $book): bool
    {
        return $this->books()->where('books.id', $book->id)->exists();
    }

    /**
     * Scope query to public lists.
     */
    public function scopePublic(Builder $query): Builder
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope query to private lists.
     */
    public function scopePrivate(Builder $query): Builder
    {
        return $query->where('is_public', false);
    }
}
