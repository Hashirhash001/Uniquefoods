<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'sku',
        'category_id',
        'brand_id',
        'price',
        'mrp',
        'price_per_kg',
        'stock',
        'is_weight_based',
        'unit',
        'min_weight',
        'max_weight',
        'is_active',
        'is_featured',
        'barcode',
        'tax_rate',
        'description'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'mrp' => 'decimal:2',
        'price_per_kg' => 'decimal:2',
        'min_weight' => 'decimal:3',
        'max_weight' => 'decimal:3',
        'tax_rate' => 'decimal:2',
        'is_weight_based' => 'boolean',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function quantityDiscounts()
    {
        return $this->hasMany(QuantityDiscount::class);
    }

    public function groupPrices()
    {
        return $this->hasMany(ProductGroupPrice::class);
    }

    public function groupOffers()
    {
        return $this->hasMany(GroupProductOffer::class);
    }

    public function substitutionGroup()
    {
        return $this->belongsTo(SubstitutionGroup::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }
}
