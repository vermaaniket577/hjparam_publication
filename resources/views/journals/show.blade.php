@extends('layouts.web')

@section('title', $journal->title)

@section('structured_data')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "Periodical",
  "name": "{{ $journal->title }}",
  "description": "{{ $journal->description }}",
  "issn": "{{ $journal->issn ?? 'XXXX-XXXX' }}",
  "publisher": {
    "@@type": "Organization",
    "name": "HJPARAM",
    "logo": {
      "@@type": "ImageObject",
      "url": "{{ asset('images/logo.png') }}"
    }
  }
}
</script>
@endsection

@section('content')
    <div class="bg-blue-900 text-white py-12">
        <div class="container mx-auto px-4 flex flex-col md:flex-row items-center justify-between">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold mb-4">{{ $journal->title }}</h1>
                <div class="flex flex-wrap gap-4 text-sm text-blue-200 mb-6">
                    <span>ISSN: {{ $journal->issn }}</span>
                    <span>Impact Factor: {{ $journal->impact_factor }}</span>
                </div>
                <p class="text-xl max-w-2xl mb-8">{{ $journal->description }}</p>
                <div class="flex gap-4">
                    <a href="{{ route('author.submit', ['journal_id' => $journal->id]) }}"
                        class="bg-yellow-500 text-blue-900 font-bold py-3 px-8 rounded shadow hover:bg-yellow-400 transition">Submit
                        Manuscript</a>
                    <a href="#volumes"
                        class="bg-blue-800 text-white font-bold py-3 px-8 rounded border border-blue-600 hover:bg-blue-700 transition">Browse
                        Issues</a>
                </div>
            </div>
            <div class="md:w-1/3 mt-8 md:mt-0 flex justify-center">
                <div
                    class="w-48 h-64 bg-white rounded shadow-lg flex items-center justify-center text-blue-900 font-bold text-4xl opacity-20 transform rotate-3">
                    {{ substr($journal->title, 0, 1) }}
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-12 flex flex-col lg:flex-row gap-12">
        <!-- Sidebar -->
        <div class="lg:w-1/4 space-y-8">
            <div class="bg-white p-6 rounded shadow border-l-4 border-blue-600">
                <h3 class="font-bold text-xl mb-4 text-gray-800">Journal Menu</h3>
                <ul class="space-y-2">
                    <li><a href="#scope" class="text-gray-600 hover:text-blue-600 font-medium">Aims & Scope</a></li>
                    <li><a href="#board" class="text-gray-600 hover:text-blue-600 font-medium">Editorial Board</a></li>
                    <li><a href="#volumes" class="text-gray-600 hover:text-blue-600 font-medium">Volumes & Issues</a></li>
                    <li><a href="{{ route('author.page', 'instructions-for-authors') }}"
                            class="text-gray-600 hover:text-blue-600 font-medium">Guide for Authors</a></li>
                </ul>
            </div>

            <div class="bg-gray-50 p-6 rounded shadow">
                <h3 class="font-bold text-lg mb-4 text-gray-800">Latest Issue</h3>
                @if($latestIssue)
                    <p class="text-sm font-bold text-blue-600 mb-2">Vol {{ $latestIssue->volume->volume_number }}, Issue
                        {{ $latestIssue->issue_number }}
                        ({{ \Carbon\Carbon::parse($latestIssue->publication_date)->format('Y') }})
                    </p>
                    <div class="space-y-4">
                        @foreach($articles->take(3) as $article)
                            <div class="border-b pb-2 last:border-0 last:pb-0">
                                <a href="{{ route('articles.show', ['journalSlug' => $journal->slug, 'articleSlug' => $article->slug]) }}"
                                    class="text-sm font-medium hover:text-blue-600 block mb-1">
                                    {{ $article->title }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 italic">No issues published yet.</p>
                @endif
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:w-3/4 space-y-12">
            <section id="scope" class="scroll-mt-24">
                <h2 class="text-2xl font-bold mb-4 text-gray-800 border-b pb-2">Aims & Scope</h2>
                <div class="prose max-w-none text-gray-700">
                    <p>{{ $journal->aims_and_scope }}</p>
                </div>
            </section>

            <section id="board" class="scroll-mt-24">
                <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2">Editorial Board</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($board as $member)
                        <div class="flex items-start space-x-4">
                            <div class="w-16 h-16 bg-gray-200 rounded-full flex-shrink-0"></div>
                            <div>
                                <h4 class="font-bold text-gray-900">{{ $member->name }}</h4>
                                <p class="text-sm text-blue-600 font-medium">{{ $member->role }}</p>
                                <p class="text-sm text-gray-600">{{ $member->affiliation }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 italic">Editorial board information coming soon.</p>
                    @endforelse
                </div>
            </section>

            <section id="volumes" class="scroll-mt-24">
                <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2">Volumes & Issues</h2>
                <div class="space-y-6">
                    @forelse($volumes as $volume)
                        <div class="bg-white border rounded p-4">
                            <h3 class="font-bold text-lg mb-2">Volume {{ $volume->volume_number }} ({{ $volume->year }})</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($volume->issues as $issue)
                                    <a href="{{ route('journals.issue', [$journal->slug, $volume->volume_number, $issue->issue_number]) }}"
                                        class="bg-gray-100 hover:bg-blue-100 text-blue-700 px-3 py-1 rounded text-sm hover:bg-blue-200 transition">
                                        Issue {{ $issue->issue_number }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 italic">No volumes found.</p>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
@endsection