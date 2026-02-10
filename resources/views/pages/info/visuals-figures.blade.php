@extends('layouts.web')
@section('title', 'Visuals & Figures | HJPARAM Publication')

@section('content')
    <div class="bg-slate-50 py-24">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <span
                    class="inline-block px-3 py-1 bg-orange-100 text-orange-700 text-[10px] font-bold uppercase tracking-[0.2em] rounded-full mb-6">Scientific
                    Illustration</span>
                <h1 class="text-4xl md:text-5xl font-serif font-bold text-slate-900 mb-8">Visuals & <span
                        class="text-orange-600">Figures</span></h1>

                <div class="bg-white p-8 md:p-12 rounded-[2rem] shadow-sm border border-slate-100 mb-12">
                    <div class="prose prose-slate max-w-none">
                        <p class="text-lg text-slate-600 mb-12 leading-relaxed">
                            A picture is worth a thousand data points. Our design team specializes in creating
                            publication-ready scientific illustrations, charts, and graphical abstracts that make your work
                            stand out.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
                            <div class="group">
                                <div
                                    class="h-48 bg-slate-100 rounded-3xl mb-6 shadow-inner flex items-center justify-center text-slate-300">
                                    <svg class="w-16 h-16 opacity-30 group-hover:scale-110 transition-transform" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                            stroke-width="1.5"></path>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-bold text-slate-900 mb-2">Graphical Abstracts</h4>
                                <p class="text-sm text-slate-500">Concise, visual summaries of your research findings for
                                    high-impact social sharing and indexing.</p>
                            </div>
                            <div class="group">
                                <div
                                    class="h-48 bg-slate-100 rounded-3xl mb-6 shadow-inner flex items-center justify-center text-slate-300">
                                    <svg class="w-16 h-16 opacity-30 group-hover:scale-110 transition-transform" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                                            stroke-width="1.5"></path>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-bold text-slate-900 mb-2">Technical Charts</h4>
                                <p class="text-sm text-slate-500">Converting raw data into elegant, high-resolution vector
                                    graphics that meet strict journal standards.</p>
                            </div>
                        </div>

                        <div class="bg-orange-50 p-8 rounded-3xl border border-orange-100 flex items-center gap-6 mb-12">
                            <div
                                class="w-12 h-12 bg-orange-600 text-white rounded-full flex items-center justify-center text-xl shrink-0">
                                ✨</div>
                            <p class="text-orange-900 text-sm font-medium">Authors using professional graphical abstracts
                                see a <span class="font-bold underline">35% increase</span> in article downloads and social
                                media engagement.</p>
                        </div>

                        <div class="text-center pt-8 border-t border-slate-100">
                            <a href="{{ route('info.page', 'process') }}"
                                class="text-blue-600 hover:text-slate-900 font-bold uppercase tracking-widest text-xs flex items-center justify-center gap-2">
                                LEARN ABOUT OUR PROCESS
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M17 8l4 4m0 0l-4 4m4-4H3" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection