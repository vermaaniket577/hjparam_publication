@extends('layouts.admin')

@section('title', 'Edit Journal')
@section('breadcrumb', 'Journals / Edit')

@section('content')
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md overflow-hidden p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Edit Journal: {{ $journal->title }}</h2>

        <form action="{{ route('admin.journals.update', $journal) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700">Journal Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $journal->title) }}"
                        class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                        required>
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="slug" class="block text-sm font-medium text-gray-700">Slug (URL)</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $journal->slug) }}"
                        class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                        required>
                    @error('slug') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-4">
                    <label for="issn" class="block text-sm font-medium text-gray-700">ISSN</label>
                    <input type="text" name="issn" id="issn" value="{{ old('issn', $journal->issn) }}"
                        class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    @error('issn') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="impact_factor" class="block text-sm font-medium text-gray-700">Impact Factor</label>
                    <input type="number" step="0.01" name="impact_factor" id="impact_factor"
                        value="{{ old('impact_factor', $journal->impact_factor) }}"
                        class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    @error('impact_factor') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="3"
                    class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('description', $journal->description) }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="aims_and_scope" class="block text-sm font-medium text-gray-700">Aims & Scope</label>
                <textarea name="aims_and_scope" id="aims_and_scope" rows="5"
                    class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('aims_and_scope', $journal->aims_and_scope) }}</textarea>
                @error('aims_and_scope') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="is_active" name="is_active" type="checkbox" value="1"
                            class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" {{ old('is_active', $journal->is_active) ? 'checked' : '' }}>
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="is_active" class="font-medium text-gray-700">Active</label>
                        <p class="text-gray-500">Enable this journal to be visible on the public site.</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                    <a href="{{ route('admin.journals.editorial.index', $journal) }}"
                        class="w-full sm:w-auto bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 font-bold px-4 py-2.5 rounded-xl text-sm transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Editorial Team & Responsibilities ({{ $journal->editorialBoard()->count() }})
                    </a>
                    <a href="{{ route('admin.journals.volumes.index', $journal) }}"
                        class="w-full sm:w-auto bg-blue-50 text-blue-700 border border-blue-200 hover:bg-blue-100 font-bold px-4 py-2.5 rounded-xl text-sm transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        Volumes & Issues ({{ $journal->volumes()->count() }})
                    </a>
                </div>
                <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                    <a href="{{ route('admin.journals.index') }}"
                        class="bg-gray-200 text-gray-700 px-4 py-2.5 rounded-xl font-bold text-sm hover:bg-gray-300 transition">Cancel</a>
                    <button type="submit" class="bg-blue-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-blue-700 transition">Update
                        Journal</button>
                </div>
            </div>
        </form>
    </div>
@endsection