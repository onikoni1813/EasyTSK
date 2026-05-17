<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'is_moderator',
        'is_sub_admin',
        'sub_admin_permissions',
        'points',
        'total_earned_bdt',
        'locked_points',
        'pending_points',
        'referral_code',
        'referred_by',
        'bkash_number',
        'nagad_number',
        'rocket_number',
        'full_name',
        'trust_score',
        'total_admin_profit_generated',
        'moderator_balance',
        'is_referral_unlocked',
        'moderator_earnings_bdt',
        'total_reviews_done',
        'kyc_status',
        'kyc_notes',
        'id_number',
        'id_front_path',
        'id_back_path',
        'facebook_link',
        'telegram_username',
        'is_banned',
        'ban_reason',
        'last_ip',
        'country',
        'total_earned_lifetime',
        'device_fingerprint',
        'registration_ip',
        'mobile_number',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
        'first_earning_at',
        'first_earning_event_fired',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at'            => 'datetime',
            'password'                     => 'hashed',
            'is_admin'                     => 'boolean',
            'is_moderator'                 => 'boolean',
            'is_sub_admin'                 => 'boolean',
            'is_banned'                    => 'boolean',
            'is_referral_unlocked'         => 'boolean',
            'trust_score'                  => 'integer',
            'total_admin_profit_generated' => 'decimal:2',
            'sub_admin_permissions'        => 'array',
        ];
    }

    /**
     * Check if user can access a specific admin panel section.
     * Full admins always return true.
     */
    public function hasPermission(string $permission): bool
    {
        if ($this->is_admin) {
            return true;
        }
        if (! $this->is_sub_admin) {
            return false;
        }
        $perms = $this->sub_admin_permissions ?? [];
        return in_array($permission, $perms) || in_array('*', $perms);
    }

    /** Friendly label for role display */
    public function getRoleLabelAttribute(): string
    {
        if ($this->is_admin)     return 'Full Admin';
        if ($this->is_sub_admin) return 'Sub Admin';
        if ($this->is_moderator) return 'Moderator';
        return 'User';
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    /** Convert points balance to BDT based on admin rate */
    public function getBalanceInBdtAttribute(): string
    {
        $rate = (int) setting('point_conversion_rate', 100);

        return number_format($this->points / $rate, 2);
    }

    /** Pending points as BDT */
    public function getPendingInBdtAttribute(): string
    {
        $rate = (int) setting('point_conversion_rate', 100);

        return number_format($this->pending_points / $rate, 2);
    }

    /** Total withdrawn in BDT */
    public function getTotalWithdrawnBdtAttribute(): float
    {
        return (float) $this->withdrawals()->where('status', 'approved')->sum('amount_bdt');
    }

    /** Masked name for public display: Ras*** */
    public function getMaskedNameAttribute(): string
    {
        $name = $this->full_name ?: $this->name;
        if (strlen($name) <= 3) {
            return $name.'***';
        }

        return mb_substr($name, 0, 3).'***';
    }

    // ─── Relationships ─────────────────────────────────────────────────────────

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    public function referralRecord()
    {
        return $this->hasOne(Referral::class, 'referred_user_id');
    }

    public function referralsMade()
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function userNotifications()
    {
        return $this->hasMany(UserNotification::class)->latest();
    }

    public function unreadUserNotifications()
    {
        return $this->hasMany(UserNotification::class)->where('is_read', false);
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_banned', false)->where('is_admin', false);
    }

    // ─── Lifecycle ───────────────────────────────────────────────────────────

    protected static function booted()
    {
        static::creating(function ($user) {
            $user->referral_code = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
            $user->trust_score = 100;
            $user->registration_ip = request()->ip();
            $user->last_ip = request()->ip();
        });

        // NOTE: last_ip is now updated ONLY on login (AuthenticatedSessionController)
        // NOT on every model update — prevents admin IPs from overwriting user's last_ip
    }
}
