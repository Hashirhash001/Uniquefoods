<?php

namespace App\Models;

use App\Models\GroupProductOffer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class brand extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Add this relationship
    public function groupOffers()
    {
        return $this->hasMany(GroupProductOffer::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
