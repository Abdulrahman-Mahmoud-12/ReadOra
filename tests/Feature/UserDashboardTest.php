<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_patron_can_view_dashboard_with_catalog_metrics(): void
    {
        $user = User::factory()->create(['name' => 'Jane Patron']);
        Book::factory()->count(5)->create();
        Category::factory()->count(3)->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
        $response->assertSee('Jane Patron');
        $response->assertSee('Curated Recommendations');
        $response->assertSee('Explore by Category');
        $response->assertViewHas('totalBooks', 5);
        $response->assertViewHas('categoriesCount', 3);
    }

    public function test_authenticated_patron_can_view_favorites_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/favorites');

        $response->assertOk();
        $response->assertSee('Saved Favorites');
        $response->assertSee('Your reading list is empty');
    }

    public function test_authenticated_patron_can_view_borrowings_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/borrowings');

        $response->assertOk();
        $response->assertSee('Borrowing', false);
        $response->assertSee('No active loans or borrowings');
    }

    public function test_authenticated_patron_can_view_and_update_profile(): void
    {
        $user = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@example.com',
        ]);

        $response = $this->actingAs($user)->get('/profile');
        $response->assertOk();
        $response->assertSee('Original Name');
        $response->assertSee('original@example.com');
        $response->assertSee('Digital Card Number');

        $updateResponse = $this->actingAs($user)->patch('/profile', [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);

        $updateResponse->assertRedirect('/profile');
        $updateResponse->assertSessionHas('status', 'profile-updated');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);
    }

    public function test_unauthenticated_user_cannot_access_patron_dashboard_or_profile(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/favorites')->assertRedirect('/login');
        $this->get('/borrowings')->assertRedirect('/login');
        $this->get('/profile')->assertRedirect('/login');
    }
}
