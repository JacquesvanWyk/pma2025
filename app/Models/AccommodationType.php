<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccommodationType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'base_price',
        'base_adults',
        'extra_adult_price',
        'extra_child_price',
        'max_adults',
        'max_children',
        'total_units',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'base_price' => 'decimal:2',
            'extra_adult_price' => 'decimal:2',
            'extra_child_price' => 'decimal:2',
            'base_adults' => 'integer',
            'max_adults' => 'integer',
            'max_children' => 'integer',
            'total_units' => 'integer',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(CampBooking::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function priceFor(int $adults, int $children, int $nights): float
    {
        $extraAdults = max(0, $adults - $this->base_adults);
        $nightRate = (float) $this->base_price
            + ($extraAdults * (float) ($this->extra_adult_price ?? 0))
            + ($children * (float) ($this->extra_child_price ?? 0));

        return round($nightRate * $nights, 2);
    }

    public function depositFor(int $adults, int $children, int $nights): float
    {
        return round($this->priceFor($adults, $children, $nights) * config('camp.deposit_percentage', 0.5), 2);
    }

    public function confirmedBookingsCount(): int
    {
        return $this->bookings()->whereIn('status', ['pending', 'confirmed'])->count();
    }

    public function availableUnits(): ?int
    {
        if ($this->total_units === null) {
            return null;
        }

        return max(0, $this->total_units - $this->confirmedBookingsCount());
    }

    public function isFull(): bool
    {
        if ($this->total_units === null) {
            return false;
        }

        return $this->confirmedBookingsCount() >= $this->total_units;
    }
}
