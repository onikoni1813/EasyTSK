<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'sort_order',
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
        'secret_code_count',
        'secret_codes',
        'image_proof_count',
        'is_active',
        'is_optional',
    ];

    protected $casts = [
        'sort_order'           => 'integer',
        'settings'             => 'json',
        'instruction_images'   => 'array',
        'secret_codes'         => 'array',
        'secret_code_count'    => 'integer',
        'image_proof_count'    => 'integer',
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

    /**
     * Check if this task is unlocked for a given user.
     *
     * Rules:
     *  - The task with the lowest sort_order is ALWAYS unlocked.
     *  - Any other task requires an APPROVED submission for the task
     *    immediately before it (by sort_order).
     */
    public function isUnlockedFor(User $user): bool
    {
        // First task in sequence — always open
        $firstOrder = static::where('is_active', true)->min('sort_order');
        if ($this->sort_order == $firstOrder) {
            return true;
        }

        // Find the task immediately before this one
        $prevTask = static::where('is_active', true)
            ->where('sort_order', '<', $this->sort_order)
            ->orderByDesc('sort_order')
            ->first();

        if (! $prevTask) {
            return true; // No previous task found — unlock by default
        }

        // User must have submitted (pending OR approved) for the previous task
        // Rejected submissions do NOT count — user must resubmit
        return Submission::where('user_id', $user->id)
            ->where('task_id', $prevTask->id)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();
    }
}
