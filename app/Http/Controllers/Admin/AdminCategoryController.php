<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminCategoryController extends Controller
{
    public function __construct(
        protected AuditLogger $auditLogger
    ) {}

    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();

        $categories = Category::query()
            ->withCount('books')
            ->when($search !== '', fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.categories.index', [
            'categories' => $categories,
            'search' => $search,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $slug = Str::slug($validated['name']);
        if (Category::where('slug', $slug)->exists()) {
            return back()->with('error', "A category with name \"{$validated['name']}\" already exists.");
        }

        $category = Category::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
        ]);

        $this->auditLogger->log('category.created', $category, null, $category->toArray());

        return back()->with('status', "Category \"{$category->name}\" added successfully.");
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $oldValues = $category->toArray();
        $category->update($validated);

        $this->auditLogger->log('category.updated', $category, $oldValues, $category->toArray());

        return back()->with('status', "Category \"{$category->name}\" updated.");
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->books()->count() > 0) {
            return back()->with('error', "Cannot delete category \"{$category->name}\" because {$category->books()->count()} books belong to it.");
        }

        $old = $category->toArray();
        $category->delete();

        $this->auditLogger->log('category.deleted', null, $old, null);

        return back()->with('status', 'Category removed successfully.');
    }
}
