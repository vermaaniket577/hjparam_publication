<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of published blogs.
     */
    public function index(Request $request)
    {
        $query = Blog::published()->with('author')->latest('published_at');

        $searchQuery = $request->input('q') ?? $request->input('search');
        if (!empty($searchQuery)) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('title', 'like', "%{$searchQuery}%")
                    ->orWhere('summary', 'like', "%{$searchQuery}%")
                    ->orWhere('content', 'like', "%{$searchQuery}%")
                    ->orWhere('category', 'like', "%{$searchQuery}%")
                    ->orWhere('tags', 'like', "%{$searchQuery}%")
                    ->orWhere('author_name', 'like', "%{$searchQuery}%");
            });
        }

        $currentCategory = $request->input('category');
        if (!empty($currentCategory)) {
            $query->where('category', $currentCategory);
        }

        $currentTag = $request->input('tag');
        if (!empty($currentTag)) {
            $query->where(function ($q) use ($currentTag) {
                $q->where('tags', 'like', "%{$currentTag}%");
            });
        }

        // Sorting
        $sort = $request->input('sort', 'latest');
        if ($sort === 'popular') {
            $query->orderBy('views_count', 'desc');
        } elseif ($sort === 'oldest') {
            $query->oldest('published_at');
        }

        // Get categories with post counts
        $categoriesWithCount = Blog::published()
            ->whereNotNull('category')
            ->selectRaw('category, count(*) as count')
            ->groupBy('category')
            ->orderBy('category')
            ->get();

        // Recent posts for sidebar
        $recentPosts = Blog::published()
            ->latest('published_at')
            ->take(5)
            ->get();

        // Hero featured post (only on first page when not filtering)
        $featuredBlog = null;
        if (!$searchQuery && !$currentCategory && !$currentTag && $request->input('page', 1) == 1) {
            $featuredBlog = Blog::published()
                ->whereNotNull('featured_image')
                ->latest('published_at')
                ->first();

            if (!$featuredBlog) {
                $featuredBlog = Blog::published()->latest('published_at')->first();
            }

            if ($featuredBlog) {
                $query->where('id', '!=', $featuredBlog->id);
            }
        }

        $blogs = $query->paginate(9)->withQueryString();

        return view('pages.blogs.index', compact(
            'blogs',
            'featuredBlog',
            'categoriesWithCount',
            'recentPosts',
            'currentCategory',
            'currentTag',
            'searchQuery'
        ));
    }

    /**
     * Display a specific published blog post.
     */
    public function show($slug)
    {
        $blog = Blog::with('author')->where('slug', $slug)->firstOrFail();

        // Enforce published status unless authorized admin or editor is previewing
        if (!$blog->is_published || ($blog->published_at && $blog->published_at->isFuture())) {
            $canPreview = auth()->check() && (
                auth()->user()->isAdmin() ||
                auth()->user()->isEditor() ||
                auth()->id() === $blog->author_id
            );

            if (!$canPreview) {
                abort(404, 'Blog post not found or unpublished.');
            }
        }

        // Increment view count (safely)
        try {
            $blog->increment('views_count');
        } catch (\Throwable $e) {}

        // Related articles in same category or recent
        $relatedBlogs = Blog::published()
            ->where('id', '!=', $blog->id)
            ->when($blog->category, function ($q) use ($blog) {
                $q->where('category', $blog->category);
            })
            ->latest('published_at')
            ->take(3)
            ->get();

        // If not enough related blogs from same category, supplement with recent blogs
        if ($relatedBlogs->count() < 3) {
            $additional = Blog::published()
                ->where('id', '!=', $blog->id)
                ->whereNotIn('id', $relatedBlogs->pluck('id'))
                ->latest('published_at')
                ->take(3 - $relatedBlogs->count())
                ->get();
            $relatedBlogs = $relatedBlogs->merge($additional);
        }

        return view('pages.blogs.show', compact('blog', 'relatedBlogs'));
    }
}
