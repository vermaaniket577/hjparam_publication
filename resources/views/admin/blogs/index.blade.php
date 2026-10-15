@extends('layouts.admin')
@section('title', 'Manage Blogs')
@section('breadcrumb', 'Manage Blogs')

@section('content')
    <!-- Header with Stats -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2.5">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 2v4a2 2 0 002 2h4"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h3m-3 4h6m-6 4h6"></path>
                    </svg>
                    <span>Blog Posts & Articles</span>
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Manage, write, and publish articles displayed on the public Blog page.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('blogs.index') }}" target="_blank"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 hover:bg-gray-50 text-gray-700 dark:text-gray-300 text-sm font-semibold rounded-xl shadow-xs transition">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                    <span>View Blog Page</span>
                </a>
                <a href="{{ route('admin.blogs.create') }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-md transition hover:scale-[1.02]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Create Blog Post</span>
                </a>
            </div>
        </div>

        <!-- Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Articles</p>
                    <p class="text-2xl font-extrabold text-gray-900 dark:text-white mt-1">{{ $totalCount }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/30 rounded-xl flex items-center justify-center text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Published</p>
                    <p class="text-2xl font-extrabold text-emerald-600 mt-1">{{ $publishedCount }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-amber-500 uppercase tracking-wider">Drafts</p>
                    <p class="text-2xl font-extrabold text-amber-600 mt-1">{{ $draftCount }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-50 dark:bg-amber-900/30 rounded-xl flex items-center justify-center text-amber-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-xs mb-6">
            <form method="GET" action="{{ route('admin.blogs.index') }}" class="flex flex-col md:flex-row gap-3 items-center justify-between">
                <div class="relative w-full md:w-96">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search title, summary, author..."
                        class="w-full pl-9 pr-4 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div class="flex items-center gap-2.5 w-full md:w-auto">
                    <select name="status" onchange="this.form.submit()"
                        class="text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-3 py-2 text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="">All Statuses</option>
                        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published Only</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Drafts Only</option>
                    </select>

                    @if($categories->isNotEmpty())
                        <select name="category" onchange="this.form.submit()"
                            class="text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-3 py-2 text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    @endif

                    @if(request('search') || request('status') || request('category'))
                        <a href="{{ route('admin.blogs.index') }}"
                            class="text-xs font-semibold text-rose-600 hover:text-rose-800 px-3 py-2 border border-rose-200 rounded-xl hover:bg-rose-50 transition">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Blog Posts Table -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Article</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Author</th>
                        <th class="px-6 py-3.5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Views</th>
                        <th class="px-6 py-3.5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3.5 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($blogs as $blog)
                        <tr class="hover:bg-gray-50/70 dark:hover:bg-gray-700/40 transition">
                            <!-- Article Info -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-14 h-14 rounded-xl overflow-hidden bg-slate-100 flex-shrink-0 border border-gray-200">
                                        @if($blog->image_url)
                                            <img src="{{ $blog->image_url }}" alt="{{ $blog->title }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xs">
                                                BLOG
                                            </div>
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <a href="{{ route('admin.blogs.edit', $blog->id) }}"
                                            class="font-bold text-gray-900 dark:text-white hover:text-blue-600 text-sm line-clamp-1">
                                            {{ $blog->title }}
                                        </a>
                                        <p class="text-xs text-gray-400 mt-0.5 line-clamp-1">
                                            /blogs/{{ $blog->slug }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <!-- Category -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($blog->category)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 border border-blue-100 dark:border-blue-800">
                                        {{ $blog->category }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400 italic">Uncategorized</span>
                                @endif
                            </td>

                            <!-- Author -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-slate-200 text-slate-700 font-bold text-xs flex items-center justify-center">
                                        {{ strtoupper(substr($blog->author_display_name, 0, 1)) }}
                                    </div>
                                    <span class="text-xs font-semibold">{{ $blog->author_display_name }}</span>
                                </div>
                            </td>

                            <!-- Views -->
                            <td class="px-6 py-4 whitespace-nowrap text-center text-xs font-bold text-gray-600 dark:text-gray-400">
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    {{ number_format($blog->views_count) }}
                                </span>
                            </td>

                            <!-- Status & Quick Toggle -->
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <form action="{{ route('admin.blogs.toggle-publish', $blog->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        title="Click to toggle publish status"
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition {{ $blog->is_published ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-amber-50 text-amber-700 border border-amber-200 hover:bg-amber-100' }}">
                                        <span class="w-2 h-2 rounded-full {{ $blog->is_published ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                                        <span>{{ $blog->is_published ? 'Published' : 'Draft' }}</span>
                                    </button>
                                </form>
                            </td>

                            <!-- Date -->
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                @if($blog->published_at)
                                    <div>{{ $blog->published_at->format('M d, Y') }}</div>
                                    <div class="text-[10px] text-gray-400">{{ $blog->published_at->format('h:i A') }}</div>
                                @else
                                    <span class="text-gray-400 italic">Not set</span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-semibold">
                                <div class="flex items-center justify-end gap-2">
                                    @if($blog->is_published)
                                        <a href="{{ route('blogs.show', $blog->slug) }}" target="_blank"
                                            class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Preview on Website">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                            </svg>
                                        </a>
                                    @endif

                                    <a href="{{ route('admin.blogs.edit', $blog->id) }}"
                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit Article">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>

                                    <form action="{{ route('admin.blogs.destroy', $blog->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to permanently delete this blog post?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Delete Article">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="max-w-md mx-auto">
                                    <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">No blog posts found</h3>
                                    <p class="text-sm text-gray-500 mt-1 mb-6">
                                        Get started by posting your first academic blog or announcement article.
                                    </p>
                                    <a href="{{ route('admin.blogs.create') }}"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white font-bold rounded-xl shadow hover:bg-blue-700 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        <span>Create First Blog</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($blogs->hasPages())
            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                {{ $blogs->links() }}
            </div>
        @endif
    </div>
@endsection
