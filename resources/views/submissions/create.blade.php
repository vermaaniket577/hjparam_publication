@extends('layouts.admin')

@section('title', 'Submit New Manuscript')
@section('breadcrumb', 'New Submission')

@section('content')
    <div class="max-w-5xl mx-auto py-8">

        <!-- Header Section -->
        <div class="mb-10 text-center">
            <h1 class="text-3xl font-serif font-bold text-slate-800 tracking-tight mb-2">Submit New Manuscript</h1>
            <p class="text-slate-500 font-light text-lg">Detailed Contribution to Global Knowledge</p>
        </div>

        <form action="{{ route('submission.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <!-- 1. Primary Manuscript Details -->
            <div
                class="bg-white rounded-xl shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] overflow-hidden border border-slate-100/50">
                <div class="bg-slate-50/50 px-8 py-4 border-b border-slate-100 flex items-center gap-3">
                    <div class="bg-blue-100 text-blue-600 w-8 h-8 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h2 class="text-base font-bold text-slate-700 uppercase tracking-wider">Primary Manuscript Details</h2>
                </div>

                <div class="p-8 space-y-6">
                    <!-- Title -->
                    <div>
                        <label for="title"
                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Research Title
                            <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="title"
                            class="w-full bg-slate-50 border-slate-200 rounded-lg p-4 text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all font-serif text-lg"
                            placeholder="Full title of the manuscript..." required value="{{ old('title') }}">
                        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Journal & Issue -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="journal_id"
                                class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Target Journal
                                <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="journal_id" id="journal_id"
                                    class="w-full bg-slate-50 border-slate-200 rounded-lg p-3 text-slate-700 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 appearance-none transition-all cursor-pointer"
                                    required>
                                    <option value="" disabled selected>Select Journal...</option>
                                    @foreach($journals as $journal)
                                        <option value="{{ $journal->id }}" {{ request()->get('journal_id') == $journal->id ? 'selected' : '' }}>{{ $journal->title }}</option>
                                    @endforeach
                                </select>
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                            @error('journal_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Volume &
                                Issue</label>
                            <div class="relative">
                                <select disabled
                                    class="w-full bg-slate-100 border-slate-200 rounded-lg p-3 text-slate-400 cursor-not-allowed">
                                    <option>Assign to Issue (Optional)</option>
                                </select>
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                            <p class="text-[10px] text-slate-400 mt-1">Issues are assigned by editors upon acceptance.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Author Attribution -->
            <div
                class="bg-white rounded-xl shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] overflow-hidden border border-slate-100/50">
                <div class="bg-slate-50/50 px-8 py-4 border-b border-slate-100 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="bg-green-100 text-green-600 w-8 h-8 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h2 class="text-base font-bold text-slate-700 uppercase tracking-wider">Author Attribution</h2>
                    </div>
                    <button type="button"
                        class="bg-emerald-500 hover:bg-emerald-600 text-white text-[10px] font-bold uppercase tracking-widest px-4 py-2 rounded-full transition-colors shadow-lg shadow-emerald-500/20 flex items-center gap-2">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Scholar
                    </button>
                </div>

                <div class="p-8">
                    <!-- Scholar Card (Simulated) -->
                    <div
                        class="border border-slate-200 rounded-xl p-5 bg-slate-50/30 relative group hover:border-blue-300 transition-colors">
                        <div class="absolute top-4 right-4">
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] uppercase font-bold text-slate-400">Corresponding Author</span>
                                <div
                                    class="relative inline-flex h-5 w-9 items-center rounded-full bg-blue-600 cursor-pointer">
                                    <span
                                        class="translate-x-4.5 inline-block h-3.5 w-3.5 transform rounded-full bg-white transition shadow-sm"></span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Full
                                    Scholar Name</label>
                                <div
                                    class="font-medium text-slate-700 bg-white border border-slate-200 px-4 py-2 rounded-lg">
                                    {{ auth()->user()->name }}
                                </div>
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Institutional
                                    Email</label>
                                <div
                                    class="font-medium text-slate-700 bg-white border border-slate-200 px-4 py-2 rounded-lg">
                                    {{ auth()->user()->email }}
                                </div>
                            </div>
                            <div class="md:col-span-2">
                                <label
                                    class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Academic
                                    Affiliation</label>
                                <div
                                    class="font-medium text-slate-700 bg-white border border-slate-200 px-4 py-2 rounded-lg">
                                    {{ auth()->user()->affiliation ?? 'Not provided' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Manuscript Content (Abstract & File) -->
            <div
                class="bg-white rounded-xl shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] overflow-hidden border border-slate-100/50">
                <div class="bg-slate-50/50 px-8 py-4 border-b border-slate-100 flex items-center gap-3">
                    <div class="bg-purple-100 text-purple-600 w-8 h-8 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h2 class="text-base font-bold text-slate-700 uppercase tracking-wider">Manuscript Content</h2>
                </div>

                <div class="p-8 space-y-8">
                    <!-- Abstract -->
                    <div>
                        <label for="abstract"
                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Manuscript Abstract
                            <span class="text-red-500">*</span></label>
                        <textarea name="abstract" id="abstract" rows="8"
                            class="w-full bg-slate-50 border-slate-200 rounded-lg p-4 text-slate-700 placeholder-slate-400 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all leading-relaxed"
                            placeholder="Summary of research findings..." required>{{ old('abstract') }}</textarea>
                        @error('abstract') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- File Upload -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Manuscript File
                            (PDF, DOCX) <span class="text-red-500">*</span></label>

                        <div class="border-2 border-dashed border-slate-300 rounded-xl p-8 text-center hover:bg-slate-50 hover:border-blue-400 transition-all cursor-pointer group"
                            id="dropZone">
                            <input id="submissionFile" name="file" type="file" class="hidden" accept=".pdf,.doc,.docx"
                                required>

                            <div
                                class="w-16 h-16 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-100 group-hover:scale-110 transition-transform">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                            </div>

                            <p class="text-slate-600 font-medium mb-1 group-hover:text-blue-600 transition-colors">Click to
                                upload or drag and drop</p>
                            <p class="text-xs text-slate-400">PDF, DOC, DOCX (Max 50MB)</p>

                            <!-- File Preview -->
                            <div id="filePreview" class="mt-6 hidden">
                                <div
                                    class="inline-flex items-center gap-3 bg-blue-50 border border-blue-200 rounded-lg px-4 py-2 text-blue-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span id="fileName" class="text-sm font-medium truncate max-w-[200px]"></span>
                                    <button type="button" onclick="clearFile()"
                                        class="text-blue-400 hover:text-red-500 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @error('file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Footer Action Bar -->
            <div
                class="bg-indigo-900 rounded-2xl p-6 flex flex-col md:flex-row items-center justify-between gap-6 shadow-2xl shadow-indigo-900/40 border border-indigo-800">
                <!-- Status Indicator -->
                <div class="flex items-center gap-4">
                    <div class="flex h-3 w-3 relative">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-sky-500"></span>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-indigo-300">Submission Status</p>
                        <p class="text-white text-sm font-medium">Draft in Progress</p>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex items-center gap-4">
                    <a href="{{ route('dashboard') }}"
                        class="text-indigo-300 hover:text-white text-xs font-bold uppercase tracking-widest px-4 py-2 transition-colors">Abort</a>
                    <button type="submit"
                        class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-black text-xs uppercase tracking-[0.25em] px-8 py-4 rounded-xl shadow-lg shadow-blue-900/50 hover:shadow-blue-600/40 hover:-translate-y-0.5 transition-all duration-300">
                        Commit Manuscript
                    </button>
                </div>
            </div>

        </form>
    </div>

    <!-- JS Handling for File Upload -->
    <script>
        const fileInput = document.getElementById('submissionFile');
        const dropZone = document.getElementById('dropZone');
        const filePreview = document.getElementById('filePreview');
        const fileName = document.getElementById('fileName');

        // Click to upload
        dropZone.addEventListener('click', (e) => {
            if (e.target !== fileInput && !e.target.closest('button')) {
                fileInput.click();
            }
        });

        // File Selection Change
        fileInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                handleFile(this.files[0]);
            }
        });

        // Drag & Drop
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('bg-blue-50', 'border-blue-400');
        });

        dropZone.addEventListener('dragleave', (e) => {
            e.preventDefault();
            dropZone.classList.remove('bg-blue-50', 'border-blue-400');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('bg-blue-50', 'border-blue-400');
            if (e.dataTransfer.files.length > 0) {
                fileInput.files = e.dataTransfer.files;
                handleFile(e.dataTransfer.files[0]);
            }
        });

        function handleFile(file) {
            fileName.textContent = file.name;
            filePreview.classList.remove('hidden');
        }

        function clearFile() {
            fileInput.value = '';
            filePreview.classList.add('hidden');
            event.stopPropagation();
        }
    </script>
@endsection