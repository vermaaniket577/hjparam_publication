@extends('layouts.web')
@section('title', $journal->title . ' - Vol ' . $volume->volume_number . ' Issue ' . $issue->issue_number . ' | HJPARAM')

@section('content')
    <div class="bg-blue-900 text-white py-12">
        <div class="container mx-auto px-4">
            <nav class="flex text-sm text-blue-200 mb-6" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2">
                    <li><a href="{{ route('journals.index') }}" class="hover:text-white transition">Journals</a></li>
                    <li><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"></path></svg></li>
                    <li><a href="{{ route('journals.show', $journal->slug) }}" class="hover:text-white transition">{{ $journal->title }}</a></li>
                    <li><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"></path></svg></li>
                    <li class="text-white font-medium">Vol {{ $volume->volume_number }}, Issue {{ $issue->issue_number }}</li>
                </ol>
            </nav>

            <h1 class="text-3xl md:text-4xl font-bold mb-2">Volume {{ $volume->volume_number }}, Issue {{ $issue->issue_number }}</h1>
            <p class="text-blue-200 mb-0 font-medium italic">Published on {{ \Carbon\Carbon::parse($issue->publication_date)->format('F d, Y') }}</p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-12">
        <div class="flex flex-col lg:flex-row gap-12">
            <!-- Sidebar (Optional, maybe journal info) -->
            <div class="lg:w-1/4">
                <div class="bg-white p-6 rounded shadow border-l-4 border-blue-600 sticky top-24">
                    <h3 class="font-bold text-xl mb-4 text-gray-800">Journal Details</h3>
                    <div class="space-y-4">
                        <div class="flex flex-col">
                            <span class="text-xs uppercase text-gray-500 font-bold tracking-widest">Journal</span>
                            <a href="{{ route('journals.show', $journal->slug) }}" class="text-blue-600 font-medium hover:underline">{{ $journal->title }}</a>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs uppercase text-gray-500 font-bold tracking-widest">ISSN</span>
                            <span class="text-gray-800">{{ $journal->issn }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs uppercase text-gray-500 font-bold tracking-widest">Impact Factor</span>
                            <span class="text-gray-800 font-bold">{{ $journal->impact_factor }}</span>
                        </div>
                        <hr>
                        <a href="{{ route('author.submit', ['journal_id' => $journal->id]) }}" class="block text-center bg-blue-600 text-white font-bold py-2 rounded shadow hover:bg-blue-700 transition">Submit to Journal</a>
                    </div>
                </div>
            </div>

            <!-- Content Area: Article List -->
            <div class="lg:w-3/4">
                <h2 class="text-2xl font-bold mb-8 text-gray-900 border-b pb-4">Table of Contents ({{ $articles->count() }} articles)</h2>
                
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
