@extends('layouts.admin')

@section('title', 'Edit Research Article')
@section('breadcrumb', 'Articles / Edit')

@section('content')
    <div class="max-w-5xl mx-auto" x-data="{ 
            title: '{{ addslashes($article->title) }}', 
            slug: '{{ $article->slug }}',
            authors: {{ json_encode($article->authors->map(function ($a) {
        return [
            'name' => $a->name,
            'email' => $a->email,
            'affiliation' => $a->affiliation,
            'is_corresponding' => $a->is_corresponding ? 1 : 0
        ];
    })) }},
            addAuthor() {
                this.authors.push({ name: '', email: '', affiliation: '', is_corresponding: 0 });
            },
            removeAuthor(index) {
                if (this.authors.length > 1) this.authors.splice(index, 1);
            },
            generateSlug() {
                this.slug = this.title.toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/(^-|-$)+/g, '');
            }
        }">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-serif font-bold text-slate-800">Edit <span class="text-blue-600">Manuscript</span>
                </h1>
                <p class="text-slate-500 mt-1">Update scholarly metadata and author attributions.</p>
            </div>
            <a href="{{ route('admin.articles.index') }}"
                class="flex items-center gap-2 text-slate-500 hover:text-slate-800 transition-colors font-bold uppercase tracking-widest text-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M10 19l-7-7m0 0l7-7m-7 7h18" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    </path>
                </svg>
                Back to Archive
            </a>
        </div>

        <form action="{{ route('admin.articles.update', $article->id) }}" method="POST" enctype="multipart/form-data"
            class="space-y-8">
            @csrf
            @method('PUT')

            {{-- Metadata Section --}}
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                <div class="p-8 border-b border-slate-50">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                    stroke-width="2"></path>
                            </svg>
                        </div>
                        <h2 class="text-lg font-bold text-slate-800">Primary Manuscript Details</h2>
                    </div>

                    <div class="grid grid-cols-1 gap-8">
                        <div>
                            <label for="title"
                                class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">Research
                                Title</label>
                            <textarea name="title" id="title" x-model="title" rows="2" @input="generateSlug()"
                                class="w-full px-5 py-4 rounded-2xl bg-slate-50 border border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all placeholder:text-slate-300 font-serif text-lg font-bold"
                                required>{{ $article->title }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label for="slug"
                                    class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">URL
                                    Semantic Slug</label>
                                <input type="text" name="slug" id="slug" x-model="slug"
                                    class="w-full px-5 py-4 rounded-2xl bg-slate-100 border border-slate-200 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all font-mono text-xs"
                                    readonly required>
                            </div>

                            <div>
                                <label for="doi"
                                    class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">Digital
                                    Object Identifier (DOI)</label>
                                <input type="text" name="doi" id="doi" value="{{ $article->doi }}"
                                    class="w-full px-5 py-4 rounded-2xl bg-slate-50 border border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all"
                                    placeholder="10.xxxx/journal.id.article.id">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-8 bg-slate-50/50">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label for="journal_id"
                                class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">Target
                                Journal</label>
                            <select name="journal_id" id="journal_id"
                                class="w-full px-5 py-4 rounded-2xl bg-white border border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all font-bold text-slate-700"
                                required>
                                @foreach($journals as $journal)
                                    <option value="{{ $journal->id }}" {{ $article->journal_id == $journal->id ? 'selected' : '' }}>{{ $journal->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="issue_id"
                                class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">Volume &
                                Issue</label>
                            <select name="issue_id" id="issue_id"
                                class="w-full px-5 py-4 rounded-2xl bg-white border border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all font-bold text-slate-700">
                                <option value="">Assign to Issue (Optional)</option>
                                @foreach($issues as $issue)
                                    <option value="{{ $issue->id }}" {{ $article->issue_id == $issue->id ? 'selected' : '' }}>
                                        Volume {{ $issue->volume->volume_number ?? '?' }} - Issue {{ $issue->issue_number }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Author Information Section --}}
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                <div class="p-8 border-b border-slate-50 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                                    stroke-width="2"></path>
                            </svg>
                        </div>
                        <h2 class="text-lg font-bold text-slate-800">Author Attribution</h2>
                    </div>
                    <button type="button" @click="addAuthor()"
                        class="text-[10px] font-black uppercase tracking-widest bg-emerald-600 text-white px-4 py-2 rounded-full hover:bg-emerald-700 shadow-lg shadow-emerald-600/20 transition-all">
                        + Add Scholar
                    </button>
                </div>

                <div class="p-8 space-y-6">
                    <template x-for="(author, index) in authors" :key="index">
                        <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100 relative group/author">
                            <button type="button" @click="removeAuthor(index)" x-show="authors.length > 1"
                                class="absolute top-4 right-4 text-slate-300 hover:text-red-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M6 18L18 6M6 6l12 12" stroke-width="2.5"></path>
                                </svg>
                            </button>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                <div class="md:col-span-2">
                                    <label
                                        class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Full
                                        Scholar Name</label>
                                    <input type="text" :name="`authors[${index}][name]`" x-model="author.name" required
                                        class="w-full px-4 py-3 rounded-xl bg-white border border-slate-200 focus:ring-4 focus:ring-blue-500/10 outline-none font-bold text-slate-700">
                                </div>
                                <div class="md:col-span-2">
                                    <label
                                        class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Institutional
                                        Email</label>
                                    <input type="email" :name="`authors[${index}][email]`" x-model="author.email"
                                        class="w-full px-4 py-3 rounded-xl bg-white border border-slate-200 focus:ring-4 focus:ring-blue-500/10 outline-none font-bold text-slate-700">
                                </div>
                                <div class="md:col-span-3">
                                    <label
                                        class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Academic
                                        Affiliation</label>
                                    <input type="text" :name="`authors[${index}][affiliation]`" x-model="author.affiliation"
                                        class="w-full px-4 py-3 rounded-xl bg-white border border-slate-200 focus:ring-4 focus:ring-blue-500/10 outline-none font-medium text-slate-600">
                                </div>
                                <div class="flex items-end pb-1">
                                    <label class="flex items-center gap-3 cursor-pointer group/toggle">
                                        <input type="hidden" :name="`authors[${index}][is_corresponding]`"
                                            x-model="author.is_corresponding">
                                        <div @click="author.is_corresponding = author.is_corresponding == 1 ? 0 : 1"
                                            class="flex w-11 h-6 shrink-0 cursor-pointer items-center rounded-full p-1 duration-300 ease-in-out transition-colors border border-transparent shadow-inner"
                                            :class="author.is_corresponding == 1 ? 'bg-blue-600 border-blue-500/50' : 'bg-slate-200 border-slate-300/50'">
                                            <div class="h-4 w-4 transform rounded-full bg-white shadow-lg transition duration-300 ease-in-out"
                                                :class="author.is_corresponding == 1 ? 'translate-x-5 shadow-blue-900/40' : 'translate-x-0 shadow-slate-400/30'">
                                            </div>
                                        </div>
                                        <span
                                            class="text-[9px] font-black uppercase tracking-widest text-slate-400">Corresponding
                                            Author</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Narrative & Resources --}}
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                <div class="p-8 space-y-8">
                    <div>
                        <label for="abstract"
                            class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">Manuscript
                            Abstract</label>
                        <textarea name="abstract" id="abstract" rows="4"
                            class="w-full px-5 py-4 rounded-2xl bg-slate-50 border border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all leading-relaxed"
                            placeholder="Summary of research findings...">{{ $article->abstract }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                        <div>
                            <label for="pdf_path"
                                class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">Update
                                Manuscript PDF (Optional)</label>
                            <input type="file" name="pdf_path" id="pdf_path" class="hidden"
                                @change="fileName = $event.target.files[0].name"
                                x-data="{ fileName: 'Change PDF Document' }">
                            <label for="pdf_path"
                                class="w-full px-5 py-4 rounded-2xl bg-slate-50 border-2 border-dashed border-blue-200 hover:border-blue-500 transition-all flex items-center justify-center gap-4 text-sm text-slate-600 font-bold cursor-pointer">
                                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
                                        stroke-width="2"></path>
                                </svg>
                                <span x-text="fileName"></span>
                            </label>
                            @if($article->pdf_path)
                                <p class="text-[10px] text-slate-400 mt-2 ml-2">Current: {{ basename($article->pdf_path) }}</p>
                            @endif
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="status"
                                    class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">Status</label>
                                <select name="status"
                                    class="w-full px-5 py-4 rounded-2xl bg-slate-50 border border-slate-200 focus:ring-4">
                                    <option value="published" {{ $article->status == 'published' ? 'selected' : '' }}>
                                        Published</option>
                                    <option value="under_review" {{ $article->status == 'under_review' ? 'selected' : '' }}>
                                        Under Review</option>
                                    <option value="accepted" {{ $article->status == 'accepted' ? 'selected' : '' }}>Accepted
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label for="published_at"
                                    class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">Release
                                    Date</label>
                                <input type="date" name="published_at"
                                    value="{{ $article->published_at ? $article->published_at->format('Y-m-d') : date('Y-m-d') }}"
                                    class="w-full px-5 py-4 rounded-2xl bg-slate-50 border border-slate-200">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PREMIUM SCHOLARLY COMMIT BAR --}}
                <div
                    class="p-1.5 bg-[#0f172a] flex items-center justify-between group/commit shadow-[0_-10px_40px_rgba(0,0,0,0.1)]">
                    <!-- Validation Status -->
                    <div class="flex items-center gap-6 px-10">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse shadow-[0_0_10px_rgba(16,185,129,0.5)]">
                            </div>
                            <span class="text-[10px] font-black uppercase tracking-[0.35em] text-emerald-400/80">Registry
                                ID: {{ $article->id }}</span>
                        </div>
                    </div>

                    <!-- Action Cluster -->
                    <div class="flex items-center pr-1.5 gap-2">
                        <a href="{{ route('admin.articles.index') }}"
                            class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 hover:text-white transition-colors duration-300">
                            Abort
                        </a>
                        <button type="submit"
                            class="relative overflow-hidden bg-blue-600 hover:bg-blue-500 text-white px-16 py-6 rounded-2xl transition-all duration-500 group/btn shadow-2xl shadow-blue-900/40 border border-blue-400/20">
                            <div
                                class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover/btn:translate-x-full transition-transform duration-1000">
                            </div>
                            <span class="relative z-10 text-[11px] font-black uppercase tracking-[0.4em]">Apply Registry
                                Updates</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection