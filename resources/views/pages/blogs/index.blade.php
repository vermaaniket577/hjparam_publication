@extends('layouts.web')
@section('title', 'Insights & Blog | HJPARAM Publication')
@section('meta_description', 'Explore the latest articles, academic publishing trends, research highlights, and scholarly insights from HJPARAM Publication.')

@section('content')
    <!-- Blog Hero Header -->
    <div class="relative bg-gradient-to-b from-slate-900 via-slate-900 to-slate-800 text-white pt-24 pb-20 overflow-hidden">
        <!-- Background subtle particle circles -->
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-blue-500 blur-3xl"></div>
            <div class="absolute top-1/2 right-0 w-80 h-80 rounded-full bg-indigo-500 blur-3xl"></div>
        </div>

        <div class="container mx-auto px-4 sm:px-6 relative z-10 max-w-6xl">
            <!-- Breadcrumbs -->
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-6 uppercase tracking-wider">
                <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
                <span>&rsaquo;</span>
                <span class="text-slate-300">Initiatives</span>
                <span>&rsaquo;</span>
                <span class="text-blue-400">Blog</span>
            </div>

            <div class="max-w-3xl">
                <span class="inline-flex items-center gap-2 px-3 py-1 bg-blue-500/10 border border-blue-400/30 text-blue-400 text-xs font-bold uppercase tracking-widest rounded-full mb-4">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span>
                    Scholarly Perspectives & Updates
                </span>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-serif font-bold text-white tracking-tight leading-tight mb-6">
                    HJPARAM <span class="bg-gradient-to-r from-blue-400 via-indigo-300 to-sky-300 bg-clip-text text-transparent">Insights & Blog</span>
                </h1>
                <p class="text-base sm:text-lg text-slate-300 font-light leading-relaxed">
                    Thought leadership, research methodology spotlights, open-access developments, and publishing ethics from our editorial board and global contributors.
                </p>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="bg-slate-50/70 min-h-screen py-12">
        <div class="container mx-auto px-4 sm:px-6 max-w-6xl">

            <!-- Search & Filter Controls -->
            <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-xs border border-slate-200/80 mb-10">
                <form method="GET" action="{{ route('blogs.index') }}" class="flex flex-col md:flex-row gap-4 items-center justify-between">
                    <!-- Search Input -->
                    <div class="relative w-full md:w-96">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </span>
                        <input type="text" name="q" value="{{ $searchQuery }}"
                            placeholder="Search articles by title, topic, or keyword..."
                            class="w-full pl-10 pr-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                    </div>

                    <!-- Category Pills & Clear -->
                    <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto pb-1 md:pb-0 custom-scrollbar">
                        <a href="{{ route('blogs.index', array_filter(['q' => $searchQuery])) }}"
                            class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap {{ empty($currentCategory) && empty($currentTag) ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                            All Topics
                        </a>
                        @foreach($categoriesWithCount->take(4) as $catItem)
                            <a href="{{ route('blogs.index', array_filter(['category' => $catItem->category, 'q' => $searchQuery])) }}"
                                class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition whitespace-nowrap {{ $currentCategory === $catItem->category ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                                {{ $catItem->category }}
                                <span class="ml-1 opacity-60 text-[10px]">({{ $catItem->count }})</span>
                            </a>
                        @endforeach

                        @if($searchQuery || $currentCategory || $currentTag)
                            <a href="{{ route('blogs.index') }}"
                                class="px-3 py-1.5 rounded-xl text-xs font-semibold text-rose-600 bg-rose-50 hover:bg-rose-100 transition whitespace-nowrap">
                                Clear Filters &times;
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Hero Featured Post Highlight (when on page 1 without active search) -->
            @if($featuredBlog)
                <div class="mb-14">
                    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-md overflow-hidden hover:shadow-xl transition-all duration-300">
                        <div class="grid grid-cols-1 lg:grid-cols-12">
                            <!-- Image Column -->
                            <div class="lg:col-span-7 relative overflow-hidden bg-slate-900/5 flex items-center justify-center min-h-[280px] sm:min-h-[380px] lg:min-h-[420px]">
                                @if($featuredBlog->image_url)
                                    <!-- Ambient subtle blurred backdrop to seamlessly fill any aspect-ratio differences -->
                                    <div class="absolute inset-0 bg-cover bg-center blur-2xl opacity-25 scale-110 pointer-events-none"
                                         style="background-image: url('{{ $featuredBlog->image_url }}');"></div>
                                    <div class="absolute inset-0 bg-slate-900/5 pointer-events-none"></div>

                                    <a href="{{ route('blogs.show', $featuredBlog->slug) }}" class="relative z-10 w-full h-full flex items-center justify-center">
                                        <img src="{{ $featuredBlog->image_url }}" alt="{{ $featuredBlog->title }}"
                                            class="w-full h-full max-h-[500px] object-contain group-hover:scale-[1.02] transition-transform duration-500">
                                    </a>
                                @else
                                    <div class="w-full h-full min-h-[300px] bg-gradient-to-br from-blue-700 via-indigo-700 to-sky-600 flex items-center justify-center p-8 text-white">
                                        <div class="text-center">
                                            <span class="text-4xl font-serif font-bold opacity-30">HJPARAM</span>
                                            <p class="text-sm tracking-widest uppercase font-semibold mt-2 opacity-80">Featured Insight</p>
                                        </div>
                                    </div>
                                @endif
                                <div class="absolute top-4 left-4 z-20 pointer-events-none">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider bg-white/95 text-blue-700 shadow-md backdrop-blur-xs">
                                        <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                                        Featured Story
                                    </span>
                                </div>
                            </div>

                            <!-- Content Column -->
                            <div class="lg:col-span-5 p-6 sm:p-10 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center gap-3 text-xs font-semibold text-slate-500 mb-3">
                                        @if($featuredBlog->category)
                                            <span class="text-blue-600 uppercase font-bold tracking-wider">{{ $featuredBlog->category }}</span>
                                            <span>&bull;</span>
                                        @endif
                                        <span>{{ $featuredBlog->reading_time }} min read</span>
                                        <span>&bull;</span>
                                        <span>{{ $featuredBlog->published_at ? $featuredBlog->published_at->format('M d, Y') : '' }}</span>
                                    </div>

                                    <h2 class="text-2xl sm:text-3xl font-serif font-bold text-slate-900 leading-snug hover:text-blue-700 transition">
                                        <a href="{{ route('blogs.show', $featuredBlog->slug) }}">
                                            {{ $featuredBlog->title }}
                                        </a>
                                    </h2>

                                    <p class="text-slate-600 text-sm sm:text-base leading-relaxed mt-4 line-clamp-4">
                                        {{ $featuredBlog->summary ?: Str::limit(strip_tags($featuredBlog->content), 180) }}
                                    </p>
                                </div>

                                <div class="pt-6 sm:pt-8 mt-6 border-t border-slate-100 flex items-center justify-between">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 text-white font-bold text-xs flex items-center justify-center shadow-xs">
                                            {{ strtoupper(substr($featuredBlog->author_display_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-slate-800">{{ $featuredBlog->author_display_name }}</p>
                                            <p class="text-[10px] text-slate-400">Author & Contributor</p>
                                        </div>
                                    </div>

                                    <a href="{{ route('blogs.show', $featuredBlog->slug) }}"
                                        class="inline-flex items-center gap-2 text-xs font-extrabold uppercase tracking-widest text-blue-600 hover:text-blue-800 group">
                                        <span>Read Article</span>
                                        <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Main Layout: Grid + Sidebar -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

                <!-- Blog Posts Grid (8 cols) -->
                <div class="lg:col-span-8">
                    @if($currentCategory || $currentTag || $searchQuery)
                        <div class="mb-6 flex items-center justify-between">
                            <h2 class="text-xl font-bold text-slate-900">
                                @if($currentCategory)
                                    Category: <span class="text-blue-600">{{ $currentCategory }}</span>
                                @elseif($currentTag)
                                    Tag: <span class="text-blue-600">#{{ $currentTag }}</span>
                                @elseif($searchQuery)
                                    Search Results for: <span class="text-blue-600">&ldquo;{{ $searchQuery }}&rdquo;</span>
                                @endif
                            </h2>
                            <span class="text-xs text-slate-400">{{ $blogs->total() }} articles</span>
                        </div>
                    @endif

                    @if($blogs->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-7">
                            @foreach($blogs as $blog)
                                <article class="bg-white rounded-3xl border border-slate-200/80 shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col h-full group overflow-hidden">
                                    <!-- Thumbnail -->
                                    <a href="{{ route('blogs.show', $blog->slug) }}" class="block relative h-52 bg-slate-100 overflow-hidden">
                                        @if($blog->image_url)
                                            <img src="{{ $blog->image_url }}" alt="{{ $blog->title }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        @else
                                            <div class="w-full h-full bg-gradient-to-tr from-slate-800 to-blue-900 flex items-center justify-center text-white">
                                                <span class="font-serif font-bold text-lg opacity-40">HJPARAM</span>
                                            </div>
                                        @endif

                                        @if($blog->category)
                                            <span class="absolute top-3.5 left-3.5 px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-white/95 text-blue-700 shadow-sm backdrop-blur-xs">
                                                {{ $blog->category }}
                                            </span>
                                        @endif
                                    </a>

                                    <!-- Content -->
                                    <div class="p-6 flex flex-col flex-1">
                                        <div class="flex items-center gap-2 text-[11px] font-semibold text-slate-400 mb-3">
                                            <span>{{ $blog->published_at ? $blog->published_at->format('M d, Y') : '' }}</span>
                                            <span>&bull;</span>
                                            <span>{{ $blog->reading_time }} min read</span>
                                        </div>

                                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-blue-600 transition leading-snug line-clamp-2 mb-3">
                                            <a href="{{ route('blogs.show', $blog->slug) }}">
                                                {{ $blog->title }}
                                            </a>
                                        </h3>

                                        <p class="text-slate-500 text-xs sm:text-sm leading-relaxed line-clamp-3 mb-6 flex-1">
                                            {{ $blog->summary ?: Str::limit(strip_tags($blog->content), 120) }}
                                        </p>

                                        <!-- Footer Author & Action -->
                                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs mt-auto">
                                            <div class="flex items-center gap-2">
                                                <div class="w-7 h-7 rounded-full bg-blue-100 text-blue-700 font-bold text-[11px] flex items-center justify-center">
                                                    {{ strtoupper(substr($blog->author_display_name, 0, 1)) }}
                                                </div>
                                                <span class="font-medium text-slate-700 text-xs">{{ $blog->author_display_name }}</span>
                                            </div>

                                            <a href="{{ route('blogs.show', $blog->slug) }}"
                                                class="font-bold text-blue-600 group-hover:text-blue-800 flex items-center gap-1">
                                                <span>Read</span>
                                                <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if($blogs->hasPages())
                            <div class="mt-12">
                                {{ $blogs->links() }}
                            </div>
                        @endif

                    @else
                        <!-- Empty Search / Filter State -->
                        <div class="bg-white rounded-3xl p-12 text-center border border-slate-200/80">
                            <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 mb-2">No Articles Found</h3>
                            <p class="text-slate-500 text-sm max-w-md mx-auto mb-6">
                                We couldn't find any articles matching your search criteria. Try different keywords or browse all topics.
                            </p>
                            <a href="{{ route('blogs.index') }}"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow hover:bg-blue-700 transition">
                                Reset Filters & View All
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Sidebar (4 cols) -->
                <div class="lg:col-span-4 space-y-8">
                    <!-- Categories Widget -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100 flex items-center justify-between">
                            <span>Browse Categories</span>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                            </svg>
                        </h4>
                        <div class="space-y-1.5">
                            @forelse($categoriesWithCount as $catItem)
                                <a href="{{ route('blogs.index', ['category' => $catItem->category]) }}"
                                    class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold transition {{ $currentCategory === $catItem->category ? 'bg-blue-50 text-blue-700 font-bold' : 'hover:bg-slate-50 text-slate-700' }}">
                                    <span>{{ $catItem->category }}</span>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] {{ $currentCategory === $catItem->category ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $catItem->count }}
                                    </span>
                                </a>
                            @empty
                                <p class="text-xs text-slate-400 italic">No categories yet.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Recent Articles Widget -->
                    @if($recentPosts->isNotEmpty())
                        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs">
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100 flex items-center justify-between">
                                <span>Recent Stories</span>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </h4>
                            <div class="space-y-4">
                                @foreach($recentPosts as $recent)
                                    <a href="{{ route('blogs.show', $recent->slug) }}" class="flex items-start gap-3.5 group">
                                        <div class="w-14 h-14 rounded-xl overflow-hidden bg-slate-100 flex-shrink-0 border border-slate-200">
                                            @if($recent->image_url)
                                                <img src="{{ $recent->image_url }}" alt="{{ $recent->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                            @else
                                                <div class="w-full h-full bg-blue-100 text-blue-700 font-bold text-[10px] flex items-center justify-center">
                                                    POST
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-bold text-slate-800 group-hover:text-blue-600 transition line-clamp-2 leading-snug">
                                                {{ $recent->title }}
                                            </p>
                                            <span class="text-[10px] text-slate-400 mt-1 block">
                                                {{ $recent->published_at ? $recent->published_at->format('M d, Y') : '' }}
                                            </span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Scholarly Call to Action Card -->
                    <div class="rounded-3xl p-7 bg-gradient-to-br from-blue-700 via-indigo-700 to-blue-900 text-white shadow-xl relative overflow-hidden">
                        <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
                        <span class="inline-block px-2.5 py-1 bg-white/15 rounded-md text-[10px] font-extrabold uppercase tracking-widest text-blue-200 mb-3">
                            Author Submissions Open
                        </span>
                        <h4 class="text-lg font-serif font-bold text-white leading-snug mb-2">
                            Publish Your Scientific Work With HJPARAM
                        </h4>
                        <p class="text-xs text-blue-100/80 leading-relaxed mb-6 font-light">
                            Fast, rigorous peer review, open-access indexing, and global scientific dissemination for your manuscripts.
                        </p>
                        <a href="{{ route('author.submit') }}"
                            class="inline-block w-full py-3 text-center bg-white hover:bg-slate-50 text-blue-900 font-bold text-xs uppercase tracking-wider rounded-xl shadow-md transition hover:scale-[1.02]">
                            Submit Manuscript
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
