<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $fillable = [
        'gateway',
        'transaction_reference',
        'amount',
        'currency',
        'donor_email',
        'donor_name',
        'status',
        'is_recurring',
        'item_name',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'is_recurring' => 'boolean',
            'metadata' => 'array',
        ];
    }
}
