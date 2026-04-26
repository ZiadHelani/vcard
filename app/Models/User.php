<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserSubscriptionStatus;
use App\Helpers\MediaCollectionHelper;
use App\Traits\MustHaveMedia;
use Database\Factories\UserFactory;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;

class User extends Authenticatable implements HasMedia
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, MustVerifyEmail, HasApiTokens, MustHaveMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'otp_code',
        'password',
        'provider',
        'provider_id',
        'provider_token',
        'provider_refresh_token',
        'provider_expires_at',
        'fcm_token',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
            'provider_expires_at' => 'datetime',
        ];
    }

    public function getCollection(): string
    {
        return MediaCollectionHelper::PROFILE;
    }

    public function subscriptions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function cards(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Card::class);
    }

    public function hasActiveSubscription(): bool
    {
        return $this->subscriptions()
            ->where('status', UserSubscriptionStatus::ACTIVE)
            ->where('start_at', '<=', now())
            ->where('end_at', '>=', now())
            ->exists();
    }

    public function activeSubscriptionNow()
    {
        return $this->subscriptions()
            ->where('status', UserSubscriptionStatus::ACTIVE)
            ->where('start_at', '<=', now())
            ->where('end_at', '>=', now())
            ->latest('start_at')
            ->first();
    }

    public function contacts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Contact::class);
    }

    public function getImageAttribute(): string
    {
        return $this->getFirstMediaUrl($this->getCollection());
    }

    public function activites(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function supports(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Support::class);
    }

    public function devices(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SerialDevice::class);
    }

    public function activeDevices(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SerialDevice::class)->where('is_active', true);
    }

}
