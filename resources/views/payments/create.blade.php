@extends('layouts.web')

@section('title', 'Publication Fee Payment - HJPARAM')

@section('content')
<div style="background-color: #f8fafc; padding: 48px 16px; min-height: 85vh; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; box-sizing: border-box;">
    <div style="max-width: 1200px; margin: 0 auto; box-sizing: border-box;">

        <!-- Page Header -->
        <div style="margin-bottom: 32px;">
            <p style="color: #2563eb; font-weight: 800; font-size: 11px; text-transform: uppercase; letter-spacing: 2px; margin: 0 0 6px 0;">Publication Fee Payment</p>
            <h1 style="font-size: 34px; color: #0f172a; font-weight: 900; margin: 0 0 10px 0; letter-spacing: -0.5px; line-height: 1.2;">Pay Publication Fee / APC</h1>
            <p style="color: #64748b; font-size: 14px; margin: 0; max-width: 700px; line-height: 1.6;">
                Choose your preferred payment method below to complete the article processing charges (APC). Instant online payment with automatic receipt or manual QR scan is supported.
            </p>
        </div>

        @if(session('success'))
            <div style="background-color: #ecfdf5; border: 1px solid #6ee7b7; border-radius: 14px; padding: 16px 20px; color: #065f46; font-size: 14px; font-weight: 600; margin-bottom: 24px; display: flex; align-items: center; gap: 10px;">
                <span style="font-size: 18px;">✓</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div style="background-color: #fef2f2; border: 1px solid #fecaca; border-radius: 14px; padding: 16px 20px; color: #991b1b; font-size: 14px; font-weight: 600; margin-bottom: 24px;">
                {{ session('error') }}
            </div>
        @endif

        @php
            $isOnlineEnabled = isset($paymentSetting) && $paymentSetting->online_payment_enabled;
            $currencySym = $paymentSetting->currency_symbol ?? '₹';
            $journalFee = (float) ($paymentSetting->journal_fee ?? 1500);
            $conferenceFee = (float) ($paymentSetting->conference_fee ?? 2500);
            $defaultAmt = $journalFee;
        @endphp

        <!-- Main Layout Flex Container -->
        <div style="display: flex; flex-direction: row; flex-wrap: wrap; gap: 32px; align-items: flex-start; box-sizing: border-box; width: 100%;">

            <!-- Left: Main Form Card -->
            <div style="flex: 1 1 600px; min-width: 300px; max-width: 100%; background: #ffffff; border-radius: 20px; border: 1px solid #e2e8f0; box-shadow: 0 10px 30px rgba(0,0,0,0.06); padding: 32px; box-sizing: border-box;">
                
                @if($isOnlineEnabled)
                    <!-- Mode Switcher Tabs -->
                    <div style="display: flex; background: #f1f5f9; border-radius: 14px; padding: 4px; margin-bottom: 28px; border: 1px solid #e2e8f0; max-width: 440px;">
                        <button type="button" 
                                id="tab-btn-online" 
                                onclick="switchPaymentMode('online')"
                                style="flex: 1; padding: 10px 14px; border-radius: 10px; font-size: 13px; font-weight: 700; border: none; cursor: pointer; transition: all 0.2s ease; background: #ffffff; color: #2563eb; box-shadow: 0 2px 6px rgba(0,0,0,0.08);">
                            ⚡ Pay Online (Instant)
                        </button>
                        <button type="button" 
                                id="tab-btn-manual" 
                                onclick="switchPaymentMode('manual')"
                                style="flex: 1; padding: 10px 14px; border-radius: 10px; font-size: 13px; font-weight: 700; border: none; cursor: pointer; transition: all 0.2s ease; background: transparent; color: #64748b;">
                            📷 QR Code / Manual
                        </button>
                    </div>
                @endif

                <!-- Dynamic Error Alert Box -->
                <div id="payment-alert" style="display: none; padding: 14px 18px; border-radius: 12px; font-size: 13px; font-weight: 600; margin-bottom: 20px;"></div>

                <!-- ================= 1. ONLINE PAYMENT FORM ================= -->
                <div id="section-online" style="display: {{ $isOnlineEnabled ? 'block' : 'none' }};">
                    <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 14px; padding: 16px; margin-bottom: 24px; display: flex; align-items: flex-start; gap: 14px;">
                        <div style="width: 36px; height: 36px; border-radius: 10px; background: #2563eb; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;">
                            💳
                        </div>
                        <div>
                            <h4 style="margin: 0 0 4px 0; font-size: 14px; font-weight: 700; color: #1e3a8a;">Instant Online Payment Checkout</h4>
                            <p style="margin: 0; font-size: 12px; color: #3b82f6; line-height: 1.5;">
                                {{ $paymentSetting->gateway_instructions ?: 'Pay securely using Credit/Debit Cards, UPI (PhonePe, GPay, Paytm), Net Banking, or Wallets with instant digital confirmation.' }}
                            </p>
                        </div>
                    </div>

                    <form id="online-payment-form" onsubmit="handleOnlinePaymentSubmit(event)" style="display: flex; flex-direction: column; gap: 18px;">
                        @csrf

                        <!-- Fee Category Radio Buttons -->
                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
                                Select Fee Category <span style="color: #ef4444;">*</span>
                            </label>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px;">
                                
                                <!-- Journal Fees Option -->
                                <label id="online-card-journal" style="display: flex; align-items: center; gap: 12px; padding: 13px 16px; border: 2px solid #2563eb; background: #eff6ff; border-radius: 12px; cursor: pointer; transition: all 0.2s ease;">
                                    <input type="radio" name="online_fee_type" value="journal" checked onchange="toggleFeeType('journal', 'online')" style="accent-color: #2563eb; width: 18px; height: 18px; cursor: pointer;">
                                    <div>
                                        <div style="font-size: 14px; font-weight: 800; color: #1e3a8a;">📖 Journal Fees</div>
                                        <div style="font-size: 11px; color: #3b82f6; margin-top: 2px;">Article Processing Charges (APC) — <strong>{{ $currencySym }}{{ number_format($journalFee, 2) }}</strong></div>
                                    </div>
                                </label>

                                <!-- Conference Fees Option -->
                                <label id="online-card-conference" style="display: flex; align-items: center; gap: 12px; padding: 13px 16px; border: 2px solid #e2e8f0; background: #f8fafc; border-radius: 12px; cursor: pointer; transition: all 0.2s ease;">
                                    <input type="radio" name="online_fee_type" value="conference" onchange="toggleFeeType('conference', 'online')" style="accent-color: #2563eb; width: 18px; height: 18px; cursor: pointer;">
                                    <div>
                                        <div style="font-size: 14px; font-weight: 800; color: #1e293b;">🏛️ Conference Fees</div>
                                        <div style="font-size: 11px; color: #64748b; margin-top: 2px;">Registration & Presentation — <strong>{{ $currencySym }}{{ number_format($conferenceFee, 2) }}</strong></div>
                                    </div>
                                </label>

                            </div>
                        </div>

                        <!-- Row 1: Name & Email -->
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
                            <div>
                                <label style="display: block; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Author Full Name <span style="color: #ef4444;">*</span></label>
                                <input type="text" id="online_full_name" required value="{{ old('full_name', $submission->user->name ?? (auth()->user()->name ?? '')) }}"
                                    style="width: 100%; padding: 11px 14px; border: 1px solid #cbd5e1; border-radius: 10px; background: #f8fafc; font-size: 14px; color: #1e293b; box-sizing: border-box; outline: none;"
                                    placeholder="e.g. Dr. John Doe">
                            </div>
                            <div>
                                <label style="display: block; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Email Address <span style="color: #ef4444;">*</span></label>
                                <input type="email" id="online_email" required value="{{ old('email', $submission->user->email ?? (auth()->user()->email ?? '')) }}"
                                    style="width: 100%; padding: 11px 14px; border: 1px solid #cbd5e1; border-radius: 10px; background: #f8fafc; font-size: 14px; color: #1e293b; box-sizing: border-box; outline: none;"
                                    placeholder="author@example.com">
                            </div>
                        </div>

                        <!-- Row 2: Mobile & Affiliation -->
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
                            <div>
                                <label style="display: block; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Mobile Number / WhatsApp <span style="color: #ef4444;">*</span></label>
                                <input type="text" id="online_mobile" required value="{{ old('mobile_number', $submission->user->phone ?? (auth()->user()->phone ?? '')) }}"
                                    style="width: 100%; padding: 11px 14px; border: 1px solid #cbd5e1; border-radius: 10px; background: #f8fafc; font-size: 14px; color: #1e293b; box-sizing: border-box; outline: none;"
                                    placeholder="+91 9876543210">
                            </div>
                            <div>
                                <label style="display: block; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Affiliation / Organization</label>
                                <input type="text" id="online_affiliation" value="{{ old('affiliation', $submission->user->affiliation ?? (auth()->user()->affiliation ?? '')) }}"
                                    style="width: 100%; padding: 11px 14px; border: 1px solid #cbd5e1; border-radius: 10px; background: #f8fafc; font-size: 14px; color: #1e293b; box-sizing: border-box; outline: none;"
                                    placeholder="College / University / Organization">
                            </div>
                        </div>

                        <!-- Paper Title -->
                        <div>
                            <label id="online-title-label" style="display: block; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Manuscript / Paper Title <span style="color: #ef4444;">*</span></label>
                            <input type="text" id="online_paper_title" required value="{{ old('paper_title', $submission->title ?? '') }}"
                                style="width: 100%; padding: 11px 14px; border: 1px solid #cbd5e1; border-radius: 10px; background: #f8fafc; font-size: 14px; color: #1e293b; box-sizing: border-box; outline: none;"
                                placeholder="Full title of submitted manuscript">
                        </div>

                        <!-- Locked Fee Amount Input -->
                        <div>
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px;">
                                <label id="online-amount-label" style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">
                                    Journal Fee Amount ({{ $paymentSetting->currency ?? 'INR' }}) <span style="color: #ef4444;">*</span>
                                </label>
                                <span style="font-size: 11px; color: #059669; font-weight: 700; background: #ecfdf5; padding: 2px 8px; border-radius: 6px; border: 1px solid #a7f3d0; display: inline-flex; align-items: center; gap: 4px;">
                                    🔒 Fixed Official Fee
                                </span>
                            </div>
                            <div style="position: relative; width: 100%;">
                                <span style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); font-weight: 800; color: #2563eb; font-size: 16px;">{{ $currencySym }}</span>
                                <input type="text" id="online_amount" readonly required
                                    value="{{ number_format($journalFee, 2, '.', '') }}"
                                    style="width: 100%; padding: 12px 14px 12px 36px; border: 1.5px solid #cbd5e1; border-radius: 10px; background: #f1f5f9; font-size: 16px; font-weight: 800; color: #0f172a; box-sizing: border-box; cursor: not-allowed;"
                                    title="Fee amount is fixed by administration based on selected category">
                            </div>
                            <p id="online-fee-note" style="font-size: 11px; color: #64748b; margin: 4px 0 0 0;">Standard article processing charge (APC) for peer-reviewed journal publication.</p>
                        </div>

                        <!-- Pay Button -->
                        <button type="submit" id="btn-pay-online"
                            style="width: 100%; background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 50%, #059669 100%); color: #ffffff; padding: 16px; border-radius: 12px; border: none; font-size: 14px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; cursor: pointer; box-shadow: 0 8px 20px rgba(37,99,235,0.3); transition: all 0.2s ease; margin-top: 6px;">
                            ⚡ Pay Online Now (<span id="btn-online-amount">{{ $currencySym }}{{ number_format($defaultAmt, 2) }}</span>)
                        </button>

                        <div style="display: flex; items-center; justify-content: center; gap: 14px; color: #94a3b8; font-size: 11px; font-weight: 600; text-align: center; margin-top: 4px; flex-wrap: wrap;">
                            <span>🔒 256-Bit SSL Encrypted</span>
                            <span>•</span>
                            <span>⚡ Instant Receipt</span>
                            <span>•</span>
                            <span>✓ PCI-DSS Certified</span>
                        </div>
                    </form>
                </div>

                <!-- ================= 2. MANUAL / QR CODE FORM ================= -->
                <div id="section-manual" style="display: {{ $isOnlineEnabled ? 'none' : 'block' }};">
                    <form action="{{ route('payments.store') }}" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 18px;">
                        @csrf

                        <!-- Fee Category Radio Buttons -->
                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
                                Select Fee Category <span style="color: #ef4444;">*</span>
                            </label>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px;">
                                
                                <!-- Journal Fees Option -->
                                <label id="manual-card-journal" style="display: flex; align-items: center; gap: 12px; padding: 13px 16px; border: 2px solid #2563eb; background: #eff6ff; border-radius: 12px; cursor: pointer; transition: all 0.2s ease;">
                                    <input type="radio" name="fee_type" value="journal" {{ old('fee_type', 'journal') === 'journal' ? 'checked' : '' }} onchange="toggleFeeType('journal', 'manual')" style="accent-color: #2563eb; width: 18px; height: 18px; cursor: pointer;">
                                    <div>
                                        <div style="font-size: 14px; font-weight: 800; color: #1e3a8a;">📖 Journal Fees</div>
                                        <div style="font-size: 11px; color: #3b82f6; margin-top: 2px;">Article Processing Charges (APC) — <strong>{{ $currencySym }}{{ number_format($journalFee, 2) }}</strong></div>
                                    </div>
                                </label>

                                <!-- Conference Fees Option -->
                                <label id="manual-card-conference" style="display: flex; align-items: center; gap: 12px; padding: 13px 16px; border: 2px solid #e2e8f0; background: #f8fafc; border-radius: 12px; cursor: pointer; transition: all 0.2s ease;">
                                    <input type="radio" name="fee_type" value="conference" {{ old('fee_type') === 'conference' ? 'checked' : '' }} onchange="toggleFeeType('conference', 'manual')" style="accent-color: #2563eb; width: 18px; height: 18px; cursor: pointer;">
                                    <div>
                                        <div style="font-size: 14px; font-weight: 800; color: #1e293b;">🏛️ Conference Fees</div>
                                        <div style="font-size: 11px; color: #64748b; margin-top: 2px;">Registration & Presentation — <strong>{{ $currencySym }}{{ number_format($conferenceFee, 2) }}</strong></div>
                                    </div>
                                </label>

                            </div>
                        </div>

                        <!-- Row 1: Name & Email -->
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
                            <div>
                                <label style="display: block; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Full Name <span style="color: #ef4444;">*</span></label>
                                <input type="text" name="full_name" required value="{{ old('full_name', $submission->user->name ?? (auth()->user()->name ?? '')) }}"
                                    style="width: 100%; padding: 11px 14px; border: 1px solid #cbd5e1; border-radius: 10px; background: #f8fafc; font-size: 14px; color: #1e293b; box-sizing: border-box;"
                                    placeholder="Author full name">
                                @error('full_name') <p style="color: #ef4444; font-size: 12px; margin: 4px 0 0 0;">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label style="display: block; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Email Address <span style="color: #ef4444;">*</span></label>
                                <input type="email" name="email" required value="{{ old('email', $submission->user->email ?? (auth()->user()->email ?? '')) }}"
                                    style="width: 100%; padding: 11px 14px; border: 1px solid #cbd5e1; border-radius: 10px; background: #f8fafc; font-size: 14px; color: #1e293b; box-sizing: border-box;"
                                    placeholder="name@example.com">
                                @error('email') <p style="color: #ef4444; font-size: 12px; margin: 4px 0 0 0;">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Row 2: Mobile & Affiliation -->
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
                            <div>
                                <label style="display: block; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Mobile Number <span style="color: #ef4444;">*</span></label>
                                <input type="text" name="mobile_number" required value="{{ old('mobile_number', $submission->user->phone ?? (auth()->user()->phone ?? '')) }}"
                                    style="width: 100%; padding: 11px 14px; border: 1px solid #cbd5e1; border-radius: 10px; background: #f8fafc; font-size: 14px; color: #1e293b; box-sizing: border-box;"
                                    placeholder="+91 9876543210">
                                @error('mobile_number') <p style="color: #ef4444; font-size: 12px; margin: 4px 0 0 0;">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label style="display: block; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Affiliation</label>
                                <input type="text" name="affiliation" value="{{ old('affiliation', $submission->user->affiliation ?? (auth()->user()->affiliation ?? '')) }}"
                                    style="width: 100%; padding: 11px 14px; border: 1px solid #cbd5e1; border-radius: 10px; background: #f8fafc; font-size: 14px; color: #1e293b; box-sizing: border-box;"
                                    placeholder="College / University / Organization">
                            </div>
                        </div>

                        <!-- Paper Title -->
                        <div>
                            <label id="manual-title-label" style="display: block; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Manuscript / Paper Title <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="paper_title" required value="{{ old('paper_title', $submission->title ?? '') }}"
                                style="width: 100%; padding: 11px 14px; border: 1px solid #cbd5e1; border-radius: 10px; background: #f8fafc; font-size: 14px; color: #1e293b; box-sizing: border-box;"
                                placeholder="Full title of submitted manuscript">
                            @error('paper_title') <p style="color: #ef4444; font-size: 12px; margin: 4px 0 0 0;">{{ $message }}</p> @enderror
                        </div>

                        <!-- Row 3: Amount & Method -->
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
                            <div>
                                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px;">
                                    <label id="manual-amount-label" style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">
                                        Fee Amount ({{ $paymentSetting->currency ?? 'INR' }}) <span style="color: #ef4444;">*</span>
                                    </label>
                                    <span style="font-size: 11px; color: #059669; font-weight: 700; background: #ecfdf5; padding: 2px 8px; border-radius: 6px; border: 1px solid #a7f3d0;">
                                        🔒 Fixed Fee
                                    </span>
                                </div>
                                <div style="position: relative; width: 100%;">
                                    <span style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); font-weight: 800; color: #2563eb; font-size: 16px;">{{ $currencySym }}</span>
                                    <input type="text" name="payment_amount" id="manual_amount" readonly required
                                        value="{{ number_format(old('fee_type') === 'conference' ? $conferenceFee : $journalFee, 2, '.', '') }}"
                                        style="width: 100%; padding: 11px 14px 11px 36px; border: 1.5px solid #cbd5e1; border-radius: 10px; background: #f1f5f9; font-size: 15px; font-weight: 800; color: #0f172a; box-sizing: border-box; cursor: not-allowed;">
                                </div>
                                @error('payment_amount') <p style="color: #ef4444; font-size: 12px; margin: 4px 0 0 0;">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label style="display: block; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Payment Method <span style="color: #ef4444;">*</span></label>
                                <select name="payment_method" required
                                    style="width: 100%; padding: 11px 14px; border: 1px solid #cbd5e1; border-radius: 10px; background: #f8fafc; font-size: 14px; color: #1e293b; box-sizing: border-box;">
                                    <option value="UPI" {{ old('payment_method')=='UPI'?'selected':'' }}>UPI (PhonePe / GPay / Paytm)</option>
                                    <option value="QR Code" {{ old('payment_method')=='QR Code'?'selected':'' }}>QR Code Scan</option>
                                    <option value="Bank Transfer" {{ old('payment_method')=='Bank Transfer'?'selected':'' }}>Bank Transfer (NEFT/IMPS/RTGS)</option>
                                    <option value="Other" {{ old('payment_method')=='Other'?'selected':'' }}>Other</option>
                                </select>
                                @error('payment_method') <p style="color: #ef4444; font-size: 12px; margin: 4px 0 0 0;">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Transaction ID -->
                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Transaction ID / UTR Number <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="transaction_id" required value="{{ old('transaction_id') }}"
                                style="width: 100%; padding: 11px 14px; border: 1px solid #cbd5e1; border-radius: 10px; background: #f8fafc; font-size: 14px; font-family: monospace; color: #1e293b; box-sizing: border-box;"
                                placeholder="UPI reference, bank UTR, or transaction number">
                            @error('transaction_id') <p style="color: #ef4444; font-size: 12px; margin: 4px 0 0 0;">{{ $message }}</p> @enderror
                        </div>

                        <!-- File Proof Upload -->
                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Upload Payment Screenshot Proof <span style="color: #ef4444;">*</span></label>
                            
                            <style>
                                #payment_screenshot::file-selector-button {
                                    margin-right: 14px;
                                    border-radius: 8px;
                                    border: none;
                                    background-color: #2563eb;
                                    padding: 8px 16px;
                                    font-size: 13px;
                                    font-weight: 700;
                                    color: white;
                                    cursor: pointer;
                                    transition: background-color 0.2s;
                                }
                                #payment_screenshot::file-selector-button:hover {
                                    background-color: #1d4ed8;
                                }
                            </style>

                            <div style="border: 2px dashed #cbd5e1; border-radius: 14px; background: #f8fafc; padding: 20px; text-align: center;">
                                <input id="payment_screenshot" type="file" name="payment_screenshot" accept=".jpg,.jpeg,.png,.pdf" required
                                    style="width: 100%; font-size: 13px; color: #64748b; cursor: pointer;">
                            </div>
                            @error('payment_screenshot') <p style="color: #ef4444; font-size: 12px; margin: 4px 0 0 0;">{{ $message }}</p> @enderror
                        </div>

                        <!-- Manual Submit Button -->
                        <button type="submit"
                            style="width: 100%; background: #0f172a; color: #ffffff; padding: 16px; border-radius: 12px; border: none; font-size: 14px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; cursor: pointer; box-shadow: 0 6px 16px rgba(15,23,42,0.2); transition: all 0.2s ease; margin-top: 6px;">
                            Submit Payment Proof
                        </button>
                    </form>
                </div>

            </div>

            <!-- Right: Fixed Sidebar with Constrained QR Card -->
            <div style="width: 380px; max-width: 100%; flex-shrink: 0; box-sizing: border-box;">
                
                <div style="background: #ffffff; border-radius: 20px; border: 1px solid #e2e8f0; box-shadow: 0 10px 30px rgba(0,0,0,0.06); padding: 26px; box-sizing: border-box; text-align: center;">
                    
                    <!-- QR Box with Strict Dimension Bounds -->
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 16px; margin: 0 auto 20px auto; max-width: 280px; box-sizing: border-box;">
                        @if(isset($paymentSetting) && $paymentSetting->qr_code_url)
                            <div style="width: 100%; max-width: 240px; margin: 0 auto; background: #ffffff; border-radius: 12px; padding: 8px; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.06); box-sizing: border-box;">
                                <img src="{{ $paymentSetting->qr_code_url }}" alt="Payment QR Code"
                                    style="width: 100%; max-width: 220px; height: auto; max-height: 220px; object-fit: contain; display: block; margin: 0 auto; border-radius: 8px;">
                            </div>
                            <p style="margin: 12px 0 2px 0; font-size: 11px; font-weight: 900; color: #2563eb; text-transform: uppercase; letter-spacing: 1.5px;">Scan & Pay</p>
                            <p style="margin: 0; font-size: 11px; color: #64748b;">PhonePe, Google Pay, Paytm, BHIM</p>
                        @else
                            <div style="padding: 36px 12px; color: #94a3b8; font-size: 13px; font-weight: 600;">
                                Official QR code will appear here after admin upload.
                            </div>
                        @endif
                    </div>

                    <!-- Instructions -->
                    <div style="text-align: left; border-top: 1px solid #f1f5f9; pt: 16px; margin-top: 16px;">
                        <h3 style="font-size: 14px; font-weight: 800; color: #0f172a; margin: 0 0 10px 0;">Payment Instructions</h3>
                        <p style="font-size: 13px; color: #64748b; line-height: 1.6; margin: 0; white-space: pre-line;">{{ !empty($paymentSetting->instructions) ? $paymentSetting->instructions : "1. Pay the publication charges using instant online gateway or QR scan.\n2. Ensure author details and manuscript title match.\n3. Digital receipt is generated automatically." }}</p>
                    </div>

                </div>

                <!-- Support Box -->
                <div style="margin-top: 20px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 16px; padding: 18px; font-size: 12px; color: #1e3a8a; line-height: 1.5; box-sizing: border-box;">
                    <strong style="display: block; margin-bottom: 4px; font-size: 13px;">Need Payment Assistance?</strong>
                    For invoice requests, international SWIFT wires, or APC waivers, reach out to our editorial office at <a href="mailto:support@hjparam.com" style="color: #2563eb; font-weight: 700; text-decoration: underline;">support@hjparam.com</a>.
                </div>

            </div>

        </div>
    </div>
