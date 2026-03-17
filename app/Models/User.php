<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'mobile',
        'password',
        'provider',
        'provider_id',
        'avatar',
        'is_admin',
        'is_verified',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
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
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function addresses()
    {
        return $this->hasMany(\App\Models\UserAddress::class);
    }

    public function wishlistItems()
    {
        return $this->hasMany(\App\Models\Wishlist::class);
    }

    public function cartItems()
    {
        return $this->hasMany(\App\Models\Cart::class);
    }

    public function groups()
    {
        return $this->belongsToMany(CustomerGroup::class)
                    ->withTimestamps();
    }

    public function buyAgainProducts()
    {
        return $this->hasMany(BuyAgain::class);
    }

    // ✅ ADD HELPER METHOD
    public function isSocialUser()
    {
        return !empty($this->provider);
    }

    // ✅ ADD PROFILE PICTURE METHOD
    public function getProfilePictureAttribute()
    {
        if ($this->avatar) {
            return $this->avatar;
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=0f508d&color=fff&size=200';
    }
}
