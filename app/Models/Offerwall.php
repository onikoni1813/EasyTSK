<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Offerwall extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'display_name',
        'description',
        'icon_emoji',
        'color',
        'is_active',
        'sort_order',
        'iframe_url_template',
        'widget_script',
        'display_mode',
        'api_key',
        'secret_key',
        'postback_method',
        'security_type',
        'security_field',
        'hmac_fields',
        'ip_whitelist',
        'field_user_id',
        'field_reward',
        'field_transaction_id',
        'field_campaign_id',
        'reward_type',
        'conversion_rate',
        'platform_share_pct',
        'requires_task_lock',
    ];

    protected function casts(): array
    {
        return [
            'is_active'          => 'boolean',
            'requires_task_lock' => 'boolean',
            'hmac_fields'        => 'array',
            'ip_whitelist'       => 'array',
            'conversion_rate'    => 'float',
            'platform_share_pct' => 'float',
            'sort_order'         => 'integer',
        ];
    }

    // ─── Auto-generate slug on create ────────────────────────────────────────

    protected static function booted(): void
    {
        static::creating(function (self $offerwall) {
            if (empty($offerwall->slug)) {
                $offerwall->slug = Str::slug($offerwall->name);
            }
            // Ensure uniqueness
            $original = $offerwall->slug;
            $counter = 1;
            while (static::where('slug', $offerwall->slug)->exists()) {
                $offerwall->slug = $original . '-' . $counter++;
            }
        });
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Generate the postback URL for this offerwall.
     * Admin copies this URL to the ad network's dashboard.
     */
    public function getPostbackUrl(): string
    {
        return url("/api/postback/{$this->slug}");
    }

    /**
     * Build the user-specific iframe URL by replacing placeholders.
     * Supported placeholders: {user_id}, {api_key}
     */
    public function getIframeUrl(User $user): ?string
    {
        if (empty($this->iframe_url_template)) {
            return null;
        }

        return str_replace(
            ['{user_id}', '{USER_ID}', '[user_id]', '[USER_ID]', '{api_key}'],
            [$user->id, $user->id, $user->id, $user->id, $this->api_key ?? ''],
            $this->iframe_url_template
        );
    }

    // ─── Relationships ───────────────────────────────────────────────────────

    public function postbackLogs()
    {
        return $this->hasMany(PostbackLog::class);
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
