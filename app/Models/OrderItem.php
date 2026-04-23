<?php

namespace App\Models;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'product_id', 'product_name', 'product_sku',
        'price', 'quantity','vat_rate', 'vat_amount', 'weight', 'subtotal'
    ];

    protected $casts = [
        'price'      => 'decimal:2',
        'quantity'   => 'decimal:3',
        'vat_rate'   => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'subtotal'   => 'decimal:2',
        'weight'     => 'decimal:3',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
