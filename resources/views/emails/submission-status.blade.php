<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subjectLine }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f1f5f9;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #1e293b;
            -webkit-text-size-adjust: 100%;
        }
        table {
            border-spacing: 0;
            border-collapse: collapse;
        }
        td {
            padding: 0;
        }
        img {
            border: 0;
        }
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f1f5f9;
            padding: 30px 10px;
        }
        .main-container {
            max-width: 620px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }
        .header {
            background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
            padding: 32px 28px;
            text-align: center;
        }
        .header-title {
            color: #ffffff;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -0.3px;
            margin: 8px 0 0 0;
        }
        .header-subtitle {
            color: #bfdbfe;
            font-size: 13px;
            margin: 4px 0 0 0;
            font-weight: 500;
        }
        .body-content {
            padding: 32px 28px;
        }
        .greeting {
            font-size: 16px;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 12px;
        }
        .status-hero {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 5px solid {{ $badgeColor }};
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
        }
        .status-badge {
            display: inline-block;
            background-color: {{ $badgeColor }};
            color: #ffffff;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 5px 12px;
            border-radius: 20px;
            margin-bottom: 10px;
        }
        .headline {
            font-size: 17px;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 8px 0;
            line-height: 1.3;
        }
        .message-body {
            font-size: 14px;
            line-height: 1.6;
            color: #334155;
            margin: 0;
        }
        .paper-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 18px 20px;
            margin: 22px 0;
        }
        .paper-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        .paper-title {
            font-size: 15px;
            font-weight: 700;
            color: #1e293b;
            margin: 0 0 10px 0;
            line-height: 1.4;
        }
        .paper-meta-table {
            width: 100%;
            font-size: 12px;
        }
        .paper-meta-table td {
            padding: 4px 0;
        }
        .paper-meta-label {
            color: #64748b;
            font-weight: 600;
            width: 32%;
        }
        .paper-meta-val {
            color: #0f172a;
            font-weight: 600;
        }
        .remarks-box {
            background-color: #fffbeb;
            border: 1px solid #fde68a;
            border-left: 4px solid #f59e0b;
            border-radius: 10px;
            padding: 16px;
            margin: 20px 0;
        }
        .remarks-title {
            font-size: 12px;
            font-weight: 700;
            color: #b45309;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }
        .remarks-content {
            font-size: 13px;
            color: #78350f;
            line-height: 1.5;
            white-space: pre-line;
        }
        .schedule-box {
            background-color: #eef2ff;
            border: 1px solid #c7d2fe;
            border-left: 4px solid #4f46e5;
            border-radius: 10px;
            padding: 16px;
            margin: 20px 0;
        }
        .schedule-title {
            font-size: 12px;
            font-weight: 700;
            color: #3730a3;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .cta-container {
            text-align: center;
            margin: 32px 0 24px 0;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff !important;
            text-decoration: none;
            font-size: 14px;
            font-weight: 700;
            padding: 14px 32px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.25);
            transition: all 0.2s ease;
        }
        .footer {
            background-color: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 24px 28px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
            line-height: 1.6;
        }
        .footer a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <table role="presentation" width="100%">
            <tr>
                <td align="center">
                    <div class="main-container">
                        
                        <!-- Header Banner -->
                        <div class="header">
                            <div class="header-title">HJPARAM PUBLICATION</div>
                            <div class="header-subtitle">Scholarly Editorial Management Portal</div>
                        </div>

                        <!-- Content Area -->
                        <div class="body-content">
                            <div class="greeting">
                                Dear {{ $submission->user->name ?? 'Author' }},
                            </div>

                            <p class="message-body" style="margin-bottom: 16px;">
                                We are writing to notify you of an important update regarding your scholarly manuscript submitted to <strong>HJPARAM Publication</strong>.
                            </p>

                            <!-- Status Hero Banner -->
                            <div class="status-hero">
                                <span class="status-badge">{{ $badgeText }}</span>
                                <div class="headline">{{ $headline }}</div>
                                <p class="message-body">{{ $messageBody }}</p>
                            </div>

                            <!-- Manuscript Details Card -->
                            <div class="paper-card">
                                <div class="paper-label">Manuscript Details</div>
                                <div class="paper-title">{{ $submission->title }}</div>
                                
                                <table class="paper-meta-table">
                                    <tr>
                                        <td class="paper-meta-label">Reference ID:</td>
                                        <td class="paper-meta-val">#SUB-{{ str_pad($submission->id, 5, '0', STR_PAD_LEFT) }}</td>
                                    </tr>
                                    @if($submission->journal)
                                        <tr>
                                            <td class="paper-meta-label">Target Journal:</td>
                                            <td class="paper-meta-val">{{ $submission->journal->title }}</td>
                                        </tr>
                                    @elseif($submission->conference)
                                        <tr>
                                            <td class="paper-meta-label">Conference:</td>
                                            <td class="paper-meta-val">{{ $submission->conference->title }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td class="paper-meta-label">Article Type:</td>
                                        <td class="paper-meta-val">{{ ucfirst(str_replace('_', ' ', $submission->article_type ?? 'Research Paper')) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="paper-meta-label">Current Status:</td>
                                        <td class="paper-meta-val" style="color: {{ $badgeColor }}; font-weight: 700;">
                                            {{ ucfirst(str_replace('_', ' ', $submission->status)) }}
                                        </td>
                                    </tr>
                                    @if($submission->payment_status)
                                        <tr>
                                            <td class="paper-meta-label">Fee Status:</td>
                                            <td class="paper-meta-val">{{ ucfirst(str_replace('_', ' ', $submission->payment_status)) }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td class="paper-meta-label">Submission Date:</td>
                                        <td class="paper-meta-val">{{ $submission->created_at ? $submission->created_at->format('M d, Y') : '-' }}</td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Editorial Remarks / Comments (if any) -->
                            @if(!empty($remarks))
                                <div class="remarks-box">
                                    <div class="remarks-title">Editorial & Review Remarks:</div>
                                    <div class="remarks-content">{{ $remarks }}</div>
                                </div>
                            @endif

                            <!-- Presentation Schedule Details (if scheduled) -->
                            @if(!empty($submission->presentation_day) || !empty($submission->conference_link))
                                <div class="schedule-box">
                                    <div class="schedule-title">Presentation & Meeting Information:</div>
                                    <table class="paper-meta-table">
                                        @if($submission->presentation_day)
                                            <tr>
                                                <td class="paper-meta-label" style="color: #4338ca;">Date / Day:</td>
                                                <td class="paper-meta-val">{{ $submission->presentation_day }}</td>
                                            </tr>
                                        @endif
                                        @if($submission->presentation_time)
                                            <tr>
                                                <td class="paper-meta-label" style="color: #4338ca;">Time Slot:</td>
                                                <td class="paper-meta-val">{{ $submission->presentation_time }}</td>
                                            </tr>
                                        @endif
                                        @if($submission->conference_link)
                                            <tr>
                                                <td class="paper-meta-label" style="color: #4338ca;">Meeting Link:</td>
                                                <td class="paper-meta-val">
                                                    <a href="{{ $submission->conference_link }}" target="_blank" style="color: #4f46e5; text-decoration: underline; word-break: break-all;">
                                                        {{ $submission->conference_link }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                            @endif

                            <!-- Certificate Codes (if generated) -->
                            @if(!empty($submission->certificate_attendee_code) || !empty($submission->certificate_presentation_code))
                                <div style="background-color: #ecfdf5; border: 1px solid #a7f3d0; border-left: 4px solid #10b981; border-radius: 10px; padding: 16px; margin: 20px 0;">
                                    <div style="font-size: 12px; font-weight: 700; color: #047857; text-transform: uppercase; margin-bottom: 6px;">Certificate Verification:</div>
                                    @if($submission->certificate_presentation_code)
                                        <p style="margin: 3px 0; font-size: 13px; color: #065f46;">
                                            <strong>Presenter Certificate Code:</strong> <code style="background: #ffffff; padding: 2px 6px; border-radius: 4px; border: 1px solid #a7f3d0;">{{ $submission->certificate_presentation_code }}</code>
                                        </p>
                                    @endif
                                    @if($submission->certificate_attendee_code)
                                        <p style="margin: 3px 0; font-size: 13px; color: #065f46;">
                                            <strong>Attendee Certificate Code:</strong> <code style="background: #ffffff; padding: 2px 6px; border-radius: 4px; border: 1px solid #a7f3d0;">{{ $submission->certificate_attendee_code }}</code>
                                        </p>
                                    @endif
                                </div>
                            @endif

                            <!-- Call to Action -->
                            <div class="cta-container">
                                <a href="{{ $actionUrl }}" class="cta-button" target="_blank">
                                    {{ $actionText }} &rarr;
                                </a>
                            </div>

                            <p style="font-size: 13px; color: #64748b; line-height: 1.5; margin-top: 24px;">
                                If you have any questions or require editorial assistance, please reply to this email or reach out through the <a href="{{ url('/') }}" style="color: #2563eb; font-weight: 600;">HJPARAM Support Portal</a>.
                            </p>
                        </div>

                        <!-- Footer -->
                        <div class="footer">
                            <p style="margin: 0 0 6px 0; font-weight: 600; color: #475569;">HJPARAM Publication • Scholarly Publishing Excellence</p>
                            <p style="margin: 0 0 8px 0;">This is an automated notification from the HJPARAM Peer Review & Editorial Management System.</p>
                            <p style="margin: 0;">&copy; {{ date('Y') }} HJPARAM. All rights reserved.</p>
                        </div>

                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
