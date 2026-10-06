@extends('layouts.web')

@section('title', 'Submit New Manuscript | HJPARAM Academic Publishing')

@section('content')
<div class="bg-slate-50/60 py-10 md:py-14 min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ 
        coAuthors: [],
        addCoAuthor() {
            this.coAuthors.push({
                name: '',
                email: '',
                department: '',
                institution: '',
                city_state_country: '',
                orcid: ''
            });
        },
        removeCoAuthor(index) {
            this.coAuthors.splice(index, 1);
        }
    }">

        <!-- Breadcrumb & Header Section -->
        <div class="mb-8">
            <nav class="flex items-center gap-2 text-xs font-medium text-slate-500 mb-3">
                <a href="{{ route('home') }}" class="hover:text-blue-600 transition-colors">Home</a>
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <a href="{{ route('author.guidelines') }}" class="hover:text-blue-600 transition-colors">Author Services</a>
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-blue-600 font-semibold">Submit Manuscript</span>
            </nav>

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-200/80 pb-6">
                <div>
                    <h1 class="text-2xl md:text-3xl font-serif font-bold text-slate-900 tracking-tight mb-1.5">
                        Submit New Manuscript
                    </h1>
                    <p class="text-slate-500 text-sm">
                        Original research contribution to global open access academic journals.
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('author.guidelines') }}" target="_blank"
                       class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-blue-600 bg-white border border-slate-200 px-3.5 py-2 rounded-xl transition shadow-xs">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Author Guidelines
                    </a>
                </div>
            </div>
        </div>

        @if(session('error'))
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
                <p class="font-bold mb-1">Please correct the following errors:</p>
                <ul class="list-disc list-inside text-xs space-y-0.5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('submission.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <!-- 1. Primary Manuscript Details -->
            <div class="bg-white rounded-2xl shadow-xs border border-slate-200/90 overflow-hidden">
                <div class="bg-slate-50/80 px-6 sm:px-8 py-4 border-b border-slate-200/80 flex items-center gap-3">
                    <div class="bg-blue-100 text-blue-600 w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">1. Primary Manuscript Details</h2>
                        <p class="text-xs text-slate-500">Select target publication journal and article title</p>
                    </div>
                </div>

                <div class="p-6 sm:p-8 space-y-6">
                    <!-- Research Title -->
                    <div>
                        <label for="title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Research Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="title"
                            class="w-full bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-3.5 text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all font-serif text-base md:text-lg"
                            placeholder="Full title of the research manuscript..." required value="{{ old('title') }}">
                        @error('title') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Journal & Issue Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="journal_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Target Journal <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="journal_id" id="journal_id"
                                    class="w-full bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 text-sm font-medium focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-500 appearance-none transition-all cursor-pointer"
                                    required>
                                    <option value="" disabled {{ request()->get('journal_id') ? '' : 'selected' }}>Select Target Journal...</option>
                                    @foreach($journals as $journal)
                                        <option value="{{ $journal->id }}" {{ (old('journal_id', request()->get('journal_id')) == $journal->id) ? 'selected' : '' }}>
                                            {{ $journal->title }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-3.5 pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                            @error('journal_id') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Volume & Issue Assignment
                            </label>
                            <div class="relative">
                                <input type="text" disabled value="Assigned by Editor upon peer-review acceptance"
                                    class="w-full bg-slate-100/80 border border-slate-200 rounded-xl px-4 py-3 text-slate-400 text-sm cursor-not-allowed">
                                <div class="absolute inset-y-0 right-0 flex items-center px-3.5 pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                            </div>
                            <p class="text-[11px] text-slate-400 mt-1.5">Editorial desk assigns Volume/Issue once review is finalized.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Author Attribution & Affiliation (Standard Academic Format) -->
            <div class="bg-white rounded-2xl shadow-xs border border-slate-200/90 overflow-hidden">
                <div class="bg-slate-50/80 px-6 sm:px-8 py-4 border-b border-slate-200/80 flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="bg-emerald-100 text-emerald-600 w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">2. Author Attribution & Affiliation</h2>
                            <p class="text-xs text-slate-500">Provide official institutional affiliation details</p>
                        </div>
                    </div>

                    <button type="button" @click="addCoAuthor()"
                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200/80 transition-colors cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Co-Author
                    </button>
                </div>

                <div class="p-6 sm:p-8 space-y-6">
                    <!-- Primary / Corresponding Author Card -->
                    <div class="border border-blue-200/80 bg-blue-50/20 rounded-2xl p-5 md:p-6 relative transition-all">
                        <div class="flex items-center justify-between mb-5 pb-3 border-b border-blue-100">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-blue-600 text-white text-[11px] font-bold flex items-center justify-center">1</span>
                                <span class="text-xs font-bold text-slate-800 uppercase tracking-wider">Primary Corresponding Author</span>
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700 uppercase tracking-wider">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                Lead Author
                            </span>
                        </div>

                        <div class="space-y-4">
                            <!-- Row 1: Full Name & Official Email Address -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                                <div>
                                    <label for="author_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Full Author Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="author_name" name="author_name"
                                        value="{{ old('author_name', auth()->user()->name) }}" required
                                        class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 placeholder-slate-400 text-xs font-medium focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all"
                                        placeholder="e.g. Dr. Jane Doe">
                                </div>

                                <div>
                                    <label for="official_email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Official Email Address <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" id="official_email" name="official_email"
                                        value="{{ old('official_email', auth()->user()->email) }}" required
                                        class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 placeholder-slate-400 text-xs font-medium focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all"
                                        placeholder="e.g. author@university.edu">
                                </div>
                            </div>

                            <!-- Row 2: Department / Division & Institution / University (Standard Clean Format) -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                                <div>
                                    <label for="department" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Department / Division
                                    </label>
                                    <input type="text" id="department" name="department"
                                        value="{{ old('department') }}"
                                        class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 placeholder-slate-400 text-xs font-medium focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all"
                                        placeholder="e.g., Department of Computer Science">
                                </div>

                                <div>
                                    <label for="institution" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Institution / University <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="institution" name="institution"
                                        value="{{ old('institution', auth()->user()->affiliation) }}" required
                                        class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 placeholder-slate-400 text-xs font-medium focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all"
                                        placeholder="e.g., Stanford University">
                                </div>
                            </div>

                            <!-- Row 3: City, State / Province, Country & ORCID ID -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                                <div>
                                    <label for="city_state_country" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        City, State / Province, Country <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="city_state_country" name="city_state_country"
                                        value="{{ old('city_state_country') }}" required
                                        class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 placeholder-slate-400 text-xs font-medium focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all"
                                        placeholder="e.g., Stanford, CA, United States">
                                </div>

                                <div>
                                    <label for="orcid" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        ORCID ID <span class="text-slate-400 text-[10px] normal-case">(Optional)</span>
                                    </label>
                                    <input type="text" id="orcid" name="orcid"
                                        value="{{ old('orcid') }}"
                                        class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 placeholder-slate-400 text-xs font-mono focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all"
                                        placeholder="e.g., 0000-0002-1825-0097">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dynamic Co-Authors Section -->
                    <template x-for="(coAuthor, index) in coAuthors" :key="index">
                        <div class="border border-slate-200 bg-slate-50/40 rounded-2xl p-5 md:p-6 relative transition-all">
                            <div class="flex items-center justify-between mb-5 pb-3 border-b border-slate-200/80">
                                <div class="flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-full bg-slate-700 text-white text-[11px] font-bold flex items-center justify-center" x-text="index + 2"></span>
                                    <span class="text-xs font-bold text-slate-700 uppercase tracking-wider" x-text="'Co-Author #' + (index + 1)"></span>
                                </div>
                                <button type="button" @click="removeCoAuthor(index)"
                                    class="inline-flex items-center gap-1 text-[11px] font-bold text-red-600 hover:text-red-700 hover:bg-red-50 px-2.5 py-1 rounded-lg transition-colors cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Remove
                                </button>
                            </div>

                            <div class="space-y-4">
                                <!-- Co-Author Row 1 -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                            Full Author Name <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" :name="'co_authors[' + index + '][name]'" x-model="coAuthor.name" required
                                            class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 placeholder-slate-400 text-xs font-medium focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all"
                                            placeholder="Co-author full name">
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                            Official Email Address <span class="text-red-500">*</span>
                                        </label>
                                        <input type="email" :name="'co_authors[' + index + '][email]'" x-model="coAuthor.email" required
                                            class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 placeholder-slate-400 text-xs font-medium focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all"
                                            placeholder="coauthor@university.edu">
                                    </div>
                                </div>

                                <!-- Co-Author Row 2 -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                            Department / Division
                                        </label>
                                        <input type="text" :name="'co_authors[' + index + '][department]'" x-model="coAuthor.department"
                                            class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 placeholder-slate-400 text-xs font-medium focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all"
                                            placeholder="e.g., Department of Physics">
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                            Institution / University <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" :name="'co_authors[' + index + '][institution]'" x-model="coAuthor.institution" required
                                            class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 placeholder-slate-400 text-xs font-medium focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all"
                                            placeholder="e.g., Harvard University">
                                    </div>
                                </div>

                                <!-- Co-Author Row 3 -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                            City, State / Province, Country <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" :name="'co_authors[' + index + '][city_state_country]'" x-model="coAuthor.city_state_country" required
                                            class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 placeholder-slate-400 text-xs font-medium focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all"
                                            placeholder="e.g., Cambridge, MA, United States">
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                            ORCID ID <span class="text-slate-400 text-[10px] normal-case">(Optional)</span>
                                        </label>
                                        <input type="text" :name="'co_authors[' + index + '][orcid]'" x-model="coAuthor.orcid"
                                            class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 placeholder-slate-400 text-xs font-mono focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all"
                                            placeholder="e.g., 0000-0003-9876-5432">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- 3. Manuscript Content (Abstract, Keywords, File) -->
            <div class="bg-white rounded-2xl shadow-xs border border-slate-200/90 overflow-hidden">
                <div class="bg-slate-50/80 px-6 sm:px-8 py-4 border-b border-slate-200/80 flex items-center gap-3">
                    <div class="bg-purple-100 text-purple-600 w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">3. Manuscript Content & Upload</h2>
                        <p class="text-xs text-slate-500">Provide manuscript abstract, keywords, and document file</p>
                    </div>
                </div>

                <div class="p-6 sm:p-8 space-y-6">
                    <!-- Abstract -->
                    <div>
                        <label for="abstract" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Manuscript Abstract <span class="text-red-500">*</span>
                        </label>
                        <textarea name="abstract" id="abstract" rows="7"
                            class="w-full bg-slate-50/50 border border-slate-200 rounded-xl p-4 text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all leading-relaxed text-sm font-normal"
                            placeholder="Provide a comprehensive abstract outlining the background, methodology, primary results, and key conclusions (typically 150-300 words)..." required>{{ old('abstract') }}</textarea>
                        @error('abstract') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Keywords (Optional) -->
                    <div>
                        <label for="keywords" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Keywords <span class="text-slate-400 text-[10px] normal-case">(Optional, comma-separated)</span>
                        </label>
                        <input type="text" name="keywords" id="keywords" value="{{ old('keywords') }}"
                            class="w-full bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 placeholder-slate-400 text-sm focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all"
                            placeholder="e.g. Sustainable Energy, Machine Learning, Open Access, Environmental Engineering">
                    </div>

                    <!-- File Upload -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Manuscript File (PDF, DOC, DOCX) <span class="text-red-500">*</span>
                        </label>

                        <div class="border-2 border-dashed border-slate-200 hover:border-blue-400 rounded-2xl p-8 text-center bg-slate-50/40 hover:bg-blue-50/20 transition-all cursor-pointer group"
                            id="dropZone">
                            <input id="submissionFile" name="file" type="file" class="hidden" accept=".pdf,.doc,.docx" required>

                            <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-3.5 group-hover:scale-105 group-hover:bg-blue-100 transition-all">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                            </div>

                            <p class="text-slate-800 font-bold text-sm mb-1 group-hover:text-blue-600 transition-colors">
                                Drag & drop manuscript here, or <span class="text-blue-600 underline underline-offset-2">browse files</span>
                            </p>
                            <p class="text-xs text-slate-400">Accepted formats: .pdf, .doc, .docx (Max 500MB)</p>

                            <!-- File Preview -->
                            <div id="filePreview" class="mt-4 hidden inline-flex items-center gap-3 bg-white border border-blue-200 rounded-xl px-4 py-2.5 shadow-xs">
                                <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <span id="fileName" class="text-xs font-bold text-slate-800 block truncate max-w-[240px]"></span>
                                    <span id="fileSize" class="text-[10px] text-slate-400 font-medium"></span>
                                </div>
                                <button type="button" onclick="clearFile(event)"
                                    class="text-slate-400 hover:text-red-500 p-1 rounded-md transition-colors" title="Remove file">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        @error('file') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- 4. Declarations & Submission Action Bar -->
            <div class="bg-white rounded-2xl shadow-xs border border-slate-200/90 p-6 sm:p-8 space-y-6">
                <div class="space-y-3">
                    <label class="flex items-start gap-3 cursor-pointer group">
                        <input type="checkbox" required name="declaration_originality" value="1"
                            class="mt-1 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-xs text-slate-600 leading-relaxed group-hover:text-slate-900 transition-colors">
                            <strong>Originality & Plagiarism:</strong> I confirm that this manuscript represents original research work, has not been published elsewhere, and is not currently under consideration by any other journal or conference.
                        </span>
                    </label>

                    <label class="flex items-start gap-3 cursor-pointer group">
                        <input type="checkbox" required name="declaration_ethics" value="1"
                            class="mt-1 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-xs text-slate-600 leading-relaxed group-hover:text-slate-900 transition-colors">
                            <strong>Ethics & Authorship:</strong> All listed contributors meet the criteria for authorship, have approved this submission, and agree to HJPARAM's editorial policies.
                        </span>
                    </label>
                </div>

                <div class="border-t border-slate-200/80 pt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></div>
                        <span class="text-xs font-semibold text-slate-500">Ready for Editorial Review</span>
                    </div>

                    <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                        <a href="{{ route('dashboard') }}"
                            class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:text-slate-900 hover:bg-slate-50 text-xs font-bold uppercase tracking-wider transition-colors">
                            Cancel
                        </a>

                        <button type="submit"
                            class="inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-xl bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-xs font-bold uppercase tracking-wider shadow-lg shadow-blue-600/20 hover:shadow-blue-600/30 hover:-translate-y-0.5 transition-all cursor-pointer">
                            <span>Submit Manuscript</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>

<!-- JavaScript for File Drag & Drop -->
<script>
    const fileInput = document.getElementById('submissionFile');
    const dropZone = document.getElementById('dropZone');
    const filePreview = document.getElementById('filePreview');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');

    dropZone.addEventListener('click', (e) => {
        if (e.target !== fileInput && !e.target.closest('button')) {
            fileInput.click();
        }
    });

    fileInput.addEventListener('change', function () {
        if (this.files && this.files[0]) {
            handleFile(this.files[0]);
        }
    });

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-blue-500', 'bg-blue-50/40');
    });

    dropZone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-blue-500', 'bg-blue-50/40');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-blue-500', 'bg-blue-50/40');
        if (e.dataTransfer.files.length > 0) {
            fileInput.files = e.dataTransfer.files;
            handleFile(e.dataTransfer.files[0]);
        }
    });

    function formatBytes(bytes, decimals = 2) {
        if (!+bytes) return '0 Bytes';
        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`;
    }

    function handleFile(file) {
        fileName.textContent = file.name;
        fileSize.textContent = formatBytes(file.size);
        filePreview.classList.remove('hidden');
    }

    function clearFile(event) {
        event.stopPropagation();
        fileInput.value = '';
        filePreview.classList.add('hidden');
    }
</script>
@endsection