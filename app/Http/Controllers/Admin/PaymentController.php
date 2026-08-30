<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentSetting;
use App\Models\PaymentSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = PaymentSubmission::query()->with('reviewer');

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('paper_title', 'like', "%{$search}%")
                    ->orWhere('transaction_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('fee_type')) {
            $query->where('fee_type', $request->fee_type);
        }

        if ($request->filled('payment_type')) {
            if ($request->payment_type === 'online') {
                $query->where('payment_method', 'like', '%Online%')
                    ->orWhere('payment_method', 'like', '%Razorpay%')
                    ->orWhere('payment_method', 'like', '%PhonePe%')
                    ->orWhere('payment_method', 'like', '%Stripe%');
            } elseif ($request->payment_type === 'manual') {
                $query->whereNotIn('payment_method', ['Online Gateway', 'Razorpay', 'PhonePe', 'Stripe', 'PayPal']);
            }
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $payments = $query->latest()->paginate(15)->withQueryString();
        $paymentSetting = PaymentSetting::current();

        $selectedPayment = null;
        if ($request->route('payment')) {
            $paymentParam = $request->route('payment');
            $selectedPayment = is_numeric($paymentParam) ? PaymentSubmission::find($paymentParam) : null;
        }

        $stats = [
            'total_count' => PaymentSubmission::count(),
            'approved_count' => PaymentSubmission::where('status', PaymentSubmission::STATUS_APPROVED)->count(),
            'pending_count' => PaymentSubmission::where('status', PaymentSubmission::STATUS_PENDING)->count(),
            'total_revenue' => PaymentSubmission::where('status', PaymentSubmission::STATUS_APPROVED)->sum('payment_amount'),
        ];

        return view('admin.payments.index', compact('payments', 'paymentSetting', 'stats', 'selectedPayment'));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'default_amount' => 'nullable|numeric|min:0|max:999999.99',
            'journal_fee_amount' => 'nullable|numeric|min:0|max:999999.99',
            'conference_fee_amount' => 'nullable|numeric|min:0|max:999999.99',
            'instructions' => 'nullable|string|max:2000',
            'qr_code' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'online_payment_enabled' => 'nullable|boolean',
            'gateway_provider' => 'required|string|in:razorpay,phonepe,stripe,custom_link,upi_qr,paypal',
            'currency' => 'required|string|max:10',
            'gateway_key_id' => 'nullable|string|max:255',
            'gateway_key_secret' => 'nullable|string|max:255',
            'gateway_merchant_id' => 'nullable|string|max:255',
            'gateway_payment_link' => 'nullable|url|max:1000',
            'gateway_instructions' => 'nullable|string|max:1000',
        ]);

        $setting = PaymentSetting::current();

        $journalFee = $validated['journal_fee_amount'] ?? $validated['default_amount'] ?? $setting->journal_fee;
        $validated['journal_fee_amount'] = $journalFee;
        $validated['default_amount'] = $journalFee;
        $validated['conference_fee_amount'] = $validated['conference_fee_amount'] ?? $setting->conference_fee;

        $validated['online_payment_enabled'] = $request->has('online_payment_enabled');

        if ($request->hasFile('qr_code')) {
            if ($setting->qr_code_path && Storage::disk('public')->exists($setting->qr_code_path)) {
                Storage::disk('public')->delete($setting->qr_code_path);
            }

            $validated['qr_code_path'] = $request->file('qr_code')->store('payment-qr', 'public');
        }

        $setting->update($validated);

        return back()->with('success', 'Payment gateway settings and fee amounts updated successfully.');
    }

    public function updateStatus(Request $request, PaymentSubmission $payment)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'admin_note' => 'nullable|string|max:1000',
        ]);

        $payment->update([
            'status' => $validated['status'],
            'admin_note' => $validated['admin_note'] ?? null,
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
        ]);

        return back()->with('success', 'Payment status updated successfully.');
    }

    public function downloadScreenshot(PaymentSubmission $payment)
    {
        abort_unless($payment->screenshot_path && Storage::disk('local')->exists($payment->screenshot_path), 404);

        return Storage::disk('local')->download(
            $payment->screenshot_path,
            $payment->screenshot_original_name ?: "payment-proof-{$payment->id}"
        );
    }

    public function export(Request $request): StreamedResponse
    {
        $filename = 'payment-submissions-' . now()->format('Y-m-d-His') . '.csv';

        $query = PaymentSubmission::query()->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'ID',
                'Full Name',
                'Email',
                'Mobile',
                'Affiliation',
                'Paper Title',
                'Amount',
                'Currency',
                'Method',
                'Transaction / Reference ID',
                'Gateway Order ID',
                'Gateway Payment ID',
                'Status',
                'Submitted At',
            ]);

            $query->chunk(200, function ($payments) use ($handle) {
                foreach ($payments as $payment) {
                    fputcsv($handle, [
                        $payment->id,
                        $payment->full_name,
                        $payment->email,
                        $payment->mobile_number,
                        $payment->affiliation,
                        $payment->paper_title,
                        $payment->payment_amount,
                        $payment->currency ?? 'INR',
                        $payment->payment_method,
                        $payment->transaction_id,
                        $payment->gateway_order_id,
                        $payment->gateway_payment_id,
                        $payment->status,
                        optional($payment->created_at)->format('Y-m-d H:i:s'),
                    ]);
                }
            });

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
