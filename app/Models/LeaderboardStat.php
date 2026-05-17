<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaderboardStat extends Model
{
    //
    protected $fillable = [
        'user_id',
        'type',
        'period',
        'total_amount_or_count',
        'last_updated_at'
    ];

    protected $casts = [
        'last_updated_at' => 'datetime',
        'total_amount_or_count' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
