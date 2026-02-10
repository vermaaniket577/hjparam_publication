@extends('layouts.admin')

@section('title', 'Platform Analytics')
@section('breadcrumb', 'Analytics')

@section('content')
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-bold text-gray-800">Platform Analytics</h2>
            <div class="flex space-x-2">
                <button class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50">Last 30
                    Days</button>
                <button class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50">Last
                    Year</button>
            </div>
        </div>

        <!-- Review Stats Cards (Using reuseable components ideally, but inline for now) -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wider">Total Users</h3>
                    <span class="p-2 bg-blue-50 rounded-full text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </span>
                </div>
                <div class="flex items-end justify-between">
                    <span class="text-3xl font-bold text-gray-800">{{ $stats['total_users'] }}</span>
                    <span class="text-green-500 text-sm font-bold flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        +12%
                    </span>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wider">Submissions</h3>
                    <span class="p-2 bg-indigo-50 rounded-full text-indigo-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </span>
                </div>
                <div class="flex items-end justify-between">
                    <span class="text-3xl font-bold text-gray-800">{{ $stats['total_submissions'] }}</span>
                    <span class="text-green-500 text-sm font-bold flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        +5%
                    </span>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wider">Published</h3>
                    <span class="p-2 bg-green-50 rounded-full text-green-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </span>
                </div>
                <div class="flex items-end justify-between">
                    <span class="text-3xl font-bold text-gray-800">{{ $stats['published_articles'] }}</span>
                    <span class="text-gray-400 text-sm">Total</span>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wider">Pending Review</h3>
                    <span class="p-2 bg-yellow-50 rounded-full text-yellow-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </span>
                </div>
                <div class="flex items-end justify-between">
                    <span class="text-3xl font-bold text-gray-800">{{ $stats['pending_reviews'] }}</span>
                    <span class="text-yellow-500 text-sm font-bold">Needs Action</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Submissions Chart -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-6">Submissions Overview</h3>
                <div class="h-64 flex items-end justify-between space-x-4 px-2">
                    @foreach($submissionsByMonth as $month)
                            @php 
                                                $height = $month['count'] > 0 ? ($month['count'] / 50) * 100 : 5; // Normalize to max 20 for demo (50 px)
                                if ($height > 100)
                                    $height = 100;
                            @endphp

                                                       <div class="flex flex-col items-center flex-1 group">
                                <div cla
                                       ss="w-full bg-blue-100 rounded-t-sm relative h-full flex items-end group-hover:bg-blue-200 transition">
                                    <div class="w-full bg-blue-600 rounded-t-sm transition-all duration-500" style="height: {{ $height }}%"></div>
                                    <!-- Tooltip -->
                                    <div class="absolute -top-10 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition">
                                        {{ $month['count'] }} Submissions
                                    </div>
                                </div>
                               <div class="text-xs text-gray-500 mt-2">{{ $month['month_name'] }}</div>
                           </div>
                    @endforeach

                                       <!-- Empty placeholder bars if no data -->
                     @if($submissionsByMonth->isEmpty())
                        <div class="w-full h-full flex items-center justify-center text-gray-400">No submission data available yet.</div>
                     @endif
                </div>
            </div>

            <!-- Top Journals -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-6">Top Performing Journals</h3>
                <div class="space-y-4">
                    @foreach($articlesByJournal as $journal)
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-sm font-medium text-gray-700">{{ $journal->title }}</span>
                                <span class="text-sm text-gray-500 font-bold">{{ $journal->articles_count }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                @php 
                                                            $width = ($journal->articles_count / 50) * 100;
                                    if ($width > 100)
                                        $width = 100;
                                @endphp
                               <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $width }}%"></div>
                            </div>
                        </div>
                    @endforeach
                     @if($articlesByJournal->isEmpty())
                        <div class="text-gray-400 text-center py-10">No journals found.</div>
                     @endif
                </div>
            </div>
        </div>

        <!-- Top Viewed Articles -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-bold text-gray-800">Most Viewed Articles</h3>

                                        </div>

                                        <div class="overflow-x-auto">

                                        <table class="min-w-full divide-y divide-gray-200">

                                            <thead class="bg-white">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Article Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Journal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Views</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($topArticles as $article)
                            <tr>

                                                            <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ Str::limit($article->title, 50) }}</div>

                                </td>

                                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $article->journal->title }}</td>
                                <td class="px-6 py-4 text-sm font-bold text-gray-900">{{ $article->views_count }}</td>
                                <td class="px-6 py-4 text-right text-sm text-gray-500">{{ $article->published_at ? $article->published_at->format('M d, Y') : 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">No articles viewed yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
