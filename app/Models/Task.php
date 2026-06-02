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
        'requires_email_proof',
        'external_link',
        'instruction_images',
        'settings',
        'secret_code',
        'is_active',
        'is_optional',
    ];

    protected $casts = [
        'settings'             => 'json',
        'instruction_images'   => 'array',
        'is_active'            => 'boolean',
        'is_optional'          => 'boolean',
        'requires_text_proof'  => 'boolean',
        'requires_image_proof' => 'boolean',
        'requires_email_proof' => 'boolean',
    ];

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}
