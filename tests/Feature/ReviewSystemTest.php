<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Publisher;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewSystemTest extends TestCase
{
    use RefreshDatabase;

    protected User $patron;

    protected User $admin;

    protected Book $book;

    protected function setUp(): void
    {
        parent::setUp();

        $this->patron = User::factory()->create(['role' => 'user']);
        $this->admin = User::factory()->create(['role' => 'admin']);

        $publisher = Publisher::factory()->create();
        $this->book = Book::factory()->create([
            'publisher_id' => $publisher->id,
            'title' => 'Clean Code',
            'average_rating' => 4.0,
            'ratings_count' => 0,
        ]);
    }

    public function test_guest_cannot_submit_review(): void
    {
        $response = $this->post(route('reviews.store', $this->book), [
            'rating' => 5,
            'title' => 'Incredible Masterpiece',
            'content' => 'Must read for every developer.',
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_patron_can_submit_review_and_updates_book_aggregates(): void
    {
        $response = $this->actingAs($this->patron)->post(route('reviews.store', $this->book), [
            'rating' => 5,
            'title' => 'Exceptional Book',
            'content' => 'Learned so many great clean code principles.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('reviews', [
            'user_id' => $this->patron->id,
            'book_id' => $this->book->id,
            'rating' => 5,
            'title' => 'Exceptional Book',
            'status' => 'approved',
        ]);

        // Aggregate updated
        $this->book->refresh();
        $this->assertEquals(5.00, (float) $this->book->average_rating);
        $this->assertEquals(1, $this->book->ratings_count);
    }

    public function test_submitting_subsequent_review_updates_existing_review(): void
    {
        // First review: 5 stars
        $this->actingAs($this->patron)->post(route('reviews.store', $this->book), [
            'rating' => 5,
            'title' => 'Initial Impression',
        ]);

        // Second review: update to 4 stars
        $response = $this->actingAs($this->patron)->post(route('reviews.store', $this->book), [
            'rating' => 4,
            'title' => 'Revised Thoughts',
        ]);

        $response->assertRedirect();

        $this->assertEquals(1, Review::where('user_id', $this->patron->id)->where('book_id', $this->book->id)->count());
        $this->assertDatabaseHas('reviews', [
            'user_id' => $this->patron->id,
            'book_id' => $this->book->id,
            'rating' => 4,
            'title' => 'Revised Thoughts',
        ]);

        $this->book->refresh();
        $this->assertEquals(4.00, (float) $this->book->average_rating);
    }

    public function test_patron_can_delete_own_review_and_recalculates_aggregates(): void
    {
        $otherPatron = User::factory()->create();

        // Patron 1 gives 5 stars
        Review::create([
            'user_id' => $this->patron->id,
            'book_id' => $this->book->id,
            'rating' => 5,
            'status' => 'approved',
        ]);

        // Patron 2 gives 3 stars
        $review2 = Review::create([
            'user_id' => $otherPatron->id,
            'book_id' => $this->book->id,
            'rating' => 3,
            'status' => 'approved',
        ]);

        $review2->updateBookAggregates();
        $this->book->refresh();
        $this->assertEquals(4.00, (float) $this->book->average_rating);
        $this->assertEquals(2, $this->book->ratings_count);

        // Patron 2 deletes their 3 star review
        $response = $this->actingAs($otherPatron)->delete(route('reviews.destroy', $review2));
        $response->assertRedirect();

        $this->assertDatabaseMissing('reviews', ['id' => $review2->id]);
        $this->book->refresh();
        $this->assertEquals(5.00, (float) $this->book->average_rating);
        $this->assertEquals(1, $this->book->ratings_count);
    }

    public function test_admin_can_moderate_and_reject_review(): void
    {
        $review = Review::create([
            'user_id' => $this->patron->id,
            'book_id' => $this->book->id,
            'rating' => 1,
            'content' => 'Spam content',
            'status' => 'approved',
        ]);
        $review->updateBookAggregates();

        // Admin rejects the review
        $response = $this->actingAs($this->admin)->patch(route('admin.reviews.status', $review), [
            'status' => 'rejected',
        ]);

        $response->assertRedirect();
        $this->assertEquals('rejected', $review->fresh()->status);

        // Rejected review excluded from active calculation
        $this->book->refresh();
        $this->assertEquals(0, $this->book->ratings_count);
    }
}
