<?php

namespace App\Models;

use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_number', 'user_id', 'customer_name', 'customer_email', 'customer_phone',
        'shipping_address', 'shipping_city', 'shipping_postcode', 'shipping_country',
        'restaurant_store',
        'billing_address', 'billing_city', 'billing_postcode', 'billing_country',
        'subtotal', 'shipping_cost', 'tax', 'discount', 'total',
        'payment_method', 'cod_delivery_method', 'payment_status', 'stripe_payment_intent_id', 'paid_at',
        'status', 'customer_notes', 'admin_notes',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function generateOrderNumber()
    {
        $prefix = 'UF';
        $timestamp = now()->format('ymd');
        $random = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 4));
        return $prefix . $timestamp . $random;
    }

    // Relationship
    public function activities()
    {
        return $this->hasMany(\App\Models\OrderActivity::class)->latest();
    }

    // Auto-log creation
    protected static function booted(): void
    {
        static::created(function (Order $order) {
            $order->activities()->create([
                'user_id'     => auth()->id(),
                'type'        => 'order_placed',
                'title'       => 'Order Placed',
                'description' => "Order {$order->order_number} was created.",
                'meta'        => ['order_number' => $order->order_number],
            ]);
        });
    }
}
