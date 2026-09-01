<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Publisher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AiAssistantTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_ai_assistant_page(): void
    {
        $response = $this->actingAs(User::factory()->create())->get(route('assistant.index'));
        $response->assertStatus(200);
        $response->assertSee('Ask ReadOra AI');
    }

    public function test_admin_can_view_ai_assistant_page(): void
    {
        $response = $this->actingAs(User::factory()->admin()->create())->get(route('assistant.index'));

        $response->assertOk()->assertSee('Ask ReadOra AI');
    }

    public function test_only_admin_ai_context_contains_administrative_metrics(): void
    {
        config()->set('ai.provider', 'openrouter');

        Http::fake([
            'https://openrouter.ai/api/v1/chat/completions' => Http::response([
                'choices' => [['message' => ['content' => 'Acknowledged.']]],
            ], 200),
        ]);

        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $this->actingAs($admin)->postJson(route('assistant.chat'), ['message' => 'Show me library metrics.'])
            ->assertOk();

        $this->actingAs($user)->postJson(route('assistant.chat'), ['message' => 'Show me library metrics.'])
            ->assertOk();

        $requests = Http::recorded();
        $adminContext = $requests[0][0]['messages'][0]['content'];
        $userContext = $requests[1][0]['messages'][0]['content'];

        $this->assertStringContainsString('Administrator-only operational context', $adminContext);
        $this->assertStringContainsString('Registered users: 2', $adminContext);
        $this->assertStringNotContainsString('Administrator-only operational context', $userContext);
        $this->assertStringNotContainsString('Registered users:', $userContext);
    }

    public function test_admin_receives_authorized_answer_for_user_count(): void
    {
        Http::preventStrayRequests();

        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->postJson(route('assistant.chat'), [
            'message' => 'How many users are registered?',
        ]);

        $response->assertOk()
            ->assertJsonPath('message', 'There are **'.User::count().' registered users** in the library system.');
    }

    public function test_normal_user_does_not_receive_admin_count_answer(): void
    {
        config()->set('ai.provider', 'openrouter');

        Http::fake([
            'https://openrouter.ai/api/v1/chat/completions' => Http::response([
                'choices' => [['message' => ['content' => 'Access is limited to public catalog information.']]],
            ], 200),
        ]);

        $response = $this->actingAs(User::factory()->create())->postJson(route('assistant.chat'), [
            'message' => 'How many users are registered?',
        ]);

        $response->assertOk()
            ->assertJsonPath('message', 'Access is limited to public catalog information.');
    }

    public function test_ai_chat_returns_successful_completion(): void
    {
        config()->set('ai.provider', 'openrouter');
        $user = User::factory()->create();

        Http::fake([
            'https://openrouter.ai/api/v1/chat/completions' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => 'Based on our library catalog, I highly recommend Clean Code by Robert C. Martin.',
                            'reasoning_details' => [
                                ['type' => 'reasoning.text', 'text' => 'The recommendation matches the requested topic.'],
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $response = $this->actingAs($user)->postJson(route('assistant.chat'), [
            'message' => 'Can you recommend a good programming book?',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Based on our library catalog, I highly recommend Clean Code by Robert C. Martin.',
            'reasoning_details' => [
                ['type' => 'reasoning.text', 'text' => 'The recommendation matches the requested topic.'],
            ],
        ]);

        Http::assertSent(function ($request): bool {
            return $request->url() === config('ai.providers.openrouter.base_url')
                && $request['model'] === config('ai.providers.openrouter.model')
                && $request['reasoning']['enabled'] === true;
        });
    }

    public function test_ai_chat_uses_the_selected_nvidia_provider(): void
    {
        config()->set('ai.provider', 'nvidia');
        config()->set('ai.providers.nvidia.api_key', 'test-nvidia-key');
        config()->set('ai.providers.nvidia.base_url', 'https://integrate.api.nvidia.com/v1/chat/completions');
        config()->set('ai.providers.nvidia.model', 'test-nvidia-model');

        Http::fake([
            'https://integrate.api.nvidia.com/v1/chat/completions' => Http::response([
                'choices' => [
                    ['message' => ['content' => 'NVIDIA response']],
                ],
            ], 200),
        ]);

        $response = $this->actingAs(User::factory()->create())->postJson(route('assistant.chat'), [
            'message' => 'Recommend a book.',
        ]);

        $response->assertOk()->assertJsonPath('message', 'NVIDIA response');
        Http::assertSent(function ($request): bool {
            return $request->url() === 'https://integrate.api.nvidia.com/v1/chat/completions'
                && $request['model'] === 'test-nvidia-model'
                && ! array_key_exists('reasoning', $request->data());
        });
    }

    public function test_ai_chat_uses_local_ollama_without_an_api_key(): void
    {
        config()->set('ai.provider', 'ollama');
        config()->set('ai.providers.ollama.api_key', null);
        config()->set('ai.providers.ollama.base_url', 'http://127.0.0.1:11434/v1/chat/completions');
        config()->set('ai.providers.ollama.model', 'llama3.2');

        Http::fake([
            'http://127.0.0.1:11434/v1/chat/completions' => Http::response([
                'choices' => [
                    ['message' => ['content' => 'Ollama response']],
                ],
            ], 200),
        ]);

        $response = $this->actingAs(User::factory()->create())->postJson(route('assistant.chat'), [
            'message' => 'Recommend a book.',
        ]);

        $response->assertOk()->assertJsonPath('message', 'Ollama response');
        Http::assertSent(function ($request): bool {
            return $request->url() === 'http://127.0.0.1:11434/v1/chat/completions'
                && $request['model'] === 'llama3.2'
                && ! $request->hasHeader('Authorization');
        });
    }

    public function test_ai_book_insights_endpoint_returns_analysis(): void
    {
        config()->set('ai.provider', 'openrouter');
        $user = User::factory()->create();
        $publisher = Publisher::factory()->create();
        $book = Book::factory()->create([
            'publisher_id' => $publisher->id,
            'title' => 'Design Patterns',
            'description' => 'Elements of reusable object-oriented software.',
        ]);

        Http::fake([
            'https://openrouter.ai/api/v1/chat/completions' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => "**Core Thesis**\nDesign patterns provide reusable templates for common architectural problems.",
                        ],
                    ],
                ],
            ], 200),
        ]);

        $response = $this->actingAs($user)->getJson(route('books.ai-insights', $book));
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'book_id' => $book->id,
        ]);
        $this->assertStringContainsString('Core Thesis', $response->json('insights'));
    }

    public function test_graceful_fallback_when_ai_api_fails(): void
    {
        config()->set('ai.provider', 'openrouter');
        $user = User::factory()->create();

        Http::fake([
            'https://openrouter.ai/api/v1/chat/completions' => Http::response(['error' => 'Rate limit exceeded'], 429),
        ]);

        $response = $this->actingAs($user)->postJson(route('assistant.chat'), [
            'message' => 'Hello AI',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertStringContainsString('issue communicating with the AI service', $response->json('message'));
    }
}
