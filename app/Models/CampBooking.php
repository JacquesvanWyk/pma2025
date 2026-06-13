<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CampBooking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'accommodation_type_id',
        'name',
        'email',
        'phone',
        'adults',
        'children',
        'nights',
        'arrival_date',
        'departure_date',
        'estimated_total',
        'deposit_amount',
        'deposit_paid',
        'deposit_paid_at',
        'proof_of_payment',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'adults' => 'integer',
            'children' => 'integer',
            'nights' => 'integer',
            'estimated_total' => 'decimal:2',
            'deposit_amount' => 'decimal:2',
            'deposit_paid' => 'boolean',
            'deposit_paid_at' => 'datetime',
            'arrival_date' => 'date',
            'departure_date' => 'date',
        ];
    }

    public function accommodationType(): BelongsTo
    {
        return $this->belongsTo(AccommodationType::class);
    }

    public function recalculateTotals(): void
    {
        if (! $this->accommodationType) {
            return;
        }

        $this->estimated_total = $this->accommodationType->priceFor(
            $this->adults,
            $this->children,
            $this->nights,
        );

        $this->deposit_amount = round(
            (float) $this->estimated_total * config('camp.deposit_percentage', 0.5),
            2
        );
    }

    public function eftReference(): string
    {
        $surname = strtoupper(explode(' ', trim($this->name))[1] ?? $this->name);

        return 'CAMP-'.$this->id.'-'.$surname;
    }
}
