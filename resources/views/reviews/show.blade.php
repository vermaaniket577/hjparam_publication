@extends('layouts.admin')
@section('title', 'Review Submission #' . $review->submission->id)
@section('breadcrumb', 'Review Submission')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Submission Details (Left Column) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-slate-800 shadow-sm rounded-3xl p-6 md:p-8 border border-slate-200/80 dark:border-slate-700">
                <div class="flex items-center gap-2 mb-3">
                    <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300">
                        {{ ucfirst(str_replace('_', ' ', $review->submission->article_type ?? 'Research Paper')) }}
                    </span>
                    @if($review->submission->conference)
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700">
                            {{ $review->submission->conference->title }}
                        </span>
                    @endif
                </div>

                <h1 class="text-xl md:text-2xl font-serif font-black text-slate-900 dark:text-white mb-4 leading-snug">
                    {{ $review->submission->title }}
                </h1>

                <div class="mb-6 space-y-2">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Abstract</h3>
                    <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-700/40 text-slate-700 dark:text-slate-200 text-xs md:text-sm leading-relaxed border border-slate-200/60">
                        {{ $review->submission->abstract }}
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-700/50 rounded-2xl border border-slate-200/80 dark:border-slate-600">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-red-100 text-red-600 flex items-center justify-center font-bold text-xs">
                            DOC
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-900 dark:text-white">Full Manuscript Document</p>
                            <p class="text-[11px] text-slate-500">{{ $review->submission->filename ?? 'manuscript.docx' }}</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.submissions.download', $review->submission) }}"
                       class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-blue-600/20">
                        Download for Review
                    </a>
                </div>
            </div>
        </div>

        <!-- Review Form (Right Column) -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-slate-800 shadow-sm rounded-3xl p-6 md:p-8 border border-slate-200/80 dark:border-slate-700 sticky top-24 space-y-4">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white pb-3 border-b border-slate-100 dark:border-slate-700">
                    Reviewer Response Form
                </h3>

                @if($review->completed_at)
                    <div class="space-y-4 text-xs">
                        <div class="bg-emerald-50 text-emerald-800 p-3.5 rounded-2xl border border-emerald-200 font-bold">
                            ✓ Review Completed & Submitted on {{ $review->completed_at->format('M d, Y') }}
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Recommendation</span>
                            <div class="font-bold text-slate-800 dark:text-white capitalize">
                                {{ str_replace('_', ' ', $review->recommendation) }}
                            </div>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Feedback Comments</span>
                            <div class="text-slate-600 dark:text-slate-300 p-3 bg-slate-50 dark:bg-slate-700/50 rounded-xl">
                                {{ $review->comments }}
                            </div>
                        </div>
                        @if($review->review_file_url)
                            <div>
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Uploaded Review File</span>
                                <a href="{{ $review->review_file_url }}" target="_blank" download class="text-blue-600 font-bold hover:underline">
                                    Download Annotated Review File
                                </a>
                            </div>
                        @endif
                    </div>
                @else
                    <form action="{{ route('reviews.update', $review->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Recommendation <span class="text-red-500">*</span></label>
                            <select name="recommendation" required
                                    class="w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-xs font-bold dark:text-white">
                                <option value="">Select recommendation...</option>
                                <option value="accept">Accept Manuscript</option>
                                <option value="minor_revision">Minor Revisions Required</option>
                                <option value="major_revision">Major Revisions Required</option>
                                <option value="reject">Reject Manuscript</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Reviewer Comments & Feedback <span class="text-red-500">*</span></label>
                            <textarea name="comments" rows="5" required minlength="10" placeholder="Provide detailed critique and recommendations..."
                                      class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-xs font-medium dark:text-white"></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Upload Annotated / Evaluation File (Optional)</label>
                            <input type="file" name="review_file" accept=".pdf,.doc,.docx,.txt"
                                   class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700">
                        </div>

                        <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md shadow-blue-600/30 transition-all">
                            Submit Review Response
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection