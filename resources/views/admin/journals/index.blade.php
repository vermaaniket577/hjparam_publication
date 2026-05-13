@extends('layouts.admin')

@section('title', 'Journals Registry')
@section('breadcrumb', 'Journals')

@section('content')
    <div class="space-y-8 pb-20">
        <!-- Header & Vital Stats -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <h2 class="text-3xl font-black text-slate-800 tracking-tight">Journals <span class="text-blue-600">Archive</span></h2>
                <p class="text-slate-500 text-sm font-medium mt-1">Manage, monitor, and configure academic publication outlets.</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="hidden sm:flex bg-white px-6 py-3 rounded-2xl shadow-sm border border-slate-100 items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                    <span class="text-xs font-black text-slate-600 uppercase tracking-widest">{{ $journals->total() }} Active Outlets</span>
                </div>
                <a href="{{ route('admin.journals.create') }}"
                    class="px-8 py-4 bg-[#0f172a] text-white text-[11px] font-black uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-blue-900/10 hover:bg-blue-700 hover:-translate-y-0.5 transition-all active:scale-95">
                    Add New Journal
                </a>
            </div>
        </div>

        <!-- Global Journals Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            
            <!-- Strategic 'Add New' Placeholder -->
            <a href="{{ route('admin.journals.create') }}" 
               class="group relative flex flex-col items-center justify-center p-10 bg-slate-50/50 border-2 border-dashed border-slate-200 rounded-[2.5rem] hover:border-blue-400 hover:bg-blue-50/50 transition-all duration-500 min-h-[400px]">
                <div class="w-20 h-20 bg-white rounded-3xl shadow-xl flex items-center justify-center text-slate-300 group-hover:text-blue-600 group-hover:scale-110 group-hover:rotate-90 transition-all duration-700">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="3" stroke-linecap="round"/></svg>
                </div>
                <div class="mt-8 text-center">
                    <span class="block text-[11px] font-black text-slate-400 group-hover:text-blue-900 uppercase tracking-[0.3em]">Initialize Registry</span>
                    <span class="block text-[9px] font-medium text-slate-300 mt-2 uppercase tracking-widest group-hover:text-slate-400">Click to expand database</span>
                </div>
            </a>

            <!-- Dynamic Journals List -->
            @forelse($journals as $journal)
                <div class="group bg-white rounded-[2.5rem] border border-slate-100 shadow-[0_10px_40px_-15px_rgba(0,0,0,0.05)] hover:shadow-[0_50px_100px_-20px_rgba(30,58,138,0.18)] transition-all duration-700 flex flex-col overflow-hidden relative">
                    
                    <!-- Cover & Status Section -->
                    <div class="relative aspect-[3/4] overflow-hidden bg-[#020617]">
                        @if($journal->cover_image)
                            <img src="{{ asset('storage/' . $journal->cover_image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center p-8 text-center relative overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-br from-[#0f172a] via-[#1e293b] to-[#0f172a]"></div>
                                <div class="absolute inset-0 opacity-5 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
                                <span class="text-white/10 font-serif font-black text-8xl italic transform -rotate-12 select-none group-hover:scale-125 transition-transform duration-1000">{{ substr($journal->title, 0, 1) }}</span>
                                <div class="mt-6 w-12 h-1.5 bg-blue-500/20 rounded-full group-hover:w-20 group-hover:bg-blue-500/40 transition-all duration-700"></div>
                            </div>
                        @endif

                        <!-- Status Badge -->
                        <div class="absolute top-6 left-6 z-10">
                            <span class="px-4 py-2 {{ $journal->is_active ? 'bg-emerald-50/90 text-emerald-700 border-emerald-100' : 'bg-red-50/90 text-red-700 border-red-100' }} text-[9px] font-black uppercase tracking-[0.2em] rounded-xl border backdrop-blur-md shadow-sm">
                                {{ $journal->is_active ? 'Indexed' : 'Restricted' }}
                            </span>
                        </div>

                        <!-- Hover Action Console -->
                        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md opacity-0 group-hover:opacity-100 transition-all duration-500 flex flex-col items-center justify-center gap-4">
                            <a href="{{ route('admin.journals.edit', $journal) }}" 
                               class="w-48 py-3.5 bg-white text-slate-900 rounded-2xl flex items-center justify-center gap-3 shadow-2xl transform translate-y-8 group-hover:translate-y-0 transition-all duration-500 hover:bg-blue-600 hover:text-white font-black uppercase text-[10px] tracking-widest">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2.5"/></svg>
                                Modify Data
                            </a>
                            <form action="{{ route('admin.journals.destroy', $journal) }}" method="POST" 
                                  onsubmit="return confirm('Attention: archiving this journal is permanent. Proceed?');"
                                  class="w-48 transform translate-y-8 group-hover:translate-y-0 transition-all duration-500 delay-75">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full py-3.5 bg-red-600/20 text-red-200 border border-red-500/30 rounded-2xl flex items-center justify-center gap-3 hover:bg-red-600 hover:text-white transition-all font-black uppercase text-[10px] tracking-widest backdrop-blur-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2.5"/></svg>
                                    Archive Out
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Metadata Console -->
                    <div class="p-8 flex flex-col flex-grow">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">ISSN: {{ $journal->issn ?? 'UNASSIGNED' }}</span>
                            <div class="flex items-center gap-1.5">
                                <div class="w-1.5 h-1.5 rounded-full {{ $journal->is_active ? 'bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]' : 'bg-red-500' }}"></div>
                            </div>
                        </div>
                        <h3 class="text-xl font-serif font-bold text-slate-900 leading-tight mb-6 group-hover:text-blue-900 transition-colors line-clamp-2">{{ $journal->title }}</h3>
                        
                        <div class="mt-auto pt-6 border-t border-slate-50 flex items-center justify-between">
                            <div class="flex flex-col">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Impact Rating</span>
                                <span class="text-xl font-black text-blue-600 tracking-tight">{{ number_format($journal->impact_factor ?? 0.00, 2) }}</span>
                            </div>
                            <div class="flex items-center gap-3 text-slate-300">
                                <div class="flex flex-col items-end">
                                    <span class="text-[8px] font-black uppercase tracking-tighter">Scholarly Depth</span>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase">OA / Peer-Reviewed</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-32 flex flex-col items-center justify-center bg-slate-50 rounded-[3rem] border-2 border-dashed border-slate-200">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center text-slate-200 mb-6 shadow-xl shadow-slate-200/50">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" stroke-width="2"/></svg>
                    </div>
                    <h4 class="text-xl font-bold text-slate-400 uppercase tracking-widest">Repository Vacant</h4>
                    <p class="text-slate-400 text-sm mt-2">Initialize your first academic outlet to begin population.</p>
                </div>
            @endforelse
        </div>

        <!-- Intelligent Pagination -->
        @if($journals->hasPages())
            <div class="mt-12 bg-white p-8 rounded-[2.5rem] shadow-[0_10px_40px_-15px_rgba(0,0,0,0.03)] border border-slate-100 flex items-center justify-center">
                {{ $journals->links() }}
            </div>
        @endif
    </div>
@endsection