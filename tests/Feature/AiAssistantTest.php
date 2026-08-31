<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Publisher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AiAssistantTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_ai_assistant_page(): void
    {
        $response = $this->get(route('assistant.index'));
        $response->assertStatus(200);
        $response->assertSee('Ask ReadOra AI');
    }

    public function test_ai_chat_returns_successful_completion(): void
    {
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

        $response = $this->postJson(route('assistant.chat'), [
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

        $response = $this->postJson(route('assistant.chat'), [
            'message' => 'Recommend a book.',
        ]);

        $response->assertOk()->assertJsonPath('message', 'NVIDIA response');
        Http::assertSent(function ($request): bool {
            return $request->url() === 'https://integrate.api.nvidia.com/v1/chat/completions'
                && $request['model'] === 'test-nvidia-model'
                && ! array_key_exists('reasoning', $request->data());
        });
    }

    public function test_ai_book_insights_endpoint_returns_analysis(): void
    {
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

        $response = $this->getJson(route('books.ai-insights', $book));
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'book_id' => $book->id,
        ]);
        $this->assertStringContainsString('Core Thesis', $response->json('insights'));
    }

    public function test_graceful_fallback_when_ai_api_fails(): void
    {
        Http::fake([
            'https://openrouter.ai/api/v1/chat/completions' => Http::response(['error' => 'Rate limit exceeded'], 429),
        ]);

        $response = $this->postJson(route('assistant.chat'), [
            'message' => 'Hello AI',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertStringContainsString('issue communicating with the AI service', $response->json('message'));
    }
}
