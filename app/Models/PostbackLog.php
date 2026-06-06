<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostbackLog extends Model
{
    protected $fillable = [
        'offerwall_id',
        'slug',
        'user_id',
        'status',
        'ip_address',
        'reward_raw',
        'points_credited',
        'external_transaction_id',
        'error_message',
        'raw_payload',
    ];

    protected function casts(): array
    {
        return [
            'raw_payload'     => 'array',
            'reward_raw'      => 'float',
            'points_credited' => 'integer',
        ];
    }

    public function offerwall()
    {
        return $this->belongsTo(Offerwall::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
