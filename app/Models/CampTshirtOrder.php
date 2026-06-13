<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CampTshirtOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'size',
        'quantity',
        'unit_price',
        'donation_amount',
        'total',
        'payment_status',
        'transaction_reference',
        'lines',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'unit_price' => 'decimal:2',
            'donation_amount' => 'decimal:2',
            'total' => 'decimal:2',
            'lines' => 'array',
            'metadata' => 'array',
        ];
    }

    public function markPaid(string $transactionReference, array $metadata = []): void
    {
        $this->update([
            'payment_status' => 'paid',
            'transaction_reference' => $transactionReference,
            'metadata' => $metadata,
        ]);
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }
}
