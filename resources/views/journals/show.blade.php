@extends('layouts.web')
@section('title', $journal->title . ' | HJPARAM Publication')

@section('content')
    <!-- Journal Hero Banner -->
    <div class="relative overflow-hidden text-white border-b border-slate-800"
        style="background: linear-gradient(135deg, #071026 0%, #0f2757 50%, #17387a 100%);">
        
        <!-- Subtle Ambient Academic Glows -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-1/3 w-80 h-80 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-14 relative z-10">
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs font-semibold text-slate-300 mb-6 flex-wrap">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
                <span class="text-slate-500">/</span>
                <a href="{{ route('journals.index') }}" class="hover:text-white transition-colors">Journals</a>
                @if($journal->topic)
                    <span class="text-slate-500">/</span>
                    <a href="{{ route('topics.show', $journal->topic->slug) }}" class="hover:text-white transition-colors">{{ $journal->topic->name }}</a>
                @endif
                <span class="text-slate-500">/</span>
                <span class="text-blue-300 truncate max-w-xs sm:max-w-md">{{ $journal->title }}</span>
            </nav>

            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-8 lg:gap-12">
                <!-- Left Details -->
                <div class="flex-1 min-w-0">
                    <!-- Badges -->
                    <div class="flex flex-wrap items-center gap-2.5 mb-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 backdrop-blur-xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            Open Access
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-500/20 text-blue-200 border border-blue-400/30 backdrop-blur-xs">
                            <svg class="w-3.5 h-3.5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Peer-Reviewed
                        </span>
                        @if($journal->topic)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-white/10 text-slate-200 border border-white/15">
                                {{ $journal->topic->name }}
                            </span>
                        @endif
                    </div>

                    <!-- Journal Title -->
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-serif font-black text-white tracking-tight leading-snug mb-4">
                        {{ $journal->title }}
                    </h1>

                    <!-- Key Metadata Strip -->
                    <div class="flex flex-wrap items-center gap-3 sm:gap-6 text-xs sm:text-sm text-slate-300 mb-6 py-2.5 px-4 rounded-xl bg-white/5 border border-white/10 w-fit">
                        <div class="flex items-center gap-2">
                            <span class="text-slate-400 uppercase tracking-wider font-bold text-[11px]">ISSN:</span>
                            <span class="font-mono font-bold text-white">{{ $journal->issn ?: 'Assigned on Publication' }}</span>
                        </div>
                        <span class="text-white/20 hidden sm:inline">|</span>
                        <div class="flex items-center gap-2">
                            <span class="text-slate-400 uppercase tracking-wider font-bold text-[11px]">Impact Factor:</span>
                            <span class="font-bold text-amber-300">{{ $journal->impact_factor ?: 'Evaluating' }}</span>
                        </div>
                        <span class="text-white/20 hidden sm:inline">|</span>
                        <div class="flex items-center gap-2">
                            <span class="text-slate-400 uppercase tracking-wider font-bold text-[11px]">Review:</span>
                            <span class="font-medium text-slate-200">Double-Blind</span>
                        </div>
                    </div>

                    <!-- Description -->
                    <p class="text-slate-200 text-sm sm:text-base leading-relaxed mb-8 max-w-3xl line-clamp-4">
                        {{ $journal->description }}
                    </p>

                    <!-- Action CTAs -->
                    <div class="flex flex-wrap items-center gap-3.5">
                        <a href="{{ route('author.submit', ['journal_id' => $journal->id]) }}"
                            style="background: linear-gradient(135deg, #0284c7 0%, #059669 100%);"
                            class="inline-flex items-center gap-2 text-white font-bold text-sm py-3.5 px-7 rounded-xl shadow-lg hover:opacity-95 hover:scale-[1.02] active:scale-[0.98] transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            <span>Submit Manuscript</span>
                        </a>

                        <a href="#volumes"
                            class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white font-bold text-sm py-3.5 px-6 rounded-xl border border-white/20 backdrop-blur-xs transition-all">
                            <svg class="w-4 h-4 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            <span>Browse Volumes</span>
                        </a>

                        <a href="{{ route('author.download.copyright-form') }}" download
                            class="inline-flex items-center gap-2 bg-slate-900/60 hover:bg-slate-900 text-amber-300 font-semibold text-xs py-3.5 px-4 rounded-xl border border-amber-400/30 transition-all"
                            title="Download Copyright Transfer Form">
                            <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            <span>Copyright Form</span>
                        </a>
                    </div>
                </div>

                <!-- Right Journal Cover (Executive 3D Mockup) -->
                <div class="lg:w-72 flex-shrink-0 w-full flex justify-center lg:justify-end">
                    <div class="relative group cursor-pointer">
                        <!-- Book Card Frame with Spine Effect -->
                        <div class="w-56 h-80 sm:w-60 sm:h-84 rounded-2xl overflow-hidden shadow-2xl relative transition-transform duration-500 group-hover:-translate-y-1.5"
                             style="background: linear-gradient(145deg, #0b1528 0%, #172554 45%, #0f172a 100%); border: 1px solid rgba(255, 255, 255, 0.15); box-shadow: 0 20px 35px -10px rgba(0,0,0,0.6), 0 0 20px rgba(59, 130, 246, 0.2);">
                            
                            @if($journal->cover_image)
                                <img src="{{ asset('storage/' . $journal->cover_image) }}" alt="{{ $journal->title }}"
                                    class="w-full h-full object-cover">
                            @else
                                <!-- High-End Executive Hardcover Design -->
                                <div class="w-full h-full flex flex-col justify-between p-5 relative select-none">
                                    <!-- Book Spine Highlight -->
                                    <div class="absolute left-0 top-0 bottom-0 w-4 bg-gradient-to-r from-black/40 via-white/10 to-transparent pointer-events-none"></div>

                                    <!-- Top Header -->
                                    <div class="flex justify-between items-center border-b border-white/10 pb-3">
                                        <span class="text-[9px] font-black uppercase tracking-widest text-amber-400">HJPARAM PRESS</span>
                                        <span class="text-[8px] font-bold uppercase tracking-wider text-emerald-400 bg-emerald-950/80 px-1.5 py-0.5 rounded border border-emerald-500/30">OPEN</span>
                                    </div>

                                    <!-- Center Emblem -->
                                    <div class="my-auto text-center px-2">
                                        <div class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-br from-blue-600/30 to-indigo-600/30 border border-blue-400/40 flex items-center justify-center text-blue-200 font-serif font-black text-3xl shadow-inner mb-3">
                                            {{ substr($journal->title, 0, 1) }}
                                        </div>
                                        <h3 class="font-serif font-bold text-xs text-white leading-tight line-clamp-3">
                                            {{ $journal->title }}
                                        </h3>
                                        @if($journal->issn)
                                            <p class="text-[9px] font-mono text-slate-400 mt-1.5">ISSN {{ $journal->issn }}</p>
                                        @endif
                                    </div>

                                    <!-- Bottom Footer Ribbon -->
                                    <div class="pt-3 border-t border-white/10 text-center">
                                        <span class="text-[8px] font-black uppercase tracking-wider text-slate-300 block">
                                            International Research Journal
                                        </span>
                                    </div>
                                </div>
                            @endif

                            <!-- Gold Accent Top Border -->
                            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-400 via-yellow-300 to-amber-500"></div>
                        </div>

                        <!-- Drop shadow effect -->
                        <div class="w-48 h-4 bg-black/40 blur-md rounded-full mx-auto mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Sticky Sub-Navigation Bar -->
    <div class="sticky top-[76px] z-40 bg-white/95 backdrop-blur-md border-b border-slate-200 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-1 sm:gap-2 overflow-x-auto py-2.5 no-scrollbar text-xs sm:text-sm font-bold text-slate-600">
                <a href="#scope" class="px-3.5 py-1.5 rounded-lg hover:text-blue-700 hover:bg-blue-50 transition-colors whitespace-nowrap">Aims & Scope</a>
                
                <!-- Editorial Dropdown -->
                <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    <button class="px-3.5 py-1.5 rounded-lg hover:text-blue-700 hover:bg-blue-50 transition-colors whitespace-nowrap flex items-center gap-1 font-bold text-slate-600"
                            :class="{ 'text-blue-700 bg-blue-50': open }">
                        <span>Editorial</span>
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-transition.opacity.duration.150ms class="absolute top-full left-0 w-56 bg-white rounded-xl shadow-xl border border-slate-200 py-2 z-50">
                        <a href="#editorial-team" class="block px-4 py-2 text-xs font-semibold text-blue-600 hover:bg-blue-50 transition">Editorial Team</a>
                        <a href="#editorial-responsibilities" class="block px-4 py-2 text-xs font-semibold text-blue-600 hover:bg-blue-50 transition">Editorial Responsibilities</a>
                    </div>
                </div>

                <a href="#volumes" class="px-3.5 py-1.5 rounded-lg hover:text-blue-700 hover:bg-blue-50 transition-colors whitespace-nowrap">Volumes & Issues</a>
                <a href="#latest-articles" class="px-3.5 py-1.5 rounded-lg hover:text-blue-700 hover:bg-blue-50 transition-colors whitespace-nowrap">Latest Articles</a>
                <a href="{{ route('author.page', 'instructions-for-authors') }}" class="px-3.5 py-1.5 rounded-lg hover:text-blue-700 hover:bg-blue-50 transition-colors whitespace-nowrap">Guide for Authors</a>
                <a href="{{ route('policies.index') }}" class="px-3.5 py-1.5 rounded-lg hover:text-blue-700 hover:bg-blue-50 transition-colors whitespace-nowrap text-blue-600 font-extrabold">Journal Policies &rarr;</a>
            </div>
        </div>
    </div>

    <!-- Main Content Layout -->
    <div class="bg-slate-50 min-h-screen py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col lg:flex-row gap-8 items-start">
            
            <!-- Left Sidebar -->
            <aside class="w-full lg:w-80 flex-shrink-0 space-y-6">
                <!-- Navigation Menu Card -->
                <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-400 mb-3 px-2">Journal Navigation</h3>
                    <ul class="space-y-1">
                        <li>
                            <a href="#scope" class="flex items-center justify-between px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:bg-blue-50 hover:text-blue-700 transition">
                                <span class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Aims & Scope
                                </span>
                                <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </li>
                        <li>
                            <a href="#editorial-team" class="flex items-center justify-between px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:bg-blue-50 hover:text-blue-700 transition">
                                <span class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    Editorial Team
                                </span>
                                <span class="text-xs bg-slate-100 text-slate-600 font-bold px-2 py-0.5 rounded-full">{{ $board->count() }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="#editorial-responsibilities" class="flex items-center justify-between px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:bg-blue-50 hover:text-blue-700 transition">
                                <span class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    Editorial Responsibilities
                                </span>
                                <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </li>
                        <li>
                            <a href="#volumes" class="flex items-center justify-between px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:bg-blue-50 hover:text-blue-700 transition">
                                <span class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                    Volumes & Issues
                                </span>
                                <span class="text-xs bg-slate-100 text-slate-600 font-bold px-2 py-0.5 rounded-full">{{ $volumes->count() }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('author.page', 'instructions-for-authors') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:bg-blue-50 hover:text-blue-700 transition">
                                <span class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    Guide for Authors
                                </span>
                                <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('policies.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:bg-blue-50 hover:text-blue-700 transition">
                                <span class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    Journal Policies
                                </span>
                                <span class="text-xs bg-blue-100 text-blue-700 font-bold px-2 py-0.5 rounded-full">7</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Author Quick Downloads Card -->
                <div class="bg-gradient-to-br from-slate-900 to-blue-950 text-white rounded-2xl p-5 shadow-sm border border-slate-800">
                    <h3 class="text-xs font-black uppercase tracking-wider text-amber-400 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        <span>Author Documents</span>
                    </h3>
                    <p class="text-xs text-slate-300 mb-4">Official publication templates and submission documents for authors.</p>
                    
                    <div class="space-y-2">
                        <a href="{{ route('author.download.copyright-form') }}" download
                            class="flex items-center justify-between p-3 rounded-xl bg-white/10 hover:bg-white/20 border border-white/10 transition text-xs font-bold text-white group">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-amber-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Copyright Transfer Form
                            </span>
                            <span class="text-[10px] uppercase font-mono bg-amber-400/20 text-amber-300 px-2 py-0.5 rounded">.DOC</span>
                        </a>

                        <a href="{{ route('author.download.article-template') }}" download
                            class="flex items-center justify-between p-3 rounded-xl bg-white/10 hover:bg-white/20 border border-white/10 transition text-xs font-bold text-white group">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Manuscript Template
                            </span>
                            <span class="text-[10px] uppercase font-mono bg-blue-400/20 text-blue-300 px-2 py-0.5 rounded">.DOC</span>
                        </a>
                    </div>
                </div>

                <!-- Latest Issue Spotlight -->
                <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xs font-black uppercase tracking-wider text-slate-400">Latest Issue</h3>
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    </div>

                    @if($latestIssue)
                        <div class="p-3.5 bg-blue-50/60 rounded-xl border border-blue-100 mb-4">
                            <p class="text-xs font-bold text-blue-900">
                                Volume {{ $latestIssue->volume->volume_number }}, Issue {{ $latestIssue->issue_number }}
                            </p>
                            <p class="text-[11px] text-slate-500 mt-0.5">
                                {{ ($latestIssue->volume->month ? $latestIssue->volume->month . ' ' : '') . \Carbon\Carbon::parse($latestIssue->publication_date)->format('Y') }}
                            </p>
                        </div>

                        <div class="space-y-3">
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Featured Articles</p>
                            @forelse($articles->take(3) as $article)
                                <div class="border-b border-slate-100 pb-2.5 last:border-0 last:pb-0">
                                    <a href="{{ route('articles.show', ['journalSlug' => $journal->slug, 'articleSlug' => $article->slug]) }}"
                                        class="text-xs font-semibold text-slate-800 hover:text-blue-600 block line-clamp-2 transition-colors">
                                        {{ $article->title }}
                                    </a>
                                </div>
                            @empty
                                <p class="text-xs text-slate-400 italic">Articles undergoing indexing.</p>
                            @endforelse
                        </div>
                    @else
                        <div class="text-center py-6 text-slate-400">
                            <svg class="w-8 h-8 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            <p class="text-xs font-medium">Inaugural Issue in preparation</p>
                        </div>
                    @endif
                </div>

                <!-- Journal Metrics Card -->
                <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs text-xs space-y-3">
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-400 mb-2">Publishing Metrics</h3>
                    <div class="flex justify-between items-center py-1.5 border-b border-slate-100">
                        <span class="text-slate-500">Peer Review</span>
                        <span class="font-bold text-slate-800">Double-Blind</span>
                    </div>
                    <div class="flex justify-between items-center py-1.5 border-b border-slate-100">
                        <span class="text-slate-500">Initial Decision</span>
                        <span class="font-bold text-slate-800">18 - 24 Days</span>
                    </div>
                    <div class="flex justify-between items-center py-1.5 border-b border-slate-100">
                        <span class="text-slate-500">Publication License</span>
                        <span class="font-bold text-slate-800">CC-BY 4.0</span>
                    </div>
                    <div class="flex justify-between items-center py-1.5">
                        <span class="text-slate-500">Archiving Status</span>
                        <span class="font-bold text-emerald-600">CrossMark & LOCKSS</span>
                    </div>
                </div>
            </aside>

            <!-- Main Content Area -->
            <main class="flex-1 min-w-0 space-y-10">
                <!-- Aims & Scope -->
                <section id="scope" class="scroll-mt-32 bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-xs">
                    <div class="flex items-center gap-3 border-b border-slate-100 pb-4 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h2 class="text-xl sm:text-2xl font-serif font-black text-slate-900 tracking-tight">Aims & Scope</h2>
                            <p class="text-xs text-slate-500">Editorial focus and subject domains covered by this journal</p>
                        </div>
                    </div>

                    <div class="prose max-w-none text-slate-700 text-sm sm:text-base leading-relaxed space-y-4">
                        @if($journal->aims_and_scope)
                            {!! nl2br(e($journal->aims_and_scope)) !!}
                        @else
                            <p>{{ $journal->description }}</p>
                            <p>The journal welcomes original research papers, systematic reviews, case analyses, and short communications spanning theoretical and empirical academic contributions.</p>
                        @endif
                    </div>
                </section>

                <!-- Editorial Team -->
                <section id="editorial-team" class="scroll-mt-32 bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-xs">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            <div>
                                <h2 class="text-xl sm:text-2xl font-serif font-black text-slate-900 tracking-tight">Editorial Team</h2>
                                <p class="text-xs text-slate-500">Distinguished international scholars, editors, and peer reviewers</p>
                            </div>
                        </div>
                        <a href="{{ route('author.page', 'instructions-for-authors') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 hidden sm:inline">
                            Join Board &rarr;
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse($board as $member)
                            <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-white hover:shadow-xs transition flex items-start gap-4">
                                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-700 to-indigo-800 text-white font-serif font-bold text-xl flex items-center justify-center flex-shrink-0 shadow-xs overflow-hidden">
                                    @if($member->photo)
                                        <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                                    @else
                                        {{ substr($member->name, 0, 1) }}
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold 
                                        @if(str_contains(strtolower($member->role), 'chief')) bg-amber-100 text-amber-900 border border-amber-300
                                        @elseif(str_contains(strtolower($member->role), 'associate')) bg-blue-100 text-blue-900 border border-blue-300
                                        @else bg-slate-100 text-slate-700 border border-slate-200 @endif mb-1">
                                        {{ $member->role }}
                                    </span>
                                    <h4 class="font-bold text-sm text-slate-900 truncate">{{ $member->name }}</h4>
                                    <p class="text-xs text-slate-500 line-clamp-2 mt-0.5">{{ $member->affiliation }}</p>
                                    @if($member->email)
                                        <p class="text-[11px] text-blue-600 font-mono mt-1">{{ $member->email }}</p>
                                    @endif
                                    @if($member->bio)
                                        <p class="text-[11px] text-slate-400 italic line-clamp-2 mt-1">"{{ $member->bio }}"</p>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-8 text-center bg-slate-50 rounded-xl border border-dashed border-slate-200">
                                <p class="text-sm font-semibold text-slate-600">Editorial board appointments in progress.</p>
                                <p class="text-xs text-slate-400 mt-1">Academicians interested in joining the editorial board can apply via the portal.</p>
                            </div>
                        @endforelse
                    </div>
                </section>

                <!-- Editorial Responsibilities -->
                <section id="editorial-responsibilities" class="scroll-mt-32 bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-xs">
                    <div class="flex items-center gap-3 border-b border-slate-100 pb-4 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <div>
                            <h2 class="text-xl sm:text-2xl font-serif font-black text-slate-900 tracking-tight">Editorial Responsibilities</h2>
                            <p class="text-xs text-slate-500">Ethical duties, peer review governance, and editorial code of conduct</p>
                        </div>
                    </div>

                    <div class="prose max-w-none text-slate-700 text-sm leading-relaxed space-y-4">
                        @if($journal->editorial_responsibilities)
                            {!! nl2br(e($journal->editorial_responsibilities)) !!}
                        @else
                            <div class="space-y-4">
                                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                                    <h4 class="font-bold text-slate-900 text-sm mb-1">1. Publication Decisions & Editorial Independence</h4>
                                    <p class="text-xs text-slate-600">The Editor-in-Chief and Editorial Board are exclusively responsible for deciding which of the submitted articles should be published, guided by the policies of the journal's editorial board and constrained by legal requirements regarding libel, copyright infringement, and plagiarism.</p>
                                </div>
                                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                                    <h4 class="font-bold text-slate-900 text-sm mb-1">2. Fair Review & Objectivity</h4>
                                    <p class="text-xs text-slate-600">Manuscripts are evaluated purely on their scholarly merit and empirical integrity, without regard to the authors' institutional affiliation, race, gender, sexual orientation, religious belief, ethnic origin, or political philosophy.</p>
                                </div>
                                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                                    <h4 class="font-bold text-slate-900 text-sm mb-1">3. Confidentiality & Conflicts of Interest</h4>
                                    <p class="text-xs text-slate-600">Editors and editorial staff must not disclose any information about a submitted manuscript to anyone other than the corresponding author, reviewers, potential reviewers, and the publisher. Unpublished materials must not be used in an editor's own research without express written consent.</p>
                                </div>
                                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                                    <h4 class="font-bold text-slate-900 text-sm mb-1">4. Vigilance over the Published Record</h4>
                                    <p class="text-xs text-slate-600">Editors will take all reasonable steps to identify and prevent the publication of papers where research misconduct has occurred. In the event of confirmed misconduct, appropriate corrections, retractions, or apologies will be promptly published.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </section>

                <!-- Volumes & Issues -->
                <section id="volumes" class="scroll-mt-32 bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-xs">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            </div>
                            <div>
                                <h2 class="text-xl sm:text-2xl font-serif font-black text-slate-900 tracking-tight">Volumes & Issues</h2>
                                <p class="text-xs text-slate-500">Access published archives and full-text collections</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @forelse($volumes as $volume)
                            <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50/50 hover:bg-white hover:border-blue-300 transition-all">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
                                    <div class="flex items-center gap-2.5">
                                        <h3 class="font-serif font-bold text-base text-slate-900">
                                            Volume {{ $volume->volume_number }}
                                        </h3>
                                        <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-800">
                                            {{ ($volume->month ? $volume->month . ' ' : '') . $volume->year }}
                                        </span>
                                    </div>
                                    <span class="text-xs text-slate-500 font-medium">
                                        {{ $volume->issues->count() }} {{ Str::plural('Issue', $volume->issues->count()) }}
                                    </span>
                                </div>

                                <div class="flex flex-wrap gap-2.5">
                                    @forelse($volume->issues as $issue)
                                        <a href="{{ route('journals.issue', [$journal->slug, $volume->volume_number, $issue->issue_number]) }}"
                                            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold bg-white hover:bg-blue-600 hover:text-white text-blue-900 border border-slate-200 hover:border-blue-600 shadow-xs hover:shadow transition-all group">
                                            <span>Issue {{ $issue->issue_number }}</span>
                                            <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        </a>
                                    @empty
                                        <span class="text-xs text-slate-400 italic">No issues published in this volume yet.</span>
                                    @endforelse
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                                <p class="text-sm font-bold text-slate-600">No volumes published yet.</p>
                                <p class="text-xs text-slate-400 mt-1">Manuscripts submitted now will appear in Volume 1, Issue 1.</p>
                                <a href="{{ route('author.submit', ['journal_id' => $journal->id]) }}"
                                    class="inline-flex items-center gap-2 mt-4 px-5 py-2 bg-blue-600 text-white text-xs font-bold rounded-xl hover:bg-blue-700 transition">
                                    Submit First Paper &rarr;
                                </a>
                            </div>
                        @endforelse
                    </div>
                </section>

                <!-- Latest Articles Section -->
                <section id="latest-articles" class="scroll-mt-32 bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-xs">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </div>
                            <div>
                                <h2 class="text-xl sm:text-2xl font-serif font-black text-slate-900 tracking-tight">Recent Published Articles</h2>
                                <p class="text-xs text-slate-500">Peer-reviewed open access papers from current releases</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @forelse($articles as $article)
                            <div class="p-5 rounded-2xl border border-slate-200 bg-white hover:border-blue-300 hover:shadow-xs transition">
                                <div class="flex items-center gap-2 text-[11px] text-slate-500 mb-2">
                                    <span class="font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded">Article</span>
                                    <span>•</span>
                                    <span>{{ \Carbon\Carbon::parse($article->publication_date ?? $article->created_at)->format('M d, Y') }}</span>
                                    @if($article->doi)
                                        <span>•</span>
                                        <span class="font-mono text-blue-600">DOI: {{ $article->doi }}</span>
                                    @endif
                                </div>
                                <h3 class="font-serif font-bold text-base text-slate-900 hover:text-blue-700 mb-2">
                                    <a href="{{ route('articles.show', ['journalSlug' => $journal->slug, 'articleSlug' => $article->slug]) }}">
                                        {{ $article->title }}
                                    </a>
                                </h3>
                                @if($article->authors && $article->authors->count() > 0)
                                    <p class="text-xs text-slate-600 mb-3">
                                        {{ $article->authors->pluck('name')->join(', ') }}
                                    </p>
                                @endif
                                <div class="flex items-center gap-3 pt-2 border-t border-slate-100">
                                    <a href="{{ route('articles.show', ['journalSlug' => $journal->slug, 'articleSlug' => $article->slug]) }}"
                                        class="text-xs font-bold text-blue-600 hover:text-blue-800">
                                        Read Full Article &rarr;
                                    </a>
                                    @if($article->pdf_path)
                                        <a href="{{ route('articles.download', $article->id) }}"
                                            class="text-xs font-bold text-slate-500 hover:text-slate-800 flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            PDF
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                                <p class="text-sm font-semibold text-slate-600">No articles published in current issue.</p>
                                <p class="text-xs text-slate-400 mt-1">Accepted articles appear here following copyediting and XML conversion.</p>
                            </div>
                        @endforelse
                    </div>
                </section>

                <!-- Policies Quick Reference Banner -->
                <section class="bg-gradient-to-r from-blue-900 to-indigo-950 text-white rounded-2xl p-6 sm:p-8 shadow-sm relative overflow-hidden">
                    <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                        <div>
                            <span class="text-amber-400 text-xs font-extrabold uppercase tracking-wider block mb-1">Open Access & Ethics</span>
                            <h3 class="text-xl font-serif font-black text-white tracking-tight mb-2">Publish with HJPARAM Academic Integrity</h3>
                            <p class="text-xs sm:text-sm text-slate-200 max-w-xl">
                                All submissions undergo rigorous double-blind peer review, plagiarism screening, CrossMark verification, and permanent digital preservation.
                            </p>
                        </div>
                        <div class="flex items-center gap-3 flex-shrink-0">
                            <a href="{{ route('policies.index') }}"
                                class="px-5 py-2.5 bg-white text-blue-900 font-bold text-xs rounded-xl shadow hover:bg-slate-100 transition">
                                View Policies
                            </a>
                            <a href="{{ route('author.submit', ['journal_id' => $journal->id]) }}"
                                class="px-5 py-2.5 bg-amber-500 text-slate-900 font-bold text-xs rounded-xl shadow hover:bg-amber-400 transition">
                                Submit Paper
                            </a>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </div>
@endsection