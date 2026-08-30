@extends('layouts.admin')

@section('title', 'Dashboard')
@section('breadcrumb', 'Overview')

@section('content')
    <div class="space-y-6">
        <!-- Overview Widgets -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Users Widget -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Users</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['users'] }}</p>
                </div>
            </div>

            <!-- Journals Widget -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                        </path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Journals</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['journals'] }}</p>
                </div>
            </div>

            <!-- Submissions Widget -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Pending Review</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['submissions_pending'] }}</p>
                </div>
            </div>

            <!-- Published Articles Widget -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Published</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['articles_published'] }}</p>
                </div>
            </div>
        </div>

        <!-- Charts & Tables Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Visualization Placeholder -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Submission Trends</h3>
                <div class="h-64 bg-gray-50 rounded flex items-center justify-center text-gray-400">
                    <span>Chart Placeholder: Monthly Submissions</span>
                </div>
            </div>

            <!-- Recent Activity Feed (Using Static for Demo) -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800">Recent Activity</h3>
                </div>
                <ul class="divide-y divide-gray-200">
                    <li class="px-6 py-4 flex items-center hover:bg-gray-50 transition">
                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">New submission received</p>
                            <p class="text-xs text-gray-500">Dr. Author submitted "AI in Healthcare"</p>
                        </div>
                        <span class="text-xs text-gray-400">2h ago</span>
                    </li>
                    <li class="px-6 py-4 flex items-center hover:bg-gray-50 transition">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-3"></span>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">Article published</p>
                            <p class="text-xs text-gray-500">"Sustainable Energy" is now live</p>
                        </div>
                        <span class="text-xs text-gray-400">1d ago</span>
                    </li>
                    <li class="px-6 py-4 flex items-center hover:bg-gray-50 transition">
                        <span class="w-2 h-2 bg-yellow-500 rounded-full mr-3"></span>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">Review overdue</p>
                            <p class="text-xs text-gray-500">Reviewer Jane needs a reminder</p>
                        </div>
                        <span class="text-xs text-gray-400">2d ago</span>
                    </li>
                </ul>
                <div class="px-6 py-3 bg-gray-50 text-right">
                    <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-500">View All Activity &rarr;</a>
                </div>
            </div>
        </div>
    </div>
@endsection