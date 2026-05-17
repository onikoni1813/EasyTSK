<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdsterraCode extends Model
{
    protected $fillable = [
        'user_id',
        'task_id',
        'code',
        'is_used',
        'expires_at',
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function isExpired(): bool
    {
        return now()->greaterThan($this->expires_at);
    }
}
