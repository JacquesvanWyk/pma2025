<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmailList extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function subscribers(): HasMany
    {
        return $this->hasMany(EmailSubscriber::class);
    }

    public function activeSubscribers(): HasMany
    {
        return $this->hasMany(EmailSubscriber::class)->whereNull('unsubscribed_at');
    }
}
