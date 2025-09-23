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

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'mobile',
        'device_token',
        'profile_image',
        'wallet_balance',
        'wallet_password',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'wallet_password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'wallet_balance' => 'decimal:2',
        'is_admin' => 'boolean',
    ];

    // Relationships
    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function campaignDonations()
    {
        return $this->hasMany(Donation::class);
    }

    public function generalDonations()
    {
        return $this->hasMany(GeneralDonation::class);
    }

    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function favoriteCampaigns()
    {
        return $this->belongsToMany(Campaign::class, 'user_favorite_campaigns');
    }

    // Alias for favorites
    public function favorites()
    {
        return $this->hasMany(UserFavoriteCampaign::class);
    }

    // Wallet password methods
    public function hasWalletPassword()
    {
        return !is_null($this->wallet_password);
    }

    public function checkWalletPassword($password)
    {
        if (!$this->hasWalletPassword()) {
            return false;
        }

        return $this->wallet_password === $password;
    }

    public function setWalletPassword($password)
    {
        $this->wallet_password = $password;
        $this->save();
    }
}
