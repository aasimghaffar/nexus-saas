<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use Billable, HasApiTokens, HasFactory, HasRoles, LogsActivity, Notifiable, TwoFactorAuthenticatable;

    protected $fillable = [
        'name',
        'company_name',
        'avatar_path',
        'phone',
        'designation',
        'notification_prefs',
        'email',
        'password',
        'suspended_at',
        'last_seen_at',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at'  => 'datetime',
            'suspended_at'       => 'datetime',
            'last_seen_at'       => 'datetime',
            'notification_prefs' => 'array',
            'password'           => 'hashed',
        ];
    }

    /* ---------------------------------------------------------- helpers */

    public function isSuspended(): bool
    {
        return $this->suspended_at !== null;
    }

    public function hasTwoFactorEnabled(): bool
    {
        return $this->two_factor_confirmed_at !== null;
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar_path) {
            return asset('storage/'.$this->avatar_path);
        }

        $n = ($this->id % 8) + 1;

        return asset("assets/images/avatars/avatar-{$n}.svg");
    }

    public function getRoleLabelAttribute(): string
    {
        $role = $this->getRoleNames()->first() ?? 'viewer';

        return config("nexus.roles.{$role}.label", ucfirst($role));
    }

    public function getRoleBadgeAttribute(): string
    {
        $role = $this->getRoleNames()->first() ?? 'viewer';

        return config("nexus.roles.{$role}.badge", config('nexus.roles.viewer.badge'));
    }

    /* ----------------------------------------------------- activity log */

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'company_name', 'phone', 'designation', 'suspended_at'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('users')
            ->setDescriptionForEvent(fn (string $event) => "User account {$event}");
    }
}
