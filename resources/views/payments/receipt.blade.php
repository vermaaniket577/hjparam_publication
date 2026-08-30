@extends('layouts.web')

@section('title', 'Payment Receipt - ' . $payment->transaction_id)

@section('content')
<div class="bg-slate-50 min-h-[75vh] py-12 px-4 sm:px-6">
    <div class="max-w-3xl mx-auto">
        
        <!-- Action Buttons -->
        <div class="flex items-center justify-between gap-4 mb-6 print:hidden">
            <a href="{{ route('home') }}" class="inline-flex items-center text-xs font-bold uppercase tracking-wider text-slate-600 hover:text-blue-600 transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Home
            </a>
            <div class="flex items-center gap-3">
                <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 shadow-sm transition cursor-pointer">
                    <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print Receipt
                </button>
                <a href="{{ route('author.submit') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-md shadow-blue-500/20 transition">
                    Submit Manuscript
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-800 text-sm font-medium flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Printable Receipt Card -->
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-200 overflow-hidden print:shadow-none print:border-none">
            
            <!-- Receipt Header Banner -->
            <div class="bg-gradient-to-r from-blue-900 via-blue-800 to-indigo-900 p-8 text-white relative overflow-hidden">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 relative z-10">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-black uppercase tracking-widest text-blue-200">Official Acknowledgment</span>
                            <span class="text-blue-300">•</span>
                            <span class="text-xs text-blue-200 font-mono">REC-{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-serif font-bold tracking-tight">Publication Fee Receipt</h1>
                        <p class="text-xs sm:text-sm text-blue-100/90 mt-1">HJPARAM Open Access Academic Publication</p>
                    </div>

                    <div class="text-left sm:text-right">
                        @if($payment->status === 'approved')
                            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-black uppercase tracking-wider bg-emerald-500 text-white shadow-sm">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                                Payment Verified / Confirmed
                            </span>
                        @elseif($payment->status === 'pending')
                            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-black uppercase tracking-wider bg-amber-400 text-slate-900 shadow-sm">
                                <svg class="w-3.5 h-3.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                                Verification in Progress
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-3.5 py-1.5 rounded-full text-xs font-black uppercase tracking-wider bg-red-500 text-white">
                                Rejected
                            </span>
                        @endif
                        <p class="text-[11px] text-blue-200 mt-2">{{ $payment->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                </div>
            </div>

            <!-- Receipt Content -->
            <div class="p-6 sm:p-8 space-y-6">
                
                <!-- Amount Summary Box -->
                <div class="bg-blue-50/60 rounded-2xl border border-blue-100 p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Amount Paid</span>
                        <div class="text-3xl font-black text-slate-900 flex items-baseline gap-1 mt-0.5">
                            <span class="text-blue-600">{{ $payment->currency_symbol ?? '₹' }}</span>
                            <span>{{ number_format($payment->payment_amount, 2) }}</span>
                            <span class="text-xs font-bold text-slate-500 uppercase ml-1">{{ $payment->currency ?? 'INR' }}</span>
                        </div>
                    </div>
                    <div class="text-left sm:text-right">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Payment Method</span>
                        <p class="text-sm font-bold text-slate-800 mt-0.5">{{ $payment->payment_method }}</p>
                        <p class="text-xs font-mono text-slate-500 mt-0.5">Ref: {{ $payment->transaction_id }}</p>
                    </div>
                </div>

                <!-- Transaction Details Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Author & Payer Information</h4>
                        <div class="space-y-2">
                            <div>
                                <span class="text-xs text-slate-500 block">Author Name</span>
                                <span class="font-bold text-slate-900">{{ $payment->full_name }}</span>
                            </div>
                            <div>
                                <span class="text-xs text-slate-500 block">Email Address</span>
                                <span class="font-semibold text-slate-800">{{ $payment->email }}</span>
                            </div>
                            <div>
                                <span class="text-xs text-slate-500 block">Mobile Number</span>
                                <span class="font-semibold text-slate-800">{{ $payment->mobile_number }}</span>
                            </div>
                            <div>
                                <span class="text-xs text-slate-500 block">Affiliation / Institution</span>
                                <span class="text-slate-700">{{ $payment->affiliation ?: 'Not provided' }}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Publication & Fee Details</h4>
                        <div class="space-y-2">
                            <div>
                                <span class="text-xs text-slate-500 block">Fee Category</span>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-black uppercase tracking-wider {{ ($payment->fee_type ?? 'journal') === 'conference' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ ($payment->fee_type ?? 'journal') === 'conference' ? '🏛️ Conference Fees' : '📖 Journal Fees (APC)' }}
                                </span>
                            </div>
                            <div>
                                <span class="text-xs text-slate-500 block">{{ ($payment->fee_type ?? 'journal') === 'conference' ? 'Conference Paper / Presentation Title' : 'Manuscript Title' }}</span>
                                <span class="font-bold text-slate-900 leading-snug block">{{ $payment->paper_title }}</span>
                            </div>
                            <div>
                                <span class="text-xs text-slate-500 block">Transaction Reference</span>
                                <span class="font-mono text-xs font-bold text-blue-700 break-all">{{ $payment->transaction_id }}</span>
                            </div>
                            @if($payment->gateway_order_id)
                                <div>
                                    <span class="text-xs text-slate-500 block">Gateway Order ID</span>
                                    <span class="font-mono text-xs text-slate-600">{{ $payment->gateway_order_id }}</span>
                                </div>
                            @endif
                            @if($payment->admin_note)
                                <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200">
                                    <span class="text-[11px] font-bold text-slate-500 block uppercase tracking-wider">Editorial Office Note</span>
                                    <span class="text-xs text-slate-700">{{ $payment->admin_note }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Footer Notice -->
                <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
                    <p>This is a computer-generated publication receipt from HJPARAM Publication.</p>
                    <p class="font-mono">Date: {{ now()->format('Y-m-d H:i:s') }}</p>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
