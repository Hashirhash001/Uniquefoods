<?php

namespace App\Models;

use App\Models\GroupDiscount;
use App\Models\GroupProductOffer;
use App\Models\ProductGroupPrice;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerGroup extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)
                    ->withTimestamps();
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'customer_group_product');
    }

    public function groupDiscounts()
    {
        return $this->hasMany(GroupDiscount::class);
    }

    public function productGroupPrices()
    {
        return $this->hasMany(ProductGroupPrice::class);
    }

    public function groupProductOffers()
    {
        return $this->hasMany(GroupProductOffer::class);
    }

}
