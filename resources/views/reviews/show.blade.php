@extends('layouts.admin')
@section('title', 'Review Submission #' . $review->submission->id)
@section('breadcrumb', 'Review Submission')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- Submission Details (Left Column) -->
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                <h1 class="text-2xl font-serif font-bold text-gray-900 dark:text-white mb-4">
                    {{ $review->submission->title }}</h1>

                <div class="mb-6 prose dark:prose-invert max-w-none">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 border-b pb-2 mb-3">Abstract</h3>
                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed">{{ $review->submission->abstract }}</p>
                </div>

                <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z">
                            </path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Manuscript File</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Download to read the full paper.</p>
                        </div>
                    </div>
                    <!-- Assuming file path is stored and we have a route to download it securely -->
                    {{-- In a real app, this should be a protected route --}}
                    <a href="#"
                        class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700 transition">
                        Download PDF
                    </a>
                </div>
            </div>
        </div>

        <!-- Review Form (Right Column) -->
        <div class="md:col-span-1">
            <div
                class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 border border-gray-200 dark:border-gray-700 sticky top-24">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4 border-b pb-2">Your Review</h3>

                @if($review->completed_at)
                    <div class="space-y-4">
                        <div class="bg-green-50 text-green-800 p-3 rounded">
                            <span class="font-bold">Status:</span> Completed
                        </div>
                        <div>
                            <label
                                class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Recommendation</label>
                            <div class="font-medium text-gray-900 dark:text-white capitalize">
                                {{ str_replace('_', ' ', $review->recommendation) }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Comments</label>
                            <div class="text-sm text-gray-600 dark:text-gray-300 italic">
                                "{{Str::limit($review->comments, 100)}}"
                            </div>
                        </div>
                        <button class="w-full mt-4 bg-gray-200 text-gray-500 font-bold py-2 rounded cursor-not-allowed"
                            disabled>Submitted</button>
                    </div>
                @else
                    <form action="{{ route('reviews.update', $review->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Recommendation</label>
                            <select name="recommendation"
                                class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                required>
                                <option value="">Select a specific recommendation...</option>
                                <option value="accept">Accept Submission</option>
                                <option value="minor_revision">Minor Revisions Required</option>
                                <option value="major_revision">Major Revisions Required</option>
                                <option value="reject">Reject Submission</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Review
                                Comments</label>
                            <textarea name="comments" rows="6"
                                class="block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                placeholder="Provide detailed feedback for the authors..." required minlength="20"></textarea>
                            <p class="text-xs text-gray-500 mt-1">Comments are confidential to the editor and author.</p>
                        </div>

                        <button type="submit"
                            class="w-full btn-loading-trigger bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded shadow-md transition duration-150 ease-in-out transform hover:-translate-y-0.5"
                            onclick="this.classList.add('btn-loading')">
                            Submit Review
                        </button>
                    </form>
                @endif
            </div>
        </div>

    </div>
@endsection