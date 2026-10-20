@extends('layouts.web')
@section('title', $blog->title . ' | HJPARAM Blog')
@section('meta_description', $blog->summary ?: Str::limit(strip_tags($blog->content), 160))

@push('styles')
<style>
    /* Academic Blog Rich Content Typography */
    .blog-content {
        color: #334155;
        font-size: 1.0625rem;
        line-height: 1.85;
    }
    .blog-content h1 {
        font-size: 2.15rem;
        font-weight: 800;
        color: #0f172a;
        margin-top: 2.5rem;
        margin-bottom: 1.25rem;
        line-height: 1.25;
        font-family: ui-serif, Georgia, Cambria, "Times New Roman", Times, serif;
    }
    .blog-content h2 {
        font-size: 1.625rem;
        font-weight: 700;
        color: #0f172a;
        margin-top: 2.75rem;
        margin-bottom: 1.15rem;
        line-height: 1.35;
        font-family: ui-serif, Georgia, Cambria, "Times New Roman", Times, serif;
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 0.5rem;
    }
    .blog-content h3 {
        font-size: 1.28rem;
        font-weight: 700;
        color: #1e293b;
        margin-top: 2rem;
        margin-bottom: 0.75rem;
        line-height: 1.4;
    }
    .blog-content h4 {
        font-size: 1.1rem;
        font-weight: 700;
        color: #334155;
        margin-top: 1.5rem;
        margin-bottom: 0.5rem;
    }
    .blog-content p {
        margin-top: 1.15rem;
        margin-bottom: 1.15rem;
        line-height: 1.85;
    }
    .blog-content ul {
        list-style-type: disc !important;
        margin-top: 1.15rem !important;
        margin-bottom: 1.25rem !important;
        padding-left: 1.75rem !important;
    }
    .blog-content ul li {
        list-style-type: disc !important;
        margin-top: 0.5rem;
        margin-bottom: 0.5rem;
        line-height: 1.75;
        padding-left: 0.25rem;
    }
    .blog-content ul ul {
        list-style-type: circle !important;
        margin-top: 0.35rem !important;
        margin-bottom: 0.35rem !important;
        padding-left: 1.5rem !important;
    }
    .blog-content ul ul li {
        list-style-type: circle !important;
    }
    .blog-content ol {
        list-style-type: decimal !important;
        margin-top: 1.15rem !important;
        margin-bottom: 1.25rem !important;
        padding-left: 1.75rem !important;
    }
    .blog-content ol li {
        list-style-type: decimal !important;
        margin-top: 0.65rem;
        margin-bottom: 0.65rem;
        line-height: 1.75;
        padding-left: 0.25rem;
    }
    .blog-content ol ol {
        list-style-type: lower-alpha !important;
        margin-top: 0.35rem !important;
        margin-bottom: 0.35rem !important;
        padding-left: 1.5rem !important;
    }
    .blog-content ol ol li {
        list-style-type: lower-alpha !important;
    }
    .blog-content strong, .blog-content b {
        font-weight: 700;
        color: #0f172a;
    }
    .blog-content em, .blog-content i {
        font-style: italic;
    }
    .blog-content a {
        color: #2563eb;
        text-decoration: underline;
        text-underline-offset: 3px;
        font-weight: 600;
        transition: color 0.15s ease;
    }
    .blog-content a:hover {
        color: #1d4ed8;
    }
    .blog-content table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1.75rem;
        margin-bottom: 1.75rem;
        font-size: 0.95rem;
        line-height: 1.6;
        border: 1px solid #cbd5e1;
        border-radius: 0.75rem;
        overflow: hidden;
    }
    .blog-content th {
        background-color: #f1f5f9;
        color: #0f172a;
        font-weight: 700;
        padding: 0.85rem 1.15rem;
        border: 1px solid #cbd5e1;
        text-align: left;
    }
    .blog-content td {
        padding: 0.85rem 1.15rem;
        border: 1px solid #e2e8f0;
        color: #334155;
        vertical-align: top;
    }
    .blog-content tbody tr:nth-child(even) {
        background-color: #f8fafc;
    }
    .blog-content tbody tr:hover {
        background-color: #f1f5f9;
    }
    .blog-content blockquote {
        border-left: 4px solid #2563eb;
        background-color: #f8fafc;
        padding: 1rem 1.5rem;
        margin: 1.5rem 0;
        border-radius: 0 0.75rem 0.75rem 0;
        font-style: italic;
        color: #475569;
    }
    .blog-content hr {
        border: 0;
        border-top: 1px solid #e2e8f0;
        margin: 2.5rem 0;
    }
    .blog-content .table-responsive {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        margin-top: 1.5rem;
        margin-bottom: 1.5rem;
        border-radius: 0.75rem;
        border: 1px solid #e2e8f0;
    }
    .blog-content .table-responsive table {
        margin: 0;
        border: none;
    }
