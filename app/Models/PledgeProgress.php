<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PledgeProgress extends Model
{
    protected $table = 'pledge_progress';

    protected $fillable = [
        'current_amount',
        'month',
        'goal_amount',
    ];

    protected function casts(): array
    {
        return [
            'current_amount' => 'decimal:2',
            'goal_amount' => 'decimal:2',
        ];
    }

    public static function current(): ?self
    {
        return self::latest()->first();
    }

    public function getPercentageAttribute(): float
    {
        if ($this->goal_amount == 0) {
            return 0;
        }

        return round(($this->current_amount / $this->goal_amount) * 100, 2);
    }
}
