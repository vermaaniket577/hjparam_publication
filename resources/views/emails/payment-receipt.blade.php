<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            margin: 0;
            padding: 24px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        .header {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 50%, #312e81 100%);
            color: #ffffff;
            padding: 32px 28px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.02em;
        }
        .header p {
            margin: 6px 0 0 0;
            font-size: 13px;
            color: #bfdbfe;
        }
        .content {
            padding: 32px 28px;
        }
        .badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 20px;
        }
        .badge-approved {
            background-color: #dcfce7;
            color: #15803d;
        }
        .badge-pending {
            background-color: #fef3c7;
            color: #b45309;
        }
        .badge-rejected {
            background-color: #fee2e2;
            color: #b91c1c;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .details-table td {
            padding: 10px 12px;
            font-size: 13px;
            border-bottom: 1px solid #f1f5f9;
        }
        .details-table td:first-child {
            color: #64748b;
            font-weight: 600;
            width: 38%;
        }
        .details-table td:last-child {
            color: #0f172a;
            font-weight: 700;
        }
        .amount-box {
            background-color: #eff6ff;
            border-radius: 12px;
            padding: 18px 24px;
            text-align: center;
            margin: 24px 0;
            border: 1px solid #dbeafe;
        }
        .amount-box .label {
            font-size: 11px;
            font-weight: 700;
            color: #3b82f6;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .amount-box .value {
            font-size: 28px;
            font-weight: 900;
            color: #1e3a8a;
            margin-top: 4px;
        }
        .btn {
            display: inline-block;
            background-color: #2563eb;
            color: #ffffff !important;
            padding: 12px 26px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 13px;
            text-decoration: none;
            text-align: center;
            margin-top: 10px;
        }
        .footer {
            background-color: #f8fafc;
            padding: 24px;
            text-align: center;
            font-size: 11px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
        }
        .footer a {
            color: #2563eb;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>HJPARAM Publication</h1>
            <p>Official Publication Fee Receipt & Acknowledgment</p>
        </div>

        <div class="content">
            <p style="font-size: 15px; margin-top: 0;">Dear <strong>{{ $payment->full_name }}</strong>,</p>

            @if($payment->status === 'approved')
                <div class="badge badge-approved">Payment Confirmed & Verified</div>
                <p style="font-size: 13.5px; line-height: 1.6; color: #334155;">
                    Thank you! We have received and confirmed your publication fee. Please find your official receipt details below:
                </p>
            @elseif($payment->status === 'pending')
                <div class="badge badge-pending">Payment Received - Verification in Progress</div>
                <p style="font-size: 13.5px; line-height: 1.6; color: #334155;">
                    Thank you for submitting your payment. Our editorial accounts team is verifying the transaction details and your receipt has been recorded.
                </p>
            @else
                <div class="badge badge-rejected">Payment Rejected</div>
                <p style="font-size: 13.5px; line-height: 1.6; color: #334155;">
                    Your submitted payment transaction could not be verified. Please review the details below or contact our editorial desk.
                </p>
            @endif

            <div class="amount-box">
                <div class="label">Amount Paid</div>
                <div class="value">{{ $payment->currency ?? 'INR' }} {{ number_format($payment->payment_amount, 2) }}</div>
            </div>

            <table class="details-table">
                <tr>
                    <td>Receipt No</td>
                    <td>REC-{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</td>
                </tr>
                <tr>
                    <td>Paper / Manuscript</td>
                    <td>{{ $payment->paper_title }}</td>
                </tr>
                <tr>
                    <td>Fee Type</td>
                    <td style="text-transform: capitalize;">{{ $payment->fee_type }} Publication Fee</td>
                </tr>
                <tr>
                    <td>Payment Method</td>
                    <td>{{ $payment->payment_method }}</td>
                </tr>
                <tr>
                    <td>Transaction / Reference ID</td>
                    <td style="font-family: monospace;">{{ $payment->transaction_id }}</td>
                </tr>
                <tr>
                    <td>Date & Time</td>
                    <td>{{ $payment->created_at->format('d M Y, h:i A') }}</td>
                </tr>
                @if($payment->admin_note)
                <tr>
                    <td>Notes</td>
                    <td>{{ $payment->admin_note }}</td>
                </tr>
                @endif
            </table>

            <div style="text-align: center; margin-top: 24px;">
                <a href="{{ route('payments.receipt', $payment->id) }}" class="btn">View & Print Full Receipt</a>
            </div>
        </div>

        <div class="footer">
            <p style="margin: 0 0 6px 0;"><strong>HJParam Publication & Scholarly Research Hub</strong></p>
            <p style="margin: 0 0 6px 0;">Official Portal: <a href="{{ config('app.url', 'https://hjparam.com') }}">{{ config('app.url', 'https://hjparam.com') }}</a> • Support: <a href="mailto:support@hjparam.com">support@hjparam.com</a></p>
            <p style="margin: 0; color: #94a3b8;">&copy; {{ date('Y') }} HJPARAM. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
