<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuantityDiscount extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_id',
        'min_quantity',
        'discount_amount'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class)
                    ->withTrashed();
    }
}
