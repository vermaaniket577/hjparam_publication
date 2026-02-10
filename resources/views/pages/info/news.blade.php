@extends('layouts.web')
@section('title', 'News & Announcements')

@section('content')
    <div class="bg-slate-50 py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-16">
                    <span
                        class="inline-block px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-bold uppercase tracking-wider rounded-full mb-4">Latest
                        Updates</span>
                    <h1 class="text-4xl md:text-5xl font-serif font-bold text-slate-900">News & <span
                            class="text-blue-600">Announcements</span></h1>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse($news as $item)
                        <div
                            class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 flex flex-col h-full hover:shadow-xl transition-all">
                            <div class="flex items-center gap-3 mb-6">
                                @if($item->category)
                                    <span
                                        class="px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-bold uppercase tracking-wider rounded-full">{{ $item->category }}</span>
                                @endif
                                <span
                                    class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">{{ $item->published_at->format('d M Y') }}</span>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 mb-4">{{ $item->title }}</h3>
                            <p class="text-slate-500 text-sm leading-relaxed mb-8">{{ Str::limit($item->description, 120) }}</p>
                            <a href="{{ route('info.page', $item->slug) }}"
                                class="mt-auto text-blue-600 text-xs font-bold uppercase tracking-widest flex items-center gap-2 group">
                                Read Full Story
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M17 8l4 4m0 0l-4 4m4-4H3" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                </svg>
                            </a>
                        </div>
                    @empty
                        <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-20 text-slate-400">
                            No news articles available at the moment.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection