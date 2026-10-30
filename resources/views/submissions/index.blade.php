@extends('layouts.web')

@section('title', 'My Submissions - HJPARAM Publication')

@section('content')
<div class="bg-slate-50 min-h-screen py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto space-y-6">

        <!-- Breadcrumb & Header -->
        <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-200 pb-5">
            <div>
                <nav class="flex items-center text-xs font-semibold text-slate-500 gap-2 mb-1">
                    <a href="{{ route('home') }}" class="hover:text-blue-600 transition-colors">Home</a>
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition-colors">Author Hub</a>
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="text-slate-800 font-bold">My Submissions</span>
                </nav>
                <h1 class="text-2xl font-serif font-black text-slate-900 tracking-tight">My Submissions</h1>
            </div>
            <a href="{{ route('submission.create') }}"
                class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-md shadow-blue-600/25 flex items-center gap-1.5">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>New Submission</span>
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-slate-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Manuscript</th>
                            <th scope="col"
                                class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Journal</th>
                            <th scope="col"
                                class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date
                            </th>
                            <th scope="col"
                                class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th scope="col"
                                class="px-6 py-3.5 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($submissions as $submission)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-gray-900">{{ Str::limit($submission->title, 60) }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-0.5">ID: #{{ $submission->id }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $submission->journal->title ?? ($submission->conference->title ?? 'N/A') }}
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-500">
                                    {{ $submission->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 text-[11px] font-bold rounded-full uppercase tracking-wider
                                        @if($submission->status === 'published') bg-emerald-100 text-emerald-800
                                        @elseif($submission->status === 'accepted') bg-blue-100 text-blue-800
                                        @elseif($submission->status === 'rejected') bg-rose-100 text-rose-800
                                        @else bg-amber-100 text-amber-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $submission->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-xs font-bold">
                                    <a href="{{ route('submission.show', $submission) }}" class="text-blue-600 hover:text-blue-800">
                                        View &rarr;
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500 text-sm">
                                    No submissions found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($submissions->hasPages())
                <div class="p-4 border-t border-slate-200">
                    {{ $submissions->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
