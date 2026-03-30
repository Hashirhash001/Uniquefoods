<?php

namespace Database\Seeders;

use App\Models\ShippingSetting;
use Illuminate\Database\Seeder;

class ShippingSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key'   => 'mode',
                'value' => 'free',
                'type'  => 'string',
                'label' => 'Shipping Mode',
                // options: free | free_above_threshold | distance_based | threshold_and_distance
            ],
            [
                'key'   => 'free_threshold',
                'value' => '50.00',
                'type'  => 'decimal',
                'label' => 'Free Delivery Above (£)',
            ],
            [
                'key'   => 'base_rate',
                'value' => '2.99',
                'type'  => 'decimal',
                'label' => 'Base Delivery Charge (£)',
            ],
            [
                'key'   => 'rate_per_mile',
                'value' => '0.50',
                'type'  => 'decimal',
                'label' => 'Rate Per Mile (£)',
            ],
            [
                'key'   => 'max_delivery_miles',
                'value' => '10',
                'type'  => 'integer',
                'label' => 'Max Delivery Radius (miles)',
            ],
            [
                'key'   => 'store_postcode',
                'value' => 'SW1A1AA',
                'type'  => 'string',
                'label' => 'Store Postcode',
            ],
        ];

        foreach ($settings as $setting) {
            ShippingSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
