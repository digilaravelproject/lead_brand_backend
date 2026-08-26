<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Dealer extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'phone_number',
        'alternative_phone_number',
        'email',
        'password',
        'login_password',
        'user_limit',
        'referral_code',
        'is_active',
        'subscription_started_at',
        'subscription_ends_at',
    ];

    protected $hidden = ['password', 'login_password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'login_password' => 'encrypted',
            'is_active' => 'boolean',
            'user_limit' => 'integer',
            'subscription_started_at' => 'datetime',
            'subscription_ends_at' => 'datetime',
        ];
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function remainingUserSlots(): int
    {
        return max(0, $this->user_limit - $this->users()->count());
    }

    public function hasSubscriptionAccess(): bool
    {
        $endsAt = $this->subscription_ends_at ?? $this->created_at?->copy()->addYear();

        return $this->is_active
            && $endsAt !== null
            && $endsAt->isFuture();
    }

    public function subscriptionStatus(): string
    {
        return $this->hasSubscriptionAccess() ? 'active' : 'expired';
    }
}