</div>

<!-- Include Razorpay Checkout SDK -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
var currencySymbol = "{{ $currencySym }}";
var journalFee = {{ (float) $journalFee }};
var conferenceFee = {{ (float) $conferenceFee }};

function switchPaymentMode(mode) {
    var secOnline = document.getElementById('section-online');
    var secManual = document.getElementById('section-manual');
    var btnOnline = document.getElementById('tab-btn-online');
    var btnManual = document.getElementById('tab-btn-manual');

    if (mode === 'online') {
        if (secOnline) secOnline.style.display = 'block';
        if (secManual) secManual.style.display = 'none';
        if (btnOnline) {
            btnOnline.style.background = '#ffffff';
            btnOnline.style.color = '#2563eb';
            btnOnline.style.boxShadow = '0 2px 6px rgba(0,0,0,0.08)';
        }
        if (btnManual) {
            btnManual.style.background = 'transparent';
            btnManual.style.color = '#64748b';
            btnManual.style.boxShadow = 'none';
        }
    } else {
        if (secOnline) secOnline.style.display = 'none';
        if (secManual) secManual.style.display = 'block';
        if (btnManual) {
            btnManual.style.background = '#ffffff';
            btnManual.style.color = '#2563eb';
            btnManual.style.boxShadow = '0 2px 6px rgba(0,0,0,0.08)';
        }
        if (btnOnline) {
            btnOnline.style.background = 'transparent';
            btnOnline.style.color = '#64748b';
            btnOnline.style.boxShadow = 'none';
        }
    }
}

