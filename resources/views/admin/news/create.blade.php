@extends('layouts.admin')
@section('title', 'Create News')
@section('breadcrumb', 'Add News')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Create New News Article</h3>

            <form action="{{ route('admin.news.store') }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Title</label>
                    <input type="text" name="title"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Slug (URL)</label>
                        <input type="text" name="slug"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Auto-generated from title if empty">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category</label>
                        <input type="text" name="category"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="e.g. Technology, Partnership">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Published
                            Date</label>
                        <input type="date" name="published_at"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="flex items-center pt-8">
                        <input type="checkbox" name="is_active" value="1"
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" checked>
                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Active / Published</span>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Brief Description (Meta /
                        Cards)</label>
                    <textarea name="description" rows="3"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        required></textarea>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Content (HTML
                        allowed)</label>
                    <textarea name="content" rows="15"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 font-mono text-sm"
                        required></textarea>
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('admin.news.index') }}"
                        class="px-4 py-2 border border-gray-300 rounded text-gray-700 mr-2 hover:bg-gray-50">Cancel</a>
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white font-bold rounded hover:bg-blue-700 shadow">Create
                        News</button>
                </div>
            </form>
        </div>
    </div>
@endsection