<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupProductOffer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'customer_group_id',
        'offer_type',
        'product_id',
        'category_id',
        'brand_id',
        'discount_type',
        'discount_value',
        'offer_price',
        'starts_at',
        'ends_at'
    ];

    protected $casts = [
        'starts_at' => 'date',
        'ends_at'   => 'date',
        'offer_price' => 'decimal:2',
        'discount_value' => 'decimal:2',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function customerGroup()
    {
        return $this->belongsTo(CustomerGroup::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('starts_at', '<=', now())
                     ->where('ends_at', '>=', now());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('starts_at', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('ends_at', '<', now());
    }

    public function scopeForProduct($query, $productId)
    {
        return $query->where('offer_type', 'product')
                     ->where('product_id', $productId);
    }

    public function scopeForCategory($query, $categoryId)
    {
        return $query->where('offer_type', 'category')
                     ->where('category_id', $categoryId);
    }

    public function scopeForBrand($query, $brandId)
    {
        return $query->where('offer_type', 'brand')
                     ->where('brand_id', $brandId);
    }

    // Helper method to get offer name
    public function getOfferNameAttribute()
    {
        switch ($this->offer_type) {
            case 'product':
                return $this->product ? $this->product->name : 'Unknown Product';
            case 'category':
                return $this->category ? "All " . $this->category->name : 'Unknown Category';
            case 'brand':
                return $this->brand ? "All " . $this->brand->name . " Products" : 'Unknown Brand';
            default:
                return 'Unknown';
        }
    }

    // Helper method to calculate discounted price
    public function calculateDiscountedPrice($originalPrice)
    {
        if ($this->offer_price) {
            return $this->offer_price;
        }

        if ($this->discount_type === 'percentage') {
            return $originalPrice - ($originalPrice * $this->discount_value / 100);
        } else {
            return max(0, $originalPrice - $this->discount_value);
        }
    }
}
