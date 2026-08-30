{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ url('/') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ route('journals.index') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ route('articles.show', ['journalSlug' => 'all', 'articleSlug' => 'latest']) }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
    @if($hasContact)
        <url>
            <loc>{{ route('info.page', 'contact') }}</loc>
            <lastmod>{{ now()->toAtomString() }}</lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.5</priority>
        </url>
    @endif
    @if($hasProcess)
        <url>
            <loc>{{ route('info.page', 'process') }}</loc>
            <lastmod>{{ now()->toAtomString() }}</lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.7</priority>
        </url>
    @endif
    @if($hasProposal)
        <url>
            <loc>{{ route('info.page', 'proposals') }}</loc>
            <lastmod>{{ now()->toAtomString() }}</lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.7</priority>
        </url>
    @endif

    @foreach ($topics as $topic)
        <url>
            <loc>{{ route('topics.show', $topic->slug) }}</loc>
            <lastmod>{{ $topic->updated_at->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.7</priority>
        </url>
    @endforeach

    @foreach ($journals as $journal)
        <url>
            <loc>{{ route('journals.show', $journal->slug) }}</loc>
            <lastmod>{{ $journal->updated_at->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.9</priority>
        </url>
    @endforeach

    @foreach ($articles as $article)
        <url>
            <loc>{{ route('articles.show', ['journalSlug' => $article->journal->slug, 'articleSlug' => $article->slug]) }}
            </loc>
            <lastmod>
                {{ $article->published_at ? $article->published_at->toAtomString() : $article->updated_at->toAtomString() }}
            </lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.6</priority>
        </url>
    @endforeach

    @foreach ($pages as $page)
        <url>
            <loc>{{ route($page->category . '.page', $page->slug) }}</loc>
            <lastmod>{{ $page->updated_at->toAtomString() }}</lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.5</priority>
        </url>
    @endforeach
</urlset>