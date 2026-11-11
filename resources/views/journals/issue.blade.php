@extends('layouts.web')
@section('title', $journal->title . ' - Vol. ' . $volume->volume_number . ', Issue ' . $issue->issue_number . ' (' . ($volume->month ?: \Carbon\Carbon::parse($issue->publication_date)->format('F')) . ' ' . ($volume->year ?: \Carbon\Carbon::parse($issue->publication_date)->format('Y')) . ') | HJPARAM')

@section('content')
    @php
        $issueMonth = $volume->month ?: ($issue->publication_date ? \Carbon\Carbon::parse($issue->publication_date)->format('F') : 'Regular');
        $issueYear = $volume->year ?: ($issue->publication_date ? \Carbon\Carbon::parse($issue->publication_date)->format('Y') : date('Y'));
    @endphp

    <div class="bg-gradient-to-br from-slate-950 via-blue-950 to-slate-900 text-white py-12 border-b border-slate-800">
        <div class="container mx-auto px-4 max-w-6xl">
            <nav class="flex text-xs text-blue-300 font-semibold mb-6 flex-wrap" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2">
                    <li><a href="{{ route('journals.index') }}" class="hover:text-white transition">Journals</a></li>
                    <li><svg class="w-4 h-4 text-slate-500" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"></path></svg></li>
                    <li><a href="{{ route('journals.show', $journal->slug) }}" class="hover:text-white transition">{{ $journal->title }}</a></li>
                    <li><svg class="w-4 h-4 text-slate-500" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"></path></svg></li>
                    <li class="text-white font-medium">Vol. {{ $volume->volume_number }}, Issue No. {{ $issue->issue_number }} ({{ $issueMonth }} {{ $issueYear }})</li>
                </ol>
            </nav>

            <!-- Journal Title & Issue Header (Official ISSN Archive Requirement) -->
            <div class="space-y-2">
                <span class="inline-block px-3 py-1 text-xs font-bold rounded-full bg-blue-500/20 text-blue-300 border border-blue-400/30">
                    Official Archive Issue
                </span>
                <p class="text-sm font-bold text-slate-300 uppercase tracking-widest">
                    Journal: <span class="text-white font-serif font-black">{{ $journal->title }}</span>
                </p>
                <h1 class="text-2xl md:text-4xl font-serif font-black text-white leading-tight">
                    Volume {{ $volume->volume_number }}, Issue No. {{ $issue->issue_number }}
                </h1>
                <div class="flex flex-wrap items-center gap-4 text-xs sm:text-sm text-slate-300 pt-2">
                    <span class="flex items-center gap-1.5 font-bold text-amber-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Period: {{ $issueMonth }} {{ $issueYear }}
                    </span>
                    <span>•</span>
                    <span class="font-medium text-slate-300">
                        Release Date: {{ \Carbon\Carbon::parse($issue->publication_date)->format('F d, Y') }}
                    </span>
                    <span>•</span>
                    <span class="font-mono text-slate-300">
                        ISSN: {{ $journal->issn ?: 'Applied (Online)' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-12 max-w-6xl">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar: Journal Details & ISSN Metadata -->
            <div class="lg:w-1/3 flex-shrink-0">
                <div class="bg-white p-6 rounded-2xl shadow-xs border border-slate-200 sticky top-24 space-y-4 text-xs">
                    <h3 class="font-black text-sm uppercase tracking-wider text-slate-900 border-b border-slate-100 pb-3">
                        Journal Information
                    </h3>

                    <div class="space-y-3 text-slate-700">
                        <div>
                            <span class="text-[10px] uppercase text-slate-400 font-bold block mb-0.5">Journal Title</span>
                            <a href="{{ route('journals.show', $journal->slug) }}" class="text-blue-700 font-bold hover:underline text-sm block">
                                {{ $journal->title }}
                            </a>
                        </div>

                        <div class="grid grid-cols-2 gap-2 pt-1 border-t border-slate-100">
                            <div>
                                <span class="text-[10px] uppercase text-slate-400 font-bold block mb-0.5">Volume</span>
                                <span class="font-bold text-slate-900">Vol. {{ $volume->volume_number }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase text-slate-400 font-bold block mb-0.5">Issue No.</span>
                                <span class="font-bold text-slate-900">Issue {{ $issue->issue_number }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2 pt-1 border-t border-slate-100">
                            <div>
                                <span class="text-[10px] uppercase text-slate-400 font-bold block mb-0.5">Month</span>
                                <span class="font-bold text-slate-900">{{ $issueMonth }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase text-slate-400 font-bold block mb-0.5">Year</span>
                                <span class="font-bold text-slate-900">{{ $issueYear }}</span>
                            </div>
                        </div>

                        <div class="pt-1 border-t border-slate-100">
                            <span class="text-[10px] uppercase text-slate-400 font-bold block mb-0.5">Format</span>
                            <span class="font-medium text-slate-800">{{ $journal->effective_format }}</span>
                        </div>

                        <div class="pt-1 border-t border-slate-100">
                            <span class="text-[10px] uppercase text-slate-400 font-bold block mb-0.5">Frequency</span>
                            <span class="font-medium text-slate-800">{{ $journal->effective_frequency }}</span>
                        </div>

                        <div class="pt-1 border-t border-slate-100">
                            <span class="text-[10px] uppercase text-slate-400 font-bold block mb-0.5">Language</span>
                            <span class="font-medium text-slate-800">{{ $journal->effective_language }}</span>
                        </div>

                        <div class="pt-1 border-t border-slate-100">
                            <span class="text-[10px] uppercase text-slate-400 font-bold block mb-0.5">Starting Year</span>
                            <span class="font-medium text-slate-800">{{ $journal->effective_starting_year }}</span>
                        </div>

                        <div class="pt-1 border-t border-slate-100">
                            <span class="text-[10px] uppercase text-slate-400 font-bold block mb-0.5">Publisher</span>
                            <span class="font-medium text-slate-800">{{ $journal->effective_publisher }}</span>
                        </div>

                        <div class="pt-1 border-t border-slate-100">
                            <span class="text-[10px] uppercase text-slate-400 font-bold block mb-0.5">ISSN</span>
                            <span class="font-mono font-bold text-emerald-700">{{ $journal->issn ?: 'Applied (Online)' }}</span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-200 space-y-2">
                        <a href="{{ route('author.submit', ['journal_id' => $journal->id]) }}" 
                           class="block text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded-xl shadow-xs transition">
                            Submit Manuscript
                        </a>
                        <a href="{{ route('journals.show', $journal->slug) }}#volumes" 
                           class="block text-center bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-2 px-4 rounded-xl transition">
                            &larr; Back to All Issues
                        </a>
                    </div>
                </div>
            </div>

            <!-- Content Area: Article List -->
            <div class="lg:w-2/3 flex-grow">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-200 pb-4 mb-6">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Table of Contents</h2>
                        <p class="text-xs text-slate-500">
                            {{ $journal->title }} &bull; Vol. {{ $volume->volume_number }}, Issue No. {{ $issue->issue_number }} ({{ $issueMonth }} {{ $issueYear }})
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold px-3 py-1 rounded-full bg-blue-50 text-blue-800 border border-blue-200">
                            {{ $articles->count() }} Articles Published
                        </span>
                        @if($articles->count() >= 5)
                            <span class="text-xs font-bold px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200">
                                Complete Issue ✓
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="space-y-10">
                    @forelse($articles as $article)
                        <article class="group">
                            <div class="flex flex-wrap items-center gap-2 mb-2 text-xs font-bold uppercase tracking-wider text-blue-600">
                                <span class="bg-blue-50 px-2 py-0.5 rounded">Research Article</span>
                                <span>DOI: <a href="https://doi.org/{{ $article->doi }}" class="hover:underline transition">{{ $article->doi }}</a></span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition mb-3">
                                <a href="{{ route('articles.show', [$journal->slug, $article->slug]) }}">{{ $article->title }}</a>
                            </h3>
                            <div class="text-sm text-gray-600 mb-4">
                                @foreach($article->authors as $author)
                                    <span class="hover:text-blue-600 cursor-default">{{ $author->name }}{{ !$loop->last ? ', ' : '' }}</span>
                                @endforeach
                            </div>
                            <p class="text-gray-700 text-sm line-clamp-3 mb-4 leading-relaxed">
                                {{ $article->abstract }}
                            </p>
                            <div class="flex gap-4 items-center">
                            <div class="flex flex-wrap gap-2 items-center">
                                <a href="{{ route('articles.show', [$journal->slug, $article->slug]) }}#abstract" class="inline-flex items-center justify-center text-[10px] font-bold text-slate-400 hover:text-blue-900 uppercase tracking-widest border border-slate-100 px-3 py-1.5 rounded-full hover:border-blue-200 transition-all leading-none">
                                    Abstract
                                </a>
                                <a href="{{ route('articles.show', [$journal->slug, $article->slug]) }}" class="inline-flex items-center justify-center text-[10px] font-bold text-slate-400 hover:text-blue-900 uppercase tracking-widest border border-slate-100 px-3 py-1.5 rounded-full hover:border-blue-200 transition-all leading-none">
                                    Full-Text
                                </a>
                                <a href="{{ route('articles.download', $article->id) }}" class="inline-flex items-center justify-center text-[10px] font-bold text-blue-900 uppercase tracking-widest border border-blue-100 bg-blue-50 px-3 py-1.5 rounded-full hover:bg-blue-600 hover:text-white transition-all leading-none">
                                    Download PDF
                                </a>
                                <div class="flex items-center gap-4 text-xs text-gray-400 ml-auto">
                                    <span class="flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>{{ $article->views_count }}</span>
                                    <span class="flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>{{ $article->downloads_count }}</span>
                                </div>
                            </div>
                            </div>
                        </article>
                    @empty
                        <div class="py-12 text-center text-gray-500 italic bg-gray-50 rounded-lg border-2 border-dashed border-gray-200">
                            No research articles have been published in this issue yet.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
