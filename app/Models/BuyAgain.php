<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BuyAgain extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'product_id',
        'order_count'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class)
                    ->withTrashed();
    }
}
