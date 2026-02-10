@extends('layouts.admin')
@section('title', 'Submission Details')
@section('breadcrumb', 'Submission Details')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left Column: Submission Info -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Main Info Card -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <span
                            class="inline-block px-2 py-1 text-xs font-semibold rounded-full 
                                                        {{ $submission->status === 'published' ? 'bg-green-100 text-green-800' :
        ($submission->status === 'rejected' ? 'bg-red-100 text-red-800' :
            ($submission->status === 'under_review' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800')) }}">
                            {{ ucfirst(str_replace('_', ' ', $submission->status)) }}
                        </span>
                        <h1 class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">{{ $submission->title }}</h1>
                    </div>
                    <div class="text-right text-sm text-gray-500 dark:text-gray-400">
                        <p>Submitted: {{ $submission->created_at->format('M d, Y') }}</p>
                        <p>ID: #{{ $submission->id }}</p>
                    </div>
                </div>

                <div class="prose dark:prose-invert max-w-none mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 border-b pb-2 mb-2">Abstract</h3>
                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed">{{ $submission->abstract }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <div>
                        <span class="block text-xs font-bold text-gray-500 uppercase">Author</span>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $submission->user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $submission->user->email }}</p>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-gray-500 uppercase">Journal</span>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $submission->journal->title }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <span class="block text-xs font-bold text-gray-500 uppercase mb-1">Files</span>
                        @if($submission->file_path)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z">
                                        </path>
                                    </svg>
                                    <span
                                        class="text-sm text-gray-700 font-medium">{{ basename($submission->file_path) }}</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <!-- Download Button Only -->
                                    <a href="{{ route('admin.submissions.download', $submission) }}"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg transition-all shadow-md group">
                                        <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        Download Manuscript
                                    </a>
                                </div>
                            </div>
                        @else
                            <p class="text-sm text-gray-500 italic">No manuscript file uploaded</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Reviews Section -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                        </path>
                    </svg>
                    Peer Reviews
                </h3>

                @if($submission->reviews->count() > 0)
                    <div class="space-y-4">
                        @foreach($submission->reviews as $review)
                            <div
                                class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 {{ $review->completed_at ? 'bg-green-50 dark:bg-green-900/10' : 'bg-gray-50 dark:bg-gray-700/50' }}">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <p class="font-bold text-gray-900 dark:text-white text-sm">{{ $review->reviewer->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Assigned:
                                            {{ $review->created_at->format('M d, Y') }}
                                        </p>
                                    </div>
                                    <div>
                                        @if($review->completed_at)
                                            <span
                                                class="px-2 py-0.5 rounded text-xs font-bold bg-green-200 text-green-800 dark:bg-green-800 dark:text-green-100">Completed</span>
                                        @else
                                            <span
                                                class="px-2 py-0.5 rounded text-xs font-bold bg-yellow-200 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">Pending</span>
                                        @endif
                                    </div>
                                </div>

                                @if($review->completed_at)
                                    <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-600">
                                        <div class="grid grid-cols-2 gap-4 mb-2">
                                            <div>
                                                <span class="text-xs font-bold text-gray-500 uppercase">Recommendation</span>
                                                <p
                                                    class="font-medium text-sm capitalize {{ $review->recommendation == 'accept' ? 'text-green-600' : ($review->recommendation == 'reject' ? 'text-red-600' : 'text-yellow-600') }}">
                                                    {{ str_replace('_', ' ', $review->recommendation) }}
                                                </p>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-xs font-bold text-gray-500 uppercase">Date</span>
                                                <p class="text-xs text-gray-700 dark:text-gray-300">
                                                    {{ $review->completed_at->format('M d, Y H:i') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div>
                                            <span class="text-xs font-bold text-gray-500 uppercase">Comments</span>
                                            <p
                                                class="text-sm text-gray-700 dark:text-gray-300 italic mt-1 bg-white dark:bg-gray-800 p-2 rounded border border-gray-100 dark:border-gray-600">
                                                "{{ $review->comments }}"
                                            </p>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-xs text-gray-500 mt-2 italic">Waiting for reviewer to submit feedback.</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div
                        class="text-center py-6 bg-gray-50 dark:bg-gray-700 rounded-lg border border-dashed border-gray-300 dark:border-gray-600">
                        <p class="text-gray-500 dark:text-gray-400 text-sm">No reviewers assigned yet.</p>
                    </div>
                @endif
            </div>

            <!-- Manuscript Viewer Section -->
            @if($submission->file_path)
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Manuscript Viewer
                        </h3>
                        <div class="flex items-center space-x-2">
                            <button onclick="window.open('{{ route('admin.submissions.view', $submission) }}', '_blank')"
                                class="text-sm text-blue-600 hover:text-blue-800 font-medium inline-flex items-center cursor-pointer">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                Open in New Tab
                            </button>
                        </div>
                    </div>

                    @php
                        $extension = strtolower(pathinfo($submission->file_path, PATHINFO_EXTENSION));
                        $isPdf = $extension === 'pdf';
                    @endphp

                    @if($isPdf)
                        <div
                            class="border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-900">
                            <iframe src="{{ route('admin.submissions.view', $submission) }}" class="w-full h-[800px]"
                                frameborder="0" title="Manuscript Viewer">
                            </iframe>
                        </div>
                    @else
                        <div
                            class="border border-gray-300 dark:border-gray-600 rounded-lg bg-blue-50 dark:bg-blue-900/10 p-12 text-center">
                            <div class="max-w-md mx-auto">
                                <div
                                    class="bg-blue-100 dark:bg-blue-800/30 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Microsoft Word Document</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-6 leading-relaxed">
                                    To preserve 100% of the original formatting, tables, and images, Word documents must be opened
                                    in Microsoft Word.
                                </p>
                                <a href="{{ route('admin.submissions.download', $submission) }}"
                                    class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition-all shadow-lg hover:shadow-blue-500/20">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    Download & Open in Word
                                </a>
                            </div>
                        </div>
                    @endif

                    <div class="mt-4 flex items-center justify-between text-xs text-gray-500">
                        <span>📄 {{ basename($submission->file_path) }}</span>
                        <span>If the document doesn't display, <a href="{{ route('admin.submissions.download', $submission) }}"
                                class="text-blue-600 hover:underline">download it here</a></span>
                    </div>
                </div>
            @endif

        </div>

        <!-- Right Column: Actions -->
        <div class="space-y-6">

            <!-- Status Update Card -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Editorial Decision</h3>
                <form action="{{ route('admin.submissions.update', $submission->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Update Status</label>
                        <select name="status"
                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="submitted" {{ $submission->status == 'submitted' ? 'selected' : '' }}>Submitted
                            </option>
                            <option value="under_review" {{ $submission->status == 'under_review' ? 'selected' : '' }}>Under
                                Review</option>
                            <option value="accepted" {{ $submission->status == 'accepted' ? 'selected' : '' }}>Accepted
                            </option>
                            <option value="rejected" {{ $submission->status == 'rejected' ? 'selected' : '' }}>Rejected
                            </option>
                            <option value="published" {{ $submission->status == 'published' ? 'selected' : '' }}>Published
                            </option>
                        </select>
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow transition duration-150 btn-loading-trigger"
                        onclick="this.classList.add('btn-loading')">
                        Update Status
                    </button>
                </form>
            </div>

            <!-- Assign Reviewer Card -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Assign Reviewer</h3>
                <form action="{{ route('admin.submissions.assign', $submission->id) }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select User</label>
                        <select name="reviewer_id"
                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Choose a reviewer...</option>
                            @foreach($reviewers as $reviewer)
                                <option value="{{ $reviewer->id }}">{{ $reviewer->name }} ({{ ucfirst($reviewer->role) }})
                                </option>
                            @endforeach
                        </select>
                        @error('reviewer_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-2 px-4 rounded shadow-sm border border-gray-300 transition duration-150 btn-loading-trigger"
                        onclick="this.classList.add('btn-loading')">
                        Assign Reviewer
                    </button>
                </form>
            </div>

        </div>

    </div>
@endsection