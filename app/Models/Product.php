<?php

namespace App\Models;

use App\Models\GroupProductOffer;
use App\Models\ProductImage;
use App\Models\ProductReview;
use App\Models\SubstitutionGroup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'sku',
        'slug',
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
        'is_popular',
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
        'is_popular' => 'boolean'
    ];

    /* ================= BOOT - Auto generate slug ================= */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = static::generateUniqueSlug($product->name);
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('name')) {
                $product->slug = static::generateUniqueSlug($product->name, $product->id);
            }
        });
    }

    /* ================= Generate Unique Slug ================= */
    public static function generateUniqueSlug($name, $ignoreId = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

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

    /* ================= ACCESSORS ================= */
    public function getImageUrlAttribute()
    {
        // ✅ Use relationLoaded check to avoid N+1 and use cached data
        if ($this->relationLoaded('primaryImage') && $this->primaryImage) {
            return asset('storage/' . $this->primaryImage->image_path);
        }

        if ($this->relationLoaded('images') && $this->images->isNotEmpty()) {
            $primary = $this->images->firstWhere('is_primary', true);
            $image   = $primary ?? $this->images->first();
            return asset('storage/' . $image->image_path);
        }

        // Lazy fallback (when not eager loaded)
        $primary = $this->images()->where('is_primary', true)->first();
        if ($primary) {
            return asset('storage/' . $primary->image_path);
        }

        $first = $this->images()->first();
        if ($first) {
            return asset('storage/' . $first->image_path);
        }

        return asset('frontend/assets/images/products/product-placeholder.svg');
    }

    public function getDiscountPercentageAttribute()
    {
        if ($this->mrp && $this->price && $this->mrp > $this->price) {
            return round((($this->mrp - $this->price) / $this->mrp) * 100);
        }
        return 0;
    }

    /* ================= SCOPES ================= */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', 1);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    /**
     * Get stock attribute (if not in database)
     */
    public function getStockAttribute($value)
    {
        // If you have quantity field instead
        if (isset($this->attributes['quantity'])) {
            return $this->attributes['quantity'];
        }

        // Return the actual stock value
        return $value ?? 0;
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class)->where('is_approved', true);
    }

    public function getAverageRatingAttribute()
    {
        if ($this->relationLoaded('reviews')) {
            return round($this->reviews->avg('rating'), 1);
        }
        return round($this->reviews()->avg('rating'), 1);
    }

}
