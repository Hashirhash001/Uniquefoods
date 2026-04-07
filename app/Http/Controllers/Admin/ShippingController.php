<?php

namespace App\Http\Controllers\Admin;

use App\Models\ShippingSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShippingController extends Controller
{
    public function index()
    {
        $settings = ShippingSetting::all()->keyBy('key');
        return view('admin.shipping.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'mode'               => 'required|in:free,free_above_threshold,distance_based',
            'free_threshold'     => 'nullable|numeric|min:0',
            'base_rate'          => 'nullable|numeric|min:0',
            'rate_per_mile'      => 'nullable|numeric|min:0',
            'max_delivery_miles' => 'nullable|integer|min:1',
            'store_postcode'     => 'nullable|string|max:10',
        ]);

        $data = [
            'mode'               => $request->input('mode'),
            'free_threshold'     => $request->input('free_threshold'),
            'base_rate'          => $request->input('base_rate'),
            'rate_per_mile'      => $request->input('rate_per_mile'),
            'max_delivery_miles' => $request->input('max_delivery_miles'),
            'store_postcode'     => strtoupper(preg_replace('/\s+/', '', $request->input('store_postcode', ''))),
        ];

        foreach ($data as $key => $value) {
            if ($value !== null) {
                // ✅ Use firstOrFail() + save() so Eloquent fires saved() event
                // which triggers Cache::forget('shipping_settings') in booted()
                $setting = ShippingSetting::where('key', $key)->first();
                if ($setting) {
                    $setting->value = $value;
                    $setting->save(); // ✅ fires saved() → clears cache
                }
            }
        }

        return response()->json(['success' => true, 'message' => 'Shipping settings updated.']);
    }
}
