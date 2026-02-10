@extends('layouts.web')
@section('title', 'Search Results: ' . $query)

@section('content')
    <div class="bg-gray-50 py-12">
        <div class="container mx-auto px-4">

            <!-- Search Header -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 mb-8">
                <h1 class="text-2xl font-serif font-bold text-gray-900 mb-4">Search Results</h1>
                <p class="text-gray-600 mb-4">Showing results for: <span
                        class="font-bold text-gray-800">"{{ $query }}"</span></p>

                <div
                    x-data="{ advancedOpen: {{ request()->hasAny(['author', 'journal', 'year_from', 'year_to']) ? 'true' : 'false' }} }">
                    <form action="{{ route('search') }}" method="GET" class="space-y-4">
                        <div class="flex gap-2 max-w-2xl">
                            <div class="relative flex-grow">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <input type="text" name="q" value="{{ $query }}"
                                    class="block w-full rounded-md border-gray-300 pl-10 focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2"
                                    placeholder="Refine your search...">
                            </div>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-900 text-white font-medium rounded-md hover:bg-blue-800">Search</button>
                            <button type="button" @click="advancedOpen = !advancedOpen"
                                class="px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-md hover:bg-gray-50 flex items-center gap-1">
                                <svg class="w-4 h-4 transition-transform" :class="advancedOpen ? 'rotate-180' : ''"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                Advanced
                            </button>
                        </div>

                        <!-- Advanced Search Fields (Inline) -->
                        <div x-show="advancedOpen" x-transition class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Author Name</label>
                                    <input type="text" name="author" value="{{ request('author') }}"
                                        placeholder="e.g. John Doe"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Journal</label>
                                    <select name="journal"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                        <option value="">All Journals</option>
                                        @foreach(\App\Models\Journal::all() as $journal)
                                            <option value="{{ $journal->id }}" {{ request('journal') == $journal->id ? 'selected' : '' }}>{{ $journal->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Year Range</label>
                                    <div class="flex gap-2">
                                        <input type="number" name="year_from" value="{{ request('year_from') }}"
                                            placeholder="From"
                                            class="block w-1/2 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                        <input type="number" name="year_to" value="{{ request('year_to') }}"
                                            placeholder="To"
                                            class="block w-1/2 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

                <!-- Filters Sidebar (Static for now) -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded shadow-sm border border-gray-100 overflow-hidden">
                        <div
                            class="bg-gray-100 px-4 py-3 font-bold text-sm text-gray-800 uppercase tracking-wider border-b border-gray-200">
                            Filter By
                        </div>
                        <div class="p-4 space-y-4">
                            <div>
                                <h3 class="text-sm font-bold text-gray-700 mb-2">Publication Year</h3>
                                <div class="space-y-1">
                                    <label class="flex items-center">
                                        <input type="checkbox"
                                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-600">{{ now()->year }}</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox"
                                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-600">{{ now()->subYear()->year }}</span>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-sm font-bold text-gray-700 mb-2">Subject</h3>
                                <div class="space-y-1">
                                    <label class="flex items-center">
                                        <input type="checkbox"
                                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-600">Biology</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox"
                                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-600">Medicine</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Results List -->
                <div class="lg:col-span-3 space-y-6">
                    @forelse($articles as $article)
                        <div class="bg-white p-6 rounded shadow-sm border border-gray-100 hover:shadow-md transition">
                            <div class="flex flex-col md:flex-row gap-6">
                                <div class="flex-grow">
                                    <div class="mb-1 text-xs text-blue-600 font-bold uppercase tracking-wider">Open Access
                                        Article</div>
                                    <h3 class="text-lg font-bold text-gray-900 mb-2 leading-tight">
                                        <a href="{{ route('articles.show', ['journalSlug' => $article->journal->slug, 'articleSlug' => $article->slug]) }}"
                                            class="hover:text-blue-800 hover:underline">
                                            {{ $article->title }}
                                        </a>
                                    </h3>
                                    <div class="text-sm text-gray-600 mb-2 italic">
                                        by <span class="text-gray-800">
                                            @foreach($article->authors as $author)
                                                {{ $author->name }}@if(!$loop->last), @endif
                                            @endforeach
                                        </span>
                                    </div>
                                    <div class="text-sm text-gray-500 mb-3">
                                        <span class="italic">{{ $article->journal->title }}</span>
                                        <span
                                            class="font-bold">{{ $article->published_at ? $article->published_at->format('Y') : '' }}</span>,
                                        <a href="https://doi.org/{{ $article->doi }}" target="_blank"
                                            class="text-blue-600 hover:underline">https://doi.org/{{ $article->doi }}</a>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $article->abstract }}</p>

                                    <div class="flex gap-2">
                                        <a href="{{ route('articles.show', ['journalSlug' => $article->journal->slug, 'articleSlug' => $article->slug]) }}#abstract"
                                            class="inline-flex items-center justify-center px-3 py-1 border border-blue-600 text-xs font-medium rounded text-blue-600 bg-white hover:bg-blue-50 leading-none">
                                            Abstract
                                        </a>
                                        <a href="{{ route('articles.show', ['journalSlug' => $article->journal->slug, 'articleSlug' => $article->slug]) }}"
                                            class="inline-flex items-center justify-center px-3 py-1 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 leading-none">
                                            Full-Text
                                        </a>
                                        <a href="{{ route('articles.download', $article->id) }}"
                                            class="inline-flex items-center justify-center px-3 py-1 border border-blue-300 text-xs font-medium rounded text-blue-900 bg-blue-50 hover:bg-blue-600 hover:text-white transition-all leading-none">
                                            Download PDF
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white p-8 rounded shadow-sm text-center border border-gray-100">
                            <div class="inline-block p-4 rounded-full bg-gray-100 text-gray-400 mb-4">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900">No articles found</h3>
                            <p class="text-gray-500 mt-2">Try adjusting your search terms or check your spelling.</p>
                        </div>
                    @endforelse

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $articles->appends(['q' => $query])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection