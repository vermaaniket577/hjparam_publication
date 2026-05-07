@extends('layouts.web')

@section('content')
<div style="background:#f8fafc; padding:60px 0; font-family:'Inter',sans-serif;">
    <div style="max-width:1200px; margin:0 auto; padding:0 24px;">

        <div style="margin-bottom:40px;">
            <p style="color:#2563eb; font-weight:900; font-size:11px; text-transform:uppercase; letter-spacing:3px; margin:0 0 8px 0;">Publication Fee Payment</p>
            <h1 style="font-size:38px; color:#0f172a; font-weight:900; margin:0 0 12px 0; letter-spacing:-1px;">Submit Payment Details</h1>
            <p style="color:#64748b; font-size:15px; margin:0; max-width:600px; line-height:1.6;">Share your transaction details after paying the journal publication fee. Our editorial office will verify the proof and update your payment status.</p>
        </div>

        @if(session('success'))
            <div style="background:#ecfdf5; border:1px solid #6ee7b7; border-radius:12px; padding:16px 20px; color:#065f46; margin-bottom:24px; font-weight:600;">
                {{ session('success') }}
            </div>
        @endif

        <div style="display:flex; gap:32px; align-items:flex-start; flex-wrap:wrap;">

            {{-- ===== MAIN FORM ===== --}}
            <div style="flex:1; min-width:320px; background:white; border-radius:20px; border:1px solid #e2e8f0; box-shadow:0 10px 25px rgba(0,0,0,0.06); padding:40px;">
                <form action="{{ route('payments.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Row 1: Full Name & Email --}}
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px;">
                        <div>
                            <label style="display:block; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">Full Name <span style="color:#ef4444;">*</span></label>
                            <input type="text" name="full_name" required value="{{ old('full_name') }}"
                                style="width:100%; padding:12px 16px; border:1px solid #e2e8f0; border-radius:12px; background:#f8fafc; font-size:14px; color:#1e293b; box-sizing:border-box;"
                                placeholder="Author full name">
                            @error('full_name')<p style="color:#ef4444; font-size:12px; margin-top:4px;">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label style="display:block; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">Email Address <span style="color:#ef4444;">*</span></label>
                            <input type="email" name="email" required value="{{ old('email') }}"
                                style="width:100%; padding:12px 16px; border:1px solid #e2e8f0; border-radius:12px; background:#f8fafc; font-size:14px; color:#1e293b; box-sizing:border-box;"
                                placeholder="name@example.com">
                            @error('email')<p style="color:#ef4444; font-size:12px; margin-top:4px;">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- Row 2: Mobile & Affiliation --}}
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px;">
                        <div>
                            <label style="display:block; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">Mobile Number <span style="color:#ef4444;">*</span></label>
                            <input type="text" name="mobile_number" required value="{{ old('mobile_number') }}"
                                style="width:100%; padding:12px 16px; border:1px solid #e2e8f0; border-radius:12px; background:#f8fafc; font-size:14px; color:#1e293b; box-sizing:border-box;"
                                placeholder="+91 9876543210">
                            @error('mobile_number')<p style="color:#ef4444; font-size:12px; margin-top:4px;">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label style="display:block; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">Affiliation</label>
                            <input type="text" name="affiliation" value="{{ old('affiliation') }}"
                                style="width:100%; padding:12px 16px; border:1px solid #e2e8f0; border-radius:12px; background:#f8fafc; font-size:14px; color:#1e293b; box-sizing:border-box;"
                                placeholder="College / University / Organization">
                        </div>
                    </div>

                    {{-- Paper Title --}}
                    <div style="margin-bottom:20px;">
                        <label style="display:block; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">Paper Title <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="paper_title" required value="{{ old('paper_title') }}"
                            style="width:100%; padding:12px 16px; border:1px solid #e2e8f0; border-radius:12px; background:#f8fafc; font-size:14px; color:#1e293b; box-sizing:border-box;"
                            placeholder="Full title of submitted manuscript">
                        @error('paper_title')<p style="color:#ef4444; font-size:12px; margin-top:4px;">{{ $message }}</p>@enderror
                    </div>

                    {{-- Row 3: Amount & Method --}}
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px;">
                        <div>
                            <label style="display:block; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">Payment Amount <span style="color:#ef4444;">*</span></label>
                            <input type="number" step="0.01" min="1" name="payment_amount" required
                                value="{{ old('payment_amount', isset($paymentSetting) ? $paymentSetting->default_amount : '') }}"
                                style="width:100%; padding:12px 16px; border:1px solid #e2e8f0; border-radius:12px; background:#f8fafc; font-size:14px; color:#1e293b; box-sizing:border-box;"
                                placeholder="Enter amount">
                            @error('payment_amount')<p style="color:#ef4444; font-size:12px; margin-top:4px;">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label style="display:block; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">Payment Method <span style="color:#ef4444;">*</span></label>
                            <select name="payment_method" required
                                style="width:100%; padding:12px 16px; border:1px solid #e2e8f0; border-radius:12px; background:#f8fafc; font-size:14px; color:#1e293b; box-sizing:border-box;">
                                <option value="">Select method...</option>
                                <option value="UPI" {{ old('payment_method')=='UPI'?'selected':'' }}>UPI</option>
                                <option value="QR Code" {{ old('payment_method')=='QR Code'?'selected':'' }}>QR Code</option>
                                <option value="Bank Transfer" {{ old('payment_method')=='Bank Transfer'?'selected':'' }}>Bank Transfer</option>
                            </select>
                            @error('payment_method')<p style="color:#ef4444; font-size:12px; margin-top:4px;">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- Transaction ID --}}
                    <div style="margin-bottom:20px;">
                        <label style="display:block; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">Transaction ID / Reference Number <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="transaction_id" required value="{{ old('transaction_id') }}"
                            style="width:100%; padding:12px 16px; border:1px solid #e2e8f0; border-radius:12px; background:#f8fafc; font-size:14px; color:#1e293b; box-sizing:border-box;"
                            placeholder="UPI reference, bank UTR, or transaction number">
                        @error('transaction_id')<p style="color:#ef4444; font-size:12px; margin-top:4px;">{{ $message }}</p>@enderror
                    </div>

                    {{-- Upload Screenshot --}}
                    <div style="margin-bottom:32px;">
                        <label style="display:block; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">Upload Payment Screenshot <span style="color:#ef4444;">*</span></label>
                        
                        {{-- WE MUST USE A STYLE BLOCK TO STYLE THE CHOOSE FILE BUTTON. INLINE STYLES DO NOT WORK FOR FILE BUTTONS --}}
                        <style>
                            #payment_screenshot::file-selector-button {
                                margin-right: 16px;
                                border-radius: 9999px;
                                border: none;
                                background-color: #2563eb; /* blue-600 */
                                padding: 10px 20px;
                                font-size: 14px;
                                font-weight: 700;
                                color: white;
                                cursor: pointer;
                                transition: background-color 0.2s;
                            }
                            #payment_screenshot::file-selector-button:hover {
                                background-color: #1d4ed8; /* blue-700 */
                            }
                        </style>

                        <div style="border:2px dashed #e2e8f0; border-radius:16px; background:#f8fafc; padding:24px; text-align:center;">
                            <input id="payment_screenshot" type="file" name="payment_screenshot" accept=".jpg,.jpeg,.png,.pdf" required
                                style="width:100%; font-size:14px; color:#64748b; cursor:pointer;">
                        </div>
                        @error('payment_screenshot')<p style="color:#ef4444; font-size:12px; margin-top:4px;">{{ $message }}</p>@enderror
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit"
                        style="display:block; width:100%; background:linear-gradient(to right, #1d4ed8, #059669); color:white; padding:18px; border-radius:14px; border:none; font-size:14px; font-weight:900; text-transform:uppercase; letter-spacing:2px; cursor:pointer; box-shadow:0 8px 20px rgba(37,99,235,0.3);">
                        Submit Payment Details
                    </button>
                </form>
            </div>

            {{-- ===== SIDEBAR ===== --}}
            <div style="width:360px; flex-shrink:0;">
                <div style="background:white; border-radius:20px; border:1px solid #e2e8f0; box-shadow:0 10px 25px rgba(0,0,0,0.06); padding:30px;">
                    
                    <div style="background:#eff6ff; border:1px solid #dbeafe; border-radius:16px; padding:24px; text-align:center;">
                        @if(isset($paymentSetting) && $paymentSetting->qr_code_url)
                            <img src="{{ $paymentSetting->qr_code_url }}" alt="Payment QR Code"
                                style="width:100%; max-width:240px; border-radius:12px; background:white; padding:12px; box-shadow:0 2px 8px rgba(0,0,0,0.06);">
                            <p style="margin-top:12px; font-size:11px; font-weight:900; color:#2563eb; text-transform:uppercase; letter-spacing:2px;">Scan to Pay</p>
                        @else
                            <div style="padding:40px 20px; color:#94a3b8; font-size:13px; font-weight:600;">
                                QR code will appear here after admin upload.
                            </div>
                        @endif
                    </div>

                    <h2 style="font-size:18px; font-weight:900; color:#0f172a; margin:24px 0 10px 0;">Payment Instructions</h2>
                    <p style="font-size:14px; color:#64748b; line-height:1.7; margin:0; white-space:pre-line;">{{ !empty($paymentSetting->instructions) ? $paymentSetting->instructions : 'Scan QR code to pay and upload your transaction reference for verification.' }}</p>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
