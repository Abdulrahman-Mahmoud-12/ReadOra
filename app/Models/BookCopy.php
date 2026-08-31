<?php

namespace App\Models;

use Database\Factories\BookCopyFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['book_id', 'barcode', 'status', 'location', 'condition', 'acquisition_date'])]
class BookCopy extends Model
{
    public const STATUS_AVAILABLE = 'available';

    public const STATUS_BORROWED = 'borrowed';

    public const STATUS_RESERVED = 'reserved';

    public const STATUS_LOST = 'lost';

    public const STATUS_MAINTENANCE = 'maintenance';

    /** @use HasFactory<BookCopyFactory> */
    use HasFactory;

    /**
     * Get the catalog book this physical copy belongs to.
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Determine if the copy can currently be borrowed.
     */
    public function isAvailable(): bool
    {
        return $this->status === self::STATUS_AVAILABLE;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'acquisition_date' => 'date',
        ];
    }
}
