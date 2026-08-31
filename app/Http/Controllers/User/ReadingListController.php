<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\ReadingHistory;
use App\Models\ReadingList;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReadingListController extends Controller
{
    /**
     * Display all reading lists and custom shelves for the authenticated patron.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $user->ensureDefaultShelves();

        $readingLists = $user->readingLists()
            ->withCount('books')
            ->with(['books' => fn ($q) => $q->take(4)])
            ->latest()
            ->get();

        return view('user.reading-lists.index', [
            'readingLists' => $readingLists,
        ]);
    }

    /**
     * Store a newly created custom reading list.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_public' => ['nullable', 'boolean'],
        ]);

        $user = $request->user();

        $list = $user->readingLists()->create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_public' => (bool) ($validated['is_public'] ?? false),
        ]);

        return redirect()->route('reading-lists.show', $list->slug)->with('status', "Reading list '{$list->name}' created successfully!");
    }

    /**
     * Display details of a patron's reading list.
     */
    public function show(Request $request, string $slug): View
    {
        $user = $request->user();
        $readingList = $user->readingLists()
            ->where('slug', $slug)
            ->with(['books' => fn ($q) => $q->with(['publisher', 'authors', 'categories', 'copies'])])
            ->firstOrFail();

        return view('user.reading-lists.show', [
            'readingList' => $readingList,
        ]);
    }

    /**
     * Public view for a shared reading list.
     */
    public function publicShow(string $slug): View
    {
        $readingList = ReadingList::query()
            ->where('slug', $slug)
            ->where('is_public', true)
            ->with(['user', 'books' => fn ($q) => $q->with(['publisher', 'authors', 'categories', 'copies'])])
            ->firstOrFail();

        return view('user.reading-lists.public-show', [
            'readingList' => $readingList,
        ]);
    }

    /**
     * Update an existing reading list.
     */
    public function update(Request $request, ReadingList $readingList): RedirectResponse
    {
        if ($readingList->user_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_public' => ['nullable', 'boolean'],
        ]);

        $readingList->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_public' => (bool) ($validated['is_public'] ?? false),
        ]);

        return back()->with('status', 'Reading list updated successfully.');
    }

    /**
     * Delete a reading list.
     */
    public function destroy(Request $request, ReadingList $readingList): RedirectResponse
    {
        if ($readingList->user_id !== $request->user()->id) {
            abort(403);
        }

        $name = $readingList->name;
        $readingList->delete();

        return redirect()->route('reading-lists.index')->with('status', "Reading list '{$name}' deleted.");
    }

    /**
     * Add a book to a reading list / shelf.
     */
    public function addBook(Request $request, ReadingList $readingList, Book $book): RedirectResponse
    {
        if ($readingList->user_id !== $request->user()->id) {
            abort(403);
        }

        $notes = $request->string('notes')->trim()->toString();

        $readingList->books()->syncWithoutDetaching([
            $book->id => [
                'notes' => $notes ?: null,
                'order' => $readingList->books()->count() + 1,
            ],
        ]);

        // Record activity in reading history
        ReadingHistory::create([
            'user_id' => $request->user()->id,
            'book_id' => $book->id,
            'action' => "Added to shelf '{$readingList->name}'",
            'created_at' => now(),
        ]);

        return back()->with('status', "'{$book->title}' added to '{$readingList->name}'.");
    }

    /**
     * Remove a book from a reading list / shelf.
     */
    public function removeBook(Request $request, ReadingList $readingList, Book $book): RedirectResponse
    {
        if ($readingList->user_id !== $request->user()->id) {
            abort(403);
        }

        $readingList->books()->detach($book->id);

        return back()->with('status', "'{$book->title}' removed from '{$readingList->name}'.");
    }
}