function toggleFeeType(type, mode) {
    var journalCard = document.getElementById(mode + '-card-journal');
    var confCard = document.getElementById(mode + '-card-conference');
    var titleLabel = document.getElementById(mode + '-title-label');
    var titleInput = document.getElementById(mode + '_paper_title') || (mode === 'manual' ? document.querySelector('input[name="paper_title"]') : null);

    var currentFee = (type === 'conference') ? conferenceFee : journalFee;
    var formattedFee = currentFee.toFixed(2);

    var onlineAmtInput = document.getElementById('online_amount');
    var manualAmtInput = document.getElementById('manual_amount');
    var btnAmountSpan = document.getElementById('btn-online-amount');
    var onlineAmtLabel = document.getElementById('online-amount-label');
    var manualAmtLabel = document.getElementById('manual-amount-label');
    var onlineFeeNote = document.getElementById('online-fee-note');

    if (onlineAmtInput) onlineAmtInput.value = formattedFee;
    if (manualAmtInput) manualAmtInput.value = formattedFee;
    if (btnAmountSpan) btnAmountSpan.innerText = currencySymbol + formattedFee;

    if (type === 'journal') {
        if (journalCard) {
            journalCard.style.borderColor = '#2563eb';
            journalCard.style.background = '#eff6ff';
            var t = journalCard.querySelector('div > div:first-child');
            var s = journalCard.querySelector('div > div:last-child');
            if (t) t.style.color = '#1e3a8a';
            if (s) s.style.color = '#3b82f6';
        }
        if (confCard) {
            confCard.style.borderColor = '#e2e8f0';
            confCard.style.background = '#f8fafc';
            var t2 = confCard.querySelector('div > div:first-child');
            var s2 = confCard.querySelector('div > div:last-child');
            if (t2) t2.style.color = '#1e293b';
            if (s2) s2.style.color = '#64748b';
        }
        if (titleLabel) {
            titleLabel.innerHTML = 'Manuscript / Paper Title <span style="color: #ef4444;">*</span>';
        }
        if (titleInput) {
            titleInput.placeholder = 'Full title of submitted manuscript';
        }
        if (onlineAmtLabel) {
            onlineAmtLabel.innerHTML = 'Journal Fee Amount ({{ $paymentSetting->currency ?? "INR" }}) <span style="color: #ef4444;">*</span>';
        }
        if (manualAmtLabel) {
            manualAmtLabel.innerHTML = 'Journal Fee Amount ({{ $paymentSetting->currency ?? "INR" }}) <span style="color: #ef4444;">*</span>';
        }
        if (onlineFeeNote) {
            onlineFeeNote.innerText = 'Standard article processing charge (APC) for peer-reviewed journal publication.';
        }
    } else {
        if (confCard) {
            confCard.style.borderColor = '#2563eb';
            confCard.style.background = '#eff6ff';
            var t2 = confCard.querySelector('div > div:first-child');
            var s2 = confCard.querySelector('div > div:last-child');
            if (t2) t2.style.color = '#1e3a8a';
            if (s2) s2.style.color = '#3b82f6';
        }
        if (journalCard) {
            journalCard.style.borderColor = '#e2e8f0';
            journalCard.style.background = '#f8fafc';
            var t = journalCard.querySelector('div > div:first-child');
            var s = journalCard.querySelector('div > div:last-child');
            if (t) t.style.color = '#1e293b';
            if (s) s.style.color = '#64748b';
        }
        if (titleLabel) {
            titleLabel.innerHTML = 'Conference Paper / Presentation Title <span style="color: #ef4444;">*</span>';
        }
        if (titleInput) {
            titleInput.placeholder = 'Full title of accepted conference paper or presentation';
        }
        if (onlineAmtLabel) {
            onlineAmtLabel.innerHTML = 'Conference Fee Amount ({{ $paymentSetting->currency ?? "INR" }}) <span style="color: #ef4444;">*</span>';
        }
        if (manualAmtLabel) {
            manualAmtLabel.innerHTML = 'Conference Fee Amount ({{ $paymentSetting->currency ?? "INR" }}) <span style="color: #ef4444;">*</span>';
        }
        if (onlineFeeNote) {
            onlineFeeNote.innerText = 'Official delegate & presentation registration fee for accepted conference submissions.';
        }
    }
}

