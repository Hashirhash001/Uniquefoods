<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupDiscount extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'customer_group_id',
        'type',
        'value',
        'min_order_amount',
        'is_active'
    ];

    public function group()
    {
        return $this->belongsTo(CustomerGroup::class)
                    ->withTrashed();
    }
}
