@extends('layouts.admin')

@section('title', 'Payment Management')
@section('breadcrumb', 'Payments')

@section('content')
    <div class="max-w-7xl mx-auto space-y-8">
        @if(session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-emerald-800 font-medium">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-red-800 font-medium">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-[420px_1fr] gap-8 items-start">
            <section class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <h2 class="text-xl font-black text-slate-900 dark:text-white">QR Code & Instructions</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">These details appear on the public payment form.</p>

                <div class="my-6 rounded-2xl border border-slate-100 bg-slate-50 p-5 text-center">
                    @if($paymentSetting->qr_code_url)
                        <img src="{{ $paymentSetting->qr_code_url }}" alt="Payment QR Code"
                            class="mx-auto aspect-square w-full max-w-[240px] rounded-xl bg-white object-contain p-3 shadow-sm">
                    @else
                        <div class="mx-auto flex aspect-square w-full max-w-[240px] items-center justify-center rounded-xl border border-dashed border-slate-300 bg-white text-sm text-slate-400">
                            No QR uploaded
                        </div>
                    @endif
                </div>

                <form action="{{ route('admin.payments.settings') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Default Amount</label>
                        <input type="number" step="0.01" min="0" name="default_amount"
                            value="{{ old('default_amount', $paymentSetting->default_amount) }}"
                            class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-slate-800 focus:border-blue-500 focus:ring-blue-100">
                        @error('default_amount') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Payment Instructions</label>
                        <textarea name="instructions" rows="5"
                            class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-slate-800 focus:border-blue-500 focus:ring-blue-100">{{ old('instructions', $paymentSetting->instructions) }}</textarea>
                        @error('instructions') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Upload / Update QR Code</label>
                        <input type="file" name="qr_code" accept=".jpg,.jpeg,.png,.webp"
                            class="block w-full text-sm text-slate-600 file:mr-4 file:rounded-full file:border-0 file:bg-blue-600 file:px-5 file:py-2.5 file:text-sm file:font-bold file:text-white hover:file:bg-blue-700">
                        @error('qr_code') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="btn-loading-trigger w-full rounded-xl bg-blue-700 px-5 py-3 text-xs font-black uppercase tracking-widest text-white hover:bg-blue-800">
                        Save Payment Settings
                    </button>
                </form>
            </section>

            <section class="space-y-5">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-black text-slate-900 dark:text-white">Payment Submissions</h1>
                        <p class="text-sm text-slate-500">Review author payment proofs and update verification status.</p>
                    </div>
                    <a href="{{ route('admin.payments.export', request()->query()) }}"
                        class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-5 py-3 text-xs font-black uppercase tracking-widest text-white hover:bg-emerald-700">
                        Export CSV
                    </a>
                </div>

                <form action="{{ route('admin.payments.index') }}" method="GET"
                    class="grid grid-cols-1 md:grid-cols-5 gap-3 rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Name, email, title, transaction"
                        class="md:col-span-2 rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm">
                    <select name="status" class="rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm">
                        <option value="">All Status</option>
                        @foreach(['pending', 'approved', 'rejected'] as $status)
                            <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm">
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm">
                    <button class="md:col-span-5 rounded-xl bg-slate-900 px-5 py-3 text-xs font-black uppercase tracking-widest text-white hover:bg-slate-800">
                        Search / Filter
                    </button>
                </form>

                <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Author</th>
                                    <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Paper / Payment</th>
                                    <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Status</th>
                                    <th class="px-5 py-3 text-right text-xs font-bold uppercase tracking-wider text-slate-500">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($payments as $payment)
                                    <tr class="align-top hover:bg-slate-50/60">
                                        <td class="px-5 py-4">
                                            <p class="font-bold text-slate-900">{{ $payment->full_name }}</p>
                                            <p class="text-xs text-slate-500">{{ $payment->email }}</p>
                                            <p class="text-xs text-slate-500">{{ $payment->mobile_number }}</p>
                                            <p class="text-xs text-slate-400 mt-1">{{ $payment->affiliation ?: 'No affiliation' }}</p>
                                        </td>
                                        <td class="px-5 py-4">
                                            <p class="font-semibold text-slate-800">{{ Str::limit($payment->paper_title, 70) }}</p>
                                            <p class="text-xs text-slate-500 mt-1">
                                                {{ $payment->payment_method }} · {{ number_format($payment->payment_amount, 2) }}
                                            </p>
                                            <p class="text-xs font-mono text-slate-500 mt-1">Ref: {{ $payment->transaction_id }}</p>
                                            <p class="text-xs text-slate-400 mt-1">{{ $payment->created_at->format('M d, Y h:i A') }}</p>
                                        </td>
                                        <td class="px-5 py-4">
                                            @php
                                                $statusClass = [
                                                    'pending' => 'bg-amber-100 text-amber-800',
                                                    'approved' => 'bg-emerald-100 text-emerald-800',
                                                    'rejected' => 'bg-red-100 text-red-800',
                                                ][$payment->status] ?? 'bg-slate-100 text-slate-700';
                                            @endphp
                                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-black uppercase tracking-wider {{ $statusClass }}">
                                                {{ $payment->status }}
                                            </span>
                                            @if($payment->admin_note)
                                                <p class="mt-2 text-xs text-slate-500">{{ $payment->admin_note }}</p>
                                            @endif
                                        </td>
                                        <td class="px-5 py-4 text-right">
                                            <div class="flex flex-col items-end gap-3">
                                                <a href="{{ route('admin.payments.screenshot', $payment) }}"
                                                    class="text-xs font-bold text-blue-600 hover:text-blue-800">
                                                    Download Screenshot
                                                </a>
                                                <form action="{{ route('admin.payments.status', $payment) }}" method="POST"
                                                    class="flex flex-col sm:flex-row gap-2">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="text" name="admin_note" placeholder="Optional note"
                                                        class="w-44 rounded-lg border-slate-200 bg-slate-50 px-3 py-2 text-xs">
                                                    <select name="status" class="rounded-lg border-slate-200 bg-slate-50 px-3 py-2 text-xs">
                                                        @foreach(['pending', 'approved', 'rejected'] as $status)
                                                            <option value="{{ $status }}" {{ $payment->status === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                                        @endforeach
                                                    </select>
                                                    <button class="btn-loading-trigger rounded-lg bg-slate-900 px-3 py-2 text-xs font-bold text-white hover:bg-slate-800">
                                                        Save
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-5 py-12 text-center text-slate-500">No payment submissions found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="border-t border-slate-100 bg-slate-50 px-5 py-4">
                        {{ $payments->links() }}
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
