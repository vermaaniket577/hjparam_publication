@extends('layouts.web')
@section('title', 'Advanced Search')

@section('content')
    <div class="bg-gray-50 py-12">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-serif font-bold text-gray-800 mb-8 max-w-4xl mx-auto text-center">Advanced Search</h1>

            <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-sm border border-gray-100 p-8">
                <form action="{{ route('search') }}" method="GET" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Article Title</label>
                            <input type="text" name="title"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Author Name</label>
                            <input type="text" name="author"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Abstract / Keywords</label>
                        <input type="text" name="keywords"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Journal</label>
                            <select name="journal"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2">
                                <option value="">All Journals</option>
                                @foreach(\App\Models\Journal::all() as $journal)
                                    <option value="{{ $journal->id }}">{{ $journal->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Publication Year</label>
                            <div class="flex gap-2">
                                <input type="number" name="year_from" placeholder="From"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2">
                                <input type="number" name="year_to" placeholder="To"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2">
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 flex justify-end">
                        <button type="submit"
                            class="px-6 py-3 bg-blue-900 text-white font-bold rounded hover:bg-blue-800 transition shadow-md btn-loading-trigger">
                            Search Articles
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection