<?php

namespace App\Models;

use App\Models\GroupProductOffer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'parent_id',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $appends = ['image_url'];

    /**
     * Scope for active categories
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    /**
     * Get products belonging to this category
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get parent category
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get child categories (subcategories)
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Get active children only
     */
    public function activeChildren()
    {
        return $this->children()->where('is_active', 1);
    }

    // Scope: Only parent categories
    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    public function groupOffers()
    {
        return $this->hasMany(GroupProductOffer::class);
    }

    /**
     * Get the full image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return Storage::url($this->image);
        }
        return asset('assets/images/category/default.png');
    }

    /**
     * Delete image when category is deleted
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($category) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
        });
    }


}