</style>
@endpush

@section('content')
    <article class="bg-white min-h-screen py-12 lg:py-16">
        <div class="container mx-auto px-4 sm:px-6 max-w-4xl">

            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-8 uppercase tracking-wider">
                <a href="{{ route('home') }}" class="hover:text-blue-600 transition">Home</a>
                <span>&rsaquo;</span>
                <a href="{{ route('blogs.index') }}" class="hover:text-blue-600 transition">Blog</a>
                @if($blog->category)
                    <span>&rsaquo;</span>
                    <a href="{{ route('blogs.index', ['category' => $blog->category]) }}" class="hover:text-blue-600 transition">
                        {{ $blog->category }}
                    </a>
                @endif
            </nav>

            <!-- Article Header -->
            <header class="mb-10">
                <div class="flex flex-wrap items-center gap-3 mb-5">
                    @if($blog->category)
                        <a href="{{ route('blogs.index', ['category' => $blog->category]) }}"
                            class="inline-block px-3.5 py-1 bg-blue-50 text-blue-700 hover:bg-blue-100 text-xs font-extrabold uppercase tracking-widest rounded-full transition">
                            {{ $blog->category }}
                        </a>
                    @endif
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ $blog->reading_time }} min read
                    </span>
                    <span class="text-slate-300">&bull;</span>
                    <span class="text-xs font-semibold text-slate-500">
                        {{ $blog->published_at ? $blog->published_at->format('F d, Y') : 'Draft' }}
                    </span>
                    <span class="text-slate-300">&bull;</span>
                    <span class="inline-flex items-center gap-1 text-xs text-slate-400 font-medium">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        {{ number_format($blog->views_count) }} views
                    </span>
                </div>

                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-serif font-bold text-slate-900 leading-tight tracking-tight mb-6">
                    {{ $blog->title }}
                </h1>

                @if($blog->summary)
                    <p class="text-lg sm:text-xl text-slate-600 font-light leading-relaxed border-l-4 border-blue-600 pl-4 py-1 italic mb-8">
                        {{ $blog->summary }}
                    </p>
                @endif

                <!-- Author & Share Bar -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 py-5 border-y border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 text-white font-bold text-base flex items-center justify-center shadow-md">
                            {{ strtoupper(substr($blog->author_display_name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900">{{ $blog->author_display_name }}</p>
                            <p class="text-xs text-slate-500">
                                @if($blog->author && $blog->author->role)
                                    HJPARAM {{ ucfirst($blog->author->role) }}
                                @else
                                    Contributor & Editorial Board
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Social Share Buttons -->
                    <div class="flex items-center gap-2" x-data="{ copied: false }">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mr-1 hidden sm:inline">Share:</span>

                        <!-- Twitter/X -->
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($blog->title) }}&url={{ urlencode(request()->url()) }}"
                            target="_blank" rel="noopener"
                            class="w-8 h-8 rounded-full bg-slate-100 hover:bg-black hover:text-white text-slate-600 flex items-center justify-center transition"
                            title="Share on X (Twitter)">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>

                        <!-- LinkedIn -->
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}"
                            target="_blank" rel="noopener"
                            class="w-8 h-8 rounded-full bg-slate-100 hover:bg-blue-700 hover:text-white text-slate-600 flex items-center justify-center transition"
                            title="Share on LinkedIn">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                        </a>

                        <!-- Facebook -->
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                            target="_blank" rel="noopener"
                            class="w-8 h-8 rounded-full bg-slate-100 hover:bg-blue-600 hover:text-white text-slate-600 flex items-center justify-center transition"
                            title="Share on Facebook">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/></svg>
                        </a>

                        <!-- Copy Link -->
                        <button type="button"
                            @click="navigator.clipboard.writeText(window.location.href); copied = true; setTimeout(() => copied = false, 2500)"
                            class="px-2.5 py-1.5 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold flex items-center gap-1.5 transition"
                            title="Copy link to clipboard">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            <span x-text="copied ? 'Copied!' : 'Copy Link'">Copy Link</span>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Featured Image (if present) -->
            @if($blog->image_url)
                <div class="mb-12 rounded-3xl overflow-hidden shadow-lg border border-slate-200 bg-slate-900/5 relative flex items-center justify-center">
                    <div class="absolute inset-0 bg-cover bg-center blur-2xl opacity-20 scale-110 pointer-events-none"
                         style="background-image: url('{{ $blog->image_url }}');"></div>
                    <img src="{{ $blog->image_url }}" alt="{{ $blog->title }}" class="relative z-10 w-full h-auto max-h-[620px] object-contain">
                </div>
            @endif

            <!-- Main Article Body -->
            <div class="blog-content prose prose-slate prose-lg max-w-none mb-12 text-slate-800 leading-relaxed font-normal">
                @if(strip_tags($blog->content) === $blog->content)
                    {!! nl2br(e($blog->content)) !!}
                @else
                    {!! $blog->content !!}
                @endif
            </div>

            <!-- Tags -->
            @if(!empty($blog->tags_list))
                <div class="pt-6 border-t border-slate-200 mb-10">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-3">Topics & Tags</span>
                    <div class="flex flex-wrap gap-2">
                        @foreach($blog->tags_list as $tag)
                            <a href="{{ route('blogs.index', ['tag' => $tag]) }}"
                                class="inline-block px-3 py-1 bg-slate-100 hover:bg-blue-50 hover:text-blue-700 text-slate-700 text-xs font-semibold rounded-lg transition">
                                #{{ $tag }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Author Bio Card -->
            <div class="p-6 sm:p-8 bg-slate-50 rounded-3xl border border-slate-200 mb-14 flex flex-col sm:flex-row items-center sm:items-start gap-5">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-700 text-white font-bold text-xl flex items-center justify-center shrink-0 shadow-md">
                    {{ strtoupper(substr($blog->author_display_name, 0, 1)) }}
                </div>
                <div class="text-center sm:text-left flex-1">
                    <div class="flex items-center justify-center sm:justify-start gap-2 mb-1">
                        <h4 class="text-base font-bold text-slate-900">{{ $blog->author_display_name }}</h4>
                        <span class="text-[10px] bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full font-bold">Contributor</span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-light">
                        {{ $blog->author?->bio ?? 'Scholar and contributor at HJPARAM Publication. Committed to expanding access to high-impact scholarly knowledge and ethical publishing practices globally.' }}
                    </p>
                </div>
            </div>

            <!-- Related Articles -->
            @if($relatedBlogs->isNotEmpty())
                <div class="pt-12 border-t border-slate-200">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <span class="text-xs font-bold text-blue-600 uppercase tracking-widest block mb-1">More Insights</span>
                            <h3 class="text-2xl font-serif font-bold text-slate-900">Related Articles</h3>
                        </div>
                        <a href="{{ route('blogs.index') }}" class="text-xs font-bold text-blue-600 hover:underline">
                            View All &rarr;
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        @foreach($relatedBlogs as $rel)
                            <a href="{{ route('blogs.show', $rel->slug) }}"
                                class="bg-slate-50 hover:bg-white rounded-2xl p-4 border border-slate-200/80 hover:shadow-md transition flex flex-col h-full group">
                                <div class="w-full h-36 rounded-xl overflow-hidden bg-slate-200 mb-3">
                                    @if($rel->image_url)
                                        <img src="{{ $rel->image_url }}" alt="{{ $rel->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                    @else
                                        <div class="w-full h-full bg-blue-100 text-blue-700 font-bold text-xs flex items-center justify-center">
                                            HJPARAM
                                        </div>
                                    @endif
                                </div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-blue-600 mb-1">
                                    {{ $rel->category ?? 'Article' }}
                                </span>
                                <h4 class="text-sm font-bold text-slate-900 group-hover:text-blue-600 transition line-clamp-2 leading-snug mb-2 flex-1">
                                    {{ $rel->title }}
                                </h4>
                                <span class="text-[10px] text-slate-400">
                                    {{ $rel->published_at ? $rel->published_at->format('M d, Y') : '' }} &bull; {{ $rel->reading_time }} min read
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Back to Blog Button -->
            <div class="mt-14 pt-8 border-t border-slate-100 flex justify-between items-center">
                <a href="{{ route('blogs.index') }}"
                    class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-slate-600 hover:text-blue-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Back to All Blogs</span>
                </a>

                <a href="{{ route('author.submit') }}"
                    class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-blue-600 hover:text-blue-900 transition">
                    <span>Submit Your Article &rarr;</span>
                </a>
            </div>

        </div>
    </article>
@endsection
