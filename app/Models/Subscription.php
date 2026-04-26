<?php

namespace App\Models;

use App\Enums\UserSubscriptionStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    public $table = 'subscriptions';

    protected $fillable = [
        'user_id',
        'plan_id',
        'price',
        'start_at',
        'end_at',
        'status',
    ];

    protected $casts = [
        'start_at' => 'date',
        'end_at' => 'date',
        'price' => 'double',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function isActive(): bool
    {
        return $this->status === UserSubscriptionStatus::ACTIVE
            && now()->isBetween($this->start_at, $this->end_at);
    }

    public function scopeActive(Builder $query)
    {
        return $query->where('status', UserSubscriptionStatus::ACTIVE)
            ->whereDate('start_at', '<=', now())
            ->whereDate('end_at', '>=', now());
    }
}
