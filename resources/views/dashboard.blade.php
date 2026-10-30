@extends('layouts.web')

@section('title', 'Author Portal & Dashboard - HJPARAM')

@section('content')
<div class="bg-slate-50 min-h-screen py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto space-y-8">

        <!-- Welcome Banner -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 sm:p-10 text-center relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-8 -mr-8 w-40 h-40 bg-blue-50 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-40 h-40 bg-emerald-50 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10">
                <span class="inline-block px-3 py-1 bg-blue-50 text-blue-700 text-[11px] font-black uppercase tracking-wider rounded-full mb-3">
                    Author Hub
                </span>
                <h2 class="text-3xl sm:text-4xl font-serif font-black text-slate-900 mb-4 tracking-tight">
                    Welcome to HJPARAM Portal
                </h2>
                <p class="text-slate-600 mb-8 max-w-2xl mx-auto text-sm sm:text-base leading-relaxed">
                    This is your centralized hub for managing your academic contributions.
                    From here, you can submit new manuscripts, track their status, and perform reviews if assigned.
                </p>

                <div class="flex flex-wrap items-center justify-center gap-3">
                    <a href="{{ route('submission.create') }}"
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs uppercase tracking-wider transition-all shadow-md shadow-blue-600/25 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Submit New Manuscript</span>
                    </a>
                    <a href="{{ route('submission.index') }}"
                        class="px-6 py-3 bg-white hover:bg-slate-50 text-slate-700 hover:text-slate-900 border border-slate-200 rounded-xl font-bold text-xs uppercase tracking-wider transition-all shadow-xs flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>View My Submissions</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Stats for Authors -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col items-center text-center">
                <div class="text-4xl font-extrabold text-blue-600 mb-2">{{ $counts['active_submissions'] }}</div>
                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">Active Submissions</div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col items-center text-center">
                <div class="text-4xl font-extrabold text-amber-500 mb-2">{{ $counts['pending_reviews'] }}</div>
                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pending Reviews</div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col items-center text-center">
                <div class="text-4xl font-extrabold text-emerald-600 mb-2">{{ $counts['published_articles'] }}</div>
                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">Published Articles</div>
            </div>
        </div>

        @if(isset($recentSubmissions) && count($recentSubmissions) > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                    <h3 class="text-lg font-serif font-black text-slate-900">Recent Submissions</h3>
                    <a href="{{ route('submission.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 uppercase tracking-wider">
                        View All &rarr;
                    </a>
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach($recentSubmissions as $sub)
                        <div class="py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <span class="text-xs font-mono text-slate-400">#{{ $sub->id }}</span>
                                <h4 class="text-sm font-bold text-slate-800 hover:text-blue-600 transition-colors">
                                    <a href="{{ route('submission.show', $sub) }}">{{ $sub->title }}</a>
                                </h4>
                                <span class="text-xs text-slate-500">
                                    {{ $sub->journal ? $sub->journal->title : ($sub->conference ? $sub->conference->title : 'General Submission') }}
                                </span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="px-2.5 py-1 text-[11px] font-bold rounded-full uppercase tracking-wider
                                    @if($sub->status === 'published') bg-emerald-100 text-emerald-800
                                    @elseif($sub->status === 'accepted') bg-blue-100 text-blue-800
                                    @elseif($sub->status === 'rejected') bg-rose-100 text-rose-800
                                    @else bg-amber-100 text-amber-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $sub->status)) }}
                                </span>
                                <a href="{{ route('submission.show', $sub) }}" class="text-xs font-bold text-blue-600 hover:underline">
                                    Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>
@endsection