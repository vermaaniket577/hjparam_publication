<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Copyright Transfer & Author Consent Agreement - HJPARAM Publication</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700&family=Inter:wght@400;500;600;700;800&family=Lora:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: #1e293b;
            background-color: #f1f5f9;
            padding: 24px;
            font-size: 13px;
            line-height: 1.6;
        }
        .page-container {
            max-width: 820px;
            margin: 0 auto;
            background: #ffffff;
            padding: 48px 56px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
        }
        .action-bar {
            max-width: 820px;
            margin: 0 auto 16px auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s ease;
            border: none;
        }
        .btn-primary {
            background-color: #2563eb;
            color: #ffffff;
        }
        .btn-primary:hover {
            background-color: #1d4ed8;
        }
        .btn-secondary {
            background-color: #ffffff;
            color: #475569;
            border: 1px solid #cbd5e1;
        }
        .btn-secondary:hover {
            background-color: #f8fafc;
        }
        
        /* Header */
        .doc-header {
            text-align: center;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 16px;
            margin-bottom: 24px;
        }
        .doc-logo-wrap {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 12px;
        }
        .doc-logo {
            height: 52px;
            max-height: 52px;
            width: auto;
            object-fit: contain;
        }
        .doc-title-corp {
            font-family: 'Cinzel', serif;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 1px;
            color: #0f172a;
            text-transform: uppercase;
        }
        .doc-sub-corp {
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 2px;
        }
        .doc-main-heading {
            font-family: 'Lora', serif;
            font-size: 16px;
            font-weight: 700;
            color: #1e293b;
            margin-top: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: #f8fafc;
            padding: 6px 12px;
            border-radius: 6px;
            display: inline-block;
            border: 1px solid #e2e8f0;
        }

        /* Manuscript Metadata Table */
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        .meta-table th, .meta-table td {
            border: 1px solid #cbd5e1;
            padding: 8px 12px;
            text-align: left;
            font-size: 12px;
        }
        .meta-table th {
            width: 25%;
            background-color: #f8fafc;
            color: #334155;
            font-weight: 700;
        }
        .meta-table td {
            color: #0f172a;
        }

        /* Agreement Clauses */
        .clauses-section h4 {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .clauses-list {
            list-style: decimal;
            margin-left: 20px;
            margin-bottom: 20px;
            font-size: 12px;
            color: #334155;
        }
        .clauses-list li {
            margin-bottom: 8px;
            text-align: justify;
        }

        /* Signatures Table */
        .sig-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 24px;
            margin-bottom: 24px;
        }
        .sig-table th, .sig-table td {
            border: 1px solid #cbd5e1;
            padding: 10px 12px;
            font-size: 11.5px;
        }
        .sig-table th {
            background-color: #f8fafc;
            color: #334155;
            font-weight: 700;
            text-align: center;
        }
        .sig-space {
            height: 40px;
        }

        .footer-note {
            margin-top: 24px;
            padding-top: 14px;
            border-top: 1px solid #e2e8f0;
            font-size: 11px;
            color: #64748b;
            text-align: center;
        }

        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .page-container {
                box-shadow: none;
                border: none;
                padding: 20px 24px;
                max-width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <!-- Floating / Top Action Bar (Hidden when Printing) -->
    <div class="action-bar no-print">
        @if(isset($submission) && $submission)
            <a href="{{ route('submission.show', $submission) }}" class="btn btn-secondary">
                &larr; Back to Manuscript #{{ $submission->id }}
            </a>
        @else
            <a href="{{ url('/') }}" class="btn btn-secondary">
                &larr; Back to Home
            </a>
        @endif

        <div style="display: flex; gap: 8px;">
            <button onclick="window.print()" class="btn btn-primary">
                🖨️ Print / Save as PDF
            </button>
        </div>
    </div>

    <div class="page-container">
        
        <!-- Header -->
        <div class="doc-header">
            <div class="doc-logo-wrap">
                <img src="{{ asset('images/logo.png') }}?v=3" alt="HJPARAM Logo" class="doc-logo">
            </div>
            <div class="doc-title-corp">HJPARAM PUBLICATION & RESEARCH</div>
            <div class="doc-sub-corp">International Peer-Reviewed Open Access Journals & Conferences</div>
            <div class="doc-main-heading">Copyright Transfer & Author Consent Agreement</div>
        </div>

        <!-- Manuscript Information -->
        <table class="meta-table">
            <tr>
                <th>Manuscript ID</th>
                <td style="font-family: monospace; font-weight: 700; color: #2563eb;">
                    #{{ $submission->id ?? '____________________' }}
                </td>
            </tr>
            <tr>
                <th>Manuscript Title</th>
                <td style="font-weight: 600;">
                    {{ $submission->title ?? '____________________________________________________________________________________' }}
                </td>
            </tr>
            <tr>
                <th>Target Journal / Conference</th>
                <td>
                    @if(isset($submission) && $submission->conference)
                        {{ $submission->conference->title }}
                    @elseif(isset($submission) && $submission->journal)
                        {{ $submission->journal->title }}
                    @else
                        HJPARAM Academic Journals / Proceedings
                    @endif
                </td>
            </tr>
            <tr>
                <th>Corresponding Author</th>
                <td>
                    <strong>{{ $submission->user->name ?? '________________________________________' }}</strong>
                    @if(isset($submission) && $submission->user && $submission->user->email)
                        ({{ $submission->user->email }})
                    @endif
                </td>
            </tr>
            <tr>
                <th>All Contributing Authors</th>
                <td>
                    @if(isset($submission) && is_array($submission->authors_data) && count($submission->authors_data) > 0)
                        {{ implode(', ', array_column($submission->authors_data, 'name')) }}
                    @elseif(isset($submission) && $submission->user)
                        {{ $submission->user->name }}
                    @else
                        1. ____________________ &nbsp; 2. ____________________ &nbsp; 3. ____________________
                    @endif
                </td>
            </tr>
            <tr>
                <th>Date of Submission</th>
                <td>
                    {{ isset($submission) && $submission->created_at ? $submission->created_at->format('F d, Y') : date('F d, Y') }}
                </td>
            </tr>
        </table>

        <!-- Terms and Declarations -->
        <div class="clauses-section">
            <h4>Terms of Agreement & Author Declaration</h4>
            <ol class="clauses-list">
                <li>
                    <strong>Originality & Authorship:</strong> The undersigned author(s) declare that the submitted manuscript represents original research work, has not been published previously elsewhere in any language, and is not currently under consideration for publication by any other journal or conference.
                </li>
                <li>
                    <strong>Plagiarism & Academic Ethics:</strong> The authors warrant that the manuscript contains no fraudulent data, plagiarized text, or infringement of third-party intellectual property rights. Appropriate citations and credits have been given to all sources used.
                </li>
                <li>
                    <strong>Open Access & Creative Commons License:</strong> In the event of publication, the article will be published under the <strong>Creative Commons Attribution 4.0 International License (CC BY 4.0)</strong>. Authors retain copyright ownership while granting HJPARAM Publication an exclusive license to publish, distribute, archive, index, and preserve the version of record.
                </li>
                <li>
                    <strong>Consent of Co-Authors:</strong> The corresponding author confirms that all co-authors have read, reviewed, and approved the submitted manuscript, and agree to the submission of this copyright and authorship declaration.
                </li>
                <li>
                    <strong>Financial & Conflicts Disclosure:</strong> All financial support, grants, sponsorships, and institutional affiliations relevant to the research have been fully disclosed in the manuscript.
                </li>
            </ol>
        </div>

        <!-- Signature Undertaking -->
        <div class="clauses-section">
            <h4>Author Signatures & Consent</h4>
            <p style="font-size: 11.5px; color: #475569; margin-bottom: 10px;">
                By signing below, the author(s) accept and agree to abide by all the terms, editorial policies, and ethical guidelines stated above.
            </p>

            <table class="sig-table">
                <thead>
                    <tr>
                        <th style="width: 8%;">S.No</th>
                        <th style="width: 32%;">Author Full Name</th>
                        <th style="width: 35%;">Designation & Institution</th>
                        <th style="width: 25%;">Signature & Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: center; font-weight: 700;">1</td>
                        <td>
                            <strong>{{ $submission->user->name ?? '' }}</strong>
                            <div style="font-size: 10px; color: #64748b;">(Corresponding Author)</div>
                        </td>
                        <td>{{ $submission->affiliation ?? '' }}</td>
                        <td class="sig-space"></td>
                    </tr>
                    <tr>
                        <td style="text-align: center; font-weight: 700;">2</td>
                        <td></td>
                        <td></td>
                        <td class="sig-space"></td>
                    </tr>
                    <tr>
                        <td style="text-align: center; font-weight: 700;">3</td>
                        <td></td>
                        <td></td>
                        <td class="sig-space"></td>
                    </tr>
                    <tr>
                        <td style="text-align: center; font-weight: 700;">4</td>
                        <td></td>
                        <td></td>
                        <td class="sig-space"></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="footer-note">
            <p><strong>HJPARAM Publication & Research Hub</strong> • Official Portal: <a href="https://hjparam.com" style="color: #2563eb; text-decoration: none;">https://hjparam.com</a></p>
            <p>Please upload or submit the signed copy of this agreement to your author dashboard or email it to: <strong>editorial@hjparam.com</strong></p>
        </div>

    </div>

</body>
</html>
