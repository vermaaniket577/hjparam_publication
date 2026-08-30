@extends('layouts.admin')
@section('title', 'Edit Page')
@section('breadcrumb', 'Edit Page')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Edit Page: {{ $page->title }}</h3>

            <form action="{{ route('admin.pages.update', $page->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Page Title</label>
                        <input type="text" name="title"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            value="{{ old('title', $page->title) }}" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category (Menu
                            Section)</label>
                        <select name="category"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            required>
                            <option value="info" {{ $page->category == 'info' ? 'selected' : '' }}>Information</option>
                            <option value="author" {{ $page->category == 'author' ? 'selected' : '' }}>Author Services
                            </option>
                            <option value="initiatives" {{ $page->category == 'initiatives' ? 'selected' : '' }}>Initiatives
                            </option>
                            <option value="about" {{ $page->category == 'about' ? 'selected' : '' }}>About</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Slug (URL)</label>
                        <input type="text" name="slug"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            value="{{ old('slug', $page->slug) }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sort Order</label>
                        <input type="number" name="sort_order"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            value="{{ old('sort_order', $page->sort_order) }}">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description /
                        Status</label>
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" name="active" value="1"
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" {{ $page->active ? 'checked' : '' }}>
                        <span class="text-sm text-gray-600">Active (Visible in Menu)</span>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Page Content</label>
                    <textarea name="content" rows="10"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 font-mono text-sm">{{ old('content', $page->content) }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">HTML is allowed.</p>
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('admin.pages.index') }}"
                        class="px-4 py-2 border border-gray-300 rounded text-gray-700 mr-2 hover:bg-gray-50">Cancel</a>
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white font-bold rounded hover:bg-blue-700 shadow btn-loading-trigger">Update
                        Page</button>
                </div>
            </form>
        </div>
    </div>
@endsection