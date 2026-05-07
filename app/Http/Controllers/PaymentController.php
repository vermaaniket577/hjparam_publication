<?php

namespace App\Http\Controllers;

use App\Models\PaymentSetting;
use App\Models\PaymentSubmission;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function create()
    {
        $paymentSetting = PaymentSetting::current();

        return view('payments.create', compact('paymentSetting'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile_number' => ['required', 'regex:/^[0-9+\-\s()]{7,20}$/'],
            'affiliation' => 'nullable|string|max:255',
            'paper_title' => 'required|string|max:500',
            'payment_amount' => 'required|numeric|min:1|max:999999.99',
            'payment_method' => 'required|in:UPI,QR Code,Bank Transfer',
            'transaction_id' => 'required|string|max:255|unique:payment_submissions,transaction_id',
            'payment_screenshot' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $file = $request->file('payment_screenshot');
        $path = $file->store('payment-screenshots', 'local');

        PaymentSubmission::create([
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'mobile_number' => $validated['mobile_number'],
            'affiliation' => $validated['affiliation'] ?? null,
            'paper_title' => $validated['paper_title'],
            'payment_amount' => $validated['payment_amount'],
            'payment_method' => $validated['payment_method'],
            'transaction_id' => $validated['transaction_id'],
            'screenshot_path' => $path,
            'screenshot_original_name' => $file->getClientOriginalName(),
            'status' => PaymentSubmission::STATUS_PENDING,
        ]);

        return redirect()
            ->route('payments.create')
            ->with('success', 'Payment details submitted successfully. Our editorial office will verify your payment soon.');
    }

    public function settings()
    {
        return response()->json(PaymentSetting::current()->only([
            'instructions',
            'default_amount',
            'qr_code_path',
        ]) + [
            'qr_code_url' => PaymentSetting::current()->qr_code_url,
        ]);
    }
}
