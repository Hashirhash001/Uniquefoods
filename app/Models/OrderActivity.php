<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderActivity extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'order_id', 'user_id', 'type', 'title', 'description', 'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    // ── Relationships ────────────────────────────────────────────────────

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ── Helpers ──────────────────────────────────────────────────────────

    /**
     * Dot colour class for the timeline (mirrors your existing .tl-dot classes).
     */
    public function dotClass(): string
    {
        return match ($this->type) {
            'order_placed'      => 'd-blue',
            'payment_updated'   => 'd-green',
            'status_changed'    => match ($this->meta['new_status'] ?? '') {
                'cancelled'         => 'd-red',
                'processing'        => 'd-amber',
                'shipped'           => 'd-purple',
                default             => 'd-green',
            },
            'note_added'        => 'd-amber',
            default             => 'd-blue',
        };
    }
}
