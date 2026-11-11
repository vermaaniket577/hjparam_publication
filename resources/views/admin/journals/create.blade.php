@extends('layouts.admin')

@section('title', 'Add New Journal')
@section('breadcrumb', 'Journals / Create')

@section('content')
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md overflow-hidden p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Create New Journal</h2>

        <form action="{{ route('admin.journals.store') }}" method="POST" x-data="{ title: '', slug: '' }">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700">Journal Title</label>
                    <input type="text" name="title" id="title" x-model="title"
                        @input="slug = title.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '')"
                        value="{{ old('title') }}"
                        class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                        required>
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="slug" class="block text-sm font-medium text-gray-700">Slug (URL)</label>
                    <input type="text" name="slug" id="slug" x-model="slug" value="{{ old('slug') }}"
                        class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                        required>
                    @error('slug') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-4">
                    <label for="issn" class="block text-sm font-medium text-gray-700">ISSN (Online / Print)</label>
                    <input type="text" name="issn" id="issn" value="{{ old('issn') }}"
                        class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                        placeholder="e.g. 2584-XXXX or leave blank if applied">
                    @error('issn') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="impact_factor" class="block text-sm font-medium text-gray-700">Impact Factor</label>
                    <input type="number" step="0.01" name="impact_factor" id="impact_factor"
                        value="{{ old('impact_factor') }}"
                        class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    @error('impact_factor') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- ISSN PRELIMINARY DETAILS CARD (REQUIRED FOR ISSN APPLICATION) -->
            <div class="mb-6 p-4 rounded-xl border border-blue-200 bg-blue-50/40">
                <div class="flex items-center gap-2 mb-3">
                    <span class="p-1 rounded bg-blue-600 text-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </span>
                    <div>
                        <h3 class="text-sm font-bold text-blue-950 uppercase tracking-wide">Preliminary Details (ISSN Application Compliance)</h3>
                        <p class="text-xs text-blue-700">These details must match the ISSN application and are shown publicly on the journal profile.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="format" class="block text-xs font-bold text-gray-700 uppercase mb-1">Format *</label>
                        <input type="text" name="format" id="format" value="{{ old('format', 'Online / Electronic (e-Journal)') }}"
                            class="focus:ring-blue-500 focus:border-blue-500 block w-full text-xs border-gray-300 rounded-lg"
                            placeholder="e.g. Online / Electronic (e-Journal)" required>
                        @error('format') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="frequency" class="block text-xs font-bold text-gray-700 uppercase mb-1">Frequency *</label>
                        <input type="text" name="frequency" id="frequency" value="{{ old('frequency', 'Monthly') }}"
                            class="focus:ring-blue-500 focus:border-blue-500 block w-full text-xs border-gray-300 rounded-lg"
                            placeholder="e.g. Monthly, Quarterly, Bi-annual" required>
                        @error('frequency') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="starting_year" class="block text-xs font-bold text-gray-700 uppercase mb-1">Starting Year *</label>
                        <input type="number" name="starting_year" id="starting_year" value="{{ old('starting_year', 2024) }}"
                            min="1900" max="2100"
                            class="focus:ring-blue-500 focus:border-blue-500 block w-full text-xs border-gray-300 rounded-lg"
                            placeholder="e.g. 2024" required>
                        @error('starting_year') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="subject" class="block text-xs font-bold text-gray-700 uppercase mb-1">Subject / Discipline *</label>
                        <input type="text" name="subject" id="subject" value="{{ old('subject', 'Multidisciplinary / Arts, Humanities, Science & Technology') }}"
                            class="focus:ring-blue-500 focus:border-blue-500 block w-full text-xs border-gray-300 rounded-lg"
                            placeholder="e.g. Multidisciplinary Studies, Applied Science, Humanities" required>
                        @error('subject') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="language" class="block text-xs font-bold text-gray-700 uppercase mb-1">Language *</label>
                        <input type="text" name="language" id="language" value="{{ old('language', 'English, Hindi') }}"
                            class="focus:ring-blue-500 focus:border-blue-500 block w-full text-xs border-gray-300 rounded-lg"
                            placeholder="e.g. English, Hindi" required>
                        @error('language') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label for="publisher" class="block text-xs font-bold text-gray-700 uppercase mb-1">Publisher Name *</label>
                        <input type="text" name="publisher" id="publisher" value="{{ old('publisher', 'HJPARAM Publication') }}"
                            class="focus:ring-blue-500 focus:border-blue-500 block w-full text-xs border-gray-300 rounded-lg"
                            placeholder="e.g. HJPARAM Publication" required>
                        @error('publisher') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="3"
                    class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="aims_and_scope" class="block text-sm font-medium text-gray-700">Aims & Scope</label>
                <textarea name="aims_and_scope" id="aims_and_scope" rows="5"
                    class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('aims_and_scope') }}</textarea>
                @error('aims_and_scope') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="is_active" name="is_active" type="checkbox" value="1"
                            class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" {{ old('is_active', true) ? 'checked' : '' }}>
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="is_active" class="font-medium text-gray-700">Active</label>
                        <p class="text-gray-500">Enable this journal to be visible on the public site.</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('admin.journals.index') }}"
                    class="bg-gray-200 text-gray-700 px-4 py-2 rounded mr-2 hover:bg-gray-300">Cancel</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Create
                    Journal</button>
            </div>
        </form>
    </div>
@endsection