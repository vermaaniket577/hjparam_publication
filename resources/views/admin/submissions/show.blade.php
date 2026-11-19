@extends('layouts.admin')
@section('title', 'Submission Management #' . $submission->id)
@section('breadcrumb', 'Submissions / #' . $submission->id)

@section('content')
@php
    $jsSubmissionData = [
        'authors' => array_values($authorsList ?? []),
        'fallbackName' => (string) ($submission->user->name ?? ''),
        'fallbackEmail' => (string) ($submission->user->email ?? ''),
        'selectedJournalId' => (string) ($submission->journal_id ?? ($journals->first()->id ?? '')),
        'title' => (string) ($submission->title ?? ''),
        'abstract' => (string) ($submission->abstract ?? ''),
        'doi' => (string) ($submission->article?->doi ?? ('10.5281/hjparam.' . ($submission->journal->slug ?? 'pub') . '.' . date('Y') . '.' . str_pad((string) $submission->id, 4, '0', STR_PAD_LEFT))),
    ];
@endphp
<script>
    window.__submissionPageData = {!! json_encode($jsSubmissionData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE) !!};

    window.submissionAuthorsCard = function () {
        var d = window.__submissionPageData || {};
        var initialAuthors = Array.isArray(d.authors) ? JSON.parse(JSON.stringify(d.authors)) : [];
        if (initialAuthors.length === 0) {
            initialAuthors = [{
                name: d.fallbackName || '',
                email: d.fallbackEmail || '',
                institution: '',
                department: '',
                designation: '',
                mobile: '',
                orcid: ''
            }];
        }
        return {
            manageAuthors: false,
            authors: initialAuthors,
            addAuthor: function () {
                this.authors.push({
                    name: '',
                    email: '',
                    institution: '',
                    department: '',
                    designation: '',
                    mobile: '',
                    orcid: ''
                });
            },
            removeAuthor: function (idx) {
                if (this.authors.length > 1) {
                    this.authors.splice(idx, 1);
                }
            }
        };
    };

    window.submissionPublishModule = function () {
        var d = window.__submissionPageData || {};
        var initialAuthors = Array.isArray(d.authors) ? JSON.parse(JSON.stringify(d.authors)) : [];
        if (initialAuthors.length === 0) {
            initialAuthors = [{
                name: d.fallbackName || '',
                email: d.fallbackEmail || '',
                institution: '',
                role: 'author'
            }];
        }
        return {
            customOpen: false,
            selectedJournalId: d.selectedJournalId || '',
            title: d.title || '',
            abstract: d.abstract || '',
            doi: d.doi || '',
            authors: initialAuthors,
            addAuthor: function () {
                this.authors.push({ name: '', email: '', institution: '', role: 'co_author' });
            },
            removeAuthor: function (idx) {
                if (this.authors.length > 1) {
                    this.authors.splice(idx, 1);
                }
            }
        };
    };
</script>
<div class="max-w-7xl mx-auto space-y-8 pb-24">

    <!-- Top Status Banner -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 md:p-8 shadow-sm border border-slate-200/80 dark:border-slate-700 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-1.5">
            <div class="flex items-center gap-3 flex-wrap">
                <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider
                    {{ $submission->status === 'accepted' || $submission->status === 'published' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300' : 
                      ($submission->status === 'revision_requested' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300' : 
                      ($submission->status === 'rejected' ? 'bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300')) }}">
                    Status: {{ ucfirst(str_replace('_', ' ', $submission->status)) }}
                </span>
                
                @if($submission->payment_status)
                    <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider
                        {{ $submission->payment_status === 'verified' ? 'bg-emerald-500 text-white' : 
                          ($submission->payment_status === 'pending_verification' ? 'bg-amber-500 text-white' : 'bg-slate-200 text-slate-700') }}">
                        Fee: {{ ucfirst(str_replace('_', ' ', $submission->payment_status)) }}
                    </span>
                @endif

                @if($submission->conference)
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                        {{ $submission->conference->title }}
                    </span>
                @endif
            </div>
            <h1 class="text-xl md:text-2xl font-bold text-slate-900 dark:text-white">{{ $submission->title }}</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400">Submission Reference #SUB-{{ str_pad($submission->id, 5, '0', STR_PAD_LEFT) }} • Submitted {{ $submission->created_at->format('M d, Y - h:i A') }}</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            @if($submission->article)
                <a href="{{ route('articles.show', [$submission->article->journal->slug, $submission->article->slug]) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-md shadow-emerald-600/20 transition-all">
                    🌐 Live Article
                </a>
            @else
                <a href="#publish-paper-section" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-xl text-xs font-bold shadow-md shadow-purple-600/20 transition-all">
                    🚀 Publish Paper
                </a>
            @endif

            @if(in_array($submission->status, ['accepted', 'published']))
                <a href="{{ route('certificates.publication.submission', $submission) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl text-xs font-bold shadow-md shadow-emerald-700/20 transition-all">
                    🎓 Certificates ({{ rescue(fn() => $submission->relationLoaded('certificates') ? $submission->certificates->count() : (\Illuminate\Support\Facades\Schema::hasTable('certificates') ? $submission->certificates()->count() : 0), 0) }})
                </a>
            @endif
            <a href="{{ route('submission.copyright', $submission) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold shadow-md shadow-slate-800/20 transition-all" title="View / Print Copyright Agreement Form">
                📜 Copyright Form
            </a>
            <a href="{{ route('admin.submissions.download', $submission) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md shadow-blue-600/20 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Download Manuscript
            </a>
            <a href="{{ route('admin.submissions.index') }}" class="px-4 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-bold hover:bg-slate-200 transition-all">
                Back
            </a>
        </div>
    </div>

    <!-- Main Grid: Left = Manuscript Details & Scholar Info, Right = 8-Step Lifecycle Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- LEFT: Information Panel -->
        <div class="lg:col-span-5 space-y-6">
            
            <!-- Article & Abstract -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200/80 dark:border-slate-700 shadow-sm space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Manuscript Information</h3>
                
                <div class="space-y-3 text-xs">
                    <div>
                        <span class="text-slate-400 font-semibold block">Article Type:</span>
                        <span class="font-bold text-slate-800 dark:text-white capitalize">{{ str_replace('_', ' ', $submission->article_type ?? 'Research Paper') }}</span>
                    </div>

                    @if($submission->keywords)
                        <div>
                            <span class="text-slate-400 font-semibold block">Keywords:</span>
                            <span class="font-medium text-slate-700 dark:text-slate-300">{{ $submission->keywords }}</span>
                        </div>
                    @endif

                    <div>
                        <span class="text-slate-400 font-semibold block mb-1">Abstract:</span>
                        <div class="p-3.5 bg-slate-50 dark:bg-slate-700/50 rounded-xl text-slate-600 dark:text-slate-300 text-xs leading-relaxed max-h-56 overflow-y-auto">
                            {{ $submission->abstract }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scholar & Authors List & Management -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200/80 dark:border-slate-700 shadow-sm space-y-4"
                x-data="submissionAuthorsCard()">
                <div class="flex items-center justify-between gap-2 flex-wrap">
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Scholar &amp; Author Details</h3>
                        <p class="text-[11px] text-slate-500">Author attribution &amp; certificate recipients</p>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($submission->article)
                            <a href="{{ route('admin.articles.authors.manage', $submission->article) }}"
                                class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300 rounded-xl text-xs font-bold flex items-center gap-1 transition-colors"
                                title="Open full Paper Authors Manager (reorder, corresponding author, live preview)">
                                👥 Full Editor
                            </a>
                        @endif
                        <button type="button" @click="manageAuthors = !manageAuthors"
                            class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300 rounded-xl text-xs font-bold flex items-center gap-1.5 transition-colors">
                            <span x-text="manageAuthors ? 'Cancel Edit' : '✏️ Manage Authors'"></span>
                        </button>
                    </div>
                </div>

                {{-- View Mode --}}
                <div x-show="!manageAuthors" class="space-y-3">
                    @forelse($authorsList as $idx => $author)
                        <div class="p-3.5 bg-slate-50 dark:bg-slate-700/40 rounded-xl border border-slate-200/60 dark:border-slate-600 text-xs space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-900 dark:text-white">{{ $author['name'] }}</span>
                                <span class="text-[10px] font-bold {{ $idx === 0 ? 'text-emerald-700 bg-emerald-50 dark:bg-emerald-950/60 dark:text-emerald-300' : 'text-blue-600 bg-blue-50 dark:bg-blue-900/40 dark:text-blue-300' }} px-2 py-0.5 rounded">
                                    {{ $idx === 0 ? 'Lead Author' : 'Co-Author #' . $idx }}
                                </span>
                            </div>
                            @if(!empty($author['institution']) || !empty($author['designation']) || !empty($author['department']))
                                <p class="text-slate-500 dark:text-slate-400">
                                    {{ $author['designation'] ?? '' }}
                                    {{ !empty($author['department']) ? '• ' . $author['department'] : '' }}
                                    {{ !empty($author['institution']) ? '• ' . $author['institution'] : '' }}
                                </p>
                            @endif
                            <div class="flex items-center gap-3 pt-1 text-[11px] text-slate-500">
                                @if(!empty($author['email']))
                                    <span>📧 {{ $author['email'] }}</span>
                                @endif
                                @if(!empty($author['city_country']))
                                    <span>📍 {{ $author['city_country'] }}</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-3 bg-slate-50 rounded-xl text-xs">
                            <p class="font-bold text-slate-800">{{ $submission->user->name }}</p>
                            <p class="text-slate-500">{{ $submission->user->email }}</p>
                        </div>
                    @endforelse
                </div>

                {{-- Edit / Manage Mode --}}
                <div x-show="manageAuthors" x-cloak class="pt-2 border-t border-slate-200 dark:border-slate-700 space-y-4">
                    <form action="{{ route('admin.submissions.update', $submission) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="update_authors" value="1">

                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold text-slate-600 dark:text-slate-300">Update authors list (syncs with Certificates &amp; Articles)</span>
                            <button type="button" @click="addAuthor()" class="text-xs font-bold text-emerald-600 hover:text-emerald-700">
                                + Add Co-Author
                            </button>
                        </div>

                        <div class="space-y-3">
                            <template x-for="(author, index) in authors" :key="index">
                                <div class="p-3.5 bg-slate-50 dark:bg-slate-700/50 rounded-2xl border border-slate-200 dark:border-slate-600 space-y-2.5">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[10px] font-black uppercase tracking-wider text-slate-500" x-text="index === 0 ? 'Lead Author' : 'Co-Author #' + index"></span>
                                        <button type="button" @click="removeAuthor(index)" x-show="authors.length > 1" class="text-rose-500 hover:text-rose-700 font-bold text-xs">
                                            Remove
                                        </button>
                                    </div>

                                    <div>
                                        <input type="text" :name="`authors[${index}][name]`" x-model="author.name" placeholder="Author Full Name *" required
                                            class="w-full px-3 py-1.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-xs font-bold text-slate-800 dark:text-white">
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                        <input type="email" :name="`authors[${index}][email]`" x-model="author.email" placeholder="Institutional Email"
                                            class="w-full px-3 py-1.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-xs text-slate-700 dark:text-slate-200">
                                        <input type="text" :name="`authors[${index}][institution]`" x-model="author.institution" placeholder="University / Affiliation"
                                            class="w-full px-3 py-1.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-xs text-slate-700 dark:text-slate-200">
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                        <input type="text" :name="`authors[${index}][department]`" x-model="author.department" placeholder="Department"
                                            class="w-full px-3 py-1.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-xs text-slate-700 dark:text-slate-200">
                                        <input type="text" :name="`authors[${index}][designation]`" x-model="author.designation" placeholder="Designation (e.g. Professor)"
                                            class="w-full px-3 py-1.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-xs text-slate-700 dark:text-slate-200">
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-2">
                            <button type="button" @click="manageAuthors = false" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-500 hover:text-slate-700">
                                Cancel
                            </button>
                            <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md shadow-blue-600/20 transition-all">
                                Save Authors
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Assigned Staff Summary -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200/80 dark:border-slate-700 shadow-sm space-y-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Assigned Editorial Desk</h3>
                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div class="p-3 bg-slate-50 dark:bg-slate-700/40 rounded-xl">
                        <span class="text-[10px] text-slate-400 font-bold uppercase block">Handling Editor</span>
                        <span class="font-bold text-slate-800 dark:text-white">{{ $submission->editor ? $submission->editor->name : 'Unassigned' }}</span>
                    </div>
                    <div class="p-3 bg-slate-50 dark:bg-slate-700/40 rounded-xl">
                        <span class="text-[10px] text-slate-400 font-bold uppercase block">Assigned Reviewers</span>
                        <span class="font-bold text-slate-800 dark:text-white">{{ $submission->reviews->count() }} Reviewer(s)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT: 8-Step Complete Administrative Flow -->
        <div class="lg:col-span-7 space-y-6">

            <!-- DIRECT JOURNAL PUBLICATION MODULE (COMPLETE ADMINISTRATIVE FLOW) -->
            <div id="publish-paper-section" class="rounded-3xl p-6 md:p-8 shadow-xl transition-all border {{ $submission->article ? 'border-emerald-600/50' : 'border-purple-600/50' }}"
                style="background: {{ $submission->article ? 'linear-gradient(135deg, #022c22 0%, #0f172a 50%, #042f2e 100%)' : 'linear-gradient(135deg, #1e1b4b 0%, #0f172a 50%, #3b0764 100%)' }}; color: #ffffff;"
                x-data="submissionPublishModule()">

                @if($submission->article)
                    {{-- Published Article Summary & Management --}}
                    <div class="space-y-4">
                        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-white/10 pb-4">
                            <div class="flex items-center gap-2.5">
                                <span class="w-8 h-8 rounded-xl bg-emerald-500 text-slate-950 flex items-center justify-center font-black text-sm">✓</span>
                                <div>
                                    <span class="text-[10px] uppercase font-black tracking-widest text-emerald-400 block" style="color: #34d399 !important;">Publication Verified &amp; Live</span>
                                    <h3 class="text-base font-bold text-white" style="color: #ffffff !important;">Officially Published in {{ $submission->article->journal->title }}</h3>
                                </div>
                            </div>
                            <span class="px-3 py-1 bg-emerald-500/20 text-emerald-300 rounded-full border border-emerald-400/30 text-xs font-mono font-bold" style="background-color: rgba(16, 185, 129, 0.2); color: #6ee7b7; border-color: rgba(52, 211, 153, 0.3);">
                                Article #{{ $submission->article->id }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs p-4 rounded-2xl border" style="background-color: rgba(255, 255, 255, 0.06); border-color: rgba(255, 255, 255, 0.12);">
                            <div>
                                <span class="text-[10px] font-bold uppercase block" style="color: #94a3b8 !important;">Journal &amp; ISSN</span>
                                <span class="font-bold block" style="color: #ffffff !important;">{{ $submission->article->journal->title }}</span>
                                <span class="text-[11px] font-mono" style="color: #34d399 !important;">ISSN: {{ $submission->article->journal->issn ?: 'Applied' }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold uppercase block" style="color: #94a3b8 !important;">Volume &amp; Issue</span>
                                <span class="font-bold block" style="color: #ffffff !important;">
                                    Vol. {{ $submission->article->issue?->volume?->volume_number ?? '1' }} ({{ $submission->article->issue?->volume?->year ?? date('Y') }})
                                </span>
                                <span class="text-[11px]" style="color: #cbd5e1 !important;">Issue No. {{ $submission->article->issue?->issue_number ?? '1' }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold uppercase block" style="color: #94a3b8 !important;">DOI &amp; Release Date</span>
                                <a href="https://doi.org/{{ $submission->article->doi }}" target="_blank" class="font-mono hover:underline block truncate" style="color: #93c5fd !important;">
                                    {{ $submission->article->doi ?: 'Not Assigned' }}
                                </a>
                                <span class="text-[11px]" style="color: #cbd5e1 !important;">{{ $submission->article->published_at ? $submission->article->published_at->format('M d, Y') : now()->format('M d, Y') }}</span>
                            </div>
                        </div>

                        {{-- Quick Action Hub --}}
                        <div class="flex flex-wrap items-center gap-3 pt-2">
                            <a href="{{ route('articles.show', [$submission->article->journal->slug, $submission->article->slug]) }}" target="_blank"
                                class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black rounded-xl text-xs flex items-center gap-2 shadow-lg shadow-emerald-500/20 transition-all"
                                style="background-color: #10b981 !important; color: #022c22 !important;">
                                <span>🌐 View Live Article on Website</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                            <a href="{{ route('articles.download', $submission->article->id) }}" target="_blank"
                                class="px-4 py-2.5 hover:bg-white/20 font-bold rounded-xl text-xs flex items-center gap-2 transition-all"
                                style="background-color: rgba(255, 255, 255, 0.12); color: #ffffff !important;">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Official Academic PDF
                            </a>
                            <a href="{{ route('certificates.publication.article', $submission->article) }}" target="_blank"
                                class="px-4 py-2.5 hover:bg-white/20 font-bold rounded-xl text-xs flex items-center gap-2 transition-all"
                                style="background-color: rgba(255, 255, 255, 0.12); color: #fde047 !important;">
                                🎓 Publication Certificates
                            </a>
                            <a href="{{ route('admin.articles.authors.manage', $submission->article) }}"
                                class="px-4 py-2.5 hover:bg-indigo-500/30 font-bold rounded-xl text-xs flex items-center gap-2 transition-all"
                                style="background-color: rgba(99, 102, 241, 0.25); color: #c7d2fe !important; border: 1px solid rgba(165, 180, 252, 0.35);">
                                👥 Manage Paper Authors ({{ $submission->article->authors->count() }})
                            </a>
                            @if($submission->article->has_manuscript)
                                <a href="{{ route('articles.manuscript', $submission->article->id) }}"
                                    class="px-4 py-2.5 hover:bg-blue-500/30 font-bold rounded-xl text-xs flex items-center gap-2 transition-all"
                                    style="background-color: rgba(59, 130, 246, 0.25); color: #bfdbfe !important; border: 1px solid rgba(147, 197, 253, 0.3);"
                                    title="Download exact original Word document ({{ $submission->article->manuscript_extension }})">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Original Word ({{ $submission->article->manuscript_extension }})
                                </a>
                            @endif
                            <button @click="customOpen = !customOpen" type="button"
                                class="px-4 py-2.5 hover:bg-white/15 font-bold rounded-xl text-xs flex items-center gap-1.5 transition-all ml-auto"
                                style="background-color: rgba(255, 255, 255, 0.08); color: #e2e8f0 !important;">
                                <span x-text="customOpen ? 'Hide Edit Form' : 'Update Publication Details'"></span>
                                <svg class="w-3.5 h-3.5 transition-transform" :class="{ 'rotate-180': customOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                        </div>
                    </div>
                @else
                    {{-- Pre-Publication Direct Manual Publishing Flow --}}
                    <div class="space-y-4">
                        <div class="flex items-center justify-between border-b border-white/10 pb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-purple-500 to-indigo-500 text-white flex items-center justify-center font-black shadow-lg shadow-purple-500/30">
                                    🚀
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-white">Publish Paper into Journal</h3>
                                    <p class="text-xs text-purple-200/80">Direct administrative flow: issue paper in a journal, assign DOI, issue certificates &amp; make live.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Fast 1-Click Action --}}
                        <div class="p-4 rounded-2xl bg-white/5 border border-white/10 flex flex-col sm:flex-row items-center justify-between gap-4">
                            <div>
                                <span class="text-xs font-bold text-emerald-300 block">⚡ Instant 1-Click Publish</span>
                                <p class="text-[11px] text-slate-300">Publishes into <strong>{{ $submission->journal->title ?? 'Selected Journal' }}</strong> with active Volume &amp; Issue, generates DOI &amp; certificates automatically.</p>
                            </div>
                            <form action="{{ route('admin.submissions.publish', $submission) }}" method="POST" class="shrink-0 w-full sm:w-auto">
                                @csrf
                                <input type="hidden" name="journal_id" value="{{ $submission->journal_id ?? ($journals->first()->id ?? '') }}">
                                <input type="hidden" name="title" value="{{ $submission->title }}">
                                <input type="hidden" name="abstract" value="{{ $submission->abstract }}">
                                <input type="hidden" name="keywords" value="{{ $submission->keywords }}">
                                <button type="submit" onclick="return confirm('Publish this paper directly to {{ $submission->journal->title ?? 'the journal' }}? This will index it live and issue certificates.')"
                                    class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-slate-950 font-black rounded-xl text-xs uppercase tracking-wider shadow-lg shadow-emerald-500/20 transition-all flex items-center justify-center gap-2">
                                    <span>Instant Publish Paper</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </button>
                            </form>
                        </div>

                        <div class="text-center pt-1">
                            <button @click="customOpen = !customOpen" type="button" class="text-xs font-bold text-purple-300 hover:text-white inline-flex items-center gap-1.5 transition-colors">
                                <span x-text="customOpen ? '▲ Close Custom Publication Form' : '▼ Or customize Journal, Volume, Issue, DOI, Proof PDF & Authors before publishing'"></span>
                            </button>
                        </div>
                    </div>
                @endif

                {{-- CUSTOM / EDIT PUBLICATION FORM --}}
                <div x-show="customOpen" x-cloak x-transition class="pt-6 border-t border-white/10 mt-6 space-y-6">
                    <form action="{{ route('admin.submissions.publish', $submission) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Target Journal -->
                            <div>
                                <label class="block text-xs font-bold text-slate-200 uppercase mb-2">Target Journal *</label>
                                <select name="journal_id" x-model="selectedJournalId" class="w-full px-4 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-xs font-bold text-white focus:ring-2 focus:ring-purple-500" required>
                                    @foreach($journals as $j)
                                        <option value="{{ $j->id }}" {{ ($submission->journal_id == $j->id) ? 'selected' : '' }}>
                                            {{ $j->title }} (ISSN: {{ $j->issn ?: 'Applied' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Issue Assignment -->
                            <div>
                                <label class="block text-xs font-bold text-slate-200 uppercase mb-2">Volume &amp; Issue Assignment</label>
                                <select name="issue_id" class="w-full px-4 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-xs font-medium text-white focus:ring-2 focus:ring-purple-500">
                                    <option value="">⚡ Auto-assign (Creates Volume 1, Issue 1 if not exists)</option>
                                    @foreach($journals as $j)
                                        <optgroup label="{{ $j->title }}">
                                            @foreach($j->volumes as $vol)
                                                @foreach($vol->issues as $iss)
                                                    <option value="{{ $iss->id }}" {{ ($submission->article?->issue_id == $iss->id) ? 'selected' : '' }}>
                                                        {{ $j->title }} &rarr; Vol. {{ $vol->volume_number }} ({{ $vol->year }}) - Issue {{ $iss->issue_number }}
                                                    </option>
                                                @endforeach
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Research Title -->
                        <div>
                            <label class="block text-xs font-bold text-slate-200 uppercase mb-2">Research Paper Title *</label>
                            <input type="text" name="title" x-model="title" class="w-full px-4 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-xs font-bold text-white focus:ring-2 focus:ring-purple-500" required>
                        </div>

                        <!-- Abstract -->
                        <div>
                            <label class="block text-xs font-bold text-slate-200 uppercase mb-2">Abstract *</label>
                            <textarea name="abstract" x-model="abstract" rows="4" class="w-full px-4 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-xs leading-relaxed text-slate-200 focus:ring-2 focus:ring-purple-500" required></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Digital Object Identifier (DOI) -->
                            <div>
                                <label class="block text-xs font-bold text-slate-200 uppercase mb-2">Digital Object Identifier (DOI)</label>
                                <input type="text" name="doi" x-model="doi" class="w-full px-4 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-xs font-mono font-bold text-blue-300 focus:ring-2 focus:ring-purple-500">
                            </div>

                            <!-- Keywords -->
                            <div>
                                <label class="block text-xs font-bold text-slate-200 uppercase mb-2">Keywords</label>
                                <input type="text" name="keywords" value="{{ $submission->keywords }}" placeholder="Comma-separated keywords" class="w-full px-4 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-xs text-white focus:ring-2 focus:ring-purple-500">
                            </div>

                            <!-- Official Release Date -->
                            <div>
                                <label class="block text-xs font-bold text-slate-200 uppercase mb-2">Official Release Date</label>
                                <input type="date" name="published_at" value="{{ $submission->article?->published_at ? $submission->article->published_at->format('Y-m-d') : date('Y-m-d') }}" class="w-full px-4 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-xs text-white focus:ring-2 focus:ring-purple-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Upload Original Word Document (.doc, .docx) -->
                            <div>
                                <label class="block text-xs font-bold text-slate-200 uppercase mb-2">
                                    Original Word Document (.doc, .docx)
                                    @if($submission->article?->has_manuscript)
                                        <span class="ml-1.5 px-2 py-0.5 rounded text-[10px] bg-blue-500/20 text-blue-300 font-mono">
                                            Current: {{ $submission->article->manuscript_extension }} ({{ $submission->article->manuscript_size_formatted ?: 'Saved' }})
                                        </span>
                                    @endif
                                </label>
                                <input type="file" name="manuscript_file" accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" class="w-full px-4 py-2 rounded-xl bg-slate-800 border border-slate-700 text-xs text-slate-300 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer">
                                <p class="text-[11px] text-slate-400 mt-1">Upload or replace the author's original Word manuscript. Stored securely and served byte-for-byte to readers.</p>
                            </div>

                            <!-- Upload Final Proof PDF -->
                            <div>
                                <label class="block text-xs font-bold text-slate-200 uppercase mb-2">
                                    Production PDF (Exact Diagrams, Tables, Images &amp; Watermarks)
                                </label>
                                <input type="file" name="pdf_file" accept=".pdf" class="w-full px-4 py-2 rounded-xl bg-slate-800 border border-slate-700 text-xs text-slate-300 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-purple-600 file:text-white hover:file:bg-purple-700 cursor-pointer">
                                <p class="text-[11px] text-slate-400 mt-1">Upload the paper saved directly as PDF from Microsoft Word to guarantee 100% original diagrams, tables, images, watermarks, and layout.</p>
                            </div>
                        </div>

                        <!-- Authors & Scholar Attribution -->
                        <div class="space-y-3 pt-2">
                            <div class="flex items-center justify-between">
                                <label class="text-xs font-bold text-slate-200 uppercase">Author &amp; Scholar Attribution</label>
                                <button type="button" @click="addAuthor()" class="text-[11px] font-bold text-emerald-400 hover:text-emerald-300">+ Add Co-Author</button>
                            </div>

                            <div class="space-y-3">
                                <template x-for="(author, index) in authors" :key="index">
                                    <div class="p-3 bg-white/5 rounded-xl border border-white/10 grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                                        <div class="sm:col-span-4">
                                            <input type="text" :name="`authors[${index}][name]`" x-model="author.name" placeholder="Author Full Name *" required class="w-full px-3 py-1.5 rounded-lg bg-slate-800 border border-slate-700 text-xs font-bold text-white">
                                        </div>
                                        <div class="sm:col-span-3">
                                            <input type="email" :name="`authors[${index}][email]`" x-model="author.email" placeholder="Institutional Email" class="w-full px-3 py-1.5 rounded-lg bg-slate-800 border border-slate-700 text-xs text-slate-200">
                                        </div>
                                        <div class="sm:col-span-4">
                                            <input type="text" :name="`authors[${index}][affiliation]`" x-model="author.institution" placeholder="University / Affiliation" class="w-full px-3 py-1.5 rounded-lg bg-slate-800 border border-slate-700 text-xs text-slate-200">
                                        </div>
                                        <div class="sm:col-span-1 flex justify-end">
                                            <button type="button" @click="removeAuthor(index)" x-show="authors.length > 1" class="text-rose-400 hover:text-rose-300 p-1 font-bold">&times;</button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-white/10">
                            <button type="button" @click="customOpen = false" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-400 hover:text-white transition-colors">
                                Cancel
                            </button>
                            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-black rounded-xl text-xs uppercase tracking-wider shadow-lg shadow-purple-600/30 transition-all flex items-center gap-2">
                                <span>{{ $submission->article ? 'Update & Re-Publish Article' : 'Confirm & Publish Paper Now' }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- STEP 1: Assign Editor & Reviewer -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 md:p-8 border border-slate-200/80 dark:border-slate-700 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700 pb-3">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-black">1</span>
                        Assign Editor & Reviewer for Paper Review
                    </h3>
                </div>

                <form action="{{ route('admin.submissions.assign', $submission) }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Assign Editor</label>
                        <select name="editor_id" class="w-full px-3.5 py-2 rounded-xl bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-xs font-medium dark:text-white">
                            <option value="">Select Editor</option>
                            @foreach($editors as $ed)
                                <option value="{{ $ed->id }}" {{ $submission->editor_id == $ed->id ? 'selected' : '' }}>{{ $ed->name }} ({{ $ed->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Assign Reviewer</label>
                        <select name="reviewer_id" class="w-full px-3.5 py-2 rounded-xl bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-xs font-medium dark:text-white">
                            <option value="">Select Reviewer</option>
                            @foreach($reviewers as $rev)
                                <option value="{{ $rev->id }}">{{ $rev->name }} ({{ $rev->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sm:col-span-2 flex justify-end">
                        <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-sm transition-all">
                            Save Assignment
                        </button>
                    </div>
                </form>
            </div>

            <!-- STEP 2: Editor & Reviewer Response -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 md:p-8 border border-slate-200/80 dark:border-slate-700 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700 pb-3">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-indigo-600 text-white flex items-center justify-center text-xs font-black">2</span>
                        Editor & Reviewer Responses
                    </h3>
                </div>

                @if($submission->reviews->count() > 0)
                    <div class="space-y-3">
                        @foreach($submission->reviews as $review)
                            <div class="p-4 rounded-2xl border {{ $review->completed_at ? 'bg-emerald-50/50 border-emerald-200 dark:bg-emerald-950/20' : 'bg-slate-50 border-slate-200 dark:bg-slate-700/40' }} text-xs space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-slate-800 dark:text-white">{{ $review->reviewer->name }} ({{ ucfirst($review->role_type) }})</span>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase {{ $review->completed_at ? 'bg-emerald-600 text-white' : 'bg-amber-500 text-white' }}">
                                        {{ $review->completed_at ? 'Response Submitted: ' . ucfirst(str_replace('_', ' ', $review->recommendation)) : 'Pending Review' }}
                                    </span>
                                </div>

                                @if($review->comments)
                                    <p class="text-slate-600 dark:text-slate-300 leading-relaxed bg-white dark:bg-slate-800 p-3 rounded-xl border border-slate-200/60">{{ $review->comments }}</p>
                                @endif

                                @if($review->review_file_url)
                                    <div class="flex items-center justify-between pt-1">
                                        <span class="text-slate-400 text-[11px]">Uploaded Evaluation File:</span>
                                        <a href="{{ $review->review_file_url }}" target="_blank" download class="inline-flex items-center gap-1 text-blue-600 font-bold hover:underline">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            Download Reviewed File
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-400 italic">No reviews assigned yet.</p>
                @endif
            </div>

            <!-- STEP 3: Paper Approval → Article Approval / Re-submit with Correction -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 md:p-8 border border-slate-200/80 dark:border-slate-700 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700 pb-3">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-emerald-600 text-white flex items-center justify-center text-xs font-black">3</span>
                        Paper Approval & Editorial Decision
                    </h3>
                </div>

                <form action="{{ route('admin.submissions.decision', $submission) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">Editorial Decision</label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-200 dark:border-slate-600 cursor-pointer hover:bg-emerald-50/50">
                                <input type="radio" name="decision" value="accepted" {{ $submission->status === 'accepted' ? 'checked' : '' }} class="text-emerald-600">
                                <span class="text-xs font-bold text-emerald-700">Approve Paper</span>
                            </label>
                            <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-200 dark:border-slate-600 cursor-pointer hover:bg-amber-50/50">
                                <input type="radio" name="decision" value="revision_requested" {{ $submission->status === 'revision_requested' ? 'checked' : '' }} class="text-amber-600">
                                <span class="text-xs font-bold text-amber-700">Re-submit with correction</span>
                            </label>
                            <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-200 dark:border-slate-600 cursor-pointer hover:bg-rose-50/50">
                                <input type="radio" name="decision" value="rejected" {{ $submission->status === 'rejected' ? 'checked' : '' }} class="text-rose-600">
                                <span class="text-xs font-bold text-rose-700">Reject Paper</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Decision Remarks / Correction Notes for Author</label>
                        <textarea name="comments" rows="3" placeholder="Provide feedback or requested corrections..."
                                  class="w-full px-3.5 py-2 rounded-xl bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-xs font-medium dark:text-white">{{ $submission->revision_comments }}</textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-sm transition-all">
                            Submit Decision & Notify Author
                        </button>
                    </div>
                </form>
            </div>

            <!-- STEP 4 & 5: Student Payment & Verify Fee -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 md:p-8 border border-slate-200/80 dark:border-slate-700 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700 pb-3">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-purple-600 text-white flex items-center justify-center text-xs font-black">4 & 5</span>
                        Registration Fee Verification
                    </h3>
                </div>

                @if($submission->payment_transaction_id || $submission->payment_receipt_path)
                    <div class="p-4 bg-slate-50 dark:bg-slate-700/40 rounded-2xl border border-slate-200 dark:border-slate-600 text-xs space-y-3">
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            <div>
                                <span class="text-[10px] text-slate-400 font-bold uppercase block">Fee Category</span>
                                <span class="font-bold text-slate-800 dark:text-white capitalize">{{ $submission->payment_fee_type ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 font-bold uppercase block">Amount Paid</span>
                                <span class="font-bold text-emerald-600">{{ $submission->payment_amount ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 font-bold uppercase block">Transaction / UTR</span>
                                <span class="font-mono text-slate-800 dark:text-white">{{ $submission->payment_transaction_id ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 font-bold uppercase block">Payment Date</span>
                                <span class="font-medium text-slate-800 dark:text-white">{{ $submission->payment_date ? $submission->payment_date->format('M d, Y') : '-' }}</span>
                            </div>
                        </div>

                        @if($submission->payment_receipt_url)
                            <div class="pt-2 border-t border-slate-200 dark:border-slate-600 flex items-center justify-between">
                                <span class="font-semibold text-slate-600 dark:text-slate-300">Payment Receipt File:</span>
                                <a href="{{ $submission->payment_receipt_url }}" target="_blank" class="px-3 py-1.5 bg-blue-50 text-blue-700 font-bold rounded-lg hover:bg-blue-100 transition-all underline">
                                    View Receipt Document
                                </a>
                            </div>
                        @endif
                    </div>
                @else
                    <p class="text-xs text-slate-400 italic">No fee payment proof submitted by author yet.</p>
                @endif

                <form action="{{ route('admin.submissions.verify-payment', $submission) }}" method="POST" class="flex items-center gap-3 pt-2">
                    @csrf
                    <select name="payment_status" class="px-3.5 py-2 rounded-xl bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-xs font-bold dark:text-white">
                        <option value="verified" {{ $submission->payment_status === 'verified' ? 'selected' : '' }}>Mark as Fee Verified</option>
                        <option value="pending_verification" {{ $submission->payment_status === 'pending_verification' ? 'selected' : '' }}>Pending Verification</option>
                        <option value="rejected" {{ $submission->payment_status === 'rejected' ? 'selected' : '' }}>Reject Payment</option>
                    </select>
                    <button type="submit" class="px-5 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold shadow-sm transition-all">
                        Update Fee Status
                    </button>
                </form>
            </div>

            <!-- STEP 6: Send Conference Link & Presentation Scheduling -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 md:p-8 border border-slate-200/80 dark:border-slate-700 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700 pb-3">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-amber-500 text-white flex items-center justify-center text-xs font-black">6</span>
                        Send Conference Link & Presentation Time
                    </h3>
                </div>

                <form action="{{ route('admin.submissions.schedule-presentation', $submission) }}" method="POST" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @csrf
                    <div class="sm:col-span-3">
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Virtual Conference / Meeting Link <span class="text-red-500">*</span></label>
                        <input type="url" name="conference_link" value="{{ old('conference_link', $submission->conference_link ?? 'https://zoom.us/j/conference-room') }}" required placeholder="https://zoom.us/j/..."
                               class="w-full px-3.5 py-2 rounded-xl bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-xs font-medium dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Conference Day <span class="text-red-500">*</span></label>
                        <input type="text" name="presentation_day" value="{{ old('presentation_day', $submission->presentation_day ?? 'Day 1 (Morning Session)') }}" required placeholder="e.g. Day 1 (Track A)"
                               class="w-full px-3.5 py-2 rounded-xl bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-xs font-medium dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Presentation Time <span class="text-red-500">*</span></label>
                        <input type="text" name="presentation_time" value="{{ old('presentation_time', $submission->presentation_time ?? '10:30 AM - 11:00 AM UTC') }}" required placeholder="e.g. 10:30 AM - 11:00 AM"
                               class="w-full px-3.5 py-2 rounded-xl bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-xs font-medium dark:text-white">
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="w-full py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold shadow-sm transition-all">
                            Save Schedule
                        </button>
                    </div>
                </form>
            </div>

            <!-- STEP 7: Attendee & Presentation Marking -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 md:p-8 border border-slate-200/80 dark:border-slate-700 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700 pb-3">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-teal-600 text-white flex items-center justify-center text-xs font-black">7</span>
                        Attendee & Presentation Attendance Marking
                    </h3>
                </div>

                <form action="{{ route('admin.submissions.mark-attendance', $submission) }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Participant Attendance</label>
                        <select name="attendance_status" class="w-full px-3.5 py-2 rounded-xl bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-xs font-bold dark:text-white">
                            <option value="not_marked" {{ $submission->attendance_status === 'not_marked' ? 'selected' : '' }}>Not Marked</option>
                            <option value="present" {{ $submission->attendance_status === 'present' ? 'selected' : '' }}>Present (Attended)</option>
                            <option value="absent" {{ $submission->attendance_status === 'absent' ? 'selected' : '' }}>Absent</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Oral / Poster Presentation</label>
                        <select name="presentation_status" class="w-full px-3.5 py-2 rounded-xl bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-xs font-bold dark:text-white">
                            <option value="pending" {{ $submission->presentation_status === 'pending' ? 'selected' : '' }}>Pending Presentation</option>
                            <option value="presented" {{ $submission->presentation_status === 'presented' ? 'selected' : '' }}>Successfully Presented</option>
                            <option value="no_show" {{ $submission->presentation_status === 'no_show' ? 'selected' : '' }}>No Show</option>
                        </select>
                    </div>

                    <div class="sm:col-span-2 flex justify-end">
                        <button type="submit" class="px-5 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-xs font-bold shadow-sm transition-all">
                            Save Attendance & Mark as Participant
                        </button>
                    </div>
                </form>
            </div>

            <!-- STEP 8: Certificate Generation -->
            <div class="bg-gradient-to-br from-slate-900 to-blue-950 text-white rounded-3xl p-6 md:p-8 shadow-xl space-y-5">
                <div class="flex items-center justify-between border-b border-white/10 pb-3">
                    <h3 class="text-sm font-bold flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-emerald-500 text-slate-950 flex items-center justify-center text-xs font-black">8</span>
                        Official Publication Certificate &amp; Issuance
                    </h3>
                    @if(in_array($submission->status, ['accepted', 'published']))
                        <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-emerald-500/20 text-emerald-300 border border-emerald-400/30">
                            ✓ Ready to View / Print
                        </span>
                    @endif
                </div>

                {{-- PRIMARY: Official Journal Publication Certificate (Image 2 format) --}}
                <div class="p-5 rounded-2xl border" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(15, 23, 42, 0.8) 100%); border-color: rgba(52, 211, 153, 0.4);">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-500 text-slate-950">Official Certificate</span>
                                <h4 class="text-base font-bold text-white flex items-center gap-2">
                                    📜 Certificate of Publication
                                </h4>
                            </div>
                            <p class="text-xs text-slate-300">
                                Official academic publication certificate with verified certificate number, DOI, ISSN, Volume, Issue &amp; QR verification. Automatically issued for the lead author and all co-authors in official verified format.
                            </p>
                        </div>
                        <div class="shrink-0 flex items-center gap-2">
                            @if($submission->article)
                                <a href="{{ route('certificates.publication.article', $submission->article) }}" target="_blank"
                                    class="px-5 py-3 rounded-xl font-black text-xs uppercase tracking-wider shadow-lg transition-all flex items-center gap-2 hover:opacity-95"
                                    style="background-color: #10b981; color: #022c22;">
                                    <span>🎓 View / Print Publication Certificate</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            @else
                                <a href="{{ route('certificates.publication.submission', $submission) }}" target="_blank"
                                    class="px-5 py-3 rounded-xl font-black text-xs uppercase tracking-wider shadow-lg transition-all flex items-center gap-2 hover:opacity-95"
                                    style="background-color: #10b981; color: #022c22;">
                                    <span>🎓 View / Print Publication Certificate</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Conference Certificates (Only if linked to a conference) --}}
                @if($submission->conference_id || $submission->conference)
                    <div class="space-y-2 pt-2 border-t border-white/10">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Conference Participation Records</span>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Attendee Certificate Card -->
                            <div class="bg-white/10 p-4 rounded-2xl border border-white/10 space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-amber-300">Attendee Certificate</span>
                                    @if($submission->certificate_attendee_code)
                                        <span class="text-[10px] font-mono bg-emerald-500/30 text-emerald-300 px-2 py-0.5 rounded border border-emerald-400/30">{{ $submission->certificate_attendee_code }}</span>
                                    @endif
                                </div>
                                <a href="{{ route('certificates.attendee', $submission) }}" target="_blank" 
                                   class="w-full py-2.5 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl text-xs flex items-center justify-center gap-2 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    View &amp; Print Attendee Certificate
                                </a>
                            </div>

                            <!-- Presentation Certificate Card -->
                            <div class="bg-white/10 p-4 rounded-2xl border border-white/10 space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-emerald-300">Presentation Certificate</span>
                                    @if($submission->certificate_presentation_code)
                                        <span class="text-[10px] font-mono bg-emerald-500/30 text-emerald-300 px-2 py-0.5 rounded border border-emerald-400/30">{{ $submission->certificate_presentation_code }}</span>
                                    @endif
                                </div>
                                <a href="{{ route('certificates.presentation', $submission) }}" target="_blank" 
                                   class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl text-xs flex items-center justify-center gap-2 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    View &amp; Print Presentation Certificate
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection