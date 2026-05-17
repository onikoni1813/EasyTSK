<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawalMethod extends Model
{
    protected $fillable = [
        'name',
        'label',
        'icon_emoji',
        'color_class',
        'is_active',
        'min_amount',
        'charge_type',
        'charge_value',
        'account_label',
        'account_placeholder',
        'account_min_length',
        'account_max_length',
        'sort_order',
        'instructions',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'min_amount'  => 'decimal:2',
        'charge_value' => 'decimal:2',
    ];

    /** Only active methods, sorted */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    /** Human-readable charge label */
    public function getChargeLabelAttribute(): string
    {
        if ($this->charge_value <= 0) {
            return 'ফ্রি';
        }
        return $this->charge_type === 'fixed'
            ? '৳ ' . number_format((float) $this->charge_value, 2) . ' ফ্ল্যাট'
            : $this->charge_value . '%';
    }

    /** Calculate fee for a given amount */
    public function calculateFee(float $amount): float
    {
        if ($this->charge_value <= 0) return 0;
        return $this->charge_type === 'fixed'
            ? (float) $this->charge_value
            : round($amount * ($this->charge_value / 100), 2);
    }
}
