<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    protected $fillable = [
        'referrer_id',
        'referred_user_id',
        'profit_generated',
        'status',
        'bonus_points',
        'unlock_threshold',
        'unlocked_at',
    ];

    protected $casts = [
        'unlocked_at' => 'datetime',
        'profit_generated' => 'decimal:2',
    ];

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function referredUser()
    {
        return $this->belongsTo(User::class, 'referred_user_id');
    }

    public function isLocked(): bool
    {
        return $this->status === 'Locked';
    }

    public function progressPercent(): float
    {
        if (! $this->referredUser) {
            return 0;
        }

        if ($this->unlock_threshold <= 0) {
            return 100;
        }

        return min(100, round(($this->referredUser->total_earned_lifetime / $this->unlock_threshold) * 100, 1));
    }
}