function updateOnlineBtnAmount(val) {
    var num = parseFloat(val) || 0;
    var el = document.getElementById('btn-online-amount');
    if (el) el.innerText = currencySymbol + num.toFixed(2);
}

function showPaymentAlert(msg, isError) {
    var alertBox = document.getElementById('payment-alert');
    if (!alertBox) return;
    alertBox.style.display = 'block';
    if (isError) {
        alertBox.style.backgroundColor = '#fef2f2';
        alertBox.style.borderColor = '#fecaca';
        alertBox.style.color = '#991b1b';
    } else {
        alertBox.style.backgroundColor = '#ecfdf5';
        alertBox.style.borderColor = '#6ee7b7';
        alertBox.style.color = '#065f46';
    }
    alertBox.innerText = msg;
    alertBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

async function handleOnlinePaymentSubmit(e) {
    e.preventDefault();
    
    var btn = document.getElementById('btn-pay-online');
    var originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = 'Connecting to Payment Gateway...';

    var selectedFeeType = (document.querySelector('input[name="online_fee_type"]:checked') || {}).value || 'journal';

    var payload = {
        _token: '{{ csrf_token() }}',
        fee_type: selectedFeeType,
        full_name: document.getElementById('online_full_name').value.trim(),
        email: document.getElementById('online_email').value.trim(),
        mobile_number: document.getElementById('online_mobile').value.trim(),
        affiliation: document.getElementById('online_affiliation').value.trim(),
        paper_title: document.getElementById('online_paper_title').value.trim(),
        payment_amount: document.getElementById('online_amount').value.trim(),
    };

    try {
        var response = await fetch('{{ route("payments.online.initiate") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        });

        var data = await response.json();

        if (!response.ok || !data.success) {
            throw new Error(data.message || 'Unable to connect to online gateway.');
        }

        if (data.provider === 'razorpay') {
            var options = {
                key: data.key_id,
                amount: data.amount,
                currency: data.currency,
                name: data.name,
                description: data.description,
                order_id: data.order_id || undefined,
                prefill: data.prefill,
                theme: {
                    color: '#2563eb'
                },
                handler: async function(razorpayResponse) {
                    btn.innerHTML = '✓ Verifying Payment & Generating Receipt...';
                    
                    var verifyPayload = Object.assign({}, payload, {
                        gateway_payment_id: razorpayResponse.razorpay_payment_id,
                        gateway_order_id: razorpayResponse.razorpay_order_id || data.order_id || null,
                        gateway_signature: razorpayResponse.razorpay_signature || null
                    });

                    try {
                        var verifyRes = await fetch('{{ route("payments.online.verify") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(verifyPayload)
                        });

                        var verifyData = await verifyRes.json();

                        if (verifyRes.ok && verifyData.success) {
                            window.location.href = verifyData.redirect_url;
                        } else {
                            throw new Error(verifyData.message || 'Payment verification failed.');
                        }
                    } catch (vErr) {
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                        showPaymentAlert(vErr.message, true);
                    }
                },
                modal: {
                    ondismiss: function() {
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                    }
                }
            };

            var rzp = new Razorpay(options);
            rzp.on('payment.failed', function(resp) {
                btn.disabled = false;
                btn.innerHTML = originalText;
                showPaymentAlert('Payment failed: ' + (resp.error ? resp.error.description : 'Transaction cancelled.'), true);
            });
            rzp.open();
        } 
        else if (data.provider === 'link' && data.payment_link) {
            window.location.href = data.payment_link;
        } else {
            throw new Error('Payment gateway configuration is incomplete.');
        }

    } catch (err) {
        btn.disabled = false;
        btn.innerHTML = originalText;
        showPaymentAlert(err.message, true);
    }
}
</script>
@endsection
