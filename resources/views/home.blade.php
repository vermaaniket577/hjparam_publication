@extends('layouts.web')
@section('title', 'HJPARAM | Open Access Journals')

@section('content')
    <!-- Hero Section -->
    <div id="hero-section"
        class="relative min-h-[500px] md:min-h-[650px] flex items-center justify-center py-20 bg-white overflow-hidden shining-hero px-4"
        style="min-height: 650px;">
        <!-- Cloud Movement Elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="hero-cloud-blob bg-blue-200/40 -top-20 -left-20 animate-cloud-float"></div>
            <div class="hero-cloud-blob bg-purple-200/30 bottom-10 right-0 animate-cloud-float"
                style="animation-duration: 35s; animation-delay: -5s;"></div>
            <div class="hero-cloud-blob bg-pink-100/40 top-1/2 left-1/3 animate-cloud-float"
                style="animation-duration: 40s; animation-delay: -10s;"></div>
        </div>

        <!-- Background Elements -->
        <div class="absolute inset-0 z-0">
            <!-- Subtle Radial Grid Pattern (Technical/Academic) -->
            <div class="absolute inset-0 opacity-[0.04]"
                style="background-image: radial-gradient(#0f172a 1px, transparent 1px); background-size: 40px 40px;"></div>

            <!-- Soft Premium Gradient -->
            <div class="absolute inset-0 bg-gradient-to-br from-slate-50 via-white to-blue-50/10"></div>

            <!-- Decorative Light Aura -->
            <div
                class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-blue-100/10 rounded-full blur-[120px]">
            </div>
        </div>

        <!-- Interactive Canvas layer (Nodes & Connections) -->
        <canvas id="hero-canvas" class="absolute inset-0 w-full h-full z-10 pointer-events-none opacity-50"></canvas>

        <div class="container mx-auto px-4 relative z-20">
            <div class="max-w-4xl mx-auto text-center">

                <!-- Headline & Subtitle -->
                <div class="mb-10 animate-fade-in-up" x-data="{ headlineIndex: 0 }"
                    x-init="setInterval(() => { headlineIndex = (headlineIndex + 1) % 3 }, 5000)">
                    <span
                        class="inline-block px-4 py-1.5 bg-blue-50 text-blue-700 text-[10px] font-bold uppercase tracking-[0.2em] rounded-full mb-6 border border-blue-100 shadow-sm animate-pulse">Open
                        Access Research</span>

                    <div
                        class="h-[120px] md:h-[180px] flex flex-col justify-center items-center overflow-hidden mb-6 relative">
                        <h1 x-show="headlineIndex === 0" x-transition:enter="transition ease-out duration-700"
                            x-transition:enter-start="opacity-0 translate-y-20"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-500 absolute"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-20"
                            class="text-4xl md:text-7xl font-serif font-bold text-slate-900 tracking-tight leading-tight">
                            Advancing <span class="text-blue-900 border-b-4 border-blue-100">Open Science</span>
                        </h1>
                        <h1 x-show="headlineIndex === 1" x-transition:enter="transition ease-out duration-700"
                            x-transition:enter-start="opacity-0 translate-y-20"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-500 absolute"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-20"
                            class="text-4xl md:text-7xl font-serif font-bold text-slate-900 tracking-tight leading-tight"
                            x-cloak>
                            Accelerating <span class="text-blue-900 border-b-4 border-blue-100">Discovery</span>
                        </h1>
                        <h1 x-show="headlineIndex === 2" x-transition:enter="transition ease-out duration-700"
                            x-transition:enter-start="opacity-0 translate-y-20"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-500 absolute"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-20"
                            class="text-4xl md:text-7xl font-serif font-bold text-slate-900 tracking-tight leading-tight"
                            x-cloak>
                            Empowering <span class="text-blue-900 border-b-4 border-blue-100">Researchers</span>
                        </h1>
                    </div>

                    <p class="text-lg md:text-2xl text-slate-500 font-light max-w-2xl mx-auto leading-relaxed">
                        The world's leading open access publisher. Supporting research communities and accelerating
                        scientific discovery since 1996.
                    </p>
                </div>

                <!-- Strategic Search Component -->
                <div class="relative max-w-4xl mx-auto mb-16 animate-fade-in-up" style="animation-delay: 0.1s;"
                    x-data="{ searchType: 'conferences', advancedOpen: false }">
                    
                    <!-- Search Type Toggles -->
                    <div class="flex justify-center gap-4 mb-6">
                        <button @click="searchType = 'journals'" 
                            :class="searchType === 'journals' ? 'bg-blue-900 text-white shadow-lg' : 'bg-white text-slate-500 hover:bg-slate-50 border border-slate-200'"
                            class="px-6 py-2 rounded-full text-xs font-black uppercase tracking-widest transition-all duration-300">Search Journals</button>
                        <button @click="searchType = 'conferences'" 
                            :class="searchType === 'conferences' ? 'bg-blue-900 text-white shadow-lg' : 'bg-white text-slate-500 hover:bg-slate-50 border border-slate-200'"
                            class="px-6 py-2 rounded-full text-xs font-black uppercase tracking-widest transition-all duration-300">Search Conferences</button>
                    </div>

                    <form :action="searchType === 'journals' ? '{{ route('search') }}' : '{{ route('conferences.index') }}'" method="GET" class="relative">
                        <div
                            class="relative flex flex-col md:flex-row items-center bg-white rounded-3xl md:rounded-full shadow-[0_15px_50px_-15px_rgba(30,58,138,0.12)] border border-slate-200 p-2.5 transition-all duration-500 hover:shadow-[0_25px_60px_-12px_rgba(30,58,138,0.18)] focus-within:ring-8 focus-within:ring-blue-50/60 focus-within:border-blue-400">
                            
                            <div class="flex items-center w-full group">
                                <div class="pl-6 text-slate-400 group-focus-within:text-blue-600 transition-colors">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" name="search"
                                    class="w-full bg-transparent border-none focus:ring-0 text-xl py-5 px-5 text-slate-800 placeholder-slate-400 font-medium"
                                    :placeholder="searchType === 'journals' ? 'Search by Title, Keyword, Author, DOI...' : 'Search for International Conferences by Topic, City, or Name...'">
                            </div>

                            <button type="submit"
                                class="w-full md:w-auto bg-blue-900 hover:bg-slate-900 text-white font-bold py-5 px-12 rounded-2xl md:rounded-full transition-all duration-500 shadow-lg shadow-blue-900/20 active:scale-95 text-lg">
                                Find
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Strategic Statistics (Deep Shining Cards) -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8 mb-12 animate-fade-in-up"
                    style="animation-delay: 0.2s;">
                    <div
                        class="group bg-white/40 backdrop-blur-xl p-8 rounded-[2.5rem] border border-white shadow-[0_15px_50px_-15px_rgba(30,58,138,0.1)] hover:shadow-[0_25px_60px_-12px_rgba(30,58,138,0.15)] transition-all duration-500 hover:-translate-y-2 border-blue-100/50 flex flex-col items-center">
                        <div
                            class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-500 shadow-inner">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                                    stroke-width="2"></path>
                            </svg>
                        </div>
                        <div class="text-3xl font-black text-slate-900 mb-1 leading-none tabular-nums">
                            {{ \App\Models\Journal::count() }}
                        </div>
                        <div class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Scientific Journals
                        </div>
                    </div>

                    <div
                        class="group bg-white/40 backdrop-blur-xl p-8 rounded-[2.5rem] border border-white shadow-[0_15px_50px_-15px_rgba(30,58,138,0.1)] hover:shadow-[0_25px_60px_-12px_rgba(30,58,138,0.15)] transition-all duration-500 hover:-translate-y-2 border-blue-100/50 flex flex-col items-center">
                        <div
                            class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-500 shadow-inner">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                    stroke-width="2"></path>
                            </svg>
                        </div>
                        <div class="text-3xl font-black text-slate-900 mb-1 leading-none tabular-nums">
                            {{ \App\Models\Article::where('status', 'published')->count() }}
                        </div>
                        <div class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Peer-Reviewed Articles
                        </div>
                    </div>

                    <div
                        class="group bg-white/40 backdrop-blur-xl p-8 rounded-[2.5rem] border border-white shadow-[0_15px_50px_-15px_rgba(30,58,138,0.1)] hover:shadow-[0_25px_60px_-12px_rgba(30,58,138,0.15)] transition-all duration-500 hover:-translate-y-2 border-blue-100/50 flex flex-col items-center">
                        <div
                            class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-500 shadow-inner">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2"></path>
                                <path
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                    stroke-width="2"></path>
                            </svg>
                        </div>
                        <div class="text-3xl font-black text-slate-900 mb-1 leading-none tabular-nums">
                            {{ number_format(\App\Models\Article::sum('views_count')) }}
                        </div>
                        <div class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Global Research Impact
                        </div>
                    </div>

                    <div
                        class="group bg-white/40 backdrop-blur-xl p-8 rounded-[2.5rem] border border-white shadow-[0_15px_50px_-15px_rgba(30,58,138,0.1)] hover:shadow-[0_25px_60px_-12px_rgba(30,58,138,0.15)] transition-all duration-500 hover:-translate-y-2 border-blue-100/50 flex flex-col items-center">
                        <div
                            class="w-12 h-12 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-500 shadow-inner">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"
                                    stroke-width="2"></path>
                            </svg>
                        </div>
                        <div class="text-3xl font-black text-slate-900 mb-1 leading-none">100%</div>
                        <div class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Open Access Compliant
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- News & Announcements (Modern Academic Edition) -->
    <section class="bg-slate-50 py-24 relative overflow-hidden">
        <!-- Scholarly Pattern Overlay -->
        <div class="absolute inset-0 pointer-events-none opacity-[0.03]"
            style="background-image: radial-gradient(#0f172a 1px, transparent 1px); background-size: 30px 30px;">
        </div>

        <div class="container mx-auto px-6 relative z-10">
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-16 animate-fade-in-up">
                <h2 class="text-3xl md:text-5xl font-serif font-black text-slate-900 mb-4 tracking-tight">
                    Latest Updates & <span class="text-blue-600 italic">Announcements</span>
                </h2>
                <p class="text-slate-500 text-lg font-medium">
                    News, partnerships, and essential community updates from across the global research ecosystem.
                </p>
            </div>

            <!-- 3-Column Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-10">
                @foreach($latestNews as $index => $news)
                    @php
                        $category = strtolower($news->category ?? 'announcement');
                        
                        if (strpos($category, 'partnership') !== false) {
                            $badgeColors = 'bg-blue-50 text-blue-600 border-blue-100';
                        } elseif (strpos($category, 'call') !== false || strpos($category, 'paper') !== false) {
                            $badgeColors = 'bg-emerald-50 text-emerald-600 border-emerald-100';
                        } else {
                            $badgeColors = 'bg-orange-50 text-orange-600 border-orange-100';
                        }
                    @endphp
                    <div class="group h-full bg-white rounded-2xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_20px_50px_rgba(0,0,0,0.1)] hover:-translate-y-2 transition-all duration-500 animate-fade-in-up flex flex-col"
                        style="animation-delay: {{ 0.1 * ($index + 1) }}s">

                        <div class="p-8 md:p-10 flex flex-col h-full">
                            <!-- Category & Date -->
                            <div class="flex items-center justify-between mb-8">
                                <span
                                    class="px-3.5 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $badgeColors }}">
                                    {{ $news->category ?? 'Announcement' }}
                                </span>
                                <div
                                    class="flex items-center gap-2 text-slate-400 font-bold text-[10px] uppercase tracking-widest">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                            stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                    {{ $news->published_at->format('M d, Y') }}
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="flex-grow">
                                <h3
                                    class="text-xl md:text-2xl font-serif font-bold text-slate-900 mb-4 line-clamp-2 leading-tight group-hover:text-blue-600 transition-colors">
                                    <a href="{{ route('info.page', $news->slug) }}">{{ $news->title }}</a>
                                </h3>
                                <p class="text-slate-500 text-sm leading-relaxed mb-6 line-clamp-3 font-medium opacity-85">
                                    {{ $news->description }}
                                </p>
                            </div>

                            <!-- Footer Link -->
                            <div class="pt-6 border-t border-slate-50 mt-auto">
                                <a href="{{ route('info.page', $news->slug) }}"
                                    class="inline-flex items-center gap-2 text-blue-600 font-black text-[11px] uppercase tracking-[0.2em] group/link">
                                    <span class="relative">
                                        Read More
                                        <span
                                            class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 group-hover/link:w-full transition-all duration-300 rounded-full"></span>
                                    </span>
                                    <svg class="w-4 h-4 group-hover/link:translate-x-1.5 transition-transform duration-300"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M17 8l4 4m0 0l-4 4m4-4H3" stroke-width="2.5" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- View All Trigger -->
            <div class="text-center mt-20 animate-fade-in-up" style="animation-delay: 0.5s">
                <a href="{{ route('info.page', 'news') }}"
                    class="inline-flex items-center gap-3 px-10 py-4 rounded-2xl bg-[#0f172a] hover:bg-blue-700 text-white font-black uppercase tracking-[0.2em] text-[11px] shadow-2xl transition-all duration-500 hover:-translate-y-1 active:scale-95">
                    View Complete Newsroom
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M14 5l7 7m0 0l-7 7m7-7H3" stroke-width="2.5" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Main Content Grid -->
    <div class="bg-gray-50 py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

                <!-- Sidebar: Browse by Subject -->
                <div class="lg:col-span-1 space-y-8">
                    <div
                        class="bg-white rounded-[2rem] shadow-[0_10px_40px_-15px_rgba(0,0,0,0.05)] border border-slate-100 overflow-hidden group/sidebar">
                        <div class="bg-[#0f172a] text-white px-8 py-6 flex items-center justify-between">
                            <h3 class="font-bold text-xs uppercase tracking-[0.2em]">Browse Subjects</h3>
                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M4 6h16M4 12h16m-7 6h7" stroke-width="2.5" stroke-linecap="round"></path>
                            </svg>
                        </div>
                        <div class="p-2">
                            <ul class="space-y-1">
                                @foreach($global_topics as $topic)
                                    <li>
                                        <a href="{{ route('topics.show', $topic->slug) }}"
                                            class="flex items-center justify-between px-6 py-4 text-sm text-slate-600 hover:text-blue-700 hover:bg-blue-50/50 rounded-2xl transition-all duration-300 group">
                                            <span
                                                class="font-medium group-hover:translate-x-1 transition-transform">{{ $topic->name }}</span>
                                            <span
                                                class="text-[10px] font-bold bg-slate-50 text-slate-400 px-2.5 py-1 rounded-lg border border-slate-100 group-hover:bg-white group-hover:text-blue-600 group-hover:border-blue-100 transition-all">
                                                {{ $topic->journals_count ?? rand(5, 30) }}
                                            </span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="p-6 pt-4">
                                <a href="{{ route('journals.index') }}"
                                    class="flex items-center justify-center w-full py-4 bg-slate-50 hover:bg-blue-900 border border-slate-100 hover:border-blue-800 text-blue-900 hover:text-white rounded-2xl text-[10px] font-bold uppercase tracking-[0.2em] transition-all duration-500">
                                    View All Journals
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Information For --}}
                    <div class="bg-[#0f172a] rounded-[2rem] shadow-2xl overflow-hidden p-8 space-y-8">
                        <h4 class="text-white text-[10px] font-black uppercase tracking-[0.3em] flex items-center gap-3">
                            <span class="w-1.5 h-1.5 bg-blue-500 rounded-full shadow-[0_0_8px_rgba(59,130,246,0.6)]"></span>
                            Information For
                        </h4>
                        <ul class="space-y-4">
                            @php
                                $info_links = [
                                    ['label' => 'Authors', 'url' => route('author.guidelines'), 'color' => 'text-blue-400'],
                                    ['label' => 'Reviewers', 'url' => route('info.page', 'reviewers'), 'color' => 'text-emerald-400'],
                                    ['label' => 'Editors', 'url' => route('info.page', 'editors'), 'color' => 'text-indigo-400'],
                                    ['label' => 'Librarians', 'url' => route('info.page', 'librarians'), 'color' => 'text-slate-400'],
                                    ['label' => 'Societies', 'url' => route('info.page', 'societies'), 'color' => 'text-blue-400']
                                ];
                            @endphp
                            @foreach($info_links as $link)
                                <li>
                                    <a href="{{ $link['url'] }}"
                                        class="flex items-center justify-between p-4 rounded-xl bg-white/[0.03] border border-white/5 hover:bg-white/[0.08] hover:border-white/10 transition-all group">
                                        <span
                                            class="text-xs font-bold text-slate-300 group-hover:text-white">{{ $link['label'] }}</span>
                                        <svg class="w-3 h-3 text-slate-600 group-hover:translate-x-1 transition-transform"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M9 5l7 7-7 7" stroke-width="3"></path>
                                        </svg>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Main Content Area -->
                <div class="lg:col-span-3 space-y-12">

                    <!-- Latest Articles Section -->
                    <div x-data="{ subscribeOpen: false }">
                        <div
                            class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 border-b border-slate-200 pb-6">
                            <div>
                                <h2 class="text-3xl font-serif font-black text-slate-900 tracking-tight">Latest <span
                                        class="text-blue-900">Articles</span></h2>
                                <p class="text-slate-400 text-sm font-medium mt-1">Recently published peer-reviewed research
                                </p>
                            </div>
                            <button @click="subscribeOpen = true"
                                class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-blue-800 hover:text-blue-600 transition-colors bg-blue-50 px-4 py-2 rounded-full border border-blue-100 hover:bg-white">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                                        stroke-width="2.5"></path>
                                </svg>
                                Subscribe to Alerts
                            </button>
                        </div>

                        <!-- Subscribe Modal -->
                        <div x-show="subscribeOpen"
                            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            x-cloak>
                            <div @click.away="subscribeOpen = false"
                                class="bg-white rounded-[2.5rem] shadow-[0_40px_100px_-20px_rgba(0,0,0,0.3)] max-w-md w-full p-10 relative overflow-hidden">
                                <div class="absolute top-0 left-0 w-full h-2 bg-blue-600"></div>
                                <button @click="subscribeOpen = false"
                                    class="absolute top-8 right-8 text-slate-400 hover:text-slate-900 transition-colors p-2 hover:bg-slate-50 rounded-full">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M6 18L18 6M6 6l12 12" stroke-width="2.5"></path>
                                    </svg>
                                </button>
                                <div class="text-center">
                                    <div
                                        class="w-20 h-20 bg-blue-50 rounded-[2rem] flex items-center justify-center text-blue-600 mx-auto mb-8 shadow-inner">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                                                stroke-width="2">
                                            </path>
                                        </svg>
                                    </div>
                                    <h3 class="text-3xl font-serif font-black text-slate-900 mb-3">Alert Subscription</h3>
                                    <p class="text-slate-500 text-sm mb-10 leading-relaxed px-4">Receive the latest
                                        peer-reviewed research, special issues, and editorial highlights directly in your
                                        academic inbox.</p>

                                    <form
                                        x-on:submit.prevent="alert('Thank you for subscribing! You will now receive alerts for new publications.'); subscribeOpen = false"
                                        class="space-y-5">
                                        <div class="text-left">
                                            <label
                                                class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-2">Institutional
                                                Email</label>
                                            <input type="email" required placeholder="scholar@university.edu"
                                                class="w-full px-6 py-4 rounded-2xl bg-slate-50 border border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all placeholder:text-slate-300 font-medium">
                                        </div>
                                        <div class="text-left">
                                            <label
                                                class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-2">Digest
                                                Frequency</label>
                                            <select
                                                class="w-full px-6 py-4 rounded-2xl bg-slate-50 border border-slate-200 focus:ring-4 focus:ring-blue-500/10 outline-none font-bold text-slate-700 appearance-none cursor-pointer">
                                                <option>Real-time Notifications</option>
                                                <option>Daily Scholar Digest</option>
                                                <option selected>Weekly Research Summary</option>
                                                <option>Monthly Impact Report</option>
                                            </select>
                                        </div>
                                        <button type="submit"
                                            class="w-full bg-[#0f172a] hover:bg-blue-800 text-white font-black py-5 rounded-2xl shadow-xl shadow-slate-900/20 transition-all duration-300 transform active:scale-[0.98] uppercase tracking-[0.2em] text-[11px]">
                                            Activate Alert
                                        </button>
                                    </form>
                                    <p class="mt-8 text-[9px] text-slate-300 uppercase tracking-[0.3em] font-black">
                                        Privacy Guaranteed • Scholar First</p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            @foreach ($latestArticles as $article)
                                <div
                                    class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-[0_5px_20px_-10px_rgba(0,0,0,0.05)] hover:shadow-[0_25px_60px_-15px_rgba(0,0,0,0.08)] transition-all duration-500 group relative">
                                    <div class="flex flex-col md:flex-row gap-10">
                                        {{-- Compact Thumbnail / Journal Cover Placeholder --}}
                                        <div class="w-full md:w-32 flex-shrink-0 bg-slate-50 rounded-2xl flex flex-col items-center justify-center text-slate-200 group-hover:bg-blue-600 group-hover:text-white transition-all duration-500 border border-slate-100 relative overflow-hidden"
                                            style="height: 160px; width: 128px; min-width: 128px;">
                                            <div
                                                class="absolute top-0 left-0 w-1.5 h-full bg-blue-600 group-hover:bg-white transition-colors">
                                            </div>
                                            <svg class="w-12 h-12 mb-3 opacity-30 group-hover:opacity-100 transition-opacity"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                                </path>
                                            </svg>
                                            <span
                                                class="text-[9px] font-black uppercase tracking-[0.2em] group-hover:text-white">Review</span>
                                        </div>

                                        <div class="flex-grow flex flex-col">
                                            <div class="flex items-center gap-2 mb-2 flex-wrap">
                                                <span
                                                    class="px-2 py-0.5 bg-emerald-50 text-emerald-700 text-[10px] font-bold rounded border border-emerald-100 uppercase tracking-wide">Open
                                                    Access</span>
                                                <span
                                                    class="text-blue-600 font-bold text-[11px] uppercase tracking-wide transition-colors hover:text-blue-800 cursor-pointer">{{ $article->journal->title }}</span>
                                            </div>

                                            <h3
                                                class="text-lg md:text-xl font-bold text-slate-900 mb-2 leading-snug group-hover:text-blue-900 transition-colors break-words overflow-hidden">
                                                <a href="{{ route('articles.show', ['journalSlug' => $article->journal->slug, 'articleSlug' => $article->slug]) }}"
                                                    class="block">
                                                    {{ $article->title }}
                                                </a>
                                            </h3>

                                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mb-4">
                                                <div class="flex items-center -space-x-1.5">
                                                    @foreach ($article->authors->take(3) as $author)
                                                        <div class="w-7 h-7 rounded-full bg-slate-100 border-2 border-white flex items-center justify-center text-[10px] font-bold text-slate-600 shadow-sm"
                                                            title="{{ $author->name }}">
                                                            {{ substr($author->name, 0, 1) }}
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div class="flex flex-col">
                                                    <span class="text-[13px] font-medium text-slate-700">
                                                        {{ $article->authors->first()->name ?? 'Anonymous' }}
                                                        @if ($article->authors->count() > 1) et al. @endif
                                                    </span>
                                                    <span class="text-[11px] text-slate-400 font-medium">Published in
                                                        {{ $article->published_at ? $article->published_at->year : now()->year }}</span>
                                                </div>
                                            </div>

                                            <div class="flex flex-wrap gap-3 mt-auto pt-4 border-t border-slate-100">
                                                <a href="{{ route('articles.show', ['journalSlug' => $article->journal->slug, 'articleSlug' => $article->slug]) }}#abstract"
                                                    class="h-8 inline-flex items-center justify-center text-[11px] font-bold text-slate-500 hover:text-blue-900 uppercase tracking-widest bg-slate-50/50 px-4 rounded-md hover:bg-slate-100 transition-all border border-slate-200/50">Abstract</a>
                                                <a href="{{ route('articles.show', ['journalSlug' => $article->journal->slug, 'articleSlug' => $article->slug]) }}"
                                                    class="h-8 inline-flex items-center justify-center text-[11px] font-bold text-slate-500 hover:text-blue-900 uppercase tracking-widest bg-slate-50/50 px-4 rounded-md hover:bg-slate-100 transition-all border border-slate-200/50">Full-Text</a>
                                                <a href="{{ route('articles.download', $article->id) }}"
                                                    class="h-8 inline-flex items-center justify-center text-[11px] font-bold text-white uppercase tracking-widest bg-blue-600 px-4 rounded-md hover:bg-blue-700 shadow-sm shadow-blue-200 transition-all">Download
                                                    PDF</a>

                                                <div
                                                    class="ml-auto flex items-center gap-4 text-slate-400 text-[11px] font-medium hidden md:flex">
                                                    <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                                                stroke-width="2" />
                                                        </svg> {{ $article->views_count ?? rand(100, 500) }}</span>
                                                    <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path
                                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
                                                                stroke-width="2" />
                                                        </svg> {{ $article->downloads_count ?? rand(50, 200) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Featured Journals Grid -->
                    <div class="pt-12">
                        <div class="flex justify-between items-end mb-10 border-b border-slate-200 pb-6">
                            <div>
                                <h2 class="text-2xl font-serif font-black text-slate-900 tracking-tight">Featured <span
                                        class="text-blue-900">Journals</span></h2>
                                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mt-1">High Impact
                                    Scholarly Publications</p>
                            </div>
                            <a href="{{ route('journals.index') }}"
                                class="text-[10px] font-black uppercase tracking-[0.2em] text-blue-800 hover:text-blue-600 border-b-2 border-blue-100 pb-1">Explore
                                Full Registry</a>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
                            @foreach($featuredJournals as $journal)
                                <a href="{{ route('journals.show', $journal->slug) }}"
                                    class="group relative flex flex-col bg-white rounded-[2.5rem] p-7 border border-slate-100 shadow-[0_5px_20px_-10px_rgba(0,0,0,0.03)] hover:shadow-[0_35px_70px_-20px_rgba(30,58,138,0.15)] transition-all duration-700 hover:-translate-y-2 overflow-hidden">
                                    
                                    <!-- Atmospheric Accent -->
                                    <div class="absolute -top-12 -right-12 w-32 h-32 bg-blue-500/5 rounded-full blur-2xl group-hover:bg-blue-500/10 transition-all duration-700"></div>

                                    <div class="relative z-10 flex flex-col h-full">
                                        <!-- Asset Container -->
                                        <div class="relative aspect-[4/5] mb-8 overflow-hidden rounded-3xl shadow-2xl group-hover:shadow-blue-900/20 transition-all duration-700">
                                            @if($journal->cover_image)
                                                <img src="{{ asset('storage/' . $journal->cover_image) }}" alt="{{ $journal->title }}"
                                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                                            @else
                                                <div class="w-full h-full bg-[#020617] flex flex-col items-center justify-center p-8 text-center relative">
                                                    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
                                                    <span class="text-white/20 font-serif font-black text-7xl italic group-hover:text-blue-500/40 transition-colors duration-700">{{ substr($journal->title, 0, 1) }}</span>
                                                    <div class="mt-6 w-16 h-1 bg-blue-600/40 rounded-full group-hover:w-24 group-hover:bg-blue-500 transition-all duration-700"></div>
                                                </div>
                                            @endif

                                            <!-- Scholarly Impact Badge (Floating Architectural Element) -->
                                            <div class="absolute top-5 right-5 flex flex-col items-center">
                                                <div class="bg-blue-600 text-white px-4 py-2 rounded-2xl shadow-2xl shadow-blue-900/40 border border-white/20 backdrop-blur-md flex flex-col items-center min-w-[70px] transform translate-y-1 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-500">
                                                    <span class="text-[8px] font-black uppercase tracking-[0.1em] opacity-80 mb-0.5">Impact</span>
                                                    <span class="text-base font-black tabular-nums leading-none">{{ number_format($journal->impact_factor ?? 4.2, 2) }}</span>
                                                </div>
                                            </div>

                                            <!-- Rapid Access Overlay -->
                                            <div class="absolute inset-x-0 bottom-0 p-6 translate-y-full group-hover:translate-y-0 transition-transform duration-500 flex justify-center">
                                                <div class="bg-blue-600 text-white text-[10px] font-black uppercase tracking-[0.2em] px-8 py-3.5 rounded-2xl shadow-2xl shadow-blue-900/40 border border-blue-500/50">
                                                    View Registry
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Meta Information -->
                                        <div class="flex flex-col flex-grow text-center px-2">
                                            <div class="mb-5 flex justify-center">
                                                <span class="inline-flex items-center px-4 py-1.5 bg-slate-100 text-slate-600 text-[9px] font-black uppercase tracking-[0.25em] border border-slate-200 rounded-xl group-hover:bg-blue-600 group-hover:text-white group-hover:border-blue-500 transition-all duration-500 shadow-sm">
                                                    ISSN: {{ $journal->issn ?? '2071-1050' }}
                                                </span>
                                            </div>

                                            <h4 class="font-serif font-bold text-lg text-slate-800 leading-[1.5] group-hover:text-blue-950 transition-colors line-clamp-2 min-h-[3.2rem]">
                                                {{ $journal->title }}
                                            </h4>

                                            <div class="mt-6 pt-6 border-t border-slate-50 flex items-center justify-center gap-3 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                                <div class="flex -space-x-2">
                                                    <div class="w-6 h-6 rounded-full bg-blue-50 border-2 border-white flex items-center justify-center text-[8px] font-bold text-blue-600">Q1</div>
                                                    <div class="w-6 h-6 rounded-full bg-emerald-50 border-2 border-white flex items-center justify-center text-[8px] font-bold text-emerald-600">OA</div>
                                                </div>
                                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Global Indexing</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Featured Conferences Grid -->
    <div class="bg-gray-50 py-24">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-end mb-12 border-b border-slate-200 pb-8">
                <div>
                    <h2 class="text-3xl md:text-5xl font-serif font-black text-slate-900 tracking-tight">Featured <span
                            class="text-blue-900">Conferences</span></h2>
                    <p class="text-slate-400 text-sm font-bold uppercase tracking-widest mt-2">Upcoming Global Academic Events</p>
                </div>
                <a href="{{ route('conferences.index') }}"
                    class="text-[11px] font-black uppercase tracking-[0.2em] text-blue-800 hover:text-blue-600 border-b-2 border-blue-100 pb-1 transition-all">View All Events</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                @foreach($featuredConferences as $conf)
                    <div class="group bg-white rounded-[2.5rem] border border-slate-100 shadow-[0_10px_40px_-15px_rgba(0,0,0,0.05)] hover:shadow-[0_30px_80px_-20px_rgba(30,58,138,0.15)] transition-all duration-700 overflow-hidden flex flex-col hover:-translate-y-3">
                        <div class="relative h-60 overflow-hidden">
                            @if($conf->banner_image)
                                <img src="{{ asset('storage/' . $conf->banner_image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-[#0f172a] to-blue-900 flex items-center justify-center p-8 text-center relative">
                                    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
                                    <span class="text-white font-serif font-bold text-2xl leading-tight relative z-10">{{ $conf->title }}</span>
                                </div>
                            @endif
                            <div class="absolute top-6 left-6">
                                <span class="px-4 py-1.5 bg-white/95 backdrop-blur text-blue-900 text-[10px] font-black uppercase tracking-widest rounded-full shadow-xl border border-blue-50">{{ $conf->type }}</span>
                            </div>
                        </div>
                        <div class="p-10 flex flex-col flex-grow">
                            <div class="flex items-center gap-3 mb-5 flex-wrap">
                                <span class="inline-flex items-center px-3 py-1 bg-blue-50 text-blue-700 text-[10px] font-bold rounded-lg border border-blue-100 uppercase tracking-wide">{{ $conf->category->name }}</span>
                                <span class="text-slate-200">/</span>
                                <span class="inline-flex items-center px-3 py-1 bg-slate-50 text-slate-500 text-[10px] font-bold rounded-lg border border-slate-100 uppercase tracking-wide">{{ $conf->country->name }}</span>
                            </div>
                            <h3 class="text-xl md:text-2xl font-bold text-slate-900 mb-6 line-clamp-2 group-hover:text-blue-700 transition-colors leading-tight">
                                <a href="{{ route('conferences.show', $conf->slug) }}">{{ $conf->title }}</a>
                            </h3>
                            <div class="mt-auto pt-8 border-t border-slate-50 flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-50 flex flex-col items-center justify-center border border-slate-100 group-hover:bg-blue-600 group-hover:border-blue-500 transition-colors duration-500">
                                        <span class="text-[10px] font-black text-slate-400 group-hover:text-blue-100 uppercase leading-none mb-1">{{ $conf->start_date->format('M') }}</span>
                                        <span class="text-lg font-black text-slate-900 group-hover:text-white leading-none">{{ $conf->start_date->format('d') }}</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Location</span>
                                        <span class="text-sm font-bold text-slate-700">{{ $conf->city }}, {{ $conf->country->name }}</span>
                                    </div>
                                </div>
                                <a href="{{ route('conferences.show', $conf->slug) }}" class="w-12 h-12 bg-slate-900 text-white rounded-2xl flex items-center justify-center hover:bg-blue-600 transition-all duration-500 shadow-xl shadow-slate-900/10 hover:shadow-blue-600/30">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M17 8l4 4m0 0l-4 4m4-4H3" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Community Voices (Elite Researchers) -->
    <div class="bg-white py-32 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-[1px] bg-gradient-to-r from-transparent via-slate-200 to-transparent">
        </div>

        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center mb-24">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-black uppercase tracking-[0.2em] mb-6 border border-emerald-100">
                    Trusted Worldwide
                </div>
                <h2 class="text-4xl md:text-6xl font-serif font-bold text-slate-900 mb-8 tracking-tight">Community <span
                        class="text-blue-900 italic underline decoration-blue-100 decoration-8 underline-offset-8">Voices</span>
                </h2>
                <p class="text-slate-500 text-xl font-medium leading-relaxed">Hear from the distinguished scientists and
                    researchers pushing the boundaries of discovery with HJParam.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                @php
                    $testimonials = [
                        [
                            'name' => 'Dr. Sarah Mitchell',
                            'role' => 'Senior Research Fellow',
                            'inst' => 'Oxford University',
                            'initials' => 'SM',
                            'color' => 'blue',
                            'text' => '“HJParam has revolutionized how we verify our citations. The AI detection and rigorous peer review process ensure the absolute highest standards of scientific integrity.”'
                        ],
                        [
                            'name' => 'Prof. James Chen',
                            'role' => 'Editor-in-Chief',
                            'inst' => 'Tech Science Weekly',
                            'initials' => 'JC',
                            'color' => 'emerald',
                            'text' => '“A definitive game-changer for editorial integrity. The platform’s seamless interface makes managing complex global submissions intuitive, fast, and remarkably efficient.”'
                        ],
                        [
                            'name' => 'Dr. Emily Arnet',
                            'role' => 'Assistant Professor',
                            'inst' => 'MIT Open Science',
                            'initials' => 'EA',
                            'color' => 'indigo',
                            'text' => '“Fast, reliable, and aesthetically sophisticated. The detailed authenticity reports help me improve my research impact and global visibility significantly.”'
                        ]
                    ];
                @endphp

                @foreach($testimonials as $t)
                    <div
                        class="group relative bg-slate-50 rounded-[3rem] p-12 border border-slate-100 transition-all duration-500 hover:bg-white hover:shadow-[0_30px_70px_-20px_rgba(0,0,0,0.1)] hover:-translate-y-2">
                        <div class="absolute top-8 right-10 text-slate-200 group-hover:text-blue-100 transition-colors">
                            <svg class="w-16 h-16 fill-current" viewBox="0 0 32 32">
                                <path d="M10 8v8h6v-8zm12 0v8h6v-8z" />
                            </svg>
                        </div>

                        <div class="flex items-center gap-5 mb-10 relative z-10">
                            <div
                                class="w-16 h-16 rounded-2xl bg-{{ $t['color'] }}-600 flex items-center justify-center text-white font-black text-xl shadow-lg shadow-{{ $t['color'] }}-600/20 group-hover:scale-110 transition-transform duration-500">
                                {{ $t['initials'] }}
                            </div>
                            <div>
                                <h4 class="font-bold text-lg text-slate-900 leading-tight mb-1">{{ $t['name'] }}</h4>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $t['inst'] }}</p>
                            </div>
                        </div>
                        <p class="text-slate-600 text-lg leading-relaxed italic mb-8 relative z-10">{{ $t['text'] }}</p>
                        <div
                            class="mt-auto flex items-center gap-2 text-{{ $t['color'] }}-600 font-bold text-[10px] uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-opacity">
                            Verified Researcher
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Elite Conference CTA -->
    <div class="relative py-24 overflow-hidden group/section">
        <!-- Advanced Background Engine -->
        <div class="absolute inset-0 bg-[#0f172a] z-0">
            <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width=%2260%22 height=%2260%22 viewBox=%220 0 60 60%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cpath d=%22M54.627 0l.83.83L0 55.457l-.83-.83L54.627 0zm-27.313 0l.83.83L0 28.144l-.83-.83L27.314 0zm0 27.313l.83.83L0 55.457l-.83-.83L27.314 27.313z%22 fill=%22%233b82f6%22 fill-opacity=%220.1%22 fill-rule=%22evenodd%22/%3E%3C/svg%3E');"></div>
            <div class="absolute top-0 right-0 w-2/3 h-full bg-blue-600/10 -skew-x-12 translate-x-1/4 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-1/3 h-full bg-indigo-600/10 skew-x-12 -translate-x-1/4 blur-3xl"></div>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <div
                    class="inline-flex items-center gap-2.5 px-4 py-2 bg-blue-500/10 border border-blue-500/20 rounded-full text-[10px] font-black uppercase tracking-[0.3em] text-blue-400 mb-8">
                    <span
                        class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-pulse shadow-[0_0_10px_rgba(59,130,246,0.5)]"></span>
                    Conference Partnerships
                </div>
                <h2 class="text-4xl md:text-6xl font-serif font-black text-white mb-8 tracking-tight">Catalyzing Your <span
                        class="text-blue-400">Scientific Impact</span></h2>
                <p class="text-slate-400 text-lg md:text-xl mb-12 leading-relaxed font-medium max-w-2xl mx-auto">
                    Transform your conference findings into authoritative peer-reviewed proceedings. Benefit from our
                    rigorous indexing, global visibility, and rapid editorial framework.
                </p>

                <div class="flex flex-col sm:flex-row justify-center items-center gap-6">
                    <a href="{{ route('info.page', 'proposals') }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center bg-white text-slate-900 font-black px-12 py-5 rounded-2xl hover:bg-blue-400 hover:text-white transition-all duration-500 shadow-[0_20px_50px_-10px_rgba(0,0,0,0.3)] hover:-translate-y-1.5 active:scale-95 text-base whitespace-nowrap group/btn1">
                        Submit a Proposal
                        <svg class="w-5 h-5 ml-2.5 group-hover/btn1:translate-x-1.5 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M17 8l4 4m0 0l-4 4m4-4H3" stroke-width="2.5"></path>
                        </svg>
                    </a>
                    <a href="{{ route('info.page', 'process') }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center bg-white/[0.03] backdrop-blur-md border border-white/10 text-white font-black px-12 py-5 rounded-2xl hover:bg-white/10 hover:border-white/30 transition-all duration-500 hover:-translate-y-1.5 active:scale-95 uppercase tracking-[0.2em] text-[11px] whitespace-nowrap group/btn2">
                        Review Process Discovery
                        <svg class="w-5 h-5 ml-2.5 opacity-40 group-hover/btn2:opacity-100 group-hover/btn2:rotate-90 transition-all"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M19 9l-7 7-7-7" stroke-width="2.5"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Initiatives Section -->
    <div class="bg-white py-12 border-t border-gray-200">
        <div class="container mx-auto px-4">
            <h2 class="text-center text-gray-400 text-sm font-bold uppercase tracking-widest mb-8">
                {{ $settings['partners_section_title'] ?? 'Affiliated Societies & Initiatives' }}
            </h2>
            <div class="flex flex-wrap justify-center items-center gap-12">
                @foreach($partners as $partner)
                    @if($partner->logo_path)
                        <a href="{{ $partner->url }}" target="_blank"
                            class="block transform hover:scale-110 transition-all duration-300">
                            <img src="{{ asset('storage/' . $partner->logo_path) }}" alt="{{ $partner->name }}"
                                class="h-12 w-auto object-contain">
                        </a>
                    @else
                        <a href="{{ $partner->url }}" target="_blank"
                            class="h-14 px-8 bg-slate-50 border border-slate-200/60 flex items-center justify-center font-extrabold text-slate-400 hover:text-blue-600 hover:border-blue-200 hover:bg-white rounded-2xl transition-all uppercase tracking-[0.2em] text-[10px] shadow-sm">
                            {{ $partner->name }}
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    <!-- Hero Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // 1. Counter Animation Logic
            const counters = document.querySelectorAll('.counter');
            const animateCounter = (el) => {
                const target = parseInt(el.getAttribute('data-target'));
                let count = 0;
                const speed = target / 50;
                const updateCount = () => {
                    count += speed;
                    if (count < target) {
                        el.innerText = Math.ceil(count);
                        setTimeout(updateCount, 20);
                    } else {
                        el.innerText = target;
                    }
                };
                updateCount();
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        animateCounter(entry.target);
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.5 });
            counters.forEach(counter => observer.observe(counter));

            // 2. High-Performance Dash Flow-Field (Screenshot Match)
            const canvas = document.getElementById('hero-canvas');
            const section = document.getElementById('hero-section');
            if (!canvas || !section) return;

            const ctx = canvas.getContext('2d');
            let width, height;
            let particles = [];
            const particleCount = 550; // Increased density for that rich academic look

            let mouse = { x: null, y: null };

            const init = () => {
                width = canvas.width = section.offsetWidth;
                height = canvas.height = section.offsetHeight;
                particles = [];
                for (let i = 0; i < particleCount; i++) {
                    const angle = Math.random() * Math.PI * 2;
                    const distance = Math.random() * 200 + 100;
                    particles.push({
                        x: Math.random() * width,
                        y: Math.random() * height,
                        vx: 0,
                        vy: 0,
                        speed: Math.random() * 1.5 + 0.5,
                        width: Math.random() * 10 + 6,
                        height: 2.2,
                        opacity: Math.random() * 0.6 + 0.2,
                        angle: 0
                    });
                }
            };

            const getColor = (x) => {
                // Gradient mapping: Red/Amber (left) -> Pink/Purple (middle) -> Blue/Indigo (right)
                const ratio = x / width;
                if (ratio < 0.2) return '#f43f5e'; // Rose
                if (ratio < 0.4) return '#ec4899'; // Pink
                if (ratio < 0.6) return '#a855f7'; // Purple
                if (ratio < 0.8) return '#6366f1'; // Indigo
                return '#3b82f6'; // Blue
            };

            window.addEventListener('resize', init);
            section.addEventListener('mousemove', (e) => {
                const rect = section.getBoundingClientRect();
                mouse.x = e.clientX - rect.left;
                mouse.y = e.clientY - rect.top;
            });
            section.addEventListener('mouseleave', () => { mouse.x = null; mouse.y = null; });

            let time = 0;
            const animate = () => {
                ctx.clearRect(0, 0, width, height);
                time += 0.005;

                particles.forEach(p => {
                    // Flow Field Logic (Vortex + Waves)
                    const centerX = width * 0.7; // Vortex center towards the right
                    const centerY = height * 0.5;

                    const dx = p.x - centerX;
                    const dy = p.y - centerY;
                    const dist = Math.sqrt(dx * dx + dy * dy);

                    // Spiral base angle
                    const spiral = Math.atan2(dy, dx) + Math.PI / 2;

                    // Wave noise
                    const noise = Math.sin(p.x * 0.003 + time) * 0.5;

                    p.angle = spiral + noise;

                    // Mouse Interaction (Push/Pull)
                    if (mouse.x !== null) {
                        const mdx = mouse.x - p.x;
                        const mdy = mouse.y - p.y;
                        const mdist = Math.sqrt(mdx * mdx + mdy * mdy);
                        if (mdist < 200) {
                            const force = (200 - mdist) / 200;
                            p.angle += (Math.atan2(mdy, mdx) + Math.PI / 2) * force * 0.8;
                        }
                    }

                    // Update Position
                    p.vx = Math.cos(p.angle) * p.speed;
                    p.vy = Math.sin(p.angle) * p.speed;
                    p.x += p.vx;
                    p.y += p.vy;

                    // Wrap around screen with padding
                    if (p.x < -20) p.x = width + 20;
                    if (p.x > width + 20) p.x = -20;
                    if (p.y < -20) p.y = height + 20;
                    if (p.y > height + 20) p.y = -20;

                    // Render Dash
                    ctx.save();
                    ctx.translate(p.x, p.y);
                    ctx.rotate(p.angle);
                    ctx.globalAlpha = p.opacity;
                    ctx.fillStyle = getColor(p.x);

                    ctx.beginPath();
                    // Draw a crisp rounded dash
                    const r = p.height / 2;
                    ctx.roundRect(-p.width / 2, -p.height / 2, p.width, p.height, r);
                    ctx.fill();

                    ctx.restore();
                });

                requestAnimationFrame(animate);
            };

            init();
            animate();
        });
    </script>

@endsection