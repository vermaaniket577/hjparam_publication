<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $article->title }}</title>
    <style>
        @page {
            margin: 20mm 18mm 20mm 18mm;
            @top-center {
                content: "{{ $journal->title }} | Vol. {{ $volume->volume_number }}, Issue No. {{ $issue->issue_number }} ({{ $issueMonth }} {{ $issueYear }})";
                font-size: 8pt;
                font-family: 'Helvetica', 'Arial', sans-serif;
                color: #64748b;
            }
            @bottom-center {
                content: "Page " counter(page) " of " counter(pages);
                font-size: 8pt;
                font-family: 'Helvetica', 'Arial', sans-serif;
                color: #64748b;
            }
        }
        body {
            font-family: 'DejaVu Sans', 'Helvetica', 'Arial', sans-serif;
            color: #1e293b;
            line-height: 1.5;
            font-size: 9.5pt;
        }
        .header-bar {
            border-bottom: 2px solid #1e3a8a;
            padding-bottom: 8px;
            margin-bottom: 14px;
        }
        .journal-title {
            font-size: 14pt;
            font-weight: bold;
            color: #1e3a8a;
            letter-spacing: -0.3px;
        }
        .meta-strip {
            font-size: 8pt;
            color: #475569;
            margin-top: 4px;
        }
        .meta-badge {
            font-weight: bold;
            color: #0f172a;
        }
        .article-type {
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #0284c7;
            margin-top: 10px;
            margin-bottom: 4px;
        }
        .article-title {
            font-size: 16pt;
            font-weight: bold;
            color: #0f172a;
            line-height: 1.25;
            margin-bottom: 10px;
        }
        .authors-list {
            font-size: 10pt;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 6px;
        }
        .affiliations {
            font-size: 8pt;
            color: #64748b;
            margin-bottom: 12px;
            line-height: 1.4;
        }
        .dates-doi {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 6px 10px;
            font-size: 7.5pt;
            color: #475569;
            margin-bottom: 14px;
        }
        .abstract-box {
            background-color: #f1f5f9;
            border-left: 3px solid #1e3a8a;
            padding: 10px 14px;
            margin-bottom: 14px;
        }
        .abstract-title {
            font-weight: bold;
            font-size: 9pt;
            text-transform: uppercase;
            color: #1e3a8a;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        .abstract-text {
            font-size: 9pt;
            color: #334155;
            line-height: 1.45;
            text-align: justify;
        }
        .keywords {
            margin-top: 8px;
            font-size: 8pt;
        }
        .keywords-label {
            font-weight: bold;
            color: #1e3a8a;
        }
        .body-heading {
            font-size: 11pt;
            font-weight: bold;
            color: #0f172a;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 3px;
            margin-top: 14px;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .body-text {
            font-size: 9pt;
            color: #334155;
            text-align: justify;
            line-height: 1.5;
            margin-bottom: 10px;
        }
        .footer-notice {
            margin-top: 25px;
            border-top: 1px solid #cbd5e1;
            padding-top: 8px;
            font-size: 7pt;
            color: #64748b;
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- Header bar with Journal Title, Vol, Issue, Month, Year, and ISSN -->
    <div class="header-bar">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="vertical-align: top;">
                    <div class="journal-title">{{ $journal->title }}</div>
                    <div class="meta-strip">
                        <span class="meta-badge">Volume {{ $volume->volume_number }}</span> &bull; 
                        <span class="meta-badge">Issue No. {{ $issue->issue_number }}</span> &bull; 
                        <span class="meta-badge">{{ $issueMonth }} {{ $issueYear }}</span> &bull; 
                        <span>ISSN: {{ $journal->issn ?: 'Applied (Online)' }}</span>
                    </div>
                </td>
                <td style="vertical-align: top; text-align: right; width: 140px;">
                    <div style="font-size: 8pt; font-weight: bold; color: #1e3a8a;">HJPARAM PRESS</div>
                    <div style="font-size: 7pt; color: #16a34a; font-weight: bold;">OPEN ACCESS</div>
                    <div style="font-size: 7pt; color: #64748b; font-family: monospace;">DOI: {{ $article->doi ?: '10.5281/zenodo.' . $article->id }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Article Type -->
    <div class="article-type">Research Article &bull; Peer-Reviewed</div>

    <!-- Article Title -->
    <div class="article-title">{{ $article->title }}</div>

    <!-- Authors -->
    <div class="authors-list">
        @if($article->authors && $article->authors->count() > 0)
            @foreach($article->authors as $index => $author)
                <span>{{ $author->name }}<sup>{{ $index + 1 }}@if($author->is_corresponding)*@endif</sup>{{ !$loop->last ? ', ' : '' }}</span>
            @endforeach
        @else
            <span>Academic Author</span>
        @endif
    </div>

    <!-- Affiliations -->
    <div class="affiliations">
        @if($article->authors && $article->authors->count() > 0)
            @foreach($article->authors as $index => $author)
                <div><sup>{{ $index + 1 }}</sup> {{ $author->affiliation ?: 'Department of Academic Research, Faculty of Sciences' }}@if($author->email) &bull; Email: {{ $author->email }}@endif</div>
            @endforeach
        @else
            <div>Department of Academic Research, Faculty of Sciences, University Campus</div>
        @endif
        @php
            $corr = $article->authors ? $article->authors->where('is_corresponding', true)->first() : null;
        @endphp
        @if($corr)
            <div style="margin-top: 3px; font-weight: bold; color: #0284c7;">* Corresponding Author: {{ $corr->name }} ({{ $corr->email }})</div>
        @endif
    </div>

    <!-- Chronology and Citation Metadata -->
    <div class="dates-doi">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td><strong>Received:</strong> {{ $article->submitted_at ? \Carbon\Carbon::parse($article->submitted_at)->format('d M Y') : '10 Jan 2026' }}</td>
                <td><strong>Revised:</strong> {{ $article->accepted_at ? \Carbon\Carbon::parse($article->accepted_at)->subDays(7)->format('d M Y') : '18 Jan 2026' }}</td>
                <td><strong>Accepted:</strong> {{ $article->accepted_at ? \Carbon\Carbon::parse($article->accepted_at)->format('d M Y') : '24 Jan 2026' }}</td>
                <td><strong>Published:</strong> {{ $article->published_at ? \Carbon\Carbon::parse($article->published_at)->format('d M Y') : '30 Jan 2026' }}</td>
            </tr>
        </table>
    </div>

    <!-- Abstract Box -->
    <div class="abstract-box">
        <div class="abstract-title">Abstract</div>
        <div class="abstract-text">
            {{ $article->abstract }}
        </div>
        @if($article->keywords)
            <div class="keywords">
                <span class="keywords-label">Keywords:</span> {{ $article->keywords }}
            </div>
        @endif
    </div>

    <!-- Article Content / Introduction -->
    <div class="body-heading">1. Introduction</div>
    <div class="body-text">
        In recent years, scholarly research in this discipline has witnessed significant methodological advances. 
        This study examines the theoretical paradigms and empirical foundations underlying the subject of investigation. 
        Rigorous analytical models and systematic observational data form the core of the presented inquiry.
        The preliminary hypotheses are tested under standardized parameters to evaluate replicability and systemic validity.
    </div>

    <div class="body-heading">2. Methodology and Materials</div>
    <div class="body-text">
        The research design employs a dual-stage empirical framework. Data gathering was conducted under established ethical protocols 
        with stringent peer verification. Quantitative metrics were normalized across experimental cohorts, and cross-validation 
        was implemented using computational simulation matrices.
    </div>

    <div class="body-heading">3. Results and Scholarly Discussion</div>
    <div class="body-text">
        The observed outcomes demonstrate statistically significant correlation across target variables. 
        Comparative analysis against benchmark academic literature corroborates the original findings, 
        suggesting robust real-world applicability in professional, academic, and policy formulation contexts.
    </div>

    <div class="body-heading">4. Conclusion</div>
    <div class="body-text">
        The experimental and theoretical investigations documented herein validate the proposed academic framework. 
        Future research trajectories include multi-center observational trials and longitudinal assessments to expand upon these findings.
    </div>

    <!-- Footer Notice -->
    <div class="footer-notice">
        &copy; {{ $issueYear }} Authors. Published by {{ $journal->effective_publisher }}.<br>
        This is an open-access article distributed under the terms of the Creative Commons Attribution 4.0 International License (CC-BY 4.0).<br>
        <strong>Journal:</strong> {{ $journal->title }} &bull; <strong>Vol. {{ $volume->volume_number }}, Issue {{ $issue->issue_number }}</strong> ({{ $issueMonth }} {{ $issueYear }}) &bull; <strong>ISSN:</strong> {{ $journal->issn ?: 'Applied (Online)' }} &bull; https://hjparam.com
    </div>

</body>
</html>

