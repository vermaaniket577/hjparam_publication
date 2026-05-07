@extends('layouts.admin')
@section('title', 'My Conferences')
@section('breadcrumb', 'Conferences')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My Conference Portfolio</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Total: {{ $conferences->total() }} events recorded</p>
        </div>
        <a href="{{ route('organizer.conferences.create') }}" class="px-4 py-2 bg-[#0f172a] hover:bg-blue-700 text-white font-black rounded-lg shadow-sm transition-all flex items-center gap-2 active:scale-95 uppercase tracking-widest text-xs">
            <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Initialize Entry
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-100 p-6 rounded-2xl text-emerald-800 font-bold text-sm shadow-sm animate-bounce-short">
            {{ session('success') }}
        </div>
    @endif

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 dark:bg-gray-700/50 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                    <tr>
                        <th class="px-6 py-4">Event Identity</th>
                        <th class="px-6 py-4">Logistics Terminal</th>
                        <th class="px-6 py-4">Protocol Status</th>
                        <th class="px-6 py-4 text-right">Access</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($conferences as $conf)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors group">
                            <td class="px-6 py-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-300 relative overflow-hidden group-hover:border-blue-500 group-hover:text-blue-600 transition-all">
                                        @if($conf->banner_image)
                                            <img src="{{ asset('storage/' . $conf->banner_image) }}" class="w-full h-full object-cover opacity-70 group-hover:opacity-100 transition-opacity">
                                        @else
                                            <span class="text-xl font-bold">#</span>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900 dark:text-white mb-0.5 group-hover:text-blue-700 transition-all">{{ $conf->title }}</div>
                                        <div class="text-[9px] font-black text-blue-600 uppercase tracking-widest bg-blue-50 px-2 py-0.5 rounded-md inline-block">{{ $conf->category->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-8">
                                <div class="flex flex-col gap-2">
                                    <div class="flex items-center gap-2 text-xs font-bold text-slate-600 dark:text-slate-400">
                                        <svg class="w-4 h-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" stroke-width="2.5"></path>
                                        </svg>
                                        {{ $conf->city }}, {{ $conf->country->name }}
                                    </div>
                                    <div class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                                        <svg class="w-4 h-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2.5"></path>
                                        </svg>
                                        {{ $conf->start_date->format('M d, Y') }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-8">
                                @if($conf->status == 'approved')
                                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest bg-emerald-100 text-emerald-800 shadow-sm">Verified Live</span>
                                @elseif($conf->status == 'pending')
                                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest bg-amber-100 text-amber-800 shadow-sm animate-pulse">Wait Protocol</span>
                                @else
                                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest bg-red-100 text-red-800 shadow-sm">Halted Entry</span>
                                @endif
                            </td>
                            <td class="px-6 py-8 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('organizer.conferences.edit', $conf) }}" class="w-10 h-10 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center hover:bg-[#0f172a] hover:text-white transition-all duration-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('organizer.conferences.destroy', $conf) }}" method="POST" onsubmit="return confirm('Purge protocol initiated. Permanent removal. Proceed?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-10 h-10 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center hover:bg-red-600 hover:text-white transition-all duration-300">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-24 text-center">
                                <div class="w-20 h-20 bg-slate-50 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-slate-200 grayscale opacity-40">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <h3 class="font-black text-slate-300 uppercase tracking-[0.4em] mb-4">No Data Protocol Found</h3>
                                <p class="text-xs font-bold text-slate-400 max-w-sm mx-auto mb-8 uppercase tracking-widest leading-relaxed">Your event registry is currently empty. Initialize a new entry to reach the global academic community.</p>
                                <a href="{{ route('organizer.conferences.create') }}" class="inline-flex px-10 py-4 bg-blue-600 text-white font-black rounded-2xl shadow-xl shadow-blue-600/20 hover:-translate-y-1 transition-all uppercase tracking-widest text-[10px]">Initialize First Entry</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($conferences->hasPages())
            <div class="px-6 py-6 border-t border-slate-100 bg-slate-50">
                {{ $conferences->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
