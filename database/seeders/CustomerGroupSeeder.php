<?php

namespace Database\Seeders;

use App\Models\CustomerGroup;
use Illuminate\Database\Seeder;

class CustomerGroupSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            [
                'name'        => 'Home Delivery',
                'slug'        => 'home-delivery',
                'description' => 'Default group for all self-registered customers. Can browse and order products available for home delivery.',
                'is_active'   => 1,
            ],
            [
                'name'        => 'Shop',
                'slug'        => 'shop',
                'description' => 'In-store customers assigned by admin. Can see shop-specific products and pricing.',
                'is_active'   => 1,
            ],
            [
                'name'        => 'Restaurant',
                'slug'        => 'restaurant',
                'description' => 'Restaurant buyers assigned by admin. Can see bulk/restaurant-specific products and pricing.',
                'is_active'   => 1,
            ],
        ];

        foreach ($groups as $group) {
            CustomerGroup::firstOrCreate(
                ['slug' => $group['slug']],
                $group
            );
        }

        $this->command->info('✅ ' . CustomerGroup::count() . ' customer groups seeded.');
    }
}
