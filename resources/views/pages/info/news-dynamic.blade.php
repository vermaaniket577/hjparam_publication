@extends('layouts.web')
@section('title', $news->title . ' | HJPARAM News')
@section('meta_description', $news->description)

@section('content')
    <div class="bg-white py-20">
        <div class="container mx-auto px-4 max-w-4xl">
            <div class="mb-12">
                @if($news->category)
                    <span
                        class="inline-block px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-bold uppercase tracking-wider rounded-full mb-6">{{ $news->category }}</span>
                @endif
                <h1 class="text-4xl md:text-5xl font-serif font-bold text-slate-900 mb-6">{{ $news->title }}</h1>
                <div
                    class="flex items-center gap-4 text-slate-400 text-sm font-bold uppercase tracking-widest border-y border-slate-100 py-4">
                    <span>{{ $news->published_at->format('d M Y') }}</span>
                </div>
            </div>

            <div class="prose prose-slate max-w-none prose-lg">
                {!! $news->content !!}
            </div>

            <div class="mt-20 pt-10 border-t border-slate-100 flex justify-between">
                <a href="{{ route('info.page', 'news') }}"
                    class="text-slate-400 hover:text-blue-600 font-bold uppercase tracking-widest text-xs flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M15 19l-7-7 7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                    Back to News
                </a>
                <a href="{{ route('info.page', 'process') }}"
                    class="text-blue-600 hover:text-slate-900 font-bold uppercase tracking-widest text-xs">LEARN ABOUT OUR
                    PROCESS →</a>
            </div>
        </div>
    </div>
@endsection