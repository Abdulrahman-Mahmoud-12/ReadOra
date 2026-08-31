<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdvancedSearchTest extends TestCase
{
    use RefreshDatabase;

    protected Publisher $publisher;

    protected function setUp(): void
    {
        parent::setUp();
        $this->publisher = Publisher::factory()->create(['name' => 'Addison-Wesley']);
    }

    public function test_can_search_by_multi_field_keywords(): void
    {
        $book1 = Book::factory()->create([
            'publisher_id' => $this->publisher->id,
            'title' => 'Clean Architecture',
            'description' => 'A guide to software craftsmanship.',
        ]);
        $book2 = Book::factory()->create([
            'publisher_id' => $this->publisher->id,
            'title' => 'Ancient Egypt History',
            'description' => 'Pharaohs and pyramids.',
        ]);

        $response = $this->get('/books?search=craftsmanship');
        $response->assertStatus(200);
        $response->assertSee('Clean Architecture');
        $response->assertDontSee('Ancient Egypt History');
    }

    public function test_can_filter_by_multiple_categories(): void
    {
        $catCS = Category::factory()->create(['name' => 'Computer Science', 'slug' => 'computer-science']);
        $catMath = Category::factory()->create(['name' => 'Mathematics', 'slug' => 'mathematics']);
        $catArt = Category::factory()->create(['name' => 'Fine Arts', 'slug' => 'fine-arts']);

        $csBook = Book::factory()->create(['publisher_id' => $this->publisher->id, 'title' => 'Algorithms Unlocked']);
        $csBook->categories()->attach($catCS);

        $artBook = Book::factory()->create(['publisher_id' => $this->publisher->id, 'title' => 'Renaissance Painting']);
        $artBook->categories()->attach($catArt);

        $response = $this->get('/books?categories[]='.$catCS->slug);
        $response->assertStatus(200);
        $response->assertSee('Algorithms Unlocked');
        $response->assertDontSee('Renaissance Painting');
    }

    public function test_can_filter_by_minimum_rating_and_availability(): void
    {
        $highRatedBook = Book::factory()->create([
            'publisher_id' => $this->publisher->id,
            'title' => 'Masterpiece Novel',
            'average_rating' => 4.8,
        ]);
        BookCopy::factory()->create(['book_id' => $highRatedBook->id, 'status' => BookCopy::STATUS_AVAILABLE]);

        $lowRatedBook = Book::factory()->create([
            'publisher_id' => $this->publisher->id,
            'title' => 'Mediocre Book',
            'average_rating' => 2.5,
        ]);
        BookCopy::factory()->create(['book_id' => $lowRatedBook->id, 'status' => BookCopy::STATUS_AVAILABLE]);

        $response = $this->get('/books?min_rating=4.5&availability=available');
        $response->assertStatus(200);
        $response->assertSee('Masterpiece Novel');
        $response->assertDontSee('Mediocre Book');
    }

    public function test_can_filter_by_publication_era(): void
    {
        $modernBook = Book::factory()->create([
            'publisher_id' => $this->publisher->id,
            'title' => 'AI in 2024',
            'publication_year' => 2024,
        ]);
        $classicBook = Book::factory()->create([
            'publisher_id' => $this->publisher->id,
            'title' => 'Pride and Prejudice',
            'publication_year' => 1813,
        ]);

        $response = $this->get('/books?era=2020s');
        $response->assertStatus(200);
        $response->assertSee('AI in 2024');
        $response->assertDontSee('Pride and Prejudice');

        $responseClassic = $this->get('/books?era=classic');
        $responseClassic->assertStatus(200);
        $responseClassic->assertSee('Pride and Prejudice');
        $responseClassic->assertDontSee('AI in 2024');
    }

    public function test_can_sort_by_title_alphabetically(): void
    {
        $bookA = Book::factory()->create(['publisher_id' => $this->publisher->id, 'title' => 'A Book of Shadows']);
        $bookZ = Book::factory()->create(['publisher_id' => $this->publisher->id, 'title' => 'Zebra Ecology']);

        $response = $this->get('/books?sort=title_asc');
        $response->assertStatus(200);
        $response->assertSeeInOrder(['A Book of Shadows', 'Zebra Ecology']);
    }
}
