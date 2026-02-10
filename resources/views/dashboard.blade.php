@extends('layouts.admin')

@section('title', 'Welcome, ' . Auth::user()->name)
@section('breadcrumb', 'Home')

@section('content')
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Welcome to HJPARAM Portal</h2>
            <p class="text-gray-600 mb-6 max-w-2xl mx-auto">
                This is your centralized hub for managing your academic contributions.
                From here, you can submit new manuscripts, track their status, and perform reviews if assigned.
            </p>

            <div class="flex justify-center space-x-4">
                <a href="{{ route('submission.create') }}"
                    class="px-6 py-3 bg-blue-600 text-white rounded-md font-semibold hover:bg-blue-700 transition">
                    Submit New Manuscript
                </a>
                <a href="{{ route('submission.index') }}"
                    class="px-6 py-3 bg-gray-100 text-gray-700 rounded-md font-semibold hover:bg-gray-200 transition">
                    View My Submissions
                </a>
            </div>
        </div>

        <!-- Quick Stats for Authors -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex flex-col items-center">
                <div class="text-3xl font-bold text-gray-800 mb-2">{{ $counts['active_submissions'] }}</div>
                <div class="text-sm font-medium text-gray-500 uppercase">Active Submissions</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex flex-col items-center">
                <div class="text-3xl font-bold text-gray-800 mb-2">{{ $counts['pending_reviews'] }}</div>
                <div class="text-sm font-medium text-gray-500 uppercase">Pending Reviews</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex flex-col items-center">
                 <div class="text-3xl font-bold text-gray-800 mb-2">{{ $counts['published_articles'] }}</div>
                <div class="text-sm font-medium text-gray-500 uppercase">Published Articles</div>
            </div>
        </div>
    </div>
@endsection