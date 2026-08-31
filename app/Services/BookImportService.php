<?php

namespace App\Services;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;
use RuntimeException;

class BookImportService
{
    /**
     * Import books from a CSV file into the database.
     *
     * @return array{books: int, copies: int, skipped: int, errors: list<string>}
     */
    public function importFromCsv(string $filePath): array
    {
        if (! file_exists($filePath) || ! is_readable($filePath)) {
            throw new InvalidArgumentException("CSV file does not exist or is not readable at [{$filePath}].");
        }

        $handle = fopen($filePath, 'r');
        if (! $handle) {
            throw new RuntimeException("Failed to open CSV file at [{$filePath}].");
        }

        // Read header row
        $headers = fgetcsv($handle);
        if (! $headers) {
            fclose($handle);
            throw new RuntimeException("CSV file at [{$filePath}] is empty.");
        }

        // Clean headers (remove BOM and trim)
        $headers = array_map(fn ($header) => trim((string) preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $header)), $headers);

        $summary = [
            'books' => 0,
            'copies' => 0,
            'skipped' => 0,
            'errors' => [],
        ];

        $rowNumber = 1;

        while (($row = fgetcsv($handle)) !== false) {
            $rowNumber++;

            if (count($row) < count($headers)) {
                // Pad row if it has missing trailing columns
                $row = array_pad($row, count($headers), '');
            }

            $record = array_combine($headers, array_slice($row, 0, count($headers)));

            if (! $this->isValidRecord($record)) {
                $summary['skipped']++;

                continue;
            }

            try {
                $result = DB::transaction(fn () => $this->persistRecord($record));
                if ($result['book_created']) {
                    $summary['books']++;
                }
                $summary['copies'] += $result['copies_created'];
            } catch (\Throwable $e) {
                $summary['skipped']++;
                $summary['errors'][] = "Row {$rowNumber} (\"".($record['title'] ?? 'Unknown').'"): '.$e->getMessage();
            }
        }

        fclose($handle);

        return $summary;
    }

    /**
     * Determine if a CSV record contains minimum required metadata.
     *
     * @param  array<string, mixed>  $record
     */
    private function isValidRecord(array $record): bool
    {
        return filled($record['title'] ?? null)
            && filled($record['authors'] ?? null);
    }

    /**
     * Persist a book record, its publisher, authors, categories, and physical copies.
     *
     * @param  array<string, mixed>  $record
     * @return array{book_created: bool, copies_created: int}
     */
    public function persistRecord(array $record): array
    {
        $publisherName = trim((string) ($record['publisher'] ?? ''));
        $publisher = $this->resolvePublisher($publisherName ?: 'Independent Publishing');

        $title = trim((string) $record['title']);
        $isbn13 = filled($record['isbn_13'] ?? null) ? preg_replace('/[^0-9X]/i', '', trim((string) $record['isbn_13'])) : null;
        $isbn10 = filled($record['isbn_10'] ?? null) ? preg_replace('/[^0-9X]/i', '', trim((string) $record['isbn_10'])) : null;

        // Lookup existing book by ISBN-13, ISBN-10, or exact Title
        $book = null;
        if ($isbn13) {
            $book = Book::where('isbn_13', $isbn13)->first();
        }
        if (! $book && $isbn10) {
            $book = Book::where('isbn_10', $isbn10)->first();
        }
        if (! $book) {
            $book = Book::where('title', $title)->first();
        }

        $bookCreated = false;
        if (! $book) {
            $book = new Book;
            $bookCreated = true;
            $book->slug = $this->generateUniqueSlug($title);
        }

        $book->fill([
            'publisher_id' => $publisher->id,
            'title' => $title,
            'subtitle' => filled($record['subtitle'] ?? null) ? trim((string) $record['subtitle']) : null,
            'isbn_10' => $isbn10,
            'isbn_13' => $isbn13,
            'description' => filled($record['description'] ?? null) ? trim((string) $record['description']) : null,
            'language' => filled($record['language'] ?? null) ? strtolower(trim((string) $record['language'])) : 'en',
            'publication_year' => filled($record['publication_year'] ?? null) ? (int) $record['publication_year'] : null,
            'edition' => filled($record['edition'] ?? null) ? trim((string) $record['edition']) : 'Standard Edition',
            'page_count' => filled($record['page_count'] ?? null) ? (int) $record['page_count'] : null,
            'cover_image_path' => filled($record['cover_image_path'] ?? null) ? trim((string) $record['cover_image_path']) : null,
            'average_rating' => filled($record['average_rating'] ?? null) ? (float) $record['average_rating'] : 4.00,
            'ratings_count' => filled($record['ratings_count'] ?? null) ? (int) $record['ratings_count'] : 0,
            'source' => filled($record['source'] ?? null) ? trim((string) $record['source']) : 'Open Library',
            'source_identifier' => filled($record['source_identifier'] ?? null) ? trim((string) $record['source_identifier']) : null,
            'source_url' => filled($record['source_url'] ?? null) ? trim((string) $record['source_url']) : null,
        ]);

        $book->save();

        // Authors (semicolon-separated)
        $authorsString = (string) ($record['authors'] ?? '');
        $authors = $this->resolveAuthors($authorsString);
        $book->authors()->sync($authors->pluck('id'));

        // Categories (semicolon-separated)
        $categoriesString = (string) ($record['categories'] ?? 'General');
        $categories = $this->resolveCategories($categoriesString);
        $book->categories()->sync($categories->pluck('id'));

        // Physical copies
        $requestedCopies = filled($record['copies'] ?? null) ? max(1, (int) $record['copies']) : 2;
        $copiesCreated = $this->ensurePhysicalCopies($book, $requestedCopies);

        return [
            'book_created' => $bookCreated,
            'copies_created' => $copiesCreated,
        ];
    }

    private function resolvePublisher(string $name): Publisher
    {
        $name = trim($name);
        $slug = Str::slug($name);

        return Publisher::firstOrCreate(
            ['slug' => $slug],
            ['name' => $name]
        );
    }

    /**
     * @return Collection<int, Author>
     */
    private function resolveAuthors(string $authorsString): Collection
    {
        $authorNames = array_filter(array_map('trim', explode(';', $authorsString)));

        if (empty($authorNames)) {
            $authorNames = ['Unknown Author'];
        }

        $collection = collect();
        foreach ($authorNames as $name) {
            $slug = Str::slug($name);
            $author = Author::firstOrCreate(
                ['slug' => $slug],
                ['name' => $name]
            );
            $collection->push($author);
        }

        return $collection;
    }

    /**
     * @return Collection<int, Category>
     */
    private function resolveCategories(string $categoriesString): Collection
    {
        $categoryNames = array_filter(array_map('trim', explode(';', $categoriesString)));

        if (empty($categoryNames)) {
            $categoryNames = ['General Literature'];
        }

        $collection = collect();
        foreach ($categoryNames as $name) {
            $slug = Str::slug($name);
            $category = Category::firstOrCreate(
                ['slug' => $slug],
                ['name' => $name]
            );
            $collection->push($category);
        }

        return $collection;
    }

    private function ensurePhysicalCopies(Book $book, int $targetCopiesCount): int
    {
        $currentCount = $book->copies()->count();
        if ($currentCount >= $targetCopiesCount) {
            return 0;
        }

        $toCreate = $targetCopiesCount - $currentCount;
        $cleanPrefix = strtoupper(substr(preg_replace('/[^A-Z0-9]/i', '', $book->title), 0, 5));
        if (strlen($cleanPrefix) < 3) {
            $cleanPrefix = 'BOOK'.$book->id;
        }

        $locations = [
            'Main Stacks, Shelf A-'.rand(1, 20),
            'Main Stacks, Shelf B-'.rand(1, 20),
            'Reserve Desk, Section R-'.rand(1, 10),
            'Tech & Sciences Stacks, Shelf T-'.rand(1, 15),
            'Literature Section, Shelf L-'.rand(1, 25),
        ];

        for ($i = 1; $i <= $toCreate; $i++) {
            $index = $currentCount + $i;
            $barcode = sprintf('READ-%s-%03d-%02d', $cleanPrefix, $book->id, $index);

            // Ensure barcode is unique
            if (BookCopy::where('barcode', $barcode)->exists()) {
                $barcode .= '-'.rand(10, 99);
            }

            BookCopy::create([
                'book_id' => $book->id,
                'barcode' => $barcode,
                'status' => BookCopy::STATUS_AVAILABLE,
                'location' => $locations[array_rand($locations)],
                'condition' => 'good',
                'acquisition_date' => now()->subDays(rand(10, 365)),
            ]);
        }

        return $toCreate;
    }

    private function generateUniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $counter = 1;

        while (Book::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
