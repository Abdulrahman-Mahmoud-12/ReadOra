<?php

namespace Tests\Feature;

use App\Models\ApiToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityPhaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_ai_assistant_requires_authentication(): void
    {
        $this->get(route('assistant.index'))->assertRedirect('/login');

        $this->postJson(route('assistant.chat'), ['message' => 'Hello'])
            ->assertUnauthorized();
    }

    public function test_responses_include_security_headers(): void
    {
        $this->get(route('home'))
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
            ->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
    }

    public function test_forwarded_https_requests_generate_https_urls(): void
    {
        $this->withHeaders([
            'X-Forwarded-Proto' => 'https',
            'X-Forwarded-Host' => 'readora.test',
        ])->get(route('home'))
            ->assertSee('https://readora.test');
    }

    public function test_user_can_issue_and_use_a_hashed_api_token(): void
    {
        $user = User::factory()->create();

        $issueResponse = $this->actingAs($user)->postJson(route('api-tokens.store'), [
            'name' => 'Mobile client',
        ]);

        $issueResponse->assertCreated()
            ->assertJsonStructure(['token', 'expires_at', 'message']);

        $plainTextToken = $issueResponse->json('token');
        $this->assertDatabaseHas('api_tokens', [
            'user_id' => $user->id,
            'name' => 'Mobile client',
            'token_hash' => hash('sha256', $plainTextToken),
        ]);
        $this->assertDatabaseMissing('api_tokens', ['token_hash' => $plainTextToken]);

        $this->withToken($plainTextToken)->getJson('/api/me')
            ->assertOk()
            ->assertJsonPath('id', $user->id);
    }

    public function test_invalid_api_token_is_rejected(): void
    {
        $this->withToken('readora_invalid')->getJson('/api/me')
            ->assertUnauthorized();
    }

    public function test_current_api_token_can_be_revoked(): void
    {
        $user = User::factory()->create();
        $plainTextToken = 'readora_test_token';

        ApiToken::create([
            'user_id' => $user->id,
            'name' => 'Test token',
            'token_hash' => hash('sha256', $plainTextToken),
            'expires_at' => now()->addDay(),
        ]);

        $this->withToken($plainTextToken)->deleteJson('/api/tokens/current')
            ->assertOk();

        $this->assertDatabaseCount('api_tokens', 0);
        $this->withToken($plainTextToken)->getJson('/api/me')
            ->assertUnauthorized();
    }
}
