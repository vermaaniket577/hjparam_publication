<?php

namespace App\Http\Controllers;

use App\Models\PaymentSetting;
use App\Models\PaymentSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function create(Request $request)
    {
        $paymentSetting = PaymentSetting::current();
        $submission = null;
        if ($request->filled('submission_id')) {
            $submission = \App\Models\Submission::with(['journal', 'user'])->find($request->submission_id);
        }

        return view('payments.create', compact('paymentSetting', 'submission'));
    }

    /**
     * Store offline/manual payment (QR code, UPI, Bank transfer)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fee_type' => 'nullable|string|in:journal,conference',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile_number' => ['required', 'regex:/^[0-9+\-\s()]{7,20}$/'],
            'affiliation' => 'nullable|string|max:255',
            'paper_title' => 'required|string|max:500',
            'payment_amount' => 'nullable|numeric',
            'payment_method' => 'required|in:UPI,QR Code,Bank Transfer,Other',
            'transaction_id' => 'required|string|max:255|unique:payment_submissions,transaction_id',
            'payment_screenshot' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $setting = PaymentSetting::current();
        $feeType = $validated['fee_type'] ?? 'journal';
        $lockedAmount = ($feeType === 'conference') ? $setting->conference_fee : $setting->journal_fee;

        $file = $request->file('payment_screenshot');
        $path = $file->store('payment-screenshots', 'local');

        $payment = PaymentSubmission::create([
            'fee_type' => $feeType,
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'mobile_number' => $validated['mobile_number'],
            'affiliation' => $validated['affiliation'] ?? null,
            'paper_title' => $validated['paper_title'],
            'payment_amount' => $lockedAmount,
            'currency' => $setting->currency ?? 'INR',
            'payment_method' => $validated['payment_method'],
            'transaction_id' => $validated['transaction_id'],
            'screenshot_path' => $path,
            'screenshot_original_name' => $file->getClientOriginalName(),
            'status' => PaymentSubmission::STATUS_PENDING,
        ]);

        return redirect()
            ->route('payments.receipt', $payment->id)
            ->with('success', 'Payment proof submitted successfully! Your payment is pending verification by our editorial team.');
    }

    /**
     * Initiate Online Payment Gateway (Razorpay, Direct Link, PhonePe, etc.)
     */
    public function initiateOnlinePayment(Request $request)
    {
        $validated = $request->validate([
            'fee_type' => 'nullable|string|in:journal,conference',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile_number' => ['required', 'regex:/^[0-9+\-\s()]{7,20}$/'],
            'affiliation' => 'nullable|string|max:255',
            'paper_title' => 'required|string|max:500',
            'payment_amount' => 'nullable|numeric',
        ]);

        $setting = PaymentSetting::current();

        if (!$setting->online_payment_enabled) {
            return response()->json([
                'success' => false,
                'message' => 'Online payment gateway is currently disabled by the administration.',
            ], 422);
        }

        $feeType = $validated['fee_type'] ?? 'journal';
        $feeLabel = $feeType === 'conference' ? 'Conference Fee' : 'Journal Publication Fee';
        $currency = strtoupper($setting->currency ?: 'INR');
        
        // Strictly use admin-configured fee amount (prevent client tampering)
        $amount = (float) (($feeType === 'conference') ? $setting->conference_fee : $setting->journal_fee);
        $receiptId = 'HJPARAM_' . strtoupper(Str::random(10));

        // If Razorpay is enabled and credentials are configured
        if ($setting->gateway_provider === 'razorpay' && !empty($setting->gateway_key_id)) {
            $orderId = null;

            // Try creating server-side order with Razorpay if secret exists
            if (!empty($setting->gateway_key_secret)) {
                try {
                    $amountInSubunit = (int) round($amount * ($currency === 'INR' ? 100 : 100));
                    $response = Http::withBasicAuth($setting->gateway_key_id, $setting->gateway_key_secret)
                        ->post('https://api.razorpay.com/v1/orders', [
                            'amount' => $amountInSubunit,
                            'currency' => $currency,
                            'receipt' => $receiptId,
                            'notes' => [
                                'fee_type' => $feeType,
                                'fee_category' => $feeLabel,
                                'author_name' => $validated['full_name'],
                                'email' => $validated['email'],
                                'paper_title' => Str::limit($validated['paper_title'], 40),
                            ],
                        ]);

                    if ($response->successful()) {
                        $orderData = $response->json();
                        $orderId = $orderData['id'] ?? null;
                    }
                } catch (\Exception $e) {
                    \Log::warning('Razorpay Order Creation API Notice: ' . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'provider' => 'razorpay',
                'key_id' => $setting->gateway_key_id,
                'order_id' => $orderId,
                'receipt' => $receiptId,
                'amount' => (int) round($amount * 100), // in paise / cents
                'currency' => $currency,
                'name' => 'HJPARAM Publication',
                'description' => $feeLabel . ' - ' . Str::limit($validated['paper_title'], 40),
                'prefill' => [
                    'name' => $validated['full_name'],
                    'email' => $validated['email'],
                    'contact' => $validated['mobile_number'],
                ],
            ]);
        }

        // If direct payment link (e.g. PhonePe link, Stripe link, Razorpay Payment Link) is configured
        if (!empty($setting->gateway_payment_link)) {
            return response()->json([
                'success' => true,
                'provider' => 'link',
                'payment_link' => $setting->gateway_payment_link,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Online payment gateway is not yet fully configured. Please use QR Code or Bank Transfer.',
        ], 422);
    }

    /**
     * Verify and record completed online payment
     */
    public function verifyOnlinePayment(Request $request)
    {
        $validated = $request->validate([
            'fee_type' => 'nullable|string|in:journal,conference',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile_number' => ['required', 'regex:/^[0-9+\-\s()]{7,20}$/'],
            'affiliation' => 'nullable|string|max:255',
            'paper_title' => 'required|string|max:500',
            'payment_amount' => 'nullable|numeric',
            'gateway_payment_id' => 'required|string|max:255',
            'gateway_order_id' => 'nullable|string|max:255',
            'gateway_signature' => 'nullable|string|max:255',
        ]);

        $setting = PaymentSetting::current();
        $isSignatureValid = true;

        // Verify Razorpay signature if secret is provided
        if (!empty($setting->gateway_key_secret) && !empty($validated['gateway_order_id']) && !empty($validated['gateway_signature'])) {
            $expectedSignature = hash_hmac(
                'sha256',
                $validated['gateway_order_id'] . '|' . $validated['gateway_payment_id'],
                $setting->gateway_key_secret
            );
            $isSignatureValid = hash_equals($expectedSignature, $validated['gateway_signature']);
        }

        if (!$isSignatureValid) {
            return response()->json([
                'success' => false,
                'message' => 'Payment signature verification failed. Please contact support.',
            ], 400);
        }

        $feeType = $validated['fee_type'] ?? 'journal';
        $lockedAmount = ($feeType === 'conference') ? $setting->conference_fee : $setting->journal_fee;

        $payment = PaymentSubmission::create([
            'fee_type' => $feeType,
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'mobile_number' => $validated['mobile_number'],
            'affiliation' => $validated['affiliation'] ?? null,
            'paper_title' => $validated['paper_title'],
            'payment_amount' => $lockedAmount,
            'currency' => $setting->currency ?: 'INR',
            'payment_method' => 'Online Gateway (' . ucfirst($setting->gateway_provider) . ')',
            'transaction_id' => $validated['gateway_payment_id'],
            'gateway_order_id' => $validated['gateway_order_id'] ?? null,
            'gateway_payment_id' => $validated['gateway_payment_id'],
            'gateway_signature' => $validated['gateway_signature'] ?? null,
            'gateway_response' => json_encode($request->all()),
            'status' => PaymentSubmission::STATUS_APPROVED, // Instant confirmation for verified online gateway
            'reviewed_at' => now(),
            'admin_note' => 'Automatically verified via ' . ucfirst($setting->gateway_provider) . ' online payment gateway.',
        ]);

        return response()->json([
            'success' => true,
            'redirect_url' => route('payments.receipt', $payment->id),
            'message' => 'Payment successful! Your publication fee has been confirmed.',
        ]);
    }

    /**
     * View Printable Payment Receipt / Acknowledgment
     */
    public function receipt(PaymentSubmission $payment)
    {
        $paymentSetting = PaymentSetting::current();

        return view('payments.receipt', compact('payment', 'paymentSetting'));
    }

    public function settings()
    {
        $setting = PaymentSetting::current();
        return response()->json($setting->only([
            'instructions',
            'default_amount',
            'journal_fee_amount',
            'conference_fee_amount',
            'qr_code_path',
            'online_payment_enabled',
            'gateway_provider',
            'currency',
            'gateway_key_id',
            'gateway_payment_link',
            'gateway_instructions',
        ]) + [
            'journal_fee' => $setting->journal_fee,
            'conference_fee' => $setting->conference_fee,
            'qr_code_url' => $setting->qr_code_url,
        ]);
    }
}
