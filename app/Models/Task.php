<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'points',
        'admin_profit',
        'quota_max',
        'quota_remaining',
        'cooldown_hours',
        'type',
        'requires_text_proof',
        'requires_image_proof',
        'external_link',
        'settings',
        'secret_code',
        'is_active',
        'is_optional',
    ];

    protected $casts = [
        'settings' => 'json',
        'is_active' => 'boolean',
        'is_optional' => 'boolean',
    ];

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}
