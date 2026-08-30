@extends('layouts.admin')

@section('title', 'Payment & Gateway Management')
@section('breadcrumb', 'Payments')

@section('content')
<div class="max-w-7xl mx-auto space-y-6" x-data="{ activeTab: '{{ request()->has('settings') ? 'settings' : 'transactions' }}' }">
    
    <!-- Top Bar Alerts -->
    @if(session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-emerald-800 font-medium flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-red-800 font-medium">
            {{ session('error') }}
        </div>
    @endif

    <!-- Metric Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-slate-100 dark:border-gray-700 shadow-sm">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Total Payments</span>
            <div class="text-2xl font-black text-slate-900 dark:text-white">{{ number_format($stats['total_count'] ?? 0) }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-slate-100 dark:border-gray-700 shadow-sm">
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 block mb-1">Verified / Approved</span>
            <div class="text-2xl font-black text-emerald-600">{{ number_format($stats['approved_count'] ?? 0) }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-slate-100 dark:border-gray-700 shadow-sm">
            <span class="text-xs font-bold uppercase tracking-wider text-amber-500 block mb-1">Pending Verification</span>
            <div class="text-2xl font-black text-amber-500">{{ number_format($stats['pending_count'] ?? 0) }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-slate-100 dark:border-gray-700 shadow-sm">
            <span class="text-xs font-bold uppercase tracking-wider text-blue-600 block mb-1">Total Revenue</span>
            <div class="text-2xl font-black text-blue-600">{{ $paymentSetting->currency_symbol ?? '₹' }}{{ number_format($stats['total_revenue'] ?? 0, 2) }}</div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex items-center gap-2 border-b border-slate-200 dark:border-gray-700 pb-2">
        <button type="button" @click="activeTab = 'transactions'"
            :class="activeTab === 'transactions' ? 'bg-blue-600 text-white font-bold shadow-md shadow-blue-500/20' : 'bg-white dark:bg-gray-800 text-slate-600 hover:text-slate-900 border border-slate-200'"
            class="px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition flex items-center gap-2 cursor-pointer">
            <span>💳 Payment Transactions ({{ $stats['total_count'] ?? 0 }})</span>
        </button>
        <button type="button" @click="activeTab = 'settings'"
            :class="activeTab === 'settings' ? 'bg-blue-600 text-white font-bold shadow-md shadow-blue-500/20' : 'bg-white dark:bg-gray-800 text-slate-600 hover:text-slate-900 border border-slate-200'"
            class="px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition flex items-center gap-2 cursor-pointer">
            <span>⚙️ Gateway & QR Settings</span>
        </button>
    </div>

    <!-- TAB 1: TRANSACTIONS & PAYMENT RECORDS (DEFAULT / PRIMARY) -->
    <div x-show="activeTab === 'transactions'" class="space-y-6">

        @if($selectedPayment ?? null)
            <!-- Highlighted Specific Payment Banner (When viewed directly from Submissions) -->
            <div class="p-6 rounded-3xl border-2 border-blue-400 bg-blue-50/70 shadow-md space-y-4">
                <div class="flex flex-wrap items-center justify-between gap-3 pb-3 border-b border-blue-200">
                    <div class="flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold text-xs">
                            #{{ $selectedPayment->id }}
                        </span>
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Manuscript Payment Record</h3>
                            <p class="text-xs text-slate-500">Submitted on {{ $selectedPayment->created_at->format('M d, Y - h:i A') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        @php
                            $selStatusClass = [
                                'pending' => 'bg-amber-100 text-amber-900 border-amber-300',
                                'approved' => 'bg-emerald-100 text-emerald-900 border-emerald-300',
                                'rejected' => 'bg-rose-100 text-rose-900 border-rose-300',
                            ][$selectedPayment->status] ?? 'bg-slate-100 text-slate-700';
                        @endphp
                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-black uppercase tracking-wider border {{ $selStatusClass }}">
                            Status: {{ $selectedPayment->status }}
                        </span>
                        <span class="text-base font-black text-blue-700 ml-2">
                            {{ $selectedPayment->currency_symbol }}{{ number_format($selectedPayment->payment_amount, 2) }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                    <div class="p-3 bg-white rounded-xl border border-blue-100">
                        <span class="text-slate-400 font-bold block uppercase text-[10px]">Author / Payer</span>
                        <span class="font-bold text-slate-800 text-sm">{{ $selectedPayment->full_name }}</span>
                        <span class="text-slate-500 block">{{ $selectedPayment->email }}</span>
                        <span class="text-slate-500 block">{{ $selectedPayment->mobile_number }}</span>
                    </div>
                    <div class="p-3 bg-white rounded-xl border border-blue-100">
                        <span class="text-slate-400 font-bold block uppercase text-[10px]">Manuscript Title</span>
                        <span class="font-bold text-slate-800 line-clamp-2" title="{{ $selectedPayment->paper_title }}">{{ $selectedPayment->paper_title }}</span>
                        <span class="text-slate-500 text-[11px] block mt-1">{{ $selectedPayment->affiliation ?: 'No affiliation' }}</span>
                    </div>
                    <div class="p-3 bg-white rounded-xl border border-blue-100">
                        <span class="text-slate-400 font-bold block uppercase text-[10px]">Method & Transaction ID</span>
                        <span class="font-bold text-slate-800">{{ $selectedPayment->payment_method }}</span>
                        <span class="font-mono text-slate-600 text-[11px] block break-all">Ref: {{ $selectedPayment->gateway_payment_id ?? $selectedPayment->transaction_id }}</span>
                    </div>
                    <div class="p-3 bg-white rounded-xl border border-blue-100 flex flex-col justify-between">
                        <span class="text-slate-400 font-bold block uppercase text-[10px]">Screenshot / Proof</span>
                        @if($selectedPayment->screenshot_path)
                            <a href="{{ route('admin.payments.screenshot', $selectedPayment) }}"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-lg transition mt-2">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                <span>Download Proof</span>
                            </a>
                        @else
                            <span class="text-slate-400 italic text-xs mt-2">⚡ Online Automated Receipt</span>
                        @endif
                    </div>
                </div>

                @if($selectedPayment->status === 'pending')
                    <div class="pt-3 border-t border-blue-200 flex items-center justify-between">
                        <span class="text-xs text-blue-900 font-medium">Verify the transaction and click to approve or decline:</span>
                        <div class="flex items-center gap-2">
                            <form action="{{ route('admin.payments.status', $selectedPayment) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition shadow-sm cursor-pointer">
                                    ✓ Approve Payment
                                </button>
                            </form>
                            <form action="{{ route('admin.payments.status', $selectedPayment) }}" method="POST" class="inline" onsubmit="return confirm('Decline this payment?');">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition shadow-sm cursor-pointer">
                                    ✕ Decline
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-black text-slate-900 dark:text-white">Payment Submissions & Receipts</h2>
                <p class="text-xs text-slate-500">Track all online gateway payments and verify manual author QR proofs.</p>
            </div>
            <a href="{{ route('admin.payments.export', request()->query()) }}"
                class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-emerald-600 px-4 py-2 text-xs font-bold uppercase tracking-wider text-white hover:bg-emerald-700 shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export CSV
            </a>
        </div>

        <!-- Search & Filters -->
        <form action="{{ route('admin.payments.index') }}" method="GET"
            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3 rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Author, email, title, Txn ID..."
                class="sm:col-span-2 md:col-span-2 rounded-xl border-slate-200 bg-slate-50 px-3.5 py-2 text-xs">
            
            <select name="fee_type" class="rounded-xl border-slate-200 bg-slate-50 px-3 py-2 text-xs">
                <option value="">All Categories</option>
                <option value="journal" {{ request('fee_type') === 'journal' ? 'selected' : '' }}>📖 Journal Fees</option>
                <option value="conference" {{ request('fee_type') === 'conference' ? 'selected' : '' }}>🏛️ Conference Fees</option>
            </select>

            <select name="status" class="rounded-xl border-slate-200 bg-slate-50 px-3 py-2 text-xs">
                <option value="">All Status</option>
                @foreach(['pending', 'approved', 'rejected'] as $status)
                    <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                @endforeach
            </select>

            <select name="payment_type" class="rounded-xl border-slate-200 bg-slate-50 px-3 py-2 text-xs">
                <option value="">All Methods</option>
                <option value="online" {{ request('payment_type') === 'online' ? 'selected' : '' }}>⚡ Online Gateway</option>
                <option value="manual" {{ request('payment_type') === 'manual' ? 'selected' : '' }}>📷 Manual / QR Scan</option>
            </select>

            <div class="sm:col-span-2 md:col-span-5 flex items-center justify-between gap-3 pt-2 border-t border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="text-[11px] text-slate-400 uppercase font-bold">Date:</span>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="rounded-lg border-slate-200 bg-slate-50 px-2.5 py-1 text-xs">
                    <span class="text-xs text-slate-400">to</span>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="rounded-lg border-slate-200 bg-slate-50 px-2.5 py-1 text-xs">
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.payments.index') }}" class="px-3 py-1.5 text-xs text-slate-500 hover:text-slate-700">Clear</a>
                    <button type="submit" class="rounded-xl bg-slate-900 px-4 py-1.5 text-xs font-bold text-white hover:bg-slate-800 cursor-pointer">
                        Apply Filters
                    </button>
                </div>
            </div>
        </form>

        <!-- Table -->
        <div class="overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Author & Manuscript</th>
                            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Amount & Method</th>
                            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Status</th>
                            <th class="px-5 py-3.5 text-right text-[11px] font-bold uppercase tracking-wider text-slate-500">Verification Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @forelse($payments as $payment)
                            <tr class="align-top hover:bg-slate-50/60 transition-colors {{ (isset($selectedPayment) && $selectedPayment->id === $payment->id) ? 'bg-blue-50/40' : '' }}">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-2 mb-0.5">
                                        <span class="font-bold text-slate-900 text-sm">{{ $payment->full_name }}</span>
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ ($payment->fee_type ?? 'journal') === 'conference' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ ($payment->fee_type ?? 'journal') === 'conference' ? 'Conference' : 'Journal' }}
                                        </span>
                                    </div>
                                    <p class="text-slate-500">{{ $payment->email }} · {{ $payment->mobile_number }}</p>
                                    <p class="font-medium text-slate-700 mt-1 line-clamp-1" title="{{ $payment->paper_title }}">
                                        📄 {{ $payment->paper_title }}
                                    </p>
                                    <p class="text-slate-400 text-[11px] mt-0.5">{{ $payment->affiliation ?: 'No affiliation' }}</p>
                                </td>
                                
                                <td class="px-5 py-4">
                                    <div class="text-sm font-black text-slate-900">
                                        {{ $payment->currency_symbol ?? '₹' }}{{ number_format($payment->payment_amount, 2) }}
                                    </div>
                                    <div class="mt-1">
                                        @if($payment->isOnline())
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                ⚡ {{ $payment->payment_method }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                                📷 {{ $payment->payment_method }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="font-mono text-[11px] text-slate-500 mt-1 break-all">ID: {{ $payment->gateway_payment_id ?? $payment->transaction_id }}</p>
                                    <p class="text-slate-400 text-[10px]">{{ $payment->created_at->format('M d, Y h:i A') }}</p>
                                </td>

                                <td class="px-5 py-4">
                                    @php
                                        $statusClass = [
                                            'pending' => 'bg-amber-100 text-amber-800 border-amber-200',
                                            'approved' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                            'rejected' => 'bg-red-100 text-red-800 border-red-200',
                                        ][$payment->status] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                                    @endphp
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-[10px] font-black uppercase tracking-wider border {{ $statusClass }}">
                                        {{ $payment->status }}
                                    </span>
                                    @if($payment->admin_note)
                                        <p class="mt-1 text-[11px] text-slate-500 italic max-w-xs">{{ $payment->admin_note }}</p>
                                    @endif
                                </td>

                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($payment->screenshot_path)
                                            <a href="{{ route('admin.payments.screenshot', $payment) }}"
                                                class="rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-slate-700 hover:bg-slate-50 text-[11px] font-bold"
                                                title="Download Proof Screenshot">
                                                <span>Proof</span> &darr;
                                            </a>
                                        @endif

                                        @if($payment->status === 'pending')
                                            <form action="{{ route('admin.payments.status', $payment) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="rounded-lg bg-emerald-600 px-2.5 py-1 font-bold text-white hover:bg-emerald-700 text-[11px] cursor-pointer">
                                                    Approve
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.payments.status', $payment) }}" method="POST" class="inline" onsubmit="return confirm('Decline this payment submission?');">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="rounded-lg border border-rose-200 bg-rose-50 px-2 py-1 font-bold text-rose-700 hover:bg-rose-100 text-[11px] cursor-pointer">
                                                    Decline
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    <p class="font-bold text-slate-600 text-sm">No payment records found.</p>
                                    <p class="text-xs text-slate-400 mt-1">Author payments submitted online or via QR will appear here.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($payments->hasPages())
                <div class="border-t border-slate-100 p-4">
                    {{ $payments->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- TAB 2: GATEWAY & QR CODE SETTINGS -->
    <div x-show="activeTab === 'settings'" class="max-w-3xl mx-auto">
        <section class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 sm:p-8 space-y-6">
            <div class="border-b border-slate-100 dark:border-gray-700 pb-4">
                <h2 class="text-xl font-black text-slate-900 dark:text-white flex items-center gap-2">
                    <span>⚡ Payment Gateway Setup</span>
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Configure online payment gateways, API keys, and QR instructions for authors.</p>
            </div>

            <form action="{{ route('admin.payments.settings') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                <!-- Enable Online Gateway Switch -->
                <div class="p-4 rounded-2xl bg-blue-50/60 dark:bg-gray-900/50 border border-blue-100 dark:border-gray-700 flex items-center justify-between">
                    <div>
                        <span class="text-sm font-bold text-slate-900 dark:text-white block">Online Payment Gateway</span>
                        <span class="text-xs text-slate-500">Allow instant card/UPI payments online</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="online_payment_enabled" value="1" {{ $paymentSetting->online_payment_enabled ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <!-- Provider & Currency -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Gateway Provider</label>
                        <select name="gateway_provider" id="gateway_provider" class="w-full rounded-xl border-slate-200 bg-slate-50 px-3.5 py-2.5 text-xs text-slate-800 font-semibold focus:border-blue-500 focus:ring-blue-100">
                            <option value="razorpay" {{ $paymentSetting->gateway_provider === 'razorpay' ? 'selected' : '' }}>Razorpay (Cards, UPI, NetBanking)</option>
                            <option value="phonepe" {{ $paymentSetting->gateway_provider === 'phonepe' ? 'selected' : '' }}>PhonePe Gateway / Link</option>
                            <option value="stripe" {{ $paymentSetting->gateway_provider === 'stripe' ? 'selected' : '' }}>Stripe (Global Cards)</option>
                            <option value="paypal" {{ $paymentSetting->gateway_provider === 'paypal' ? 'selected' : '' }}>PayPal</option>
                            <option value="custom_link" {{ $paymentSetting->gateway_provider === 'custom_link' ? 'selected' : '' }}>Custom Payment Link</option>
                            <option value="upi_qr" {{ $paymentSetting->gateway_provider === 'upi_qr' ? 'selected' : '' }}>UPI Direct QR</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Currency</label>
                        <select name="currency" class="w-full rounded-xl border-slate-200 bg-slate-50 px-3.5 py-2.5 text-xs text-slate-800 font-semibold focus:border-blue-500 focus:ring-blue-100">
                            <option value="INR" {{ ($paymentSetting->currency ?? 'INR') === 'INR' ? 'selected' : '' }}>INR (₹) - Indian Rupee</option>
                            <option value="USD" {{ ($paymentSetting->currency ?? '') === 'USD' ? 'selected' : '' }}>USD ($) - US Dollar</option>
                            <option value="EUR" {{ ($paymentSetting->currency ?? '') === 'EUR' ? 'selected' : '' }}>EUR (€) - Euro</option>
                            <option value="GBP" {{ ($paymentSetting->currency ?? '') === 'GBP' ? 'selected' : '' }}>GBP (£) - British Pound</option>
                            <option value="AED" {{ ($paymentSetting->currency ?? '') === 'AED' ? 'selected' : '' }}>AED - UAE Dirham</option>
                        </select>
                    </div>
                </div>

                <!-- Gateway Key ID / API Key -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Gateway Key ID / Client ID</label>
                    <input type="text" name="gateway_key_id" value="{{ old('gateway_key_id', $paymentSetting->gateway_key_id) }}" placeholder="e.g. rzp_live_xxxxxxxx or publishable key"
                        class="w-full rounded-xl border-slate-200 bg-slate-50 px-3.5 py-2.5 text-xs text-slate-800 font-mono focus:border-blue-500 focus:ring-blue-100">
                    <p class="text-[11px] text-slate-400 mt-1">For Razorpay: Your Key ID from Razorpay Dashboard.</p>
                </div>

                <!-- Gateway Key Secret -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Gateway Key Secret / API Secret</label>
                    <input type="password" name="gateway_key_secret" value="{{ old('gateway_key_secret', $paymentSetting->gateway_key_secret) }}" placeholder="••••••••••••••••"
                        class="w-full rounded-xl border-slate-200 bg-slate-50 px-3.5 py-2.5 text-xs text-slate-800 font-mono focus:border-blue-500 focus:ring-blue-100">
                    <p class="text-[11px] text-slate-400 mt-1">Used for cryptographic signature verification of payments.</p>
                </div>

                <!-- Direct Payment Link (Optional) -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Direct Payment Link (Optional Fallback)</label>
                    <input type="url" name="gateway_payment_link" value="{{ old('gateway_payment_link', $paymentSetting->gateway_payment_link) }}" placeholder="https://rzp.io/l/... or payment URL"
                        class="w-full rounded-xl border-slate-200 bg-slate-50 px-3.5 py-2.5 text-xs text-slate-800 focus:border-blue-500 focus:ring-blue-100">
                </div>

                <!-- Fee Amounts Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">📖 Journal Fee (APC)</label>
                        <input type="number" step="0.01" min="0" name="journal_fee_amount"
                            value="{{ old('journal_fee_amount', $paymentSetting->journal_fee) }}"
                            class="w-full rounded-xl border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm font-bold text-slate-800 focus:border-blue-500 focus:ring-blue-100">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">🏛️ Conference Fee</label>
                        <input type="number" step="0.01" min="0" name="conference_fee_amount"
                            value="{{ old('conference_fee_amount', $paymentSetting->conference_fee) }}"
                            class="w-full rounded-xl border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm font-bold text-slate-800 focus:border-blue-500 focus:ring-blue-100">
                    </div>
                </div>

                <!-- Instructions -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Online Gateway Description</label>
                    <input type="text" name="gateway_instructions" value="{{ old('gateway_instructions', $paymentSetting->gateway_instructions) }}" placeholder="Instructions shown on online checkout"
                        class="w-full rounded-xl border-slate-200 bg-slate-50 px-3.5 py-2.5 text-xs text-slate-800 focus:border-blue-500 focus:ring-blue-100">
                </div>

                <!-- Offline QR Code & Manual Instructions -->
                <div class="pt-4 border-t border-slate-100 dark:border-gray-700 space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 dark:text-white">Offline QR Code & Bank Transfer</h3>
                    
                    <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4 text-center">
                        @if($paymentSetting->qr_code_url)
                            <img src="{{ $paymentSetting->qr_code_url }}" alt="Payment QR Code"
                                class="mx-auto aspect-square w-full max-w-[160px] rounded-xl bg-white object-contain p-2 shadow-sm border border-slate-100">
                        @else
                            <div class="mx-auto flex aspect-square w-full max-w-[160px] items-center justify-center rounded-xl border border-dashed border-slate-300 bg-white text-xs text-slate-400">
                                No QR Code Uploaded
                            </div>
                        @endif
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Upload New QR Code</label>
                        <input type="file" name="qr_code" accept=".jpg,.jpeg,.png,.webp"
                            class="block w-full text-xs text-slate-600 file:mr-3 file:rounded-xl file:border-0 file:bg-blue-600 file:px-4 file:py-2 file:text-xs file:font-bold file:text-white hover:file:bg-blue-700 cursor-pointer">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Offline Payment Instructions</label>
                        <textarea name="instructions" rows="3"
                            class="w-full rounded-xl border-slate-200 bg-slate-50 px-3.5 py-2.5 text-xs text-slate-800 focus:border-blue-500 focus:ring-blue-100">{{ old('instructions', $paymentSetting->instructions) }}</textarea>
                    </div>
                </div>

                <button type="submit" class="w-full rounded-xl bg-blue-600 hover:bg-blue-700 py-3.5 text-xs font-black uppercase tracking-widest text-white shadow-md shadow-blue-500/20 transition-all cursor-pointer">
                    Save Gateway & QR Settings
                </button>
            </form>
        </section>
    </div>
</div>
@endsection
