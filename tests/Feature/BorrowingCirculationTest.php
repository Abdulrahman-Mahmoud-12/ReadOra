<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Borrowing;
use App\Models\Category;
use App\Models\Publisher;
use App\Models\User;
use App\Services\BorrowingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BorrowingCirculationTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Book $book;

    protected BookCopy $copy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'user']);

        $category = Category::factory()->create(['name' => 'Fiction', 'slug' => 'fiction']);
        $author = Author::factory()->create(['name' => 'Jane Austen', 'slug' => 'jane-austen']);
        $publisher = Publisher::factory()->create(['name' => 'Penguin Classics', 'slug' => 'penguin-classics']);

        $this->book = Book::factory()->create([
            'publisher_id' => $publisher->id,
            'title' => 'Pride and Prejudice',
            'slug' => 'pride-and-prejudice',
            'isbn_13' => '9780141439518',
            'language' => 'en',
            'publication_year' => 1813,
            'average_rating' => 4.75,
            'ratings_count' => 1250,
        ]);

        $this->book->categories()->attach($category);
        $this->book->authors()->attach($author);

        $this->copy = BookCopy::factory()->create([
            'book_id' => $this->book->id,
            'barcode' => 'READ-PRIDE-001',
            'status' => BookCopy::STATUS_AVAILABLE,
            'condition' => 'good',
        ]);
    }

    public function test_guest_cannot_borrow_book(): void
    {
        $response = $this->post(route('borrowings.store'), [
            'book_id' => $this->book->id,
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseEmpty('borrowings');
    }

    public function test_authenticated_patron_can_borrow_available_book(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('borrowings.store'), [
                'book_id' => $this->book->id,
            ]);

        $response->assertRedirect(route('borrowings.index'));
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('borrowings', [
            'user_id' => $this->user->id,
            'book_copy_id' => $this->copy->id,
            'status' => 'active',
        ]);

        $this->assertDatabaseHas('book_copies', [
            'id' => $this->copy->id,
            'status' => BookCopy::STATUS_BORROWED,
        ]);

        $this->assertDatabaseHas('reading_histories', [
            'user_id' => $this->user->id,
            'book_id' => $this->book->id,
            'action' => 'borrowed',
        ]);
    }

    public function test_patron_cannot_borrow_when_no_copies_are_available(): void
    {
        $this->copy->update(['status' => BookCopy::STATUS_BORROWED]);

        $response = $this->actingAs($this->user)
            ->from(route('books.show', $this->book->slug))
            ->post(route('borrowings.store'), [
                'book_id' => $this->book->id,
            ]);

        $response->assertRedirect(route('books.show', $this->book->slug));
        $response->assertSessionHas('error');
        $this->assertDatabaseEmpty('borrowings');
    }

    public function test_patron_cannot_borrow_same_book_twice_concurrently(): void
    {
        // Add a second available copy of the same book
        BookCopy::factory()->create([
            'book_id' => $this->book->id,
            'barcode' => 'READ-PRIDE-002',
            'status' => BookCopy::STATUS_AVAILABLE,
        ]);

        // First loan succeeds
        $service = app(BorrowingService::class);
        $service->borrow($this->user, $this->book);

        // Attempting to borrow again should fail
        $response = $this->actingAs($this->user)
            ->post(route('borrowings.store'), [
                'book_id' => $this->book->id,
            ]);

        $response->assertSessionHas('error');
        $this->assertEquals(1, Borrowing::where('user_id', $this->user->id)->count());
    }

    public function test_patron_cannot_exceed_max_active_loans_limit(): void
    {
        $service = app(BorrowingService::class);

        // Create 5 different books and borrow them
        for ($i = 1; $i <= 5; $i++) {
            $extraBook = Book::factory()->create(['title' => "Book {$i}", 'slug' => "book-{$i}"]);
            BookCopy::factory()->create([
                'book_id' => $extraBook->id,
                'status' => BookCopy::STATUS_AVAILABLE,
            ]);
            $service->borrow($this->user, $extraBook);
        }

        $this->assertEquals(5, $this->user->activeBorrowings()->count());

        // Attempt 6th loan should fail
        $response = $this->actingAs($this->user)
            ->post(route('borrowings.store'), [
                'book_id' => $this->book->id,
            ]);

        $response->assertSessionHas('error');
        $this->assertEquals(5, $this->user->activeBorrowings()->count());
    }

    public function test_patron_can_return_borrowed_book(): void
    {
        $service = app(BorrowingService::class);
        $borrowing = $service->borrow($this->user, $this->book);

        $response = $this->actingAs($this->user)
            ->post(route('borrowings.return', $borrowing));

        $response->assertRedirect(route('borrowings.index'));
        $response->assertSessionHas('status');

        $borrowing->refresh();
        $this->assertEquals('returned', $borrowing->status);
        $this->assertNotNull($borrowing->returned_at);

        $this->copy->refresh();
        $this->assertEquals(BookCopy::STATUS_AVAILABLE, $this->copy->status);

        $this->assertDatabaseHas('reading_histories', [
            'user_id' => $this->user->id,
            'book_id' => $this->book->id,
            'action' => 'returned',
        ]);
    }

    public function test_patron_can_renew_active_loan(): void
    {
        $service = app(BorrowingService::class);
        $borrowing = $service->borrow($this->user, $this->book);
        $originalDueDate = $borrowing->due_at;

        $response = $this->actingAs($this->user)
            ->post(route('borrowings.renew', $borrowing));

        $response->assertRedirect(route('borrowings.index'));
        $response->assertSessionHas('status');

        $borrowing->refresh();
        $this->assertTrue($borrowing->due_at->gt($originalDueDate));
    }

    public function test_patron_can_toggle_favorites(): void
    {
        // 1. Save to favorites
        $response = $this->actingAs($this->user)
            ->post(route('favorites.toggle', $this->book));

        $response->assertSessionHas('status');
        $this->assertDatabaseHas('favorites', [
            'user_id' => $this->user->id,
            'book_id' => $this->book->id,
        ]);
        $this->assertDatabaseHas('reading_histories', [
            'user_id' => $this->user->id,
            'book_id' => $this->book->id,
            'action' => 'favorited',
        ]);

        // 2. Remove from favorites
        $response = $this->actingAs($this->user)
            ->post(route('favorites.toggle', $this->book));

        $response->assertSessionHas('status');
        $this->assertDatabaseMissing('favorites', [
            'user_id' => $this->user->id,
            'book_id' => $this->book->id,
        ]);
    }

    public function test_borrowings_index_displays_active_loans_and_history(): void
    {
        $service = app(BorrowingService::class);
        $service->borrow($this->user, $this->book);

        $response = $this->actingAs($this->user)
            ->get(route('borrowings.index'));

        $response->assertOk();
        $response->assertSee('Pride and Prejudice');
        $response->assertSee('Active Loan');
        $response->assertSee('Return Book');
    }
}
