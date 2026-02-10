{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title>{{ $siteTitle }}</title>
        <link>{{ $siteUrl }}</link>
        <description>{{ $siteDescription }}</description>
        <atom:link href="{{ $feedUrl }}" rel="self" type="application/rss+xml" />
        <language>en-us</language>
        <lastBuildDate>{{ $buildDate }}</lastBuildDate>

        @foreach($articles as $article)
            <item>
                <title><![CDATA[{{ $article->title }}]]></title>
                <link>{{ route('articles.show', ['journalSlug' => $article->journal->slug, 'articleSlug' => $article->slug]) }}</link>
                <guid isPermaLink="true">{{ route('articles.show', ['journalSlug' => $article->journal->slug, 'articleSlug' => $article->slug]) }}</guid>
                <pubDate>{{ $article->published_at->toRssString() }}</pubDate>
                <description><![CDATA[{{ $article->abstract }}]]></description>
                <category>{{ $article->journal->title }}</category>
                @if($article->authors->isNotEmpty())
                    <author>{{ $article->authors->first()->email }} ({{ $article->authors->first()->name }})</author>
                @endif
            </item>
        @endforeach
    </channel>
</rss>
