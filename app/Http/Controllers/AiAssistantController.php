<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\AiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AiAssistantController extends Controller
{
    public function __construct(
        protected AiService $aiService
    ) {}

    /**
     * Display the ReadOra AI Librarian Chat interface.
     */
    public function index(): View
    {
        $featuredBooks = Book::query()
            ->with(['authors', 'categories'])
            ->orderByDesc('average_rating')
            ->take(4)
            ->get();

        return view('assistant.index', [
            'featuredBooks' => $featuredBooks,
        ]);
    }

    /**
     * Handle incoming chat message to the AI Librarian.
     */
    public function chat(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
            'history' => ['nullable', 'array'],
            'history.*.role' => ['required_with:history', 'string', 'in:user,assistant'],
            'history.*.content' => ['required_with:history', 'string'],
            'history.*.reasoning_details' => ['nullable', 'array'],
        ]);

        $userMessage = $validated['message'];
        $history = $validated['history'] ?? [];

        $aiResponse = $this->aiService->askLibrarian($userMessage, $history);

        return response()->json([
            'success' => true,
            'message' => $aiResponse['content'],
            'reasoning_details' => $aiResponse['reasoning_details'] ?? null,
        ]);
    }

    /**
     * Generate AI insights and key takeaways for a specific book.
     */
    public function bookInsights(Book $book): JsonResponse
    {
        $insights = $this->aiService->generateBookInsights($book);

        return response()->json([
            'success' => true,
            'book_id' => $book->id,
            'book_title' => $book->title,
            'insights' => $insights,
        ]);
    }
}
