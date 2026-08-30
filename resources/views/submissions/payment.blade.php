@extends('layouts.web')
@section('title', 'Pay Registration Fee | ' . $submission->title)

@section('content')
<div class="bg-slate-50 min-h-screen py-12 md:py-16">
    <div class="container mx-auto px-4 max-w-3xl">
        
        <div class="mb-6">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-blue-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Dashboard
            </a>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-600 text-white rounded-2xl mb-6 text-xs md:text-sm font-bold shadow-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-3xl p-8 md:p-10 shadow-xl border border-slate-100 space-y-8">
            <div class="border-b border-slate-100 pb-5">
                <span class="px-3 py-1 bg-purple-50 text-purple-700 text-[10px] font-black uppercase tracking-widest rounded-full border border-purple-100">
                    Step 4: Registration Fee Payment
                </span>
                <h1 class="text-2xl md:text-3xl font-serif font-black text-slate-900 mt-3 leading-tight">
                    Submit Registration Fee Details
                </h1>
                <p class="text-slate-500 text-xs md:text-sm mt-1">Paper: <strong>{{ $submission->title }}</strong></p>
            </div>

            <!-- Fee Breakdown Reference for this Conference -->
            @if($submission->conference)
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-3">
                    <span class="block text-xs font-bold text-slate-800 uppercase tracking-wider">Conference Fee Reference</span>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                        <div class="p-3 bg-white rounded-xl border border-slate-200/60">
                            <span class="text-[10px] text-slate-400 font-bold block">Attendee</span>
                            <span class="font-bold text-slate-800">{{ $submission->conference->fee_attendee ?? '$100 / ₹2,500' }}</span>
                        </div>
                        <div class="p-3 bg-white rounded-xl border border-slate-200/60">
                            <span class="text-[10px] text-slate-400 font-bold block">Presentation</span>
                            <span class="font-bold text-blue-600">{{ $submission->conference->fee_presentation ?? '$200 / ₹4,500' }}</span>
                        </div>
                        <div class="p-3 bg-white rounded-xl border border-slate-200/60">
                            <span class="text-[10px] text-slate-400 font-bold block">Publication</span>
                            <span class="font-bold text-emerald-600">{{ $submission->conference->fee_publication ?? '$300 / ₹7,000' }}</span>
                        </div>
                        <div class="p-3 bg-white rounded-xl border border-slate-200/60">
                            <span class="text-[10px] text-slate-400 font-bold block">Extra Certificate</span>
                            <span class="font-bold text-purple-600">{{ $submission->conference->fee_extra_certificate ?? '$30 / ₹800' }}</span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Current Payment Status -->
            @if($submission->payment_status === 'verified')
                <div class="p-5 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-xs font-bold flex items-center gap-3">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <p class="text-sm">Payment Verified by Administration!</p>
                        <p class="font-normal text-emerald-700 mt-0.5">Your presentation slot and conference links have been confirmed.</p>
                    </div>
                </div>
            @endif

            <form action="{{ route('submissions.payment.store', $submission) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Select Fee Category <span class="text-red-500">*</span></label>
                    <select name="payment_fee_type" required
                            class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-xs font-bold text-slate-800 focus:bg-white focus:border-blue-600">
                        <option value="presentation" {{ old('payment_fee_type', $submission->payment_fee_type) == 'presentation' ? 'selected' : '' }}>Presentation Fee (Author Presentation)</option>
                        <option value="attendee" {{ old('payment_fee_type', $submission->payment_fee_type) == 'attendee' ? 'selected' : '' }}>Only Attendee Fee</option>
                        <option value="publication" {{ old('payment_fee_type', $submission->payment_fee_type) == 'publication' ? 'selected' : '' }}>Journal Publication & Presentation Fee</option>
                        <option value="extra_certificate" {{ old('payment_fee_type', $submission->payment_fee_type) == 'extra_certificate' ? 'selected' : '' }}>Extra Certificate Fee</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Amount Paid <span class="text-red-500">*</span></label>
                        <input type="text" name="payment_amount" value="{{ old('payment_amount', $submission->payment_amount) }}" required placeholder="e.g. $200 or ₹4,500"
                               class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-xs font-bold text-slate-800 focus:bg-white focus:border-blue-600">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Payment Date <span class="text-red-500">*</span></label>
                        <input type="date" name="payment_date" value="{{ old('payment_date', $submission->payment_date?->format('Y-m-d') ?? date('Y-m-d')) }}" required
                               class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-xs font-bold text-slate-800 focus:bg-white focus:border-blue-600">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Transaction ID / UTR / Reference No. <span class="text-red-500">*</span></label>
                    <input type="text" name="payment_transaction_id" value="{{ old('payment_transaction_id', $submission->payment_transaction_id) }}" required placeholder="e.g. TXN-94829482948 / UPI-Ref-002934"
                           class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-xs font-mono font-bold text-slate-800 focus:bg-white focus:border-blue-600">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Upload Payment Receipt / Proof <span class="text-red-500">*</span></label>
                    @if($submission->payment_receipt_url)
                        <div class="mb-2 p-3 bg-slate-50 rounded-xl border border-slate-200 flex items-center justify-between text-xs">
                            <span class="text-slate-600 font-medium">Uploaded Receipt: {{ basename($submission->payment_receipt_path) }}</span>
                            <a href="{{ $submission->payment_receipt_url }}" target="_blank" class="text-blue-600 font-bold underline">View Proof</a>
                        </div>
                    @endif
                    <input type="file" name="payment_receipt" accept=".pdf,.jpg,.jpeg,.png" {{ $submission->payment_receipt_path ? '' : 'required' }}
                           class="w-full text-xs text-slate-500 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 cursor-pointer">
                    <p class="text-[10px] text-slate-400 mt-1.5">PDF, PNG, JPG receipt screenshot or bank acknowledgment (Max 10MB)</p>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-4 bg-purple-600 hover:bg-purple-700 text-white font-black rounded-2xl text-xs uppercase tracking-widest shadow-xl shadow-purple-600/30 transition-all hover:scale-[1.01]">
                        Submit Fee Verification Proof
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
