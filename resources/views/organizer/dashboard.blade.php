@extends('layouts.admin')
@section('title', 'Organizer Dashboard')
@section('breadcrumb', 'Organizer Dashboard')

@section('content')
<div class="space-y-10">
    <div class="bg-[#0f172a] rounded-[2.5rem] p-10 md:p-16 text-white relative overflow-hidden shadow-2xl">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
        <div class="relative z-10">
            <h1 class="text-3xl md:text-5xl font-serif font-black mb-6 tracking-tight">Organizer <span class="text-blue-400 italic">Terminal</span></h1>
            <p class="text-white/60 text-lg font-medium max-w-2xl leading-relaxed">
                Welcome, {{ auth()->user()->name }}. Manage your international conference submissions, track approval protocols, and monitor event engagement.
            </p>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm hover:shadow-xl transition-all group">
            <div class="w-14 h-14 bg-slate-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-blue-600 group-hover:text-white transition-all">
                <svg class="w-8 h-8 opacity-40 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2.5"></path>
                </svg>
            </div>
            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Total Registry</p>
            <p class="text-3xl font-black text-slate-900">{{ $stats['total_events'] }}</p>
        </div>

        <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm hover:shadow-xl transition-all group">
            <div class="w-14 h-14 bg-slate-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-amber-500 group-hover:text-white transition-all">
                <svg class="w-8 h-8 opacity-40 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2.5"></path>
                </svg>
            </div>
            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Pending Protocol</p>
            <p class="text-3xl font-black text-slate-900">{{ $stats['pending_approval'] }}</p>
        </div>

        <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm hover:shadow-xl transition-all group">
            <div class="w-14 h-14 bg-slate-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-emerald-500 group-hover:text-white transition-all">
                <svg class="w-8 h-8 opacity-40 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2.5"></path>
                </svg>
            </div>
            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Live Broadcast</p>
            <p class="text-3xl font-black text-slate-900">{{ $stats['live_events'] }}</p>
        </div>
    </div>

    <!-- Actions & Recent Results -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <div class="lg:col-span-8 space-y-8">
            <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100 overflow-hidden">
                <div class="flex justify-between items-center mb-10 pb-6 border-b border-slate-50">
                    <h2 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em]">Recent Logistics Log</h2>
                    <a href="{{ route('organizer.conferences.index') }}" class="text-[10px] font-black text-blue-600 uppercase tracking-widest border-b border-blue-100">See All</a>
                </div>

                <div class="space-y-6">
                    @forelse($recentConferences as $conf)
                        <div class="flex items-center justify-between p-6 bg-slate-50 rounded-2xl hover:bg-white border border-transparent hover:border-slate-100 transition-all group">
                            <div class="flex items-center gap-6">
                                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm">
                                    <span class="text-lg font-black text-slate-300">#</span>
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-900 group-hover:text-blue-600 transition-colors">{{ $conf->title }}</h3>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $conf->city }}, {{ $conf->country->name }} • {{ $conf->start_date->format('M Y') }}</p>
                                </div>
                            </div>
                            <div>
                                @if($conf->status == 'approved')
                                    <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[9px] font-black uppercase rounded-lg">Live</span>
                                @else
                                    <span class="px-3 py-1 bg-amber-50 text-amber-600 text-[9px] font-black uppercase rounded-lg">Wait List</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center text-slate-400 italic">No events recorded in the registry yet.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="lg:col-span-4 flex flex-col gap-8">
            <div class="bg-blue-600 rounded-[2.5rem] p-10 text-white shadow-2xl relative overflow-hidden group">
                <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
                <div class="relative z-10 text-center">
                    <h3 class="text-xl font-black mb-4">List New Event</h3>
                    <p class="text-white/70 text-sm mb-8">Reach thousands of researchers by listing your conference in our premier global network.</p>
                    <a href="{{ route('organizer.conferences.create') }}" class="inline-block w-full py-4 bg-white text-blue-600 rounded-2xl font-black uppercase tracking-widest text-[10px] shadow-2xl hover:-translate-y-1 transition-all">Initialize Entry</a>
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] p-10 border border-slate-100 shadow-sm">
                <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest mb-6">Support Uplink</h3>
                <p class="text-slate-500 text-xs mb-6 leading-relaxed">Need help with your submission or technical roadblocks? Our editorial team is ready to assist.</p>
                <a href="{{ route('contact.index') }}" class="text-[10px] font-black text-blue-600 uppercase tracking-widest border-b border-blue-100 pb-1">Open Intelligence Port</a>
            </div>
        </div>
    </div>
</div>
@endsection
