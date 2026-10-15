<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /**
     * Pre-defined blog categories for recommendations
     */
    public const CATEGORIES = [
        'Academic Publishing',
        'Research Spotlight',
        'Open Access Insights',
        'Scientific Innovation',
        'Global Initiatives',
        'Publication Ethics',
        'Peer Review Trends',
        'Conferences & Events',
    ];

    /**
     * Display a listing of blogs.
     */
    public function index(Request $request)
    {
        $query = Blog::with('author')->latest('created_at');

        // Search query
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhere('author_name', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($status = $request->input('status')) {
            if ($status === 'published') {
                $query->where('is_published', true);
            } elseif ($status === 'draft') {
                $query->where('is_published', false);
            }
        }

        // Category filter
        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        $blogs = $query->paginate(12)->withQueryString();

        // Calculate summary metrics
        $totalCount = Blog::count();
        $publishedCount = Blog::where('is_published', true)->count();
        $draftCount = Blog::where('is_published', false)->count();
        $categories = Blog::whereNotNull('category')->distinct()->pluck('category');

        return view('admin.blogs.index', compact(
            'blogs',
            'totalCount',
            'publishedCount',
            'draftCount',
            'categories'
        ));
    }

    /**
     * Show the form for creating a new blog.
     */
    public function create()
    {
        $authors = User::whereIn('role', ['admin', 'editor', 'author'])->orderBy('name')->get();
        $categories = self::CATEGORIES;

        return view('admin.blogs.create', compact('authors', 'categories'));
    }

    /**
     * Store a newly created blog in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blogs,slug',
            'category' => 'nullable|string|max:100',
            'summary' => 'nullable|string|max:1000',
            'tags' => 'nullable|string|max:255',
            'content' => 'required|string',
            'author_id' => 'nullable|exists:users,id',
            'author_name' => 'nullable|string|max:150',
            'featured_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg,gif|max:5120',
            'featured_image_url' => 'nullable|url|max:1000',
            'is_published' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ]);

        // Auto-generate unique slug
        $baseSlug = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['title']);
        $slug = $baseSlug;
        $counter = 1;
        while (Blog::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }
        $validated['slug'] = $slug;

        // Author assignment
        if (empty($validated['author_id'])) {
            $validated['author_id'] = auth()->id() ?? User::where('role', 'admin')->value('id');
        }

        // Image Handling: priority to uploaded file, fallback to image URL
        if ($request->hasFile('featured_image_file')) {
            $validated['featured_image'] = $request->file('featured_image_file')->store('blogs', 'public');
        } elseif (!empty($request->input('featured_image_url'))) {
            $validated['featured_image'] = $request->input('featured_image_url');
        }

        // Publication Status and Date
        $validated['is_published'] = $request->boolean('is_published');
        if ($validated['is_published'] && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $blog = Blog::create($validated);

        return redirect()->route('admin.blogs.index')
            ->with('success', "Blog post \"{$blog->title}\" created successfully.");
    }

    /**
     * Show the form for editing the specified blog.
     */
    public function edit(Blog $blog)
    {
        $authors = User::whereIn('role', ['admin', 'editor', 'author'])->orderBy('name')->get();
        $categories = self::CATEGORIES;

        return view('admin.blogs.edit', compact('blog', 'authors', 'categories'));
    }

    /**
     * Update the specified blog in storage.
     */
    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blogs,slug,' . $blog->id,
            'category' => 'nullable|string|max:100',
            'summary' => 'nullable|string|max:1000',
            'tags' => 'nullable|string|max:255',
            'content' => 'required|string',
            'author_id' => 'nullable|exists:users,id',
            'author_name' => 'nullable|string|max:150',
            'featured_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg,gif|max:5120',
            'featured_image_url' => 'nullable|url|max:1000',
            'remove_featured_image' => 'nullable|boolean',
            'is_published' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ]);

        // Auto-generate or sanitize slug
        $baseSlug = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['title']);
        $slug = $baseSlug;
        $counter = 1;
        while (Blog::where('slug', $slug)->where('id', '!=', $blog->id)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }
        $validated['slug'] = $slug;

        // Image Handling
        if ($request->boolean('remove_featured_image')) {
            if ($blog->featured_image && !str_starts_with($blog->featured_image, 'http')) {
                Storage::disk('public')->delete($blog->featured_image);
            }
            $validated['featured_image'] = null;
        } elseif ($request->hasFile('featured_image_file')) {
            if ($blog->featured_image && !str_starts_with($blog->featured_image, 'http')) {
                Storage::disk('public')->delete($blog->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image_file')->store('blogs', 'public');
        } elseif (!empty($request->input('featured_image_url'))) {
            $validated['featured_image'] = $request->input('featured_image_url');
        }

        // Status update
        $validated['is_published'] = $request->boolean('is_published');
        if ($validated['is_published'] && empty($validated['published_at'])) {
            $validated['published_at'] = $blog->published_at ?? now();
        }

        $blog->update($validated);

        return redirect()->route('admin.blogs.index')
            ->with('success', "Blog post \"{$blog->title}\" updated successfully.");
    }

    /**
     * Remove the specified blog from storage.
     */
    public function destroy(Blog $blog)
    {
        $title = $blog->title;

        if ($blog->featured_image && !str_starts_with($blog->featured_image, 'http')) {
            Storage::disk('public')->delete($blog->featured_image);
        }

        $blog->delete();

        return redirect()->route('admin.blogs.index')
            ->with('success', "Blog post \"{$title}\" has been deleted.");
    }

    /**
     * Quick toggle published status
     */
    public function togglePublish(Blog $blog)
    {
        $blog->is_published = !$blog->is_published;
        if ($blog->is_published && empty($blog->published_at)) {
            $blog->published_at = now();
        }
        $blog->save();

        $statusMsg = $blog->is_published ? 'published' : 'moved to draft';
        return back()->with('success', "Blog \"{$blog->title}\" is now {$statusMsg}.");
    }
}
