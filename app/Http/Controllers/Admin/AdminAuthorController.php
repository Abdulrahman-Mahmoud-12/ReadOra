<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminAuthorController extends Controller
{
    public function __construct(
        protected AuditLogger $auditLogger
    ) {}

    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();

        $authors = Author::query()
            ->withCount('books')
            ->when($search !== '', fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.authors.index', [
            'authors' => $authors,
            'search' => $search,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'biography' => ['nullable', 'string'],
            'birth_year' => ['nullable', 'integer', 'between:-3000,2100'],
            'death_year' => ['nullable', 'integer', 'between:-3000,2100'],
        ]);

        $slug = Str::slug($validated['name']);
        $count = 1;
        while (Author::where('slug', $slug)->exists()) {
            $slug = Str::slug($validated['name'])."-{$count}";
            $count++;
        }

        $author = Author::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'biography' => $validated['biography'] ?? null,
            'birth_year' => $validated['birth_year'] ?? null,
            'death_year' => $validated['death_year'] ?? null,
        ]);

        $this->auditLogger->log('author.created', $author, null, $author->toArray());

        return back()->with('status', "Author \"{$author->name}\" added successfully.");
    }

    public function update(Request $request, Author $author): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'biography' => ['nullable', 'string'],
            'birth_year' => ['nullable', 'integer', 'between:-3000,2100'],
            'death_year' => ['nullable', 'integer', 'between:-3000,2100'],
        ]);

        $oldValues = $author->toArray();
        $author->update($validated);

        $this->auditLogger->log('author.updated', $author, $oldValues, $author->toArray());

        return back()->with('status', "Author \"{$author->name}\" updated.");
    }

    public function destroy(Author $author): RedirectResponse
    {
        if ($author->books()->count() > 0) {
            return back()->with('error', "Cannot delete \"{$author->name}\" because they are linked to {$author->books()->count()} books in the catalog.");
        }

        $old = $author->toArray();
        $author->delete();

        $this->auditLogger->log('author.deleted', null, $old, null);

        return back()->with('status', 'Author removed successfully.');
    }
}
