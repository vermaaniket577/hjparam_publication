@extends('layouts.admin')

@section('title', 'Dashboard')
@section('breadcrumb', 'Overview')

@section('content')
    <div class="space-y-8">
        <!-- Overview Widgets -->
        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Users Widget -->
            <div class="bg-white rounded-[2rem] shadow-[0_10px_30px_-15px_rgba(0,0,0,0.05)] border border-slate-100 p-8 flex flex-col relative overflow-hidden group">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-500/5 rounded-full blur-2xl group-hover:bg-blue-500/10 transition-all duration-500"></div>
                <div class="flex items-center justify-between mb-6">
                    <div class="p-4 rounded-2xl bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all duration-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </div>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Global Reach</span>
                </div>
                <div>
                    <p class="text-[11px] font-black text-slate-500 uppercase tracking-[0.2em] mb-1">Total Users</p>
                    <p class="text-4xl font-black text-slate-900 tracking-tighter">{{ $stats['users'] }}</p>
                </div>
            </div>

            <!-- Journals Widget -->
            <div class="bg-white rounded-[2rem] shadow-[0_10px_30px_-15px_rgba(0,0,0,0.05)] border border-slate-100 p-8 flex flex-col relative overflow-hidden group">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-purple-500/5 rounded-full blur-2xl group-hover:bg-purple-500/10 transition-all duration-500"></div>
                <div class="flex items-center justify-between mb-6">
                    <div class="p-4 rounded-2xl bg-purple-50 text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-all duration-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                            </path>
                        </svg>
                    </div>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Publications</span>
                </div>
                <div>
                    <p class="text-[11px] font-black text-slate-500 uppercase tracking-[0.2em] mb-1">Total Journals</p>
                    <p class="text-4xl font-black text-slate-900 tracking-tighter">{{ $stats['journals'] }}</p>
                </div>
            </div>

            <!-- Submissions Widget -->
            <div class="bg-white rounded-[2rem] shadow-[0_10px_30px_-15px_rgba(0,0,0,0.05)] border border-slate-100 p-8 flex flex-col relative overflow-hidden group">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-amber-500/5 rounded-full blur-2xl group-hover:bg-amber-500/10 transition-all duration-500"></div>
                <div class="flex items-center justify-between mb-6">
                    <div class="p-4 rounded-2xl bg-amber-50 text-amber-600 group-hover:bg-amber-600 group-hover:text-white transition-all duration-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <span class="text-[10px] font-black text-amber-600 uppercase tracking-widest">Attention Req.</span>
                </div>
                <div>
                    <p class="text-[11px] font-black text-slate-500 uppercase tracking-[0.2em] mb-1">Pending Review</p>
                    <p class="text-4xl font-black text-slate-900 tracking-tighter">{{ $stats['submissions_pending'] }}</p>
                </div>
            </div>

            <!-- Published Articles Widget -->
            <div class="bg-white rounded-[2rem] shadow-[0_10px_30px_-15px_rgba(0,0,0,0.05)] border border-slate-100 p-8 flex flex-col relative overflow-hidden group">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-emerald-500/5 rounded-full blur-2xl group-hover:bg-emerald-500/10 transition-all duration-500"></div>
                <div class="flex items-center justify-between mb-6">
                    <div class="p-4 rounded-2xl bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Live Content</span>
                </div>
                <div>
                    <p class="text-[11px] font-black text-slate-500 uppercase tracking-[0.2em] mb-1">Published</p>
                    <p class="text-4xl font-black text-slate-900 tracking-tighter">{{ $stats['articles_published'] }}</p>
                </div>
            </div>
        </div>

        <!-- Secondary Row: More Detailed Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
             <!-- Conferences Widget -->
             <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-8 flex items-center group hover:bg-[#0f172a] transition-all duration-500">
                <div class="p-5 rounded-2xl bg-indigo-50 text-indigo-600 group-hover:bg-blue-600 group-hover:text-white transition-all duration-500 mr-6 shadow-xl shadow-indigo-100 group-hover:shadow-blue-900/40">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2.5"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-[11px] font-black text-slate-400 group-hover:text-blue-200 uppercase tracking-[0.2em] mb-1">Conferences</p>
                    <div class="flex items-center gap-4">
                        <p class="text-3xl font-black text-slate-900 group-hover:text-white tracking-tighter">{{ $stats['total_conferences'] }}</p>
                        @if($stats['pending_conferences'] > 0)
                            <span class="px-3 py-1 bg-amber-50 text-amber-600 text-[9px] font-black uppercase tracking-widest rounded-xl border border-amber-100 group-hover:bg-amber-500 group-hover:text-white group-hover:border-transparent">{{ $stats['pending_conferences'] }} PENDING</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Subscriptions Widget -->
            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-8 flex items-center group hover:bg-[#0f172a] transition-all duration-500">
                <div class="p-5 rounded-2xl bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500 mr-6 shadow-xl shadow-emerald-100 group-hover:shadow-emerald-900/40">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke-width="2.5"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-[11px] font-black text-slate-400 group-hover:text-emerald-200 uppercase tracking-[0.2em] mb-1">Alert Subs</p>
                    <p class="text-3xl font-black text-slate-900 group-hover:text-white tracking-tighter">{{ $stats['total_subscribers'] }}</p>
                </div>
            </div>
        </div>

        <!-- Charts & Tables Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Visualization Placeholder -->
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-lg font-black text-slate-800 uppercase tracking-widest">Submission Trends</h3>
                    <div class="flex gap-2">
                        <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                        <span class="w-3 h-3 rounded-full bg-slate-100"></span>
                    </div>
                </div>
                <div class="h-64 bg-slate-50/50 rounded-[2rem] border border-dashed border-slate-200 flex flex-col items-center justify-center text-slate-400">
                    <svg class="w-12 h-12 mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" stroke-width="2"/></svg>
                    <span class="text-[10px] font-black uppercase tracking-widest">Visualizing Monthly Growth</span>
                </div>
            </div>

            <!-- Recent Activity Feed -->
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between">
                    <h3 class="text-lg font-black text-slate-800 uppercase tracking-widest">Recent Activity</h3>
                    <span class="px-3 py-1 bg-blue-50 text-blue-600 text-[8px] font-black uppercase tracking-widest rounded-lg">Real-time</span>
                </div>
                <ul class="divide-y divide-slate-50">
                    <li class="px-8 py-5 flex items-center hover:bg-slate-50 transition group">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 mr-4 group-hover:bg-blue-600 group-hover:text-white transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2.5"/></svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-bold text-slate-900">New submission received</p>
                            <p class="text-xs text-slate-500">Dr. Author submitted "AI in Healthcare"</p>
                        </div>
                        <span class="text-[10px] font-black text-slate-300">2h ago</span>
                    </li>
                    <li class="px-8 py-5 flex items-center hover:bg-slate-50 transition group">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 mr-4 group-hover:bg-emerald-600 group-hover:text-white transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-width="2.5"/></svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-bold text-slate-900">Article published</p>
                            <p class="text-xs text-slate-500">"Sustainable Energy" is now live</p>
                        </div>
                        <span class="text-[10px] font-black text-slate-300">1d ago</span>
                    </li>
                    <li class="px-8 py-5 flex items-center hover:bg-slate-50 transition group">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 mr-4 group-hover:bg-amber-600 group-hover:text-white transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2.5"/></svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-bold text-slate-900">Review overdue</p>
                            <p class="text-xs text-slate-500">Reviewer Jane needs a reminder</p>
                        </div>
                        <span class="text-[10px] font-black text-slate-300">2d ago</span>
                    </li>
                </ul>
                <div class="px-8 py-4 bg-slate-50/50 text-center">
                    <a href="#" class="text-[9px] font-black text-blue-600 hover:text-blue-800 uppercase tracking-[0.2em]">Audit All Trails &rarr;</a>
                </div>
            </div>
        </div>
    </div>
@endsection