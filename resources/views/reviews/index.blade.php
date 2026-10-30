@extends('layouts.web')

@section('title', 'Review Assignments - HJPARAM Publication')

@section('content')
<div class="bg-slate-50 min-h-screen py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto space-y-6">

        <!-- Header -->
        <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-200 pb-5">
            <div>
                <nav class="flex items-center text-xs font-semibold text-slate-500 gap-2 mb-1">
                    <a href="{{ route('home') }}" class="hover:text-blue-600 transition-colors">Home</a>
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="text-slate-800 font-bold">Review Assignments</span>
                </nav>
                <h1 class="text-2xl font-serif font-black text-slate-900 tracking-tight">My Assignments</h1>
            </div>
            <span class="px-3.5 py-1.5 bg-blue-100 text-blue-800 rounded-full text-xs font-black uppercase tracking-wider">
                Pending: {{ $reviews->whereNull('completed_at')->count() }}
            </span>
        </div>

        @if($reviews->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-slate-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Submission</th>
                                <th scope="col"
                                    class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th scope="col"
                                    class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Assigned Date</th>
                                <th scope="col"
                                    class="px-6 py-3.5 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($reviews as $review)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-gray-900 mb-1">
                                            {{ $review->submission->title }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ $review->submission->journal->title ?? ($review->submission->conference->title ?? 'N/A') }} | ID: #{{ $review->submission->id }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($review->completed_at)
                                            <span
                                                class="px-2.5 py-1 inline-flex text-xs font-bold rounded-full bg-emerald-100 text-emerald-800 uppercase tracking-wider">
                                                Completed
                                            </span>
                                        @else
                                            <span
                                                class="px-2.5 py-1 inline-flex text-xs font-bold rounded-full bg-amber-100 text-amber-800 uppercase tracking-wider">
                                                Pending Review
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                        {{ $review->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-bold">
                                        <a href="{{ route('reviews.show', $review->id) }}"
                                            class="text-blue-600 hover:text-blue-800">
                                            {{ $review->completed_at ? 'View Review' : 'Start Review' }} &rarr;
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-sm p-12 text-center border border-slate-200">
                <div class="mx-auto h-12 w-12 text-slate-400 mb-4 flex items-center justify-center">
                    <svg fill="none" class="h-12 w-12 text-slate-300" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800">No assignments found</h3>
                <p class="mt-2 text-sm text-slate-500">You currently have no pending review assignments.</p>
            </div>
        @endif
    </div>
</div>
@endsection