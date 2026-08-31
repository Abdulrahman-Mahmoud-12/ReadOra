<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publisher;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminPublisherController extends Controller
{
    public function __construct(
        protected AuditLogger $auditLogger
    ) {}

    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();

        $publishers = Publisher::query()
            ->withCount('books')
            ->when($search !== '', fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.publishers.index', [
            'publishers' => $publishers,
            'search' => $search,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'website' => ['nullable', 'string', 'max:255'],
        ]);

        $slug = Str::slug($validated['name']);
        if (Publisher::where('slug', $slug)->exists()) {
            return back()->with('error', "A publisher with name \"{$validated['name']}\" already exists.");
        }

        $publisher = Publisher::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'website' => $validated['website'] ?? null,
        ]);

        $this->auditLogger->log('publisher.created', $publisher, null, $publisher->toArray());

        return back()->with('status', "Publisher \"{$publisher->name}\" added successfully.");
    }

    public function update(Request $request, Publisher $publisher): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'website' => ['nullable', 'string', 'max:255'],
        ]);

        $oldValues = $publisher->toArray();
        $publisher->update($validated);

        $this->auditLogger->log('publisher.updated', $publisher, $oldValues, $publisher->toArray());

        return back()->with('status', "Publisher \"{$publisher->name}\" updated.");
    }

    public function destroy(Publisher $publisher): RedirectResponse
    {
        if ($publisher->books()->count() > 0) {
            return back()->with('error', "Cannot delete publisher \"{$publisher->name}\" because {$publisher->books()->count()} books are published by them.");
        }

        $old = $publisher->toArray();
        $publisher->delete();

        $this->auditLogger->log('publisher.deleted', null, $old, null);

        return back()->with('status', 'Publisher removed successfully.');
    }
}
