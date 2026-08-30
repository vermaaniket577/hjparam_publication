@extends('layouts.web')
@section('title', 'Journals by Subject')

@section('content')
    <div class="bg-gray-50 py-12">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-serif font-bold text-gray-800 mb-8 max-w-4xl mx-auto">Journals by Subject</h1>
            <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-sm border border-gray-100 p-8">
                <p class="text-gray-600 mb-6">Browse our open access journals by subject area.</p>

                <div class="space-y-12">
                    @foreach($journals->groupBy(fn($j) => $j->topic->name ?? 'Other') as $topicName => $topicJournals)
                        <div>
                            <h2 class="text-xl font-bold text-gray-800 mb-4 border-b-2 border-blue-600 inline-block pb-1">
                                {{ $topicName }}</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                                @foreach($topicJournals as $journal)
                                    <div
                                        class="border border-gray-100 rounded-lg p-5 hover:shadow-lg transition bg-gray-50/50 group">
                                        <h3 class="font-bold text-lg text-blue-900 group-hover:text-blue-700 mb-2">
                                            <a href="{{ route('journals.show', $journal->slug) }}">{{ $journal->title }}</a>
                                        </h3>
                                        <div class="flex items-center text-xs text-gray-500 gap-3">
                                            <span>ISSN: {{ $journal->issn }}</span>
                                            <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                            <span class="font-bold text-blue-600">IF: {{ $journal->impact_factor }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection