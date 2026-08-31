<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Borrowing;
use App\Models\Publisher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminReportsTest extends TestCase
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

    public function test_non_admin_cannot_access_reports_or_exports(): void
    {
        $response = $this->actingAs($this->patron)->get(route('admin.reports.index'));
        $response->assertStatus(403);

        $responseExport = $this->actingAs($this->patron)->get(route('admin.reports.export.books'));
        $responseExport->assertStatus(403);
    }

    public function test_admin_can_view_reports_dashboard(): void
    {
        $publisher = Publisher::factory()->create();
        $book = Book::factory()->create(['publisher_id' => $publisher->id]);
        $copy = BookCopy::factory()->create(['book_id' => $book->id]);

        Borrowing::create([
            'user_id' => $this->patron->id,
            'book_copy_id' => $copy->id,
            'borrowed_at' => now()->subDays(5),
            'due_at' => now()->addDays(9),
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.reports.index'));
        $response->assertStatus(200);
        $response->assertSee('Circulation Reports');
        $response->assertSee($book->title);
        $response->assertSee($this->patron->name);
    }

    public function test_admin_can_export_books_csv(): void
    {
        $publisher = Publisher::factory()->create(['name' => 'MIT Press']);
        Book::factory()->create([
            'publisher_id' => $publisher->id,
            'title' => 'Structure and Interpretation of Computer Programs',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.reports.export.books'));
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');

        $content = $response->streamedContent();
        $this->assertStringContainsString('Structure and Interpretation of Computer Programs', $content);
        $this->assertStringContainsString('MIT Press', $content);
    }

    public function test_admin_can_export_circulations_and_patrons_csv(): void
    {
        $publisher = Publisher::factory()->create();
        $book = Book::factory()->create(['publisher_id' => $publisher->id, 'title' => 'Domain Driven Design']);
        $copy = BookCopy::factory()->create(['book_id' => $book->id, 'barcode' => 'TEST-BC-9999']);

        Borrowing::create([
            'user_id' => $this->patron->id,
            'book_copy_id' => $copy->id,
            'borrowed_at' => now()->subDays(2),
            'due_at' => now()->addDays(12),
            'status' => 'active',
        ]);

        // Test circulations export
        $responseCirc = $this->actingAs($this->admin)->get(route('admin.reports.export.circulations'));
        $responseCirc->assertStatus(200);
        $circContent = $responseCirc->streamedContent();
        $this->assertStringContainsString('Domain Driven Design', $circContent);
        $this->assertStringContainsString('TEST-BC-9999', $circContent);

        // Test patrons export
        $responsePatrons = $this->actingAs($this->admin)->get(route('admin.reports.export.patrons'));
        $responsePatrons->assertStatus(200);
        $patronsContent = $responsePatrons->streamedContent();
        $this->assertStringContainsString($this->patron->email, $patronsContent);
    }
}
