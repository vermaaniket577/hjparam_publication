@extends('layouts.web')
@section('title', $topic->name . ' | HJPARAM Publication')

@section('content')
    <div class="bg-blue-900 text-white py-16">
        <div class="container mx-auto px-4">
            <div class="flex items-center gap-4 text-blue-300 text-sm mb-4">
                <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
                <span>/</span>
                <a href="{{ route('topics.index') }}" class="hover:text-white transition">Topics</a>
                <span>/</span>
                <span class="text-white font-bold">{{ $topic->name }}</span>
            </div>
            <h1 class="text-4xl md:text-5xl font-serif font-bold mb-4">{{ $topic->name }}</h1>
            <p class="text-xl text-blue-100 max-w-2xl">Discover leading journals and research articles in the field of
                {{ strtolower($topic->name) }}.</p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-12">
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-8 sticky top-24">
                    <h3 class="text-lg font-bold text-slate-900 mb-6 border-b pb-4">Other Topics</h3>
                    <nav class="space-y-2">
                        @foreach(\App\Models\Topic::where('active', true)->where('id', '!=', $topic->id)->orderBy('sort_order')->get() as $otherTopic)
                            <a href="{{ route('topics.show', $otherTopic->slug) }}"
                                class="block p-3 rounded-xl text-slate-600 hover:bg-blue-50 hover:text-blue-700 transition font-medium group flex justify-between items-center">
                                {{ $otherTopic->name }}
                                <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                    </path>
                                </svg>
                            </a>
                        @endforeach
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-3">
                <h2 class="text-2xl font-bold text-slate-900 mb-8 flex items-center">
                    Journals in {{ $topic->name }}
                    <span
                        class="ml-4 text-sm font-bold text-slate-400 bg-slate-100 px-3 py-1 rounded-full uppercase tracking-widest">{{ $topic->journals()->count() }}
                        Total</span>
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($topic->journals as $journal)
                        <div
                            class="bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl transition-all p-6 group">
                            <div class="flex gap-4 mb-4">
                                <div
                                    class="w-16 h-20 bg-slate-50 rounded-lg flex-shrink-0 flex items-center justify-center text-slate-300 font-bold text-2xl border border-slate-50">
                                    {{ substr($journal->title, 0, 1) }}
                                </div>
                                <div>
                                    <h3
                                        class="font-bold text-slate-900 group-hover:text-blue-600 transition mb-1 leading-tight">
                                        <a href="{{ route('journals.show', $journal->slug) }}">{{ $journal->title }}</a>
                                    </h3>
                                    <div class="text-xs text-slate-400 font-medium">ISSN: {{ $journal->issn }}</div>
                                </div>
                            </div>
                            <p class="text-sm text-slate-500 line-clamp-2 mb-4">{{ $journal->description }}</p>
                            <div class="flex items-center justify-between pt-4 border-t border-slate-50">
                                <div class="flex items-center gap-3">
                                    <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded">IF
                                        {{ $journal->impact_factor }}</span>
                                </div>
                                <a href="{{ route('journals.show', $journal->slug) }}"
                                    class="text-xs font-bold text-slate-900 hover:text-blue-600 transition flex items-center">
                                    VIEW JOURNAL
                                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                        </path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div
                            class="col-span-full py-20 text-center bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200">
                            <p class="text-slate-400 font-medium italic">No journals found in this topic yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection