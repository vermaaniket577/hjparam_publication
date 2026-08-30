<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PaymentSetting extends Model
{
    protected $fillable = [
        'qr_code_path',
        'instructions',
        'default_amount',
        'journal_fee_amount',
        'conference_fee_amount',
        'online_payment_enabled',
        'gateway_provider',
        'currency',
        'gateway_key_id',
        'gateway_key_secret',
        'gateway_merchant_id',
        'gateway_payment_link',
        'gateway_instructions',
    ];

    protected $casts = [
        'default_amount' => 'decimal:2',
        'journal_fee_amount' => 'decimal:2',
        'conference_fee_amount' => 'decimal:2',
        'online_payment_enabled' => 'boolean',
    ];

    public function getJournalFeeAttribute(): float
    {
        return (float) ($this->journal_fee_amount ?: ($this->default_amount ?: 1500));
    }

    public function getConferenceFeeAttribute(): float
    {
        return (float) ($this->conference_fee_amount ?: 2500);
    }

    public static function current(): self
    {
        // Auto-run migrations if online gateway columns are missing on live server
        if (\Illuminate\Support\Facades\Schema::hasTable('payment_settings') && !\Illuminate\Support\Facades\Schema::hasColumn('payment_settings', 'online_payment_enabled')) {
            try {
                \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            } catch (\Throwable $e) {
                \Log::warning('PaymentSetting auto-migration notice: ' . $e->getMessage());
            }
        }

        return self::firstOrCreate(
            ['id' => 1],
            [
                'instructions' => 'Scan QR code to pay publication fees. After payment, submit the transaction reference and screenshot for verification.',
                'default_amount' => 1500,
                'journal_fee_amount' => 1500,
                'conference_fee_amount' => 2500,
                'online_payment_enabled' => true,
                'gateway_provider' => 'razorpay',
                'currency' => 'INR',
                'gateway_instructions' => 'Pay seamlessly using Credit/Debit Cards, Net Banking, UPI (GPay, PhonePe, Paytm), or Wallets via secure online payment gateway.',
            ]
        );
    }

    public function getGatewayKeyIdAttribute($value): ?string
    {
        return !empty($value) ? $value : config('services.razorpay.key');
    }

    public function getGatewayKeySecretAttribute($value): ?string
    {
        return !empty($value) ? $value : config('services.razorpay.secret');
    }

    public function getQrCodeUrlAttribute(): ?string
    {
        if (!$this->qr_code_path) {
            return null;
        }

        // If running locally (e.g. 127.0.0.1 or localhost), use standard Laravel symlink path
        if (str_contains(request()->getHost(), '127.0.0.1') || str_contains(request()->getHost(), 'localhost')) {
            return \Illuminate\Support\Facades\Storage::disk('public')->url($this->qr_code_path);
        }

        // On live shared hosting without symlinks, the files are physically located at /storage/app/public/
        return asset('storage/app/public/' . $this->qr_code_path);
    }

    public function isOnlineConfigured(): bool
    {
        if (!$this->online_payment_enabled) {
            return false;
        }

        return !empty($this->gateway_key_id) || !empty($this->gateway_payment_link);
    }

    public function getCurrencySymbolAttribute(): string
    {
        return match (strtoupper($this->currency ?? 'INR')) {
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'AED' => 'AED ',
            default => '₹',
        };
    }
}
