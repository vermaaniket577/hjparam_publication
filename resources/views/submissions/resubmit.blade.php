@extends('layouts.web')
@section('title', 'Re-submit Manuscript with Corrections')

@section('content')
<div class="bg-slate-50 min-h-screen py-12 md:py-16">
    <div class="container mx-auto px-4 max-w-3xl">
        
        <div class="mb-6">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-blue-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Dashboard
            </a>
        </div>

        <div class="bg-white rounded-3xl p-8 md:p-10 shadow-xl border border-slate-100 space-y-8">
            <div class="border-b border-slate-100 pb-5">
                <span class="px-3 py-1 bg-amber-50 text-amber-700 text-[10px] font-black uppercase tracking-widest rounded-full border border-amber-100">
                    Step 3: Re-submit with Correction
                </span>
                <h1 class="text-2xl md:text-3xl font-serif font-black text-slate-900 mt-3 leading-tight">
                    Upload Corrected Manuscript
                </h1>
                <p class="text-slate-500 text-xs md:text-sm mt-1">Paper: <strong>{{ $submission->title }}</strong></p>
            </div>

            <!-- Editorial Feedback Notes -->
            @if($submission->revision_comments)
                <div class="p-5 rounded-2xl bg-amber-50/70 border border-amber-200 text-xs space-y-2">
                    <span class="block text-[11px] font-black uppercase tracking-wider text-amber-800">Editorial & Reviewer Correction Remarks:</span>
                    <p class="text-slate-700 leading-relaxed">{{ $submission->revision_comments }}</p>
                </div>
            @endif

            <form action="{{ route('submissions.resubmit.store', $submission) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Updated Abstract (Optional)</label>
                    <textarea name="abstract" rows="5" class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-800 leading-relaxed focus:bg-white focus:border-blue-600">{{ old('abstract', $submission->abstract) }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Upload Revised Full Manuscript <span class="text-red-500">*</span></label>
                    <input type="file" name="manuscript_file" required accept=".pdf,.doc,.docx"
                           class="w-full text-xs text-slate-500 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                    <p class="text-[10px] text-slate-400 mt-1.5">PDF, DOC, DOCX with incorporated reviewer corrections (Max 100MB)</p>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-2xl text-xs uppercase tracking-widest shadow-xl shadow-blue-600/30 transition-all hover:scale-[1.01]">
                        Submit Revised Manuscript
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
