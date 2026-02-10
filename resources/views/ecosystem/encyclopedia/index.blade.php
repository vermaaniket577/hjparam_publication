@extends('layouts.web')
@section('title', 'Encyclopedia | Academic Knowledge Base')

@section('content')
    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="container mx-auto px-4">
            <div class="mb-12 text-center max-w-2xl mx-auto">
                <h1 class="font-serif text-4xl font-bold text-slate-900 mb-4">Unlocking global knowledge.</h1>
                <p class="text-slate-600">Explore peer-reviewed topics curated by experts across all disciplines.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($entries as $entry)
                    <div
                        class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 hover:shadow-md transition-all group">
                        <span
                            class="text-[10px] font-bold text-blue-600 uppercase tracking-widest mb-2 block">{{ $entry->category }}</span>
                        <h3
                            class="font-serif font-bold text-2xl text-slate-900 mb-4 group-hover:text-blue-900 transition-colors">
                            <a href="{{ route('encyclopedia.show', $entry->slug) }}">{{ $entry->title }}</a>
                        </h3>
                        <p class="text-slate-600 text-sm line-clamp-4 leading-relaxed mb-6">{{ $entry->content }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-400">By {{ $entry->user->name }}</span>
                            <a href="{{ route('encyclopedia.show', $entry->slug) }}"
                                class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center group-hover:bg-blue-900 group-hover:text-white transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center bg-white rounded-3xl border border-dashed border-slate-200">
                        <p class="text-slate-400 font-bold uppercase tracking-widest">No encyclopedia entries found.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $entries->links() }}
            </div>
        </div>
    </div>
@endsection