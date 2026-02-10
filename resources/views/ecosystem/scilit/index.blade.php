@extends('layouts.web')
@section('title', 'Scilit | Academic Article Indexing')

@section('content')
    <div class="bg-gradient-to-b from-blue-900 to-slate-900 text-white py-20 overflow-hidden relative">
        <div class="absolute inset-0 opacity-10"
            style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 30px 30px;"></div>
        <div class="container mx-auto px-4 relative z-10 text-center">
            <h1 class="text-5xl font-serif font-bold mb-6">Scilit</h1>
            <p class="text-xl text-blue-100 mb-10 max-w-2xl mx-auto">Comprehensive academic indexing system. Find citations
                and metadata for millions of articles.</p>

            <div class="max-w-3xl mx-auto">
                <form action="{{ route('scilit.index') }}" method="GET" class="flex gap-2">
                    <input type="text" name="q" value="{{ request('q') }}"
                        class="flex-grow p-4 rounded-xl text-slate-900 focus:ring-4 focus:ring-blue-500/20 outline-none shadow-2xl"
                        placeholder="Search by title, author, or keyword...">
                    <button type="submit"
                        class="px-8 py-4 bg-blue-600 hover:bg-blue-500 rounded-xl font-bold transition shadow-2xl uppercase tracking-widest text-sm">Search</button>
                </form>
            </div>
        </div>
    </div>

    <div class="bg-slate-50 py-12">
        <div class="container mx-auto px-4">
            @if(request('q'))
                <div class="mb-8">
                    <h2 class="text-lg text-slate-500">Search results for: <span
                            class="text-slate-900 font-bold uppercase tracking-widest text-sm">"{{ request('q') }}"</span></h2>
                </div>
            @endif

            <div class="space-y-6">
                @forelse($articles as $article)
                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition group">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <span
                                    class="text-[10px] font-bold text-blue-600 uppercase tracking-widest mb-1 block">{{ $article->journal->title }}</span>
                                <h3 class="text-2xl font-serif font-bold text-slate-900 group-hover:text-blue-900 transition">
                                    <a
                                        href="{{ route('articles.show', ['journalSlug' => $article->journal->slug, 'articleSlug' => $article->slug]) }}">{{ $article->title }}</a>
                                </h3>
                            </div>
                            <span class="text-xs text-slate-400 font-medium">{{ $article->created_at->year }}</span>
                        </div>
                        <p class="text-slate-600 text-sm leading-relaxed mb-6 line-clamp-2 italic">by Author Name et al.</p>
                        <div class="flex items-center space-x-4">
                            <a href="#"
                                class="text-xs font-bold text-slate-400 hover:text-blue-900 uppercase tracking-widest">Metadata</a>
                            <a href="#"
                                class="text-xs font-bold text-slate-400 hover:text-blue-900 uppercase tracking-widest">Citations
                                (0)</a>
                            <a href="{{ route('articles.show', ['journalSlug' => $article->journal->slug, 'articleSlug' => $article->slug]) }}"
                                class="text-xs font-bold text-blue-900 uppercase tracking-widest hover:underline">View Article
                                &rarr;</a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-20 bg-white rounded-2xl border border-slate-200 shadow-sm">
                        <p class="text-slate-400 font-bold uppercase tracking-widest">No articles found matching your criteria.
                        </p>
                    </div>
                @endforelse
            </div>

            <div class="mt-12">
                {{ $articles->links() }}
            </div>
        </div>
    </div>
@endsection