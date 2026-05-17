<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'amount_points',
        'amount_bdt',
        'receivable_amount',
        'fee_amount',
        'admin_profit',
        'type',
        'reference_id',
        'description',
        'task_id',
        'user_reward',
        'source',
        'provider',
        'external_transaction_id',
        'timewall_transaction_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
