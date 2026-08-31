<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Publisher;
use App\Models\ReadingList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReadingListTest extends TestCase
{
    use RefreshDatabase;

    protected User $patron;

    protected User $otherPatron;

    protected Book $book;

    protected function setUp(): void
    {
        parent::setUp();

        $this->patron = User::factory()->create(['role' => 'user']);
        $this->otherPatron = User::factory()->create(['role' => 'user']);

        $publisher = Publisher::factory()->create();
        $this->book = Book::factory()->create(['publisher_id' => $publisher->id, 'title' => 'Refactoring']);
    }

    public function test_patron_has_default_shelves_initialized(): void
    {
        $response = $this->actingAs($this->patron)->get(route('reading-lists.index'));
        $response->assertStatus(200);
        $response->assertSee('Want to Read');
        $response->assertSee('Currently Reading');
        $response->assertSee('Read');

        $this->assertEquals(3, $this->patron->readingLists()->count());
    }

    public function test_patron_can_create_custom_reading_list(): void
    {
        $response = $this->actingAs($this->patron)->post(route('reading-lists.store'), [
            'name' => 'System Design Mastery',
            'description' => 'Architecture books to read in Q3.',
            'is_public' => 1,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reading_lists', [
            'user_id' => $this->patron->id,
            'name' => 'System Design Mastery',
            'is_public' => 1,
        ]);
    }

    public function test_patron_can_add_and_remove_book_from_shelf(): void
    {
        $this->patron->ensureDefaultShelves();
        $shelf = $this->patron->readingLists()->where('name', 'Want to Read')->first();

        // Add book
        $responseAdd = $this->actingAs($this->patron)->post(route('reading-lists.books.add', [$shelf, $this->book]), [
            'notes' => 'Recommended by Martin Fowler',
        ]);
        $responseAdd->assertRedirect();

        $this->assertTrue($shelf->fresh()->hasBook($this->book));
        $this->assertDatabaseHas('book_reading_list', [
            'reading_list_id' => $shelf->id,
            'book_id' => $this->book->id,
            'notes' => 'Recommended by Martin Fowler',
        ]);

        // Remove book
        $responseRemove = $this->actingAs($this->patron)->delete(route('reading-lists.books.remove', [$shelf, $this->book]));
        $responseRemove->assertRedirect();
        $this->assertFalse($shelf->fresh()->hasBook($this->book));
    }

    public function test_public_reading_list_is_accessible_to_guests(): void
    {
        $publicList = ReadingList::create([
            'user_id' => $this->patron->id,
            'name' => 'Best Clean Code Books',
            'description' => 'Top books for engineers.',
            'is_public' => true,
        ]);
        $publicList->books()->attach($this->book);

        $response = $this->get(route('reading-lists.public', $publicList->slug));
        $response->assertStatus(200);
        $response->assertSee('Best Clean Code Books');
        $response->assertSee('Refactoring');
    }

    public function test_patron_cannot_modify_another_patrons_reading_list(): void
    {
        $list = ReadingList::create([
            'user_id' => $this->otherPatron->id,
            'name' => 'Secret List',
            'is_public' => false,
        ]);

        $response = $this->actingAs($this->patron)->delete(route('reading-lists.destroy', $list));
        $response->assertStatus(403);
    }
}
