<?php

namespace Tests\Unit;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Category;
use App\Models\Favorite;
use App\Models\Publisher;
use App\Models\User;
use App\Services\BorrowingService;
use App\Services\RecommendationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecommendationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected RecommendationService $service;

    protected Publisher $publisher;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(RecommendationService::class);
        $this->publisher = Publisher::factory()->create(['name' => 'Tech Publications']);
    }

    public function test_cold_start_returns_top_rated_available_books_for_guest_or_new_user(): void
    {
        $book1 = Book::factory()->create(['publisher_id' => $this->publisher->id, 'average_rating' => 4.9, 'ratings_count' => 1000]);
        $book2 = Book::factory()->create(['publisher_id' => $this->publisher->id, 'average_rating' => 3.5, 'ratings_count' => 500]);
        BookCopy::factory()->create(['book_id' => $book1->id, 'status' => BookCopy::STATUS_AVAILABLE]);
        BookCopy::factory()->create(['book_id' => $book2->id, 'status' => BookCopy::STATUS_AVAILABLE]);

        $recommendations = $this->service->getRecommendationsForUser(null, 2);

        $this->assertNotEmpty($recommendations);
        $this->assertSame($book1->id, $recommendations->first()->id);
    }

    public function test_recommends_books_matching_patron_favorited_category_affinity(): void
    {
        $user = User::factory()->create();

        $categoryAI = Category::factory()->create(['name' => 'Artificial Intelligence']);
        $categoryHistory = Category::factory()->create(['name' => 'Ancient History']);

        // User favorited book in Artificial Intelligence
        $aiBook1 = Book::factory()->create(['publisher_id' => $this->publisher->id, 'title' => 'Deep Learning Basics', 'average_rating' => 4.2]);
        $aiBook1->categories()->attach($categoryAI);
        Favorite::create(['user_id' => $user->id, 'book_id' => $aiBook1->id]);

        // Target book to recommend in AI
        $aiBook2 = Book::factory()->create(['publisher_id' => $this->publisher->id, 'title' => 'Advanced Neural Networks', 'average_rating' => 4.0]);
        $aiBook2->categories()->attach($categoryAI);

        // Irrelevant book in History
        $historyBook = Book::factory()->create(['publisher_id' => $this->publisher->id, 'title' => 'Roman Empire', 'average_rating' => 4.0]);
        $historyBook->categories()->attach($categoryHistory);

        $recommendations = $this->service->getRecommendationsForUser($user, 3);

        $topIds = $recommendations->pluck('id')->toArray();
        $this->assertContains($aiBook2->id, $topIds);
        // AI Book 2 should score higher than History Book due to category affinity
        $this->assertTrue(
            array_search($aiBook2->id, $topIds, true) < array_search($historyBook->id, $topIds, true)
        );
    }

    public function test_recommends_books_by_authors_the_patron_has_borrowed(): void
    {
        $user = User::factory()->create();
        $author = Author::factory()->create(['name' => 'Robert C. Martin']);

        $borrowedBook = Book::factory()->create(['publisher_id' => $this->publisher->id, 'title' => 'Clean Code']);
        $borrowedBook->authors()->attach($author);
        $copy = BookCopy::factory()->create(['book_id' => $borrowedBook->id, 'status' => BookCopy::STATUS_AVAILABLE]);

        // Borrow Clean Code
        $borrowingService = app(BorrowingService::class);
        $borrowing = $borrowingService->borrow($user, $borrowedBook);
        $borrowingService->returnBook($borrowing);

        // Another book by same author
        $cleanArchitecture = Book::factory()->create(['publisher_id' => $this->publisher->id, 'title' => 'Clean Architecture', 'average_rating' => 4.1]);
        $cleanArchitecture->authors()->attach($author);

        $recommendations = $this->service->getRecommendationsForUser($user, 5);

        $this->assertTrue($recommendations->contains('id', $cleanArchitecture->id));
    }

    public function test_excludes_currently_active_borrowings_from_recommendations(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['name' => 'Programming']);

        $activeBook = Book::factory()->create(['publisher_id' => $this->publisher->id, 'title' => 'Python Basics']);
        $activeBook->categories()->attach($category);
        $copy = BookCopy::factory()->create(['book_id' => $activeBook->id, 'status' => BookCopy::STATUS_AVAILABLE]);

        // Currently actively checked out
        $borrowingService = app(BorrowingService::class);
        $borrowingService->borrow($user, $activeBook);

        $recommendations = $this->service->getRecommendationsForUser($user, 5);

        $this->assertFalse($recommendations->contains('id', $activeBook->id));
    }

    public function test_get_similar_books_returns_overlapping_category_and_author_works(): void
    {
        $category = Category::factory()->create(['name' => 'Algorithms']);
        $author = Author::factory()->create(['name' => 'Donald Knuth']);

        $targetBook = Book::factory()->create(['publisher_id' => $this->publisher->id, 'title' => 'TAOCP Vol 1']);
        $targetBook->categories()->attach($category);
        $targetBook->authors()->attach($author);

        $similarBook = Book::factory()->create(['publisher_id' => $this->publisher->id, 'title' => 'TAOCP Vol 2']);
        $similarBook->categories()->attach($category);
        $similarBook->authors()->attach($author);

        $unrelatedBook = Book::factory()->create(['publisher_id' => $this->publisher->id, 'title' => 'Cooking Recipes']);

        $similar = $this->service->getSimilarBooks($targetBook, 2);

        $this->assertFalse($similar->contains('id', $targetBook->id));
        $this->assertTrue($similar->contains('id', $similarBook->id));
    }
}
