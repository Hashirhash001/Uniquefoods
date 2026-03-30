<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ShippingSetting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'label'];

    // Get all settings as key => value array (cached)
    public static function allCached(): array
    {
        return Cache::remember('shipping_settings', 3600, function () {
            return static::all()->pluck('value', 'key')->toArray();
        });
    }

    // Clear cache when any setting is updated
    protected static function booted(): void
    {
        static::saved(fn() => Cache::forget('shipping_settings'));
        static::deleted(fn() => Cache::forget('shipping_settings'));
    }
}
