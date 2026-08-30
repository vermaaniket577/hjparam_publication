@extends('layouts.admin')

@section('title', 'Manage Submissions')
@section('breadcrumb', 'Submissions')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 dark:text-white">Manage Submissions</h2>
            <p class="text-xs text-slate-500 mt-0.5">Conference & journal manuscripts, reviews, payments, and certificates</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200/80 dark:border-slate-700 p-4">
        <form action="{{ route('admin.submissions.index') }}" method="GET" class="flex flex-wrap gap-4 items-center">
            <div class="w-full sm:w-48">
                <select name="status" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700 text-xs font-semibold dark:text-white">
                    <option value="">All Review Statuses</option>
                    <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                    <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Under Review</option>
                    <option value="revision_requested" {{ request('status') == 'revision_requested' ? 'selected' : '' }}>Revision Requested</option>
                    <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                </select>
            </div>

            <div class="w-full sm:w-48">
                <select name="payment_status" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700 text-xs font-semibold dark:text-white">
                    <option value="">All Fee Statuses</option>
                    <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    <option value="pending_verification" {{ request('payment_status') == 'pending_verification' ? 'selected' : '' }}>Pending Verification</option>
                    <option value="verified" {{ request('payment_status') == 'verified' ? 'selected' : '' }}>Fee Verified</option>
                    <option value="rejected" {{ request('payment_status') == 'rejected' ? 'selected' : '' }}>Fee Rejected</option>
                </select>
            </div>

            <button type="submit" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl shadow-sm transition-all">
                Filter
            </button>
            <a href="{{ route('admin.submissions.index') }}" class="px-4 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold rounded-xl hover:bg-slate-200 transition-all">
                Reset
            </a>
        </form>
    </div>

    <!-- Submissions Table -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm overflow-hidden border border-slate-200/80 dark:border-slate-700">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                <thead class="bg-slate-50 dark:bg-slate-700/50">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3.5 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Title / Authors</th>
                        <th class="px-6 py-3.5 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Conference / Journal</th>
                        <th class="px-6 py-3.5 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Submitted</th>
                        <th class="px-6 py-3.5 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3.5 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Fee</th>
                        <th class="px-6 py-3.5 text-right text-[11px] font-bold text-slate-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700 bg-white dark:bg-slate-800 text-xs">
                    @forelse($submissions as $submission)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/40 transition-colors">
                            <td class="px-6 py-4 font-mono font-bold text-slate-600 dark:text-slate-400">
                                #{{ $submission->id }}
                            </td>
                            <td class="px-6 py-4 max-w-xs">
                                <a href="{{ route('admin.submissions.show', $submission) }}" class="font-bold text-slate-900 dark:text-white hover:text-blue-600 block line-clamp-1">
                                    {{ $submission->title }}
                                </a>
                                <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">
                                    {{ $submission->user ? $submission->user->name : 'Anonymous' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($submission->conference)
                                    <span class="inline-block px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 text-[11px] font-bold">
                                        {{ Str::limit($submission->conference->title, 25) }}
                                    </span>
                                @elseif($submission->journal)
                                    <span class="inline-block px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 text-[11px] font-bold">
                                        {{ Str::limit($submission->journal->title, 25) }}
                                    </span>
                                @else
                                    <span class="text-slate-400">General</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-500 dark:text-slate-400">
                                {{ $submission->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase
                                    {{ $submission->status === 'accepted' || $submission->status === 'published' ? 'bg-emerald-100 text-emerald-800' :
                                      ($submission->status === 'revision_requested' ? 'bg-amber-100 text-amber-800' :
                                      ($submission->status === 'rejected' ? 'bg-rose-100 text-rose-800' : 'bg-blue-100 text-blue-800')) }}">
                                    {{ str_replace('_', ' ', $submission->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                    {{ $submission->payment_status === 'verified' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 
                                      ($submission->payment_status === 'pending_verification' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'text-slate-400') }}">
                                    {{ $submission->payment_status ?? 'unpaid' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('admin.submissions.show', $submission) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition-all shadow-xs">
                                    Manage
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400 text-xs">
                                No submissions found matching the criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($submissions->hasPages())
            <div class="px-6 py-4 bg-slate-50 dark:bg-slate-700/50 border-t border-slate-200 dark:border-slate-700">
                {{ $submissions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection