@extends('layouts.admin')
@section('title', 'Submission Management #' . $submission->id)
@section('breadcrumb', 'Submissions / #' . $submission->id)

@section('content')
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
                    🎓 Certificates ({{ rescue(fn() => $submission->certificates->count(), 0) }})
                </a>
            @endif
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

            <!-- Scholar & Authors List -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200/80 dark:border-slate-700 shadow-sm space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Scholar & Author Details</h3>

                @if(!empty($submission->scholars_data) && is_array($submission->scholars_data))
                    <div class="space-y-3">
                        @foreach($submission->scholars_data as $idx => $author)
                            <div class="p-3.5 bg-slate-50 dark:bg-slate-700/40 rounded-xl border border-slate-200/60 dark:border-slate-600 text-xs space-y-1">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-slate-900 dark:text-white">{{ $author['first_name'] ?? '' }} {{ $author['last_name'] ?? '' }}</span>
                                    <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">Author #{{ $idx + 1 }}</span>
                                </div>
                                @if(!empty($author['designation']) || !empty($author['institute']))
                                    <p class="text-slate-500 dark:text-slate-400">{{ $author['designation'] ?? '' }} {{ !empty($author['department']) ? '• ' . $author['department'] : '' }} • {{ $author['institute'] ?? '' }}</p>
                                @endif
                                <div class="flex items-center gap-3 pt-1 text-[11px] text-slate-500">
                                    <span>📧 {{ $author['email'] ?? '-' }}</span>
                                    <span>📱 {{ $author['mobile'] ?? '-' }}</span>
                                </div>
                                @if(!empty($author['orcid']))
                                    <p class="text-[10px] text-emerald-600 font-mono">ORCID: {{ $author['orcid'] }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-3 bg-slate-50 rounded-xl text-xs">
                        <p class="font-bold text-slate-800">{{ $submission->user->name }}</p>
                        <p class="text-slate-500">{{ $submission->user->email }}</p>
                    </div>
                @endif
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
            <div id="publish-paper-section" class="rounded-3xl p-6 md:p-8 shadow-xl transition-all border {{ $submission->article ? 'bg-gradient-to-br from-emerald-950 via-slate-900 to-teal-950 border-emerald-500/40 text-white' : 'bg-gradient-to-br from-indigo-950 via-slate-900 to-purple-950 border-purple-500/40 text-white' }}"
                x-data="{ 
                    customOpen: {{ $submission->article ? 'false' : 'false' }},
                    selectedJournalId: '{{ $submission->journal_id ?? ($journals->first()->id ?? '') }}',
                    title: @js($submission->title),
                    abstract: @js($submission->abstract),
                    doi: '{{ $submission->article?->doi ?? ('10.5281/hjparam.' . ($submission->journal->slug ?? 'pub') . '.' . date('Y') . '.' . str_pad($submission->id, 4, '0', STR_PAD_LEFT)) }}',
                    authors: @json($authorsList ?? []),
                    addAuthor() {
                        this.authors.push({ name: '', email: '', institution: '', role: 'co_author' });
                    },
                    removeAuthor(idx) {
                        if (this.authors.length > 1) this.authors.splice(idx, 1);
                    }
                }">

                @if($submission->article)
                    {{-- Published Article Summary & Management --}}
                    <div class="space-y-4">
                        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-white/10 pb-4">
                            <div class="flex items-center gap-2.5">
                                <span class="w-8 h-8 rounded-xl bg-emerald-500 text-slate-950 flex items-center justify-center font-black text-sm">✓</span>
                                <div>
                                    <span class="text-[10px] uppercase font-black tracking-widest text-emerald-400 block">Publication Verified & Live</span>
                                    <h3 class="text-base font-bold text-white">Officially Published in {{ $submission->article->journal->title }}</h3>
                                </div>
                            </div>
                            <span class="px-3 py-1 bg-emerald-500/20 text-emerald-300 rounded-full border border-emerald-400/30 text-xs font-mono font-bold">
                                Article #{{ $submission->article->id }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs bg-white/5 p-4 rounded-2xl border border-white/10">
                            <div>
                                <span class="text-slate-400 text-[10px] font-bold uppercase block">Journal & ISSN</span>
                                <span class="font-bold text-white block">{{ $submission->article->journal->title }}</span>
                                <span class="text-emerald-400 text-[11px] font-mono">ISSN: {{ $submission->article->journal->issn ?: 'Applied' }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400 text-[10px] font-bold uppercase block">Volume & Issue</span>
                                <span class="font-bold text-white block">
                                    Vol. {{ $submission->article->issue?->volume?->volume_number ?? '1' }} ({{ $submission->article->issue?->volume?->year ?? date('Y') }})
                                </span>
                                <span class="text-slate-300 text-[11px]">Issue No. {{ $submission->article->issue?->issue_number ?? '1' }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400 text-[10px] font-bold uppercase block">DOI & Release Date</span>
                                <a href="https://doi.org/{{ $submission->article->doi }}" target="_blank" class="font-mono text-blue-300 hover:underline block truncate">
                                    {{ $submission->article->doi ?: 'Not Assigned' }}
                                </a>
                                <span class="text-slate-300 text-[11px]">{{ $submission->article->published_at ? $submission->article->published_at->format('M d, Y') : now()->format('M d, Y') }}</span>
                            </div>
                        </div>

                        {{-- Quick Action Hub --}}
                        <div class="flex flex-wrap items-center gap-3 pt-2">
                            <a href="{{ route('articles.show', [$submission->article->journal->slug, $submission->article->slug]) }}" target="_blank"
                                class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black rounded-xl text-xs flex items-center gap-2 shadow-lg shadow-emerald-500/20 transition-all">
                                <span>🌐 View Live Article on Website</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                            <a href="{{ route('articles.download', $submission->article->id) }}" target="_blank"
                                class="px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white font-bold rounded-xl text-xs flex items-center gap-2 transition-all">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Official Academic PDF
                            </a>
                            <a href="{{ route('certificates.publication.article', $submission->article) }}" target="_blank"
                                class="px-4 py-2.5 bg-white/10 hover:bg-white/20 text-amber-300 font-bold rounded-xl text-xs flex items-center gap-2 transition-all">
                                🎓 Publication Certificates
                            </a>
                            <button @click="customOpen = !customOpen" type="button"
                                class="px-4 py-2.5 bg-white/5 hover:bg-white/15 text-slate-300 font-bold rounded-xl text-xs flex items-center gap-1.5 transition-all ml-auto">
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

                        <!-- Upload Final Proof PDF (Optional) -->
                        <div>
                            <label class="block text-xs font-bold text-slate-200 uppercase mb-2">Final Production PDF (Optional)</label>
                            <input type="file" name="pdf_file" accept=".pdf" class="w-full px-4 py-2 rounded-xl bg-slate-800 border border-slate-700 text-xs text-slate-300 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-purple-600 file:text-white hover:file:bg-purple-700 cursor-pointer">
                            <p class="text-[11px] text-slate-400 mt-1">If not uploaded, the author's submitted manuscript or dynamic PDF renderer will automatically serve as the document.</p>
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
            <div class="bg-gradient-to-br from-slate-900 to-blue-950 text-white rounded-3xl p-6 md:p-8 shadow-xl space-y-4">
                <div class="flex items-center justify-between border-b border-white/10 pb-3">
                    <h3 class="text-sm font-bold flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-rose-500 text-white flex items-center justify-center text-xs font-black">8</span>
                        Certificate Generation & Issuance
                    </h3>
                </div>

                <p class="text-xs text-slate-300">Generate and download official verified certificates for this participant:</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
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
                            View & Print Attendee Certificate
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
                            View & Print Presentation Certificate
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection