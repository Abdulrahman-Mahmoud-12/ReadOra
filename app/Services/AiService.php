<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiService
{
    protected string $provider;

    protected string $apiKey;

    protected string $baseUrl;

    protected string $model;

    public function __construct()
    {
        $this->provider = (string) config('ai.provider', 'openrouter');
        $providerConfig = config("ai.providers.{$this->provider}", []);

        $this->apiKey = (string) ($providerConfig['api_key'] ?? '');
        $this->baseUrl = rtrim((string) ($providerConfig['base_url'] ?? ''), '/');
        $this->model = (string) ($providerConfig['model'] ?? '');
    }

    /**
     * Ask the ReadOra AI Virtual Librarian.
     *
     * @param  array<array{role: string, content: string, reasoning_details?: mixed}>  $history
     */
    public function askLibrarian(string $userMessage, array $history = []): array
    {
        $systemContext = $this->buildSystemContext();

        $messages = [
            ['role' => 'system', 'content' => $systemContext],
        ];

        // Append past conversation turns (preserve reasoning_details if present)
        foreach (array_slice($history, -6) as $turn) {
            if (isset($turn['role'], $turn['content'])) {
                $msg = [
                    'role' => $turn['role'] === 'user' ? 'user' : 'assistant',
                    'content' => (string) $turn['content'],
                ];
                if (isset($turn['reasoning_details'])) {
                    $msg['reasoning_details'] = $turn['reasoning_details'];
                }
                $messages[] = $msg;
            }
        }

        $messages[] = ['role' => 'user', 'content' => $userMessage];

        return $this->sendChatCompletion($messages);
    }

    /**
     * Generate an AI synopsis, key takeaways, and discussion themes for a specific book.
     */
    public function generateBookInsights(Book $book): string
    {
        $book->loadMissing(['authors', 'categories', 'publisher']);

        $authorNames = $book->authors->pluck('name')->join(', ') ?: 'Unknown Author';
        $categoryNames = $book->categories->pluck('name')->join(', ') ?: 'General Literature';

        $prompt = <<<PROMPT
You are ReadOra AI, a scholarly and insightful literary assistant. Provide a deep, engaging analysis for the following library book:

Title: "{$book->title}"
Subtitle: "{$book->subtitle}"
Author(s): {$authorNames}
Category / Genre: {$categoryNames}
Publisher: {$book->publisher?->name} ({$book->publication_year})
Bibliographic Summary: {$book->description}

Please provide a structured response with:
1. **Core Thesis & Literary Synopsis** (2-3 concise paragraphs)
2. **Key Takeaways & Concepts** (3-4 bullet points)
3. **Ideal Reader & Prerequisites** (Who will benefit most from this work)
4. **Discussion & Study Questions** (2 thoughtful reflection questions)

Format cleanly in GitHub-flavored markdown with bold section headers.
PROMPT;

        $messages = [
            ['role' => 'system', 'content' => 'You are ReadOra AI, an expert academic and literary librarian.'],
            ['role' => 'user', 'content' => $prompt],
        ];

        return $this->sendChatCompletion($messages)['content'];
    }

    /**
     * Send a chat completion request to an OpenAI-compatible provider.
     *
     * @param  array<array{role: string, content: string, reasoning_details?: mixed}>  $messages
     */
    protected function sendChatCompletion(array $messages): array
    {
        if (empty($this->apiKey)) {
            return ['content' => "ReadOra AI is currently in offline mode. Please configure the selected AI provider's API key in the environment settings to enable live AI responses."];
        }

        try {
            $endpoint = str_ends_with($this->baseUrl, '/chat/completions')
                ? $this->baseUrl
                : "{$this->baseUrl}/chat/completions";

            $payload = [
                'model' => $this->model,
                'messages' => $messages,
            ];

            if ($this->provider === 'openrouter') {
                $payload['reasoning'] = ['enabled' => true];
            }

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Content-Type' => 'application/json',
            ])
                ->timeout(35)
                ->post($endpoint, $payload);

            if ($response->successful()) {
                $content = $response->json('choices.0.message.content');
                if (! empty($content)) {
                    $message = ['content' => trim($content)];
                    $reasoningDetails = $response->json('choices.0.message.reasoning_details');

                    if ($reasoningDetails !== null) {
                        $message['reasoning_details'] = $reasoningDetails;
                    }

                    return $message;
                }
            }

            Log::warning("{$this->provider} AI response error: ".$response->body());

            return ['content' => "I apologize, but I encountered an issue communicating with the AI service. (Status: {$response->status()}). Please try again in a moment."];
        } catch (\Throwable $e) {
            Log::error("{$this->provider} AI exception: ".$e->getMessage());

            return ['content' => "I'm having trouble connecting to the literary intelligence service right now. Please verify your connection or try again later."];
        }
    }

    /**
     * Build dynamic library catalog grounding context for the AI.
     */
    protected function buildSystemContext(): string
    {
        $topBooks = Book::query()
            ->with(['authors', 'categories'])
            ->orderByDesc('average_rating')
            ->take(8)
            ->get()
            ->map(fn ($b) => "• \"{$b->title}\" by {$b->authors->pluck('name')->join(', ')} [{$b->categories->pluck('name')->join(', ')}] (★ {$b->average_rating})")
            ->join("\n");

        $categories = Category::pluck('name')->join(', ');

        return <<<SYSTEM
You are ReadOra AI — the intelligent, warm, and highly knowledgeable Virtual Librarian of the ReadOra Library System.
You assist patrons with:
- Recommending books based on themes, genres, moods, or learning goals.
- Explaining complex ideas, historical contexts, and literary analysis.
- Guiding readers on what to read next.

Current Available Catalog Genres: {$categories}
Featured Catalog Works:
{$topBooks}

Always be concise, articulate, inspiring, and formatted with clean markdown (bullet points, bold text).
SYSTEM;
    }
}
