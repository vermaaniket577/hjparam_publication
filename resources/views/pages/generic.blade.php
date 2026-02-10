@extends('layouts.web')
@section('title', $title ?? 'Page')

@section('content')
    <div class="bg-slate-50 min-h-[60vh] py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div
                    class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                    <div class="bg-blue-900 px-12 py-16 text-center relative overflow-hidden">
                        <div
                            class="absolute top-0 right-0 w-64 h-64 bg-blue-800 rounded-full blur-3xl -mr-32 -mt-32 opacity-50">
                        </div>
                        <div
                            class="absolute bottom-0 left-0 w-64 h-64 bg-blue-700 rounded-full blur-3xl -ml-32 -mb-32 opacity-30">
                        </div>

                        <div class="relative z-10">
                            <h1 class="text-4xl md:text-5xl font-serif font-bold text-white mb-4 italic">
                                {{ $title ?? 'Page Title' }}</h1>
                            <div
                                class="flex items-center justify-center gap-2 text-blue-200 text-sm font-medium uppercase tracking-widest">
                                <span>HJPARAM</span>
                                <span>•</span>
                                <span>Official Information</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-12 md:p-16">
                        <div class="prose prose-slate prose-lg max-w-none text-slate-600 leading-relaxed font-light">
                            {!! $content ?? '<p class="text-center italic text-slate-400">Page content is currently being prepared by our editorial team.</p>' !!}
                        </div>

                        <div class="mt-16 pt-8 border-t border-slate-100 flex justify-between items-center">
                            <a href="{{ route('home') }}"
                                class="inline-flex items-center text-blue-600 hover:text-blue-800 font-bold text-sm transition group">
                                <svg class="w-4 h-4 mr-2 transform group-hover:-translate-x-1 transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                BACK TO HOME
                            </a>
                            <div class="flex gap-4">
                                <button onclick="window.print()" class="text-slate-400 hover:text-blue-600 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection