<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogRelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_book_records_keep_catalog_relationships_and_copy_availability(): void
    {
        $author = Author::factory()->create(['name' => 'Mary Wollstonecraft Shelley', 'slug' => 'mary-wollstonecraft-shelley']);
        $category = Category::factory()->create(['name' => 'Gothic Fiction', 'slug' => 'gothic-fiction']);
        $book = Book::factory()->create(['title' => 'Frankenstein', 'slug' => 'frankenstein']);
        BookCopy::factory()->for($book)->create(['status' => BookCopy::STATUS_AVAILABLE]);
        BookCopy::factory()->for($book)->borrowed()->create();

        $book->authors()->attach($author);
        $book->categories()->attach($category);
        $book->refresh();

        $this->assertTrue($book->authors->contains($author));
        $this->assertTrue($book->categories->contains($category));
        $this->assertSame(2, $book->copies()->count());
        $this->assertSame(1, $book->availableCopiesCount());
        $this->assertTrue($book->isAvailable());
    }

    public function test_book_search_matches_title_author_category_and_isbn(): void
    {
        $author = Author::factory()->create(['name' => 'Arthur Conan Doyle', 'slug' => 'arthur-conan-doyle']);
        $category = Category::factory()->create(['name' => 'Mystery', 'slug' => 'mystery']);
        $book = Book::factory()->create([
            'title' => 'The Hound of the Baskervilles',
            'slug' => 'the-hound-of-the-baskervilles',
            'isbn_13' => '9780000000001',
        ]);

        $book->authors()->attach($author);
        $book->categories()->attach($category);

        $this->assertTrue(Book::query()->search('Baskervilles')->whereKey($book)->exists());
        $this->assertTrue(Book::query()->search('Conan Doyle')->whereKey($book)->exists());
        $this->assertTrue(Book::query()->search('Mystery')->whereKey($book)->exists());
        $this->assertTrue(Book::query()->search('9780000000001')->whereKey($book)->exists());
    }
}
