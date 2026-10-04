@extends('layouts.admin')

@section('title', 'Volumes & Issues - ' . $journal->title)
@section('breadcrumb', 'Journals / ' . $journal->title . ' / Volumes & Issues')

@section('content')
<div class="space-y-8">
    <!-- Top Header -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200">
                    Archival Registry
                </span>
                <span class="text-xs text-gray-500">ISSN: {{ $journal->issn ?? 'N/A' }}</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $journal->title }}</h1>
            <p class="text-xs text-gray-500 mt-1">Manage archival volumes and publishable issues for this journal.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('journals.show', $journal->slug) }}" target="_blank"
                class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition flex items-center gap-1.5 border border-slate-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                View on Site
            </a>
            <a href="{{ route('admin.journals.edit', $journal) }}"
                class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition flex items-center gap-1.5 border border-slate-200">
                Edit Journal Details
            </a>
            <a href="{{ route('admin.journals.index') }}"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition shadow-sm">
                ← All Journals
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-sm font-medium flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(isset($errors) && $errors->any())
        <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-red-800 text-sm font-medium space-y-1">
            @foreach($errors->all() as $error)
                <div>• {{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Left Column: Add New Volume Card -->
        <div class="lg:col-span-4 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 sticky top-24">
            <div class="flex items-center gap-2 mb-4 pb-3 border-b border-gray-100 dark:border-gray-700">
                <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">+</div>
                <div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white">Create Volume</h2>
                    <p class="text-[11px] text-gray-500">e.g. Volume 1 (2026)</p>
                </div>
            </div>

            <form action="{{ route('admin.journals.volumes.store', $journal) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-400 mb-1.5">
                        Volume Number <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="volume_number" value="{{ old('volume_number', ($journal->volumes->max('volume_number') ? (int)$journal->volumes->max('volume_number') + 1 : 1)) }}" required placeholder="e.g. 1"
                        class="w-full px-3.5 py-2.5 rounded-xl bg-gray-50 border border-gray-200 text-sm font-semibold text-gray-800 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-400 mb-1.5">
                        Publication Year <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="year" value="{{ old('year', date('Y')) }}" required min="1900" max="2100" placeholder="e.g. {{ date('Y') }}"
                        class="w-full px-3.5 py-2.5 rounded-xl bg-gray-50 border border-gray-200 text-sm font-semibold text-gray-800 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition">
                </div>

                <div class="pt-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_published" value="1" checked class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                        <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">Publish Immediately (Visible to public)</span>
                    </label>
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs uppercase tracking-wider transition shadow-sm flex items-center justify-center gap-2">
                        <span>Save Volume</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Right Column: Volumes & Issues Hierarchy -->
        <div class="lg:col-span-8 space-y-6">
            <div class="flex items-center justify-between pb-2">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <span>Archival Hierarchy</span>
                    <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-700">
                        {{ $journal->volumes->count() }} {{ Str::plural('Volume', (int)$journal->volumes->count()) }}
                    </span>
                </h2>
            </div>

            @forelse($journal->volumes as $volume)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden" x-data="{ addIssueOpen: false }">
                    <!-- Volume Header -->
                    <div class="p-5 bg-gradient-to-r from-slate-50 to-white dark:from-gray-700/50 dark:to-gray-800 border-b border-gray-100 dark:border-gray-700 flex flex-wrap items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-serif font-black text-base shadow-sm">
                                V{{ $volume->volume_number }}
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-gray-900 dark:text-white leading-tight">
                                    Volume {{ $volume->volume_number }} <span class="text-gray-400 font-normal">({{ $volume->year }})</span>
                                </h3>
                                <div class="flex items-center gap-2 mt-0.5">
                                    @if($volume->is_published)
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">Published</span>
                                    @else
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full">Draft</span>
                                    @endif
                                    <span class="text-xs text-gray-400">•</span>
                                    <span class="text-xs text-gray-500">{{ $volume->issues->count() }} {{ Str::plural('Issue', (int)$volume->issues->count()) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <button type="button" @click="addIssueOpen = !addIssueOpen"
                                class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold rounded-lg transition border border-blue-200 flex items-center gap-1">
                                <span x-text="addIssueOpen ? 'Close Form' : '+ Add Issue'"></span>
                            </button>
                            <form action="{{ route('admin.journals.volumes.destroy', [$journal, $volume]) }}" method="POST"
                                onsubmit="return confirm('Delete Volume {{ $volume->volume_number }}? This will also remove all its issues.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Delete Volume">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Add Issue Collapsible Form -->
                    <div x-show="addIssueOpen" x-transition class="p-5 bg-blue-50/50 border-b border-blue-100">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-blue-900 mb-3">Add Issue to Volume {{ $volume->volume_number }}</h4>
                        <form action="{{ route('admin.volumes.issues.store', $volume) }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 items-end">
                            @csrf
                            <div>
                                <label class="block text-[11px] font-bold text-gray-600 mb-1">Issue Number <span class="text-red-500">*</span></label>
                                <input type="text" name="issue_number" value="{{ $volume->issues->max('issue_number') ? (int)$volume->issues->max('issue_number') + 1 : 1 }}" required placeholder="e.g. 1"
                                    class="w-full px-3 py-2 rounded-lg bg-white border border-gray-200 text-xs font-semibold text-gray-800">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-gray-600 mb-1">Publication Date</label>
                                <input type="date" name="publication_date" value="{{ date('Y-m-d') }}"
                                    class="w-full px-3 py-2 rounded-lg bg-white border border-gray-200 text-xs font-semibold text-gray-800">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-gray-600 mb-1">Special Issue Title (Optional)</label>
                                <input type="text" name="special_issue_title" placeholder="e.g. AI in Healthcare"
                                    class="w-full px-3 py-2 rounded-lg bg-white border border-gray-200 text-xs font-semibold text-gray-800">
                            </div>
                            <div class="flex items-center gap-2">
                                <label class="flex items-center gap-1.5 cursor-pointer text-xs font-medium text-gray-700">
                                    <input type="checkbox" name="is_published" value="1" checked class="w-3.5 h-3.5 text-blue-600 rounded border-gray-300">
                                    <span>Publish</span>
                                </label>
                                <button type="submit" class="flex-1 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg text-xs transition">
                                    Save Issue
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Issues List -->
                    <div class="p-5">
                        @if($volume->issues->isEmpty())
                            <div class="text-center py-6 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-xl">
                                <p class="text-xs text-gray-400 italic mb-2">No issues published in this volume yet.</p>
                                <button type="button" @click="addIssueOpen = true" class="text-xs font-bold text-blue-600 hover:underline">
                                    + Add Issue 1 now
                                </button>
                            </div>
                        @else
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach($volume->issues as $issue)
                                    <div class="p-3.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 hover:bg-white hover:shadow-sm transition flex items-center justify-between gap-2">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('journals.issue', [$journal->slug, $volume->volume_number, $issue->issue_number]) }}" target="_blank"
                                                    class="font-bold text-sm text-blue-700 hover:underline flex items-center gap-1">
                                                    Issue {{ $issue->issue_number }}
                                                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                </a>
                                                @if($issue->is_published)
                                                    <span class="w-2 h-2 rounded-full bg-emerald-500" title="Published"></span>
                                                @else
                                                    <span class="w-2 h-2 rounded-full bg-gray-400" title="Draft"></span>
                                                @endif
                                            </div>
                                            @if($issue->special_issue_title)
                                                <p class="text-[11px] font-medium text-purple-700 truncate max-w-[180px]" title="{{ $issue->special_issue_title }}">
                                                    ★ {{ $issue->special_issue_title }}
                                                </p>
                                            @endif
                                            <div class="text-[10px] text-gray-400 mt-1 flex items-center gap-2">
                                                @if($issue->publication_date)
                                                    <span>{{ \Carbon\Carbon::parse($issue->publication_date)->format('M Y') }}</span>
                                                @endif
                                                <span>{{ (int)($issue->articles_count ?? 0) }} {{ Str::plural('article', (int)($issue->articles_count ?? 0)) }}</span>
                                            </div>
                                        </div>

                                        <form action="{{ route('admin.volumes.issues.destroy', [$volume, $issue]) }}" method="POST"
                                            onsubmit="return confirm('Delete Issue {{ $issue->issue_number }} from Volume {{ $volume->volume_number }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-gray-300 hover:text-red-500 rounded-md transition" title="Delete Issue">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-dashed border-gray-300 dark:border-gray-700 p-12 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white mb-1">No Volumes Registered Yet</h3>
                    <p class="text-xs text-gray-500 max-w-sm mx-auto mb-6">
                        This journal has no volumes or issues. Use the "Create Volume" form on the left to initialize Volume 1 for {{ date('Y') }}.
                    </p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
