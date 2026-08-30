<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::with(['journal', 'authors'])
            ->latest()
            ->paginate(10);

        return view('admin.articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $journals = \App\Models\Journal::orderBy('title')->get();
        $issues = \App\Models\Issue::with('volume')->get(); // Issues usually need their volume context
        return view('admin.articles.create', compact('journals', 'issues'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'journal_id' => 'required|exists:journals,id',
            'issue_id' => 'nullable|exists:issues,id',
            'title' => 'required|string|max:500',
            'slug' => 'required|string|unique:articles,slug',
            'abstract' => 'required|string',
            'keywords' => 'nullable|string',
            'doi' => 'nullable|string',
            'pdf_path' => 'nullable|file|mimes:pdf|max:20480', // Support up to 20MB
            'status' => 'required|string',
            'published_at' => 'nullable|date',
            'authors' => 'required|array|min:1',
            'authors.*.name' => 'required|string|max:255',
            'authors.*.email' => 'nullable|email|max:255',
            'authors.*.affiliation' => 'nullable|string|max:500',
            'authors.*.is_corresponding' => 'nullable|boolean',
        ]);

        $article = new Article($request->except('authors', 'pdf_path'));

        if ($request->hasFile('pdf_path')) {
            $path = $request->file('pdf_path')->store('articles/pdf', 'public');
            $article->pdf_path = $path;
        }

        $article->submitted_at = now();
        if ($request->status === 'published' && !$request->published_at) {
            $article->published_at = now();
        }

        $article->save();

        // Create Authors
        foreach ($request->authors as $authorData) {
            $article->authors()->create($authorData);
        }

        return redirect()->route('admin.articles.index')
            ->with('success', 'Manuscript and author records successfully registered.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $article = Article::with('authors')->findOrFail($id);
        $journals = \App\Models\Journal::orderBy('title')->get();
        $issues = \App\Models\Issue::with('volume')->get();

        return view('admin.articles.edit', compact('article', 'journals', 'issues'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $article = Article::findOrFail($id);

        $validated = $request->validate([
            'journal_id' => 'required|exists:journals,id',
            'issue_id' => 'nullable|exists:issues,id',
            'title' => 'required|string|max:500',
            'slug' => 'required|string|unique:articles,slug,' . $article->id,
            'abstract' => 'required|string',
            'keywords' => 'nullable|string',
            'doi' => 'nullable|string',
            'pdf_path' => 'nullable|file|mimes:pdf|max:20480',
            'status' => 'required|string',
            'published_at' => 'nullable|date',
            'authors' => 'required|array|min:1',
            'authors.*.name' => 'required|string|max:255',
            'authors.*.email' => 'nullable|email|max:255',
            'authors.*.affiliation' => 'nullable|string|max:500',
            'authors.*.is_corresponding' => 'nullable|boolean',
        ]);

        $article->fill($request->except('authors', 'pdf_path'));

        if ($request->hasFile('pdf_path')) {
            // Option: delete old PDF
            if ($article->pdf_path && \Storage::disk('public')->exists($article->pdf_path)) {
                \Storage::disk('public')->delete($article->pdf_path);
            }
            $path = $request->file('pdf_path')->store('articles/pdf', 'public');
            $article->pdf_path = $path;
        }

        if ($request->status === 'published' && !$article->published_at) {
            $article->published_at = now();
        }

        $article->save();

        // Update Authors (Wipe and recreate for simplicity in this admin context)
        $article->authors()->delete();
        foreach ($request->authors as $authorData) {
            $article->authors()->create($authorData);
        }

        return redirect()->route('admin.articles.index')
            ->with('success', 'Article registry updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $article = Article::findOrFail($id);

        if ($article->pdf_path && \Storage::disk('public')->exists($article->pdf_path)) {
            \Storage::disk('public')->delete($article->pdf_path);
        }

        $article->delete();

        return redirect()->route('admin.articles.index')
            ->with('success', 'Article and associated assets removed.');
    }
}
