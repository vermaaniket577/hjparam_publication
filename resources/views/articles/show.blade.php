@extends('layouts.web')
@section('title', $article->title . ' | HJPARAM Publication')

@section('content')
    <div class="bg-white min-h-screen">
        <!-- Scholarly Header Layer -->
        <div class="bg-slate-50 border-b border-slate-200/60 pb-16 pt-24">
            <div class="container mx-auto px-6 max-w-7xl">
                <div class="space-y-8">
                    {{-- Breadcrumb & Category --}}
                    <div
                        class="flex items-center gap-3 text-[10px] font-black uppercase tracking-[0.2em] text-blue-600 mb-6">
                        <a href="{{ route('journals.show', $article->journal->slug) }}"
                            class="hover:text-blue-800 transition-colors">{{ $article->journal->title }}</a>
                        <span class="text-slate-300">/</span>
                        <span class="text-slate-500">Research Article</span>
                    </div>

                    <h1
                        class="text-3xl lg:text-5xl font-serif font-black text-slate-900 leading-[1.15] mb-8 tracking-tight max-w-5xl">
                        {{ $article->title }}
                    </h1>

                    {{-- Author Ecosystem --}}
                    <div class="flex flex-wrap gap-x-6 gap-y-4 items-center">
                        @foreach($article->authors as $author)
                            <div class="flex items-center gap-2.5 group relative cursor-help" x-data="{ open: false }">
                                <span @mouseenter="open = true" @mouseleave="open = false"
                                    class="text-base font-bold text-slate-800 border-b border-blue-200 hover:border-blue-600 hover:text-blue-700 transition-all duration-300">
                                    {{ $author->name }}@if($author->is_corresponding)<sup
                                    class="text-blue-500 ml-0.5">✉</sup>@endif
                                </span>

                                {{-- Smart Affiliation Tooltip --}}
                                <div x-show="open" x-transition.opacity
                                    class="absolute bottom-full left-0 mb-3 w-72 p-4 bg-slate-900 text-white rounded-2xl shadow-2xl z-50 text-xs leading-relaxed border border-white/10 backdrop-blur-xl">
                                    <div class="font-black uppercase tracking-widest text-[9px] text-blue-400 mb-2">Academic
                                        Affiliation</div>
                                    {{ $author->affiliation }}
                                    <div class="mt-3 pt-3 border-t border-white/5 flex items-center justify-between">
                                        <span class="text-[9px] text-slate-400">ID:
                                            {{ substr(md5($author->name), 0, 8) }}</span>
                                        @if($author->is_corresponding)
                                            <span class="text-[9px] font-bold text-blue-400 uppercase">Corresponding Author</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if(!$loop->last) <span class="text-slate-300 font-light text-xl">/</span> @endif
                        @endforeach
                    </div>

                    {{-- Article Registry Data --}}
                    <div class="flex flex-wrap gap-8 pt-8 border-t border-slate-200/60 mt-8">
                        <div>
                            <span class="block text-[9px] font-black uppercase tracking-widest text-slate-400 mb-1">Digital
                                Object Identifier</span>
                            <a href="https://doi.org/{{ $article->doi }}" target="_blank"
                                class="text-sm font-mono font-bold text-blue-600 hover:text-blue-800 transition-colors">{{ $article->doi }}</a>
                        </div>
                        <div>
                            <span class="block text-[9px] font-black uppercase tracking-widest text-slate-400 mb-1">Journal
                                Registry</span>
                            <span class="text-sm font-bold text-slate-700">Vol.
                                {{ $article->issue->volume->volume_number }}, No. {{ $article->issue->issue_number }}
                                ({{ $article->issue->publication_date->format('Y') }})</span>
                        </div>
                        <div>
                            <span
                                class="block text-[9px] font-black uppercase tracking-widest text-slate-400 mb-1">Chronology</span>
                            <span class="text-sm font-bold text-slate-700">Received:
                                {{ $article->submitted_at->format('M d, Y') }} | Published:
                                {{ $article->published_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Body: Architecture of Knowledge -->
        <div class="container mx-auto px-6 max-w-7xl py-16">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">

                <!-- Left Column: Knowledge Feed (8 cols) -->
                <div class="lg:col-span-8 space-y-16">
                    {{-- Abstract --}}
                    <section class="prose prose-slate max-w-none">
                        <h2
                            class="text-xl font-black uppercase tracking-[0.2em] text-slate-900 flex items-center gap-4 mb-8">
                            <span class="w-8 h-1 bg-blue-600"></span>
                            Abstract
                        </h2>
                        <div
                            class="text-lg text-slate-700 font-serif leading-relaxed italic border-l-4 border-blue-50/50 pl-8 py-2">
                            {{ $article->abstract }}
                        </div>
                    </section>

                    {{-- Keywords --}}
                    <section>
                        <h2 class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 mb-6">Subject Descriptors
                        </h2>
                        <div class="flex flex-wrap gap-2.5">
                            @foreach(explode(',', $article->keywords) as $keyword)
                                <span
                                    class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-white hover:border-blue-300 hover:text-blue-600 transition-all cursor-default">
                                    {{ trim($keyword) }}
                                </span>
                            @endforeach
                        </div>
                    </section>

                    {{-- Citation Engine (Elite Glassmorphism) --}}
                    <section class="bg-[#0f172a] rounded-[2.5rem] p-10 relative overflow-hidden group shadow-2xl" x-data="{ 
                                style: 'apa', 
                                text: { 
                                    apa: '{{ $article->apa_citation }}', 
                                    mla: '{{ $article->mla_citation }}' 
                                },
                                copied: false,
                                copy() {
                                    navigator.clipboard.writeText(this.text[this.style]);
                                    this.copied = true;
                                    setTimeout(() => this.copied = false, 2000);
                                }
                            }">
                        <div
                            class="absolute inset-0 bg-blue-600/5 rotate-12 translate-x-1/2 scale-150 blur-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-1000">
                        </div>

                        <div class="relative z-10">
                            <div class="flex justify-between items-center mb-10">
                                <h3 class="text-white text-lg font-bold flex items-center gap-3">
                                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"
                                            stroke-width="2"></path>
                                    </svg>
                                    Technical Citation
                                </h3>
                                <div class="flex bg-white/5 p-1 rounded-xl border border-white/5">
                                    <button @click="style = 'apa'"
                                        :class="style === 'apa' ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:text-white'"
                                        class="px-4 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all">APA</button>
                                    <button @click="style = 'mla'"
                                        :class="style === 'mla' ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:text-white'"
                                        class="px-4 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all">MLA</button>
                                </div>
                            </div>

                            <div
                                class="bg-white/[0.03] border border-white/10 rounded-2xl p-6 mb-8 min-h-[100px] flex items-center">
                                <p class="text-slate-300 text-sm font-medium leading-relaxed select-all"
                                    x-text="text[style]"></p>
                            </div>

                            <div class="flex items-center gap-6">
                                <button @click="copy()"
                                    class="flex items-center gap-2.5 text-blue-400 hover:text-white text-xs font-black uppercase tracking-widest transition-all">
                                    <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2"
                                            stroke-width="2"></path>
                                    </svg>
                                    <svg x-show="copied" class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path d="M5 13l4 4L19 7" stroke-width="3"></path>
                                    </svg>
                                    <span x-text="copied ? 'Registry Copied' : 'Copy Reference'"></span>
                                </button>
                                <a href="{{ route('articles.bibtex', $article->id) }}"
                                    class="flex items-center gap-2.5 text-slate-500 hover:text-white text-xs font-black uppercase tracking-widest transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                            stroke-width="2"></path>
                                    </svg>
                                    BibTeX Registry
                                </a>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Right Column: Institutional Hub (4 cols) -->
                <div class="lg:col-span-4 space-y-12">
                    {{-- Primary Actions --}}
                    <div class="space-y-4">
                        <a href="{{ route('articles.download', $article->id) }}"
                            class="w-full h-20 bg-blue-600 hover:bg-blue-700 text-white rounded-[1.25rem] flex items-center justify-center gap-4 transition-all duration-500 shadow-2xl shadow-blue-600/20 hover:-translate-y-1 group">
                            <svg class="w-7 h-7 group-hover:translate-y-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-width="2.5">
                                </path>
                            </svg>
                            <span class="text-lg font-black uppercase tracking-widest">Download Full-Text</span>
                        </a>
                        <div class="text-[10px] text-center font-black uppercase tracking-[0.3em] text-slate-400">Scholarly
                            Open Access PDF</div>
                    </div>

                    {{-- Metrics Dashboard --}}
                    <div class="p-8 bg-slate-50 rounded-[2.5rem] border border-slate-200/60">
                        <h4
                            class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 mb-8 flex items-center gap-3">
                            <span class="w-1.5 h-1.5 bg-blue-600 rounded-full"></span>
                            Academic Impact
                        </h4>
                        <div class="grid grid-cols-2 gap-6">
                            <div class="p-6 bg-white rounded-3xl border border-slate-200/40 shadow-sm">
                                <div class="text-3xl font-serif font-black text-slate-900 mb-1">
                                    {{ number_format($article->views_count) }}</div>
                                <div class="text-[9px] font-black uppercase tracking-widest text-slate-400">Full Views</div>
                            </div>
                            <div class="p-6 bg-white rounded-3xl border border-slate-200/40 shadow-sm">
                                <div class="text-3xl font-serif font-black text-slate-900 mb-1">
                                    {{ number_format($article->downloads_count) }}</div>
                                <div class="text-[9px] font-black uppercase tracking-widest text-slate-400">Total Downloads
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Scholarly Navigation --}}
                    <div class="p-8 bg-slate-900 rounded-[2.5rem] border border-white/5 space-y-8">
                        <h4 class="text-white text-[10px] font-black uppercase tracking-[0.3em] flex items-center gap-3">
                            <span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span>
                            Information Ecosystem
                        </h4>
                        <ul class="space-y-4">
                            @php
                                $ecosystem = [
                                    ['label' => 'Author Guidelines', 'url' => route('author.guidelines')],
                                    ['label' => 'Peer Review Process', 'url' => route('info.page', 'reviewers')],
                                    ['label' => 'Editorial Ethics', 'url' => route('info.page', 'editors')],
                                    ['label' => 'Librarian Resources', 'url' => route('info.page', 'librarians')],
                                    ['label' => 'Institutional Sales', 'url' => route('info.page', 'publishers')]
                                ];
                            @endphp
                            @foreach($ecosystem as $link)
                                <li>
                                    <a href="{{ $link['url'] }}"
                                        class="text-[13px] font-bold text-slate-400 hover:text-white flex items-center group transition-colors">
                                        <span
                                            class="w-0 group-hover:w-4 h-px bg-blue-500 mr-0 group-hover:mr-3 transition-all"></span>
                                        {{ $link['label'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection