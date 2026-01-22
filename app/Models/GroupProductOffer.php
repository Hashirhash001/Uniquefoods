<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupProductOffer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'customer_group_id',
        'product_id',
        'offer_price',
        'starts_at',
        'ends_at'
    ];

    protected $casts = [
        'starts_at' => 'date',
        'ends_at'   => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class)
                    ->withTrashed();
    }

    public function group()
    {
        return $this->belongsTo(CustomerGroup::class)
                    ->withTrashed();
    }
}
