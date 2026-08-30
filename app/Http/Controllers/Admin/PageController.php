<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $category = $request->query('category', 'all');

        $query = Page::orderBy('category')->orderBy('sort_order');

        if ($category && $category !== 'all') {
            $query->where('category', $category);
        }

        $pages = $query->paginate(20);

        return view('admin.pages.index', compact('pages', 'category'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'category' => 'required|string|in:info,author,initiatives,about',
            'sort_order' => 'integer|min:0',
            'content' => 'nullable|string',
            'active' => 'boolean',
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['title']);
        $validated['active'] = $request->has('active');

        // Ensure unique slug per category
        if (Page::where('category', $validated['category'])->where('slug', $validated['slug'])->exists()) {
            return back()->withErrors(['slug' => 'This slug already exists in this category.'])->withInput();
        }

        Page::create($validated);

        return redirect()->route('admin.pages.index')->with('success', 'Page created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'category' => 'required|string|in:info,author,initiatives,about',
            'sort_order' => 'integer|min:0',
            'content' => 'nullable|string',
            'active' => 'boolean',
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['title']);
        $validated['active'] = $request->has('active');

        // Check uniqueness if slug changed
        if ($validated['slug'] !== $page->slug || $validated['category'] !== $page->category) {
            if (
                Page::where('category', $validated['category'])
                    ->where('slug', $validated['slug'])
                    ->where('id', '!=', $page->id)
                    ->exists()
            ) {
                return back()->withErrors(['slug' => 'This slug already exists in this category.'])->withInput();
            }
        }

        $page->update($validated);

        return redirect()->route('admin.pages.index')->with('success', 'Page updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        $page->delete();
        return redirect()->route('admin.pages.index')->with('success', 'Page deleted successfully.');
    }
}
