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
            @php
                $articleUrl = $article->journal 
                    ? route('articles.show', ['journalSlug' => $article->journal->slug, 'articleSlug' => $article->slug]) 
                    : url('/articles/' . $article->slug);
                $journalTitle = $article->journal ? $article->journal->title : 'Scholarly Research';
                $pubDate = $article->published_at ? $article->published_at->toRssString() : now()->toRssString();
            @endphp
            <item>
                <title><![CDATA[{{ $article->title }}]]></title>
                <link>{{ $articleUrl }}</link>
                <guid isPermaLink="true">{{ $articleUrl }}</guid>
                <pubDate>{{ $pubDate }}</pubDate>
                <description><![CDATA[{{ $article->abstract }}]]></description>
                <category>{{ $journalTitle }}</category>
                @if($article->authors && $article->authors->isNotEmpty())
                    <author>{{ $article->authors->first()->email ?? 'editorial@hjparam.com' }} ({{ $article->authors->first()->name ?? 'Author' }})</author>
                @endif
            </item>
        @endforeach
    </channel>
</rss>
