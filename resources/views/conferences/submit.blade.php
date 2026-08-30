@extends('layouts.web')
@section('title', 'Submit Paper | ' . $conference->title)

@section('content')
<div class="bg-slate-50 min-h-screen py-12 md:py-16" x-data="paperSubmissionApp()">
    <div class="container mx-auto px-4 max-w-4xl">
        
        <!-- Breadcrumb & Back Link -->
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('conferences.show', $conference->slug) }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-blue-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Conference Details
            </a>
            <span class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-full border border-blue-100">
                HJ-CONF-{{ str_pad($conference->id, 5, '0', STR_PAD_LEFT) }}
            </span>
        </div>

        <!-- Header Card -->
        <div class="bg-white rounded-3xl p-8 md:p-10 shadow-xl border border-slate-100 mb-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-48 h-48 bg-blue-500/5 rounded-full blur-3xl -mr-12 -mt-12 pointer-events-none"></div>
            <span class="inline-block px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-[10px] font-black uppercase tracking-widest border border-blue-100 mb-3">
                Call for Papers & Submissions
            </span>
            <h1 class="text-2xl md:text-4xl font-serif font-black text-slate-900 tracking-tight leading-tight">
                Paper Submission: <span class="text-blue-600">{{ $conference->title }}</span>
            </h1>
            <p class="text-slate-500 text-xs md:text-sm mt-2 leading-relaxed">
                Please complete the paper details and author attribution below. All submissions undergo single-blind peer review by the editorial board.
            </p>

            @if($conference->paper_format_1_url || $conference->paper_format_2_url)
                <div class="mt-6 pt-6 border-t border-slate-100 flex flex-wrap items-center gap-3">
                    <span class="text-xs font-bold text-slate-700">Official Templates:</span>
                    @if($conference->paper_format_1_url)
                        <a href="{{ $conference->paper_format_1_url }}" target="_blank" download class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all">
                            <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Paper Format 1
                        </a>
                    @endif
                    @if($conference->paper_format_2_url)
                        <a href="{{ $conference->paper_format_2_url }}" target="_blank" download class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all">
                            <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Paper Format 2
                        </a>
                    @endif
                </div>
            @endif
        </div>

        @if ($errors->any())
            <div class="p-4 bg-red-50 border border-red-200 rounded-2xl mb-8">
                <div class="flex items-center gap-2 text-red-700 font-bold text-xs mb-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Please fix the submission errors below:
                </div>
                <ul class="list-disc list-inside text-xs text-red-600 space-y-0.5 ml-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('conferences.submit.store', $conference->slug) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <!-- ========================================== -->
            <!-- PART 1: PAPER DETAILS                      -->
            <!-- ========================================== -->
            <div class="bg-white rounded-3xl p-8 md:p-10 shadow-xl border border-slate-100 space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <h2 class="text-base font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                        1. Paper Details
                    </h2>
                    <p class="text-xs text-slate-400 mt-0.5">Title, article classification, abstract, keywords, and manuscript upload</p>
                </div>

                <!-- (i) Title of Paper / Article -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        (i) Title of Paper / Article <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="Full research paper / article title..." required
                           class="w-full px-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-200 text-slate-800 placeholder-slate-400 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-500/10 font-medium text-sm transition-all">
                </div>

                <!-- (ii) Article Type -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        (ii) Article Type <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <label class="flex items-center gap-3 p-3.5 rounded-2xl border border-slate-200 bg-slate-50 hover:bg-blue-50/50 hover:border-blue-300 cursor-pointer transition-all">
                            <input type="radio" name="article_type" value="research_paper" {{ old('article_type', 'research_paper') == 'research_paper' ? 'checked' : '' }} class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                            <span class="text-xs font-bold text-slate-800">Research Paper</span>
                        </label>
                        <label class="flex items-center gap-3 p-3.5 rounded-2xl border border-slate-200 bg-slate-50 hover:bg-blue-50/50 hover:border-blue-300 cursor-pointer transition-all">
                            <input type="radio" name="article_type" value="review_paper" {{ old('article_type') == 'review_paper' ? 'checked' : '' }} class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                            <span class="text-xs font-bold text-slate-800">Review Paper</span>
                        </label>
                        <label class="flex items-center gap-3 p-3.5 rounded-2xl border border-slate-200 bg-slate-50 hover:bg-blue-50/50 hover:border-blue-300 cursor-pointer transition-all">
                            <input type="radio" name="article_type" value="short_communication" {{ old('article_type') == 'short_communication' ? 'checked' : '' }} class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                            <span class="text-xs font-bold text-slate-800">Short Communication</span>
                        </label>
                    </div>
                </div>

                <!-- (iii) Abstract -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        (iii) Abstract <span class="text-red-500">*</span>
                    </label>
                    <textarea name="abstract" rows="6" placeholder="Provide a structured abstract summarizing the objectives, methodology, main findings, and key scientific conclusions..." required
                              class="w-full px-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-200 text-slate-800 placeholder-slate-400 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-500/10 font-normal text-sm leading-relaxed transition-all">{{ old('abstract') }}</textarea>
                </div>

                <!-- (iv) Keywords -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        (iv) Keywords
                    </label>
                    <input type="text" name="keywords" value="{{ old('keywords') }}" placeholder="e.g. Artificial Intelligence, Neural Networks, Cloud Computing, Big Data (comma separated)"
                           class="w-full px-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-200 text-slate-800 placeholder-slate-400 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-500/10 font-medium text-sm transition-all">
                </div>

                <!-- (v) Upload Full Manuscript -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        (v) Upload Full Manuscript <span class="text-red-500">*</span>
                    </label>
                    <div class="border-2 border-dashed border-slate-300 hover:border-blue-500 rounded-3xl p-8 text-center bg-slate-50/60 hover:bg-blue-50/20 transition-all cursor-pointer relative group"
                         @click="$refs.manuscriptInput.click()">
                        <input type="file" name="manuscript_file" x-ref="manuscriptInput" @change="handleFileChange($event)" required accept=".doc,.docx,.pdf" class="hidden">
                        
                        <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                        </div>
                        
                        <template x-if="!selectedFileName">
                            <div>
                                <p class="text-xs font-bold text-slate-700">Click to upload full manuscript file or drag & drop</p>
                                <p class="text-[10px] text-slate-400 mt-1">Accepted formats: DOC, DOCX, PDF (Maximum 100MB)</p>
                            </div>
                        </template>

                        <template x-if="selectedFileName">
                            <div class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-100 text-emerald-800 rounded-xl text-xs font-bold">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span x-text="selectedFileName"></span>
                                <span class="text-[10px] opacity-75" x-text="'(' + selectedFileSize + ')'"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- PART 2: SCHOLAR DETAILS (MULTI-AUTHOR)    -->
            <!-- ========================================== -->
            <div class="bg-white rounded-3xl p-8 md:p-10 shadow-xl border border-slate-100 space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-slate-100 pb-4">
                    <div>
                        <h2 class="text-base font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                            2. Scholar Details
                        </h2>
                        <p class="text-xs text-slate-400 mt-0.5">Enter author name, institutional affiliation, ORCID, and contact details</p>
                    </div>
                    <button type="button" @click="addAuthor()" class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-emerald-600/20 transition-all hover:scale-105">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        + Add more Author
                    </button>
                </div>

                <div class="space-y-6">
                    <template x-for="(author, index) in authors" :key="index">
                        <div class="rounded-2xl p-6 bg-slate-50 border border-slate-200/80 space-y-4 relative">
                            <div class="flex items-center justify-between border-b border-slate-200/60 pb-3">
                                <span class="text-xs font-black uppercase tracking-wider text-slate-700" x-text="index === 0 ? 'Author 1 (Primary / Corresponding Author)' : 'Author ' + (index + 1)"></span>
                                <template x-if="index > 0">
                                    <button type="button" @click="removeAuthor(index)" class="text-xs font-bold text-red-500 hover:text-red-700 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        Remove Author
                                    </button>
                                </template>
                            </div>

                            <!-- 1. Name -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">First Name <span class="text-red-500">*</span></label>
                                    <input type="text" :name="'authors[' + index + '][first_name]'" x-model="author.first_name" placeholder="First Name" required
                                           class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-200 text-xs font-medium text-slate-800 focus:border-blue-600 focus:ring-2 focus:ring-blue-500/10">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">Last Name <span class="text-red-500">*</span></label>
                                    <input type="text" :name="'authors[' + index + '][last_name]'" x-model="author.last_name" placeholder="Last Name" required
                                           class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-200 text-xs font-medium text-slate-800 focus:border-blue-600 focus:ring-2 focus:ring-blue-500/10">
                                </div>
                            </div>

                            <!-- 2. Affiliation -->
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">Designation</label>
                                    <input type="text" :name="'authors[' + index + '][designation]'" x-model="author.designation" placeholder="e.g. Professor / PhD Scholar"
                                           class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-200 text-xs font-medium text-slate-800 focus:border-blue-600 focus:ring-2 focus:ring-blue-500/10">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">Department</label>
                                    <input type="text" :name="'authors[' + index + '][department]'" x-model="author.department" placeholder="e.g. Computer Science"
                                           class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-200 text-xs font-medium text-slate-800 focus:border-blue-600 focus:ring-2 focus:ring-blue-500/10">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">Institute / University</label>
                                    <input type="text" :name="'authors[' + index + '][institute]'" x-model="author.institute" placeholder="e.g. Oxford University"
                                           class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-200 text-xs font-medium text-slate-800 focus:border-blue-600 focus:ring-2 focus:ring-blue-500/10">
                                </div>
                            </div>

                            <!-- 3. ORCID, 4. Email, 5. Mobile -->
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">ORCID ID</label>
                                    <input type="text" :name="'authors[' + index + '][orcid]'" x-model="author.orcid" placeholder="0000-0002-1825-0097"
                                           class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-200 text-xs font-medium text-slate-800 focus:border-blue-600 focus:ring-2 focus:ring-blue-500/10">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">Email Address <span class="text-red-500">*</span></label>
                                    <input type="email" :name="'authors[' + index + '][email]'" x-model="author.email" placeholder="author@domain.edu" required
                                           class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-200 text-xs font-medium text-slate-800 focus:border-blue-600 focus:ring-2 focus:ring-blue-500/10">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">Mobile Number <span class="text-red-500">*</span></label>
                                    <input type="tel" :name="'authors[' + index + '][mobile]'" x-model="author.mobile" placeholder="+1 (555) 000-0000" required
                                           class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-200 text-xs font-medium text-slate-800 focus:border-blue-600 focus:ring-2 focus:ring-blue-500/10">
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Submit Button Bar -->
            <div class="bg-[#0f172a] rounded-3xl p-6 md:p-8 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-2xl">
                <div class="text-center sm:text-left">
                    <span class="block text-white font-bold text-sm">Ready to Submit?</span>
                    <span class="block text-slate-400 text-xs mt-0.5">Your manuscript will be transmitted to the conference editorial desk.</span>
                </div>
                <button type="submit" class="w-full sm:w-auto px-10 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-black text-xs uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-blue-600/30 transition-all hover:scale-105">
                    [ Submit Paper ]
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function paperSubmissionApp() {
    return {
        selectedFileName: '',
        selectedFileSize: '',
        authors: [
            {
                first_name: '{{ Auth::user()->name ?? "" }}',
                last_name: '',
                designation: '',
                department: '',
                institute: '{{ Auth::user()->affiliation ?? "" }}',
                orcid: '',
                email: '{{ Auth::user()->email ?? "" }}',
                mobile: ''
            }
        ],

        addAuthor() {
            this.authors.push({
                first_name: '',
                last_name: '',
                designation: '',
                department: '',
                institute: '',
                orcid: '',
                email: '',
                mobile: ''
            });
        },

        removeAuthor(index) {
            if (this.authors.length > 1) {
                this.authors.splice(index, 1);
            }
        },

        handleFileChange(event) {
            const file = event.target.files[0];
            if (file) {
                this.selectedFileName = file.name;
                this.selectedFileSize = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
            }
        }
    };
}
</script>
@endsection
