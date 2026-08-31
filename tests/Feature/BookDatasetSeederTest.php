<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Category;
use App\Models\Publisher;
use Database\Seeders\BookDatasetSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookDatasetSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_loads_real_catalog_dataset_with_relationships_and_copies(): void
    {
        $seeder = new BookDatasetSeeder;
        $summary = $seeder->run();

        $this->assertSame(107, $summary['books']);
        $this->assertGreaterThanOrEqual(100, $summary['books']);
        $this->assertLessThanOrEqual(300, $summary['books']);
        $this->assertGreaterThanOrEqual(100, $summary['copies']);
        $this->assertSame(0, $summary['skipped']);

        $this->assertSame(107, Book::query()->count());
        $this->assertGreaterThan(20, Author::query()->count());
        $this->assertGreaterThan(10, Category::query()->count());
        $this->assertSame(1, Publisher::query()->count());

        $book = Book::query()
            ->where('title', 'Pride and Prejudice')
            ->with(['authors', 'categories', 'copies'])
            ->firstOrFail();

        $this->assertSame('Jane Austen', $book->authors->first()?->name);
        $this->assertTrue($book->categories->contains('name', 'Romance'));
        $this->assertSame(3, $book->copies->count());
        $this->assertTrue($book->isAvailable());
    }

    public function test_seeder_is_idempotent_for_existing_source_records(): void
    {
        $seeder = new BookDatasetSeeder;
        $seeder->run();
        $summary = $seeder->run();

        $this->assertSame(0, $summary['books']);
        $this->assertSame(0, $summary['copies']);
        $this->assertSame(107, Book::query()->count());
        $this->assertGreaterThanOrEqual(100, BookCopy::query()->count());
    }
}
