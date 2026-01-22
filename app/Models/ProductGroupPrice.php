<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductGroupPrice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_id',
        'customer_group_id',
        'price'
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
