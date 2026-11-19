@extends('layouts.admin')

@section('title', 'Manage Paper Authors — ' . Str::limit($article->title, 50))
@section('breadcrumb', 'Journals / Paper Authors / Manage')

@section('content')
    <script>
        window.articleAuthorsManager = function () {
            return {
                authors: {!! json_encode($initialAuthors, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE) !!},
                addAuthor: function () {
                    this.authors.push({
                        id: null,
                        name: '',
                        email: '',
                        affiliation: '',
                        is_corresponding: 0
                    });
                },
                removeAuthor: function (index) {
                    if (this.authors.length > 1) {
                        this.authors.splice(index, 1);
                    }
                },
                moveUp: function (index) {
                    if (index > 0) {
                        var item = this.authors.splice(index, 1)[0];
                        this.authors.splice(index - 1, 0, item);
                    }
                },
                moveDown: function (index) {
                    if (index < this.authors.length - 1) {
                        var item = this.authors.splice(index, 1)[0];
                        this.authors.splice(index + 1, 0, item);
                    }
                }
            };
        };
    </script>
    <div class="max-w-5xl mx-auto space-y-6" x-data="articleAuthorsManager()">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm font-semibold">
                {{ session('error') }}
            </div>
        @endif
        @if($errors->any())
            <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm">
                <p class="font-bold mb-1">Please fix the following errors:</p>
                <ul class="list-disc pl-5 space-y-0.5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Top Bar --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex flex-wrap items-center gap-2 text-xs font-bold uppercase tracking-wider text-blue-600 mb-1">
                    <a href="{{ route('admin.journals.index') }}" class="hover:underline">Journals</a>
                    <span>/</span>
                    @if($article->journal)
                        <a href="{{ route('admin.journals.authors.index', $article->journal) }}" class="hover:underline">{{ $article->journal->title }}</a>
                        <span>/</span>
                    @endif
                    <span>Manage Paper Authors</span>
                </div>
                <h1 class="text-2xl font-bold text-slate-900">Manage Paper Authors</h1>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('certificates.publication.article', $article) }}" target="_blank"
                    class="px-4 py-2 rounded-xl border border-emerald-300 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                    🎓 Publication Certificates &rarr;
                </a>
                @if($article->journal)
                    <a href="{{ route('articles.show', [$article->journal->slug, $article->slug]) }}" target="_blank"
                        class="px-4 py-2 rounded-xl border border-blue-200 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold transition">
                        View Live Article &rarr;
                    </a>
                    <a href="{{ route('admin.journals.authors.index', $article->journal) }}"
                        class="px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition">
                        &larr; Journal Authors
                    </a>
                @else
                    <a href="{{ route('admin.authors.index') }}"
                        class="px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition">
                        &larr; All Paper Authors
                    </a>
                @endif
            </div>
        </div>

        {{-- Paper Overview & Live Public Preview --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <div class="flex flex-wrap items-center gap-2 text-[11px] mb-2">
                    <span class="px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 font-bold border border-blue-100">
                        {{ $article->journal->title ?? 'Journal' }}
                    </span>
                    <span class="px-2 py-0.5 rounded-full font-bold uppercase text-[10px] {{ $article->status === 'published' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                        {{ str_replace('_', ' ', $article->status) }}
                    </span>
                    @if($article->doi)
                        <span class="font-mono text-slate-500">DOI: {{ $article->doi }}</span>
                    @endif
                </div>
                <h2 class="text-lg sm:text-xl font-serif font-bold text-slate-900 leading-snug">
                    {{ $article->title }}
                </h2>
            </div>

            {{-- Real-Time Public Journal Page Preview --}}
            <div class="px-6 py-4 bg-slate-50">
                <div class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">
                    Live Preview on Public Journal Article Page
                </div>
                <div class="flex flex-wrap items-center gap-x-4 gap-y-2">
                    <template x-for="(author, idx) in authors" :key="'preview-' + idx">
                        <div class="inline-flex items-center gap-2">
                            <span class="text-base font-bold text-slate-800 border-b border-blue-300">
                                <span x-text="author.name.trim() || ('Author #' + (idx + 1))"></span>
                                <sup x-show="author.is_corresponding == 1" class="text-blue-600 ml-0.5">✉</sup>
                            </span>
                            <span x-show="idx < authors.length - 1" class="text-slate-300 font-light text-lg">/</span>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- Authors Management Form --}}
        <form action="{{ route('admin.articles.authors.sync', $article) }}" method="POST"
            class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            @csrf
            @method('PUT')

            <div class="px-6 py-4 border-b border-slate-100 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h3 class="text-base font-bold text-slate-900">
                        Authors &amp; Co-Authors (<span x-text="authors.length"></span>)
                    </h3>
                    <p class="text-xs text-slate-500">
                        Use the arrow buttons to set exact author order. Changes sync automatically to the journal page, citations, submission, and certificates.
                    </p>
                </div>
                <button type="button" @click="addAuthor()"
                    style="background-color: #059669; color: #ffffff;"
                    class="px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider hover:opacity-95 transition shadow-sm">
                    + Add Co-Author
                </button>
            </div>

            <div class="p-6 space-y-4">
                <template x-for="(author, index) in authors" :key="index">
                    <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-4">
                        <div class="flex items-center justify-between gap-3 border-b border-slate-200/70 pb-3">
                            <div class="flex items-center gap-2.5">
                                <span class="w-7 h-7 rounded-full bg-blue-600 text-white text-xs font-bold flex items-center justify-center">
                                    <span x-text="index + 1"></span>
                                </span>
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-700"
                                    x-text="index === 0 ? 'Primary / First Author' : ('Co-Author #' + (index + 1))"></span>
                                <span x-show="author.is_corresponding == 1"
                                    class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 text-[10px] font-bold uppercase">
                                    Corresponding ✉
                                </span>
                            </div>

                            <div class="flex items-center gap-1.5">
                                <button type="button" @click="moveUp(index)" :disabled="index === 0"
                                    class="px-2.5 py-1 rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-100 text-xs font-bold disabled:opacity-40"
                                    title="Move Up">
                                    &uarr;
                                </button>
                                <button type="button" @click="moveDown(index)" :disabled="index === authors.length - 1"
                                    class="px-2.5 py-1 rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-100 text-xs font-bold disabled:opacity-40"
                                    title="Move Down">
                                    &darr;
                                </button>
                                <button type="button" @click="removeAuthor(index)" x-show="authors.length > 1"
                                    class="px-2.5 py-1 rounded-lg border border-red-200 bg-red-50 text-red-600 hover:bg-red-100 text-xs font-bold"
                                    title="Remove Author">
                                    Remove
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                            <div class="md:col-span-5">
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                                    Author Full Name *
                                </label>
                                <input type="text" :name="`authors[${index}][name]`" x-model="author.name" required
                                    placeholder="e.g. Anurag Shrivastava"
                                    class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-200 text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div class="md:col-span-4">
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                                    Institutional Email
                                </label>
                                <input type="email" :name="`authors[${index}][email]`" x-model="author.email"
                                    placeholder="author@university.edu"
                                    class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-200 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div class="md:col-span-3 flex items-end pb-1.5">
                                <label class="inline-flex items-center gap-2.5 cursor-pointer select-none">
                                    <input type="hidden" :name="`authors[${index}][is_corresponding]`" :value="author.is_corresponding ? 1 : 0">
                                    <input type="checkbox" :checked="author.is_corresponding == 1"
                                        @change="author.is_corresponding = $event.target.checked ? 1 : 0"
                                        class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-xs font-bold text-slate-700">Corresponding Author ✉</span>
                                </label>
                            </div>

                            <div class="md:col-span-12">
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                                    Academic Affiliation / Institution
                                </label>
                                <input type="text" :name="`authors[${index}][affiliation]`" x-model="author.affiliation"
                                    placeholder="e.g. Department of Computer Science, SAGE University, Bhopal, Madhya Pradesh, India"
                                    class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-200 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Footer Action Bar --}}
            <div class="px-6 py-4 bg-slate-900 flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="text-xs text-slate-300">
                    Syncs automatically with: <strong class="text-white">Live Journal Page</strong> · <strong class="text-white">APA/MLA/BibTeX</strong> · <strong class="text-white">Certificates</strong>
                </div>
                <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                    <a href="{{ route('admin.articles.edit', $article) }}"
                        class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-300 hover:text-white transition">
                        Full Article Editor
                    </a>
                    <button type="submit"
                        style="background-color: #2563eb; color: #ffffff;"
                        class="px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider hover:opacity-95 transition shadow-lg">
                        Save &amp; Sync Authors
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
