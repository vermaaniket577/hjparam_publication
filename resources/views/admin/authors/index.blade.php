@extends('layouts.admin')

@section('title', $selectedJournal ? $selectedJournal->title . ' — Paper Authors' : 'Journal Paper Authors Management')
@section('breadcrumb', $selectedJournal ? 'Journals / ' . $selectedJournal->title . ' / Authors' : 'Management / Paper Authors')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold flex items-center justify-between">
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm font-semibold">
                {{ session('error') }}
            </div>
        @endif
        @if($errors->any())
            <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Page Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-blue-600 mb-1">
                    <a href="{{ route('admin.journals.index') }}" class="hover:underline">Journals</a>
                    <span>/</span>
                    <span>Paper Authors Management</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">
                    @if($selectedJournal)
                        {{ $selectedJournal->title }} <span class="text-blue-600">— Authors</span>
                    @else
                        Journal Paper <span class="text-blue-600">Authors</span>
                    @endif
                </h1>
                <p class="text-sm text-slate-500 mt-1">
                    Add, edit, reorder, or remove authors on published &amp; active journal papers. Changes automatically sync to the public journal page, citations, submissions, and certificates.
                </p>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                @if($selectedJournal)
                    <a href="{{ route('admin.authors.index') }}"
                        class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold uppercase tracking-wider transition">
                        All Journals
                    </a>
                @endif
                <a href="{{ route('admin.journals.index') }}"
                    class="px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold uppercase tracking-wider transition">
                    &larr; Back to Journals
                </a>
            </div>
        </div>

        {{-- Summary Stats --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <div class="text-xs font-bold uppercase tracking-wider text-slate-400">Journal Papers</div>
                <div class="text-3xl font-extrabold text-slate-900 mt-1">{{ number_format($stats['total_articles']) }}</div>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <div class="text-xs font-bold uppercase tracking-wider text-slate-400">Registered Authors</div>
                <div class="text-3xl font-extrabold text-blue-600 mt-1">{{ number_format($stats['total_authors']) }}</div>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <div class="text-xs font-bold uppercase tracking-wider text-slate-400">Live Published Papers</div>
                <div class="text-3xl font-extrabold text-emerald-600 mt-1">{{ number_format($stats['published_articles']) }}</div>
            </div>
        </div>

        {{-- Filter & Search Bar --}}
        <form method="GET"
            action="{{ $selectedJournal && request()->routeIs('admin.journals.authors.index') ? route('admin.journals.authors.index', $selectedJournal) : route('admin.authors.index') }}"
            class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                <div class="md:col-span-5">
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Search Paper or Author</label>
                    <input type="text" name="q" value="{{ request('q') }}"
                        placeholder="Search by article title, DOI, author name, email, or affiliation..."
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                @if(!request()->routeIs('admin.journals.authors.index'))
                    <div class="md:col-span-3">
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Filter by Journal</label>
                        <select name="journal_id"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Journals</option>
                            @foreach($journals as $j)
                                <option value="{{ $j->id }}" {{ (string) request('journal_id') === (string) $j->id ? 'selected' : '' }}>
                                    {{ $j->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="{{ request()->routeIs('admin.journals.authors.index') ? 'md:col-span-4' : 'md:col-span-2' }}">
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Status</label>
                    <select name="status"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Statuses</option>
                        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                        <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>Accepted</option>
                        <option value="under_review" {{ request('status') === 'under_review' ? 'selected' : '' }}>Under Review</option>
                    </select>
                </div>
                <div class="{{ request()->routeIs('admin.journals.authors.index') ? 'md:col-span-3' : 'md:col-span-2' }} flex gap-2">
                    <button type="submit"
                        style="background-color: #2563eb; color: #ffffff;"
                        class="flex-1 px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider hover:opacity-95 transition">
                        Filter
                    </button>
                    @if(request()->hasAny(['q', 'journal_id', 'status']))
                        <a href="{{ request()->routeIs('admin.journals.authors.index') && $selectedJournal ? route('admin.journals.authors.index', $selectedJournal) : route('admin.authors.index') }}"
                            class="px-3 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-xs font-bold hover:bg-slate-50">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>

        {{-- Articles & Authors List --}}
        <div class="space-y-4">
            @forelse($articles as $article)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden" x-data="{ quickAdd: false }">
                    <div class="p-5 sm:p-6">
                        <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4">
                            <div class="space-y-2 flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 text-[11px]">
                                    <span class="px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 font-bold border border-blue-100">
                                        {{ $article->journal->title ?? 'Journal' }}
                                    </span>
                                    <span class="px-2 py-0.5 rounded-full font-bold uppercase text-[10px] {{ $article->status === 'published' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                        {{ str_replace('_', ' ', $article->status) }}
                                    </span>
                                    @if($article->doi)
                                        <span class="font-mono text-slate-500">DOI: {{ $article->doi }}</span>
                                    @endif
                                    <span class="text-slate-400">ID #{{ $article->id }}</span>
                                </div>

                                <h3 class="text-base sm:text-lg font-bold text-slate-900 leading-snug">
                                    {{ $article->title }}
                                </h3>

                                {{-- Current Authors Chips --}}
                                <div class="pt-2">
                                    <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">
                                        Displayed Authors ({{ $article->authors->count() }})
                                    </div>
                                    <div class="flex flex-wrap gap-2">
                                        @forelse($article->authors as $idx => $author)
                                            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200 text-xs">
                                                <span class="w-5 h-5 rounded-full bg-blue-600 text-white text-[10px] font-bold flex items-center justify-center shrink-0">
                                                    {{ $idx + 1 }}
                                                </span>
                                                <div>
                                                    <span class="font-bold text-slate-800">{{ $author->name }}</span>
                                                    @if($author->is_corresponding)
                                                        <span class="ml-1 px-1.5 py-0.5 rounded bg-blue-100 text-blue-700 text-[9px] font-bold uppercase">Corresponding ✉</span>
                                                    @endif
                                                    @if($author->affiliation)
                                                        <span class="block text-[10px] text-slate-500">{{ Str::limit($author->affiliation, 55) }}</span>
                                                    @endif
                                                </div>
                                                @if($article->authors->count() > 1)
                                                    <form action="{{ route('admin.articles.authors.destroy', [$article, $author]) }}" method="POST"
                                                        onsubmit="return confirm('Remove author {{ addslashes($author->name) }} from this paper?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-slate-400 hover:text-red-600 font-bold px-1" title="Remove Author">&times;</button>
                                                    </form>
                                                @endif
                                            </div>
                                        @empty
                                            <span class="text-xs text-amber-600 font-semibold">No authors assigned yet — click Manage Authors to add.</span>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="flex flex-wrap lg:flex-col items-stretch gap-2 shrink-0 lg:w-48">
                                <a href="{{ route('admin.articles.authors.manage', $article) }}"
                                    style="background-color: #2563eb; color: #ffffff;"
                                    class="px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider text-center hover:opacity-95 transition shadow-sm">
                                    Manage Authors
                                </a>
                                <button type="button" @click="quickAdd = !quickAdd"
                                    class="px-4 py-2 rounded-xl border border-blue-200 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold transition">
                                    <span x-text="quickAdd ? 'Close Quick Add' : '+ Quick Add Author'">+ Quick Add Author</span>
                                </button>
                                @if($article->journal)
                                    <a href="{{ route('articles.show', [$article->journal->slug, $article->slug]) }}" target="_blank"
                                        class="px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 text-xs font-semibold text-center transition">
                                        View Live Page &rarr;
                                    </a>
                                @endif
                            </div>
                        </div>

                        {{-- Quick-Add Author Inline Form --}}
                        <div x-show="quickAdd" x-transition.opacity.duration.150ms class="mt-4 pt-4 border-t border-slate-100">
                            <form action="{{ route('admin.articles.authors.store', $article) }}" method="POST"
                                class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end bg-blue-50/50 p-4 rounded-xl border border-blue-100">
                                @csrf
                                <div class="sm:col-span-3">
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Author Full Name *</label>
                                    <input type="text" name="name" required placeholder="e.g. Dr. Gourav Shrivastava"
                                        class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-white text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div class="sm:col-span-3">
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Email</label>
                                    <input type="email" name="email" placeholder="author@university.edu"
                                        class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-white text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div class="sm:col-span-3">
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Academic Affiliation</label>
                                    <input type="text" name="affiliation" placeholder="Department, University, City"
                                        class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-white text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div class="sm:col-span-3 flex items-center justify-between gap-2">
                                    <label class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-700 cursor-pointer">
                                        <input type="checkbox" name="is_corresponding" value="1" class="rounded border-slate-300 text-blue-600">
                                        <span>Corresponding</span>
                                    </label>
                                    <button type="submit"
                                        style="background-color: #059669; color: #ffffff;"
                                        class="px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wider hover:opacity-95 transition">
                                        Add
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
                    <p class="text-slate-600 font-semibold">No journal papers found matching your filter.</p>
                </div>
            @endforelse
        </div>

        <div class="pt-2">
            {{ $articles->links() }}
        </div>
    </div>
@endsection
