@extends('layouts.admin')

@section('title', 'Manage Submissions')
@section('breadcrumb', 'Submissions')

@section('content')
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Manage Submissions</h2>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 mb-6">
            <form action="{{ route('admin.submissions.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="w-full md:w-64">
                    <select name="status"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 sm:text-sm">
                        <option value="">All Statuses</option>
                        <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                        <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Under Review
                        </option>
                        <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                        <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>
                <button type="submit"
                    class="px-4 py-2 bg-gray-800 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                    Filter
                </button>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title
                                / Author</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Journal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Submitted</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($submissions as $submission)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    #{{ $submission->id }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ Str::limit($submission->title, 50) }}
                                    </div>
                                    <div class="text-xs text-gray-500 flex items-center mt-1">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        {{ $submission->user->name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $submission->journal->title }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $submission->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusClasses = [
                                            'submitted' => 'bg-blue-100 text-blue-800',
                                            'under_review' => 'bg-yellow-100 text-yellow-800',
                                            'verified' => 'bg-emerald-100 text-emerald-800',
                                            'accepted' => 'bg-green-100 text-green-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                            'published' => 'bg-purple-100 text-purple-800',
                                        ];
                                    @endphp
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses[$submission->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst(str_replace('_', ' ', $submission->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-3">
                                        <!-- Open in New Tab Button -->
                                        <a href="{{ route('admin.submissions.view', $submission) }}" target="_blank"
                                            rel="noopener noreferrer"
                                            class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-md transition-colors duration-150 shadow-sm"
                                            title="Open in New Tab">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                                </path>
                                            </svg>
                                            Open in New Tab
                                        </a>

                                        <!-- Update Status Dropdown -->
                                        <div class="relative" x-data="{ open: false }">
                                            <button @click="open = !open"
                                                class="text-blue-600 hover:text-blue-900 focus:outline-none font-medium">
                                                Update Status
                                            </button>
                                            <div x-show="open" @click.away="open = false"
                                                class="absolute right-0 w-40 mt-2 bg-white rounded-md shadow-lg z-50 ring-1 ring-black ring-opacity-5"
                                                style="display: none;">
                                                <form action="{{ route('admin.submissions.update', $submission) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" name="status" value="under_review"
                                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Under
                                                        Review</button>
                                                    <button type="submit" name="status" value="accepted"
                                                        class="block w-full text-left px-4 py-2 text-sm text-green-700 hover:bg-green-50">Accept</button>
                                                    <button type="submit" name="status" value="verified"
                                                        class="block w-full text-left px-4 py-2 text-sm text-emerald-700 hover:bg-emerald-50">Verify for Publish</button>
                                                    <button type="submit" name="status" value="rejected"
                                                        class="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-50">Reject</button>
                                                </form>
                                            </div>
                                        </div>
                                        @if($submission->article)
                                            <a href="{{ route('admin.articles.edit', $submission->article) }}"
                                                class="text-purple-600 hover:text-purple-900 font-medium">
                                                Published Article
                                            </a>
                                        @elseif(in_array($submission->status, ['verified', 'accepted']))
                                            <a href="{{ route('admin.submissions.show', $submission) }}"
                                                class="text-emerald-600 hover:text-emerald-900 font-medium">
                                                Publish
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                    No submissions found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $submissions->links() }}
            </div>
        </div>
    </div>
@endsection
