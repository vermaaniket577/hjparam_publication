@extends('layouts.web')
@section('title', 'Browse Journals | HJPARAM Publication')

@section('content')
    <div class="bg-gray-100 py-12">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold mb-8">All Journals</h1>

            <div class="bg-white p-6 rounded shadow mb-8">
                <h3 class="text-lg font-bold mb-4">Filter by Title</h3>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('journals.index') }}"
                        class="px-4 h-8 flex items-center justify-center border rounded transition font-medium {{ !request()->has('letter') ? 'bg-blue-600 text-white border-blue-600' : 'text-blue-600 border-gray-200 hover:bg-blue-600 hover:text-white' }}">All</a>
                    @foreach(range('A', 'Z') as $char)
                        <a href="{{ route('journals.index', ['letter' => $char]) }}"
                            class="w-8 h-8 flex items-center justify-center border rounded transition font-medium {{ request()->get('letter') === $char ? 'bg-blue-600 text-white border-blue-600' : 'text-blue-600 border-gray-200 hover:bg-blue-600 hover:text-white' }}">{{ $char }}</a>
                    @endforeach
                </div>
            </div>

            <div class="space-y-6">
                @foreach($journals as $journal)
                    <div class="bg-white p-6 rounded shadow flex flex-col md:flex-row gap-6">
                        <div
                            class="w-full md:w-32 h-40 bg-blue-100 flex-shrink-0 flex items-center justify-center text-blue-500 rounded">
                            <span class="text-4xl font-bold opacity-30">{{ substr($journal->title, 0, 1) }}</span>
                        </div>
                        <div class="flex-grow">
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">
                                <a href="{{ route('journals.show', $journal->slug) }}"
                                    class="hover:text-blue-600">{{ $journal->title }}</a>
                            </h2>
                            <div class="flex flex-wrap gap-4 text-sm text-gray-600 mb-4">
                                <span class="bg-gray-100 px-2 py-1 rounded">ISSN: {{ $journal->issn }}</span>
                                <span class="bg-blue-50 text-blue-800 px-2 py-1 rounded font-bold">IF:
                                    {{ $journal->impact_factor }}</span>
                            </div>
                            <p class="text-gray-700 mb-4">{{ $journal->description }}</p>
                            <div class="flex gap-4">
                                <a href="{{ route('journals.show', $journal->slug) }}"
                                    class="text-blue-600 font-bold hover:underline">View Journal</a>
                                <a href="{{ route('author.submit', ['journal_id' => $journal->id]) }}"
                                    class="text-green-600 font-bold hover:underline">Submit Manuscript</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection