<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookDiscoveryTest extends TestCase
{
    use RefreshDatabase;

    public function test_books_catalog_page_renders_with_paginated_books(): void
    {
        $publisher = Publisher::factory()->create();
        $author = Author::factory()->create(['name' => 'Jane Austen']);
        $category = Category::factory()->create(['name' => 'Romance']);

        $featuredBook = Book::factory()->create([
            'publisher_id' => $publisher->id,
            'title' => 'Catalog Featured Classic',
            'slug' => 'catalog-featured-classic',
            'average_rating' => 5.0,
            'ratings_count' => 1000,
        ]);
        $books = Book::factory()->count(14)->create([
            'publisher_id' => $publisher->id,
            'average_rating' => 1.0,
            'ratings_count' => 1,
        ])->prepend($featuredBook);
        foreach ($books as $book) {
            $book->authors()->attach($author);
            $book->categories()->attach($category);
            BookCopy::factory()->for($book)->create();
        }

        $response = $this->get('/books');

        $response->assertOk();
        $response->assertSee('Library Catalog');
        $response->assertSee('Catalog Featured Classic');
        $response->assertViewHas('books');
        $response->assertViewHas('categories');
    }

    public function test_books_catalog_search_filters_results_by_title(): void
    {
        $book1 = Book::factory()->create(['title' => 'The Great Gatsby', 'slug' => 'the-great-gatsby']);
        $book2 = Book::factory()->create(['title' => 'Moby-Dick', 'slug' => 'moby-dick']);

        $response = $this->get('/books?search=Gatsby');

        $response->assertOk();
        $response->assertSee('The Great Gatsby');
        $response->assertDontSee('Moby-Dick');
    }

    public function test_books_catalog_filters_by_category_slug(): void
    {
        $fiction = Category::factory()->create(['name' => 'Fiction', 'slug' => 'fiction']);
        $science = Category::factory()->create(['name' => 'Science', 'slug' => 'science']);

        $bookFiction = Book::factory()->create(['title' => 'Fiction Book', 'slug' => 'fiction-book']);
        $bookFiction->categories()->attach($fiction);

        $bookScience = Book::factory()->create(['title' => 'Science Book', 'slug' => 'science-book']);
        $bookScience->categories()->attach($science);

        $response = $this->get('/books?category=fiction');

        $response->assertOk();
        $response->assertSee('Fiction Book');
        $response->assertDontSee('Science Book');
    }

    public function test_books_catalog_filters_by_availability(): void
    {
        $availableBook = Book::factory()->create(['title' => 'Available Book', 'slug' => 'available-book']);
        BookCopy::factory()->for($availableBook)->create(['status' => BookCopy::STATUS_AVAILABLE]);

        $borrowedBook = Book::factory()->create(['title' => 'Borrowed Book', 'slug' => 'borrowed-book']);
        BookCopy::factory()->for($borrowedBook)->borrowed()->create();

        $response = $this->get('/books?availability=available');

        $response->assertOk();
        $response->assertSee('Available Book');
        $response->assertDontSee('Borrowed Book');
    }

    public function test_books_catalog_sorts_by_publication_year(): void
    {
        Book::factory()->create(['title' => 'Old Book', 'slug' => 'old-book', 'publication_year' => 1800]);
        Book::factory()->create(['title' => 'New Book', 'slug' => 'new-book', 'publication_year' => 2020]);

        $response = $this->get('/books?sort=year_desc');

        $response->assertOk();
        $books = $response->viewData('books');
        $this->assertSame('New Book', $books->first()->title);
    }

    public function test_book_details_page_renders_complete_metadata_and_copies(): void
    {
        $publisher = Publisher::factory()->create(['name' => 'Oxford University Press']);
        $author = Author::factory()->create(['name' => 'Mary Shelley']);
        $category = Category::factory()->create(['name' => 'Gothic']);
        $book = Book::factory()->create([
            'publisher_id' => $publisher->id,
            'title' => 'Frankenstein',
            'slug' => 'frankenstein',
            'publication_year' => 1818,
            'page_count' => 280,
            'description' => 'A famous gothic novel about Victor Frankenstein and the creature.',
        ]);
        $book->authors()->attach($author);
        $book->categories()->attach($category);
        $copy = BookCopy::factory()->for($book)->create([
            'barcode' => 'RO-TEST-01',
            'location' => 'Main Stacks Room A',
            'status' => BookCopy::STATUS_AVAILABLE,
        ]);

        $response = $this->get('/books/frankenstein');

        $response->assertOk();
        $response->assertSee('Frankenstein');
        $response->assertSee('Mary Shelley');
        $response->assertSee('Oxford University Press');
        $response->assertSee('Gothic');
        $response->assertSee('RO-TEST-01');
        $response->assertSee('Main Stacks Room A');
        $response->assertSee('1818');
        $response->assertSee('280 pages');
    }

    public function test_book_details_returns_404_for_non_existent_slug(): void
    {
        $response = $this->get('/books/non-existent-book-slug');

        $response->assertNotFound();
    }
}
