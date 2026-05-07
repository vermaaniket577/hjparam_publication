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

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $payments = $query->latest()->paginate(15)->withQueryString();
        $paymentSetting = PaymentSetting::current();

        return view('admin.payments.index', compact('payments', 'paymentSetting'));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'default_amount' => 'required|numeric|min:0|max:999999.99',
            'instructions' => 'required|string|max:2000',
            'qr_code' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $setting = PaymentSetting::current();

        if ($request->hasFile('qr_code')) {
            if ($setting->qr_code_path && Storage::disk('public')->exists($setting->qr_code_path)) {
                Storage::disk('public')->delete($setting->qr_code_path);
            }

            $validated['qr_code_path'] = $request->file('qr_code')->store('payment-qr', 'public');
        }

        $setting->update($validated);

        return back()->with('success', 'Payment QR code and instructions updated successfully.');
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
        abort_unless(Storage::disk('local')->exists($payment->screenshot_path), 404);

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
                'Method',
                'Transaction ID',
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
                        $payment->payment_method,
                        $payment->transaction_id,
                        $payment->status,
                        optional($payment->created_at)->format('Y-m-d H:i:s'),
                    ]);
                }
            });

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
