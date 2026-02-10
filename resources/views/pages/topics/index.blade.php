@extends('layouts.web')
@section('title', 'Browse by Topic | HJPARAM Publication')

@section('content')
    <div class="bg-slate-50 border-b border-gray-200">
        <div class="container mx-auto px-4 py-12">
            <h1 class="text-4xl font-serif font-bold text-slate-900 mb-4">Browse Journals by Topic</h1>
            <p class="text-lg text-slate-600 max-w-2xl">Explore our comprehensive collection of open access journals across
                diverse scientific disciplines.</p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach(\App\Models\Topic::where('active', true)->orderBy('sort_order')->get() as $topic)
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl transition-all p-8 group">
                    <div
                        class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">{{ $topic->name }}</h3>
                    <p class="text-slate-500 text-sm mb-6 leading-relaxed">
                        Access high-impact research papers and breakthrough discoveries in the field of
                        {{ strtolower($topic->name) }}.
                    </p>
                    <div class="flex items-center justify-between">
                        <span
                            class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ $topic->journals()->count() }}
                            Journals</span>
                        <a href="{{ route('topics.show', $topic->slug) }}"
                            class="inline-flex items-center text-sm font-bold text-blue-600 hover:text-blue-800 transition">
                            Browse
                            <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="bg-blue-900 text-white py-16">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-serif font-bold mb-6">Stay Informed</h2>
            <p class="text-blue-200 mb-8 max-w-xl mx-auto">Subscribe to our newsletter to receive the latest updates,
                featured articles, and news from your favorite research topics.</p>
            <form action="#" class="max-w-md mx-auto flex gap-2">
                <input type="email" placeholder="Enter your email address"
                    class="flex-grow px-6 py-3 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button
                    class="bg-blue-500 hover:bg-blue-400 text-white font-bold px-8 py-3 rounded-xl transition shadow-lg shadow-blue-900/50">Subscribe</button>
            </form>
        </div>
    </div>
@endsection