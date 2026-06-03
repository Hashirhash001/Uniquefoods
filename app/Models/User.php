<?php

namespace App\Models;

use App\Models\CompanyAccount;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

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

    public function companies()
    {
        return $this->belongsToMany(CompanyAccount::class, 'company_users', 'user_id', 'company_account_id')
                    ->withPivot('role', 'is_active')
                    ->withTimestamps();
    }

    public function activeCompany(): ?CompanyAccount
    {
        return $this->companies()
                    ->wherePivot('is_active', true)
                    ->first()
                    ?->load('groups');
    }

    // Helper: get effective groups (own groups MERGED with company groups)
    public function effectiveGroups()
    {
        // Use already-loaded relation to avoid N+1
        $ownGroups = $this->relationLoaded('groups')
            ? $this->groups
            : $this->groups()->where('is_active', 1)->get();

        // Guard: if company tables don't exist yet, return own groups only
        try {
            $company       = $this->activeCompany();
            $companyGroups = $company ? $company->groups : collect();
        } catch (\Illuminate\Database\QueryException $e) {
            // Only ignore "table doesn't exist" errors (code 1146)
            if ($e->getCode() !== '42S02') {
                throw $e;
            }
            $companyGroups = collect();
        }

        return $ownGroups->merge($companyGroups)->unique('id');
    }
}
