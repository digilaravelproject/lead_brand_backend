<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'dealer_id',
        'name',
        'email',
        'google_id',
        'password',
        'otp',
        'otp_expires_at',
        'profile_photo',
        'phone_number',
        'destination',
        'logo',
        'language',
        'subscription_started_at',
        'subscription_ends_at',
        'approval_status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'otp',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'otp_expires_at' => 'datetime',
            'subscription_started_at' => 'datetime',
            'subscription_ends_at' => 'datetime',
        ];
    }

    /**
     * Get the leads created by this user.
     */
    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    public function dealer()
    {
        return $this->belongsTo(Dealer::class);
    }

    public function hasExpiredTrial(): bool
    {
        return $this->subscription_ends_at !== null && $this->subscription_ends_at->isPast();
    }

    public function hasSubscriptionAccess(): bool
    {
        if ($this->approval_status === 'disapproved') {
            return false;
        }

        if ($this->subscription_ends_at === null) {
            return true;
        }

        return ! $this->hasExpiredTrial() || $this->approval_status === 'approved';
    }
}
