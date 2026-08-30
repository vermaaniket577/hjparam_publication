@extends('layouts.web')
@section('title', 'Browse Journals | HJPARAM Publication')

@section('content')
    <div class="bg-slate-50 min-h-screen py-12">
        <div class="container mx-auto px-4 max-w-5xl">
            <!-- Page Header -->
            <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-serif font-black text-slate-900 tracking-tight">All Journals</h1>
                    <p class="text-slate-500 text-sm mt-1">Explore our peer-reviewed open access scholarly journals</p>
                </div>
                <div class="text-xs font-bold text-slate-600 bg-white px-4 py-2 rounded-full border border-slate-200 shadow-xs self-start md:self-auto">
                    @if(method_exists($journals, 'total'))
                        Showing <span class="text-blue-600 font-extrabold">{{ $journals->firstItem() ?? 0 }} - {{ $journals->lastItem() ?? 0 }}</span> of <span class="text-blue-600 font-extrabold">{{ $journals->total() }}</span> Journals
                    @else
                        Showing <span class="text-blue-600 font-extrabold">{{ $journals->count() }}</span> Journals
                    @endif
                </div>
            </div>

            <!-- Search Bar (Replaced Alphabet Filter) -->
            <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-sm mb-8">
                <form action="{{ route('journals.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-grow">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search journals by title, ISSN, or subject keywords..."
                            class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="submit"
                            class="w-full sm:w-auto px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl transition-all shadow-xs hover:shadow-md flex items-center justify-center gap-2 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Search
                        </button>
                        @if(request()->filled('search'))
                            <a href="{{ route('journals.index') }}"
                                class="px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-sm rounded-xl transition-all flex items-center justify-center">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Horizontal Journal Cards List -->
            <div class="space-y-5">
                @forelse($journals as $journal)
                    <div class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 shadow-xs hover:shadow-md hover:border-blue-300 transition-all flex flex-col sm:flex-row gap-5 items-start">
                        
                        <!-- Left Fixed Book Thumbnail -->
                        <div class="flex-shrink-0 rounded-xl overflow-hidden shadow-xs relative"
                             style="flex: 0 0 130px; width: 130px; min-width: 130px; max-width: 130px; height: 165px; background: linear-gradient(145deg, #0b1528 0%, #172554 50%, #0f172a 100%); border-top: 3px solid #f59e0b;">
                            @if($journal->cover_image)
                                <img src="{{ asset('storage/' . $journal->cover_image) }}" alt="{{ $journal->title }}"
                                    class="w-full h-full object-cover" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <div class="w-full h-full flex flex-col justify-between p-2.5 text-center select-none">
                                    <div class="flex justify-between items-center text-[7px] font-black uppercase text-slate-400">
                                        <span>HJPARAM</span>
                                        <span class="text-amber-400">PEER</span>
                                    </div>
                                    <div class="my-auto">
                                        <div class="w-9 h-9 mx-auto rounded-lg bg-blue-600/25 border border-blue-400/40 flex items-center justify-center text-blue-300 font-serif font-black text-lg shadow-xs">
                                            {{ substr($journal->title, 0, 1) }}
                                        </div>
                                    </div>
                                    <div class="text-[7px] font-bold text-emerald-400 uppercase">
                                        OPEN ACCESS
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Right Information Area -->
                        <div class="flex-grow flex flex-col justify-between w-full h-full">
                            <div>
                                <!-- Badges -->
                                <div class="flex flex-wrap items-center gap-2 mb-2">
                                    <span class="px-2.5 py-0.5 bg-slate-100 text-slate-700 text-[11px] font-bold font-mono rounded-md border border-slate-200">
                                        ISSN: {{ $journal->issn ?? '2071-1050' }}
                                    </span>
                                    <span class="px-2.5 py-0.5 bg-emerald-50 text-emerald-700 text-[11px] font-bold rounded-md border border-emerald-100">
                                        Impact Factor: {{ number_format($journal->impact_factor ?? 4.2, 2) }}
                                    </span>
                                    <span class="px-2.5 py-0.5 bg-blue-50 text-blue-700 text-[11px] font-bold rounded-md border border-blue-100">
                                        Scopus / WoS
                                    </span>
                                </div>

                                <!-- Journal Title -->
                                <h2 class="text-lg sm:text-xl font-bold text-slate-900 hover:text-blue-600 transition-colors mb-2">
                                    <a href="{{ route('journals.show', $journal->slug) }}">{{ $journal->title }}</a>
                                </h2>

                                <!-- Description -->
                                <p class="text-slate-600 text-sm leading-relaxed line-clamp-2 sm:line-clamp-3 mb-4">
                                    {{ $journal->description ?? 'Peer-reviewed international research journal dedicated to advancing scholarly discovery and open scientific collaboration.' }}
                                </p>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-wrap items-center gap-3 pt-3 border-t border-slate-100">
                                <a href="{{ route('journals.show', $journal->slug) }}"
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg transition-all inline-flex items-center gap-1.5 shadow-xs">
                                    <span>View Journal</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                                <a href="{{ route('author.submit', ['journal_id' => $journal->id]) }}"
                                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-lg transition-all inline-flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    <span>Submit Manuscript</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
                        <div class="w-14 h-14 mx-auto rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mb-4">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-1">No Journals Found</h3>
                        <p class="text-slate-500 text-sm mb-4">We couldn't find any journals matching "{{ request('search') }}".</p>
                        <a href="{{ route('journals.index') }}" class="inline-flex px-4 py-2 bg-blue-600 text-white text-xs font-bold rounded-lg hover:bg-blue-700 transition">
                            View All Journals
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination Navigation (Page Numbers) -->
            @if(method_exists($journals, 'hasPages') && $journals->hasPages())
                <div class="mt-10 pt-6 border-t border-slate-200 flex justify-center">
                    {{ $journals->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection