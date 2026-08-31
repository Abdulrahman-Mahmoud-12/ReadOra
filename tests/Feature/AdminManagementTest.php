<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Category;
use App\Models\Publisher;
use App\Models\User;
use App\Services\BorrowingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $patron;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->patron = User::factory()->create(['role' => 'user']);
    }

    public function test_non_admin_users_cannot_access_admin_dashboard(): void
    {
        $response = $this->actingAs($this->patron)->get('/admin');
        $response->assertStatus(403);
    }

    public function test_admin_can_access_admin_dashboard_with_metrics(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Library Administration Overview');
    }

    public function test_admin_can_view_circulation_desk_and_return_book(): void
    {
        $publisher = Publisher::factory()->create();
        $book = Book::factory()->create(['publisher_id' => $publisher->id]);
        $copy = BookCopy::factory()->create(['book_id' => $book->id, 'status' => BookCopy::STATUS_AVAILABLE]);

        $borrowingService = app(BorrowingService::class);
        $borrowing = $borrowingService->borrow($this->patron, $book);

        $response = $this->actingAs($this->admin)->get('/admin/circulations');
        $response->assertStatus(200);
        $response->assertSee($book->title);

        // Admin returns book
        $returnResponse = $this->actingAs($this->admin)->post(route('admin.circulations.return', $borrowing));
        $returnResponse->assertRedirect();

        $this->assertDatabaseHas('borrowings', [
            'id' => $borrowing->id,
            'status' => 'returned',
        ]);
        $this->assertDatabaseHas('book_copies', [
            'id' => $copy->id,
            'status' => 'available',
        ]);
    }

    public function test_admin_can_create_new_book_with_physical_copies(): void
    {
        $author = Author::factory()->create();
        $category = Category::factory()->create();
        $publisher = Publisher::factory()->create();

        $response = $this->actingAs($this->admin)->post(route('admin.books.store'), [
            'title' => 'Test Driven Development by Example',
            'publisher_id' => $publisher->id,
            'authors' => [$author->id],
            'categories' => [$category->id],
            'isbn_13' => '9780321146533',
            'publication_year' => 2002,
            'language' => 'en',
            'page_count' => 240,
            'initial_copies' => 3,
        ]);

        $response->assertRedirect(route('admin.books.index'));

        $this->assertDatabaseHas('books', ['title' => 'Test Driven Development by Example']);
        $book = Book::where('title', 'Test Driven Development by Example')->first();
        $this->assertCount(3, $book->copies);
        $this->assertDatabaseHas('audit_logs', ['action' => 'book.created']);
    }

    public function test_admin_cannot_delete_book_with_active_loans(): void
    {
        $publisher = Publisher::factory()->create();
        $book = Book::factory()->create(['publisher_id' => $publisher->id]);
        $copy = BookCopy::factory()->create(['book_id' => $book->id, 'status' => BookCopy::STATUS_AVAILABLE]);

        $borrowingService = app(BorrowingService::class);
        $borrowingService->borrow($this->patron, $book);

        $response = $this->actingAs($this->admin)->delete(route('admin.books.destroy', $book));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('books', ['id' => $book->id]);
    }

    public function test_admin_can_manage_physical_copies(): void
    {
        $publisher = Publisher::factory()->create();
        $book = Book::factory()->create(['publisher_id' => $publisher->id]);

        $response = $this->actingAs($this->admin)->post(route('admin.copies.store'), [
            'book_id' => $book->id,
            'barcode' => 'READ-TEST-001',
            'location' => 'Main Stacks, Shelf C-1',
            'condition' => 'good',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('book_copies', ['barcode' => 'READ-TEST-001']);
    }

    public function test_admin_can_promote_and_demote_users_with_safeguard(): void
    {
        // Promote patron to admin
        $promoteResponse = $this->actingAs($this->admin)->patch(route('admin.users.role', $this->patron), [
            'role' => 'admin',
        ]);
        $promoteResponse->assertRedirect();
        $this->assertTrue($this->patron->fresh()->isAdmin());

        // Demote back to user
        $demoteResponse = $this->actingAs($this->admin)->patch(route('admin.users.role', $this->patron), [
            'role' => 'user',
        ]);
        $demoteResponse->assertRedirect();
        $this->assertFalse($this->patron->fresh()->isAdmin());

        // Attempt to demote the ONLY admin left
        $forbiddenDemote = $this->actingAs($this->admin)->patch(route('admin.users.role', $this->admin), [
            'role' => 'user',
        ]);
        $forbiddenDemote->assertSessionHas('error');
        $this->assertTrue($this->admin->fresh()->isAdmin());
    }

    public function test_admin_cannot_delete_user_with_active_loans(): void
    {
        $publisher = Publisher::factory()->create();
        $book = Book::factory()->create(['publisher_id' => $publisher->id]);
        $copy = BookCopy::factory()->create(['book_id' => $book->id, 'status' => BookCopy::STATUS_AVAILABLE]);

        $borrowingService = app(BorrowingService::class);
        $borrowingService->borrow($this->patron, $book);

        $response = $this->actingAs($this->admin)->delete(route('admin.users.destroy', $this->patron));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $this->patron->id]);
    }

    public function test_admin_can_view_audit_logs(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.audit-logs.index'));
        $response->assertStatus(200);
        $response->assertSee('System Audit Trail');
    }
}
