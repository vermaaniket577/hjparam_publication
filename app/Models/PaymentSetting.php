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
    ];

    protected $casts = [
        'default_amount' => 'decimal:2',
    ];

    public static function current(): self
    {
        return self::firstOrCreate(
            ['id' => 1],
            [
                'instructions' => 'Scan QR code to pay publication fees. After payment, submit the transaction reference and screenshot for verification.',
                'default_amount' => 0,
            ]
        );
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
}
