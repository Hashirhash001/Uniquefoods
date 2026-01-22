<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run()
    {
        $brands = [
            // International Brands
            ['name' => 'Nestlé', 'is_active' => 1],
            ['name' => 'Unilever', 'is_active' => 1],
            ['name' => 'Coca-Cola', 'is_active' => 1],
            ['name' => 'PepsiCo', 'is_active' => 1],
            ['name' => 'Kellogg\'s', 'is_active' => 1],

            // Indian Brands - Food
            ['name' => 'Amul', 'is_active' => 1],
            ['name' => 'Britannia', 'is_active' => 1],
            ['name' => 'Parle', 'is_active' => 1],
            ['name' => 'ITC', 'is_active' => 1],
            ['name' => 'Mother Dairy', 'is_active' => 1],
            ['name' => 'Haldiram\'s', 'is_active' => 1],
            ['name' => 'MTR', 'is_active' => 1],
            ['name' => 'Tata Consumer Products', 'is_active' => 1],
            ['name' => 'Fortune', 'is_active' => 1],
            ['name' => 'Aashirvaad', 'is_active' => 1],

            // Rice & Grains
            ['name' => 'India Gate', 'is_active' => 1],
            ['name' => 'Kohinoor', 'is_active' => 1],
            ['name' => 'Daawat', 'is_active' => 1],

            // Spices & Masala
            ['name' => 'Everest', 'is_active' => 1],
            ['name' => 'MDH', 'is_active' => 1],
            ['name' => 'Catch', 'is_active' => 1],

            // Beverages
            ['name' => 'Red Bull', 'is_active' => 1],
            ['name' => 'Bisleri', 'is_active' => 1],
            ['name' => 'Real', 'is_active' => 1],
            ['name' => 'Tropicana', 'is_active' => 1],

            // Personal Care
            ['name' => 'Dove', 'is_active' => 1],
            ['name' => 'Pantene', 'is_active' => 1],
            ['name' => 'Colgate', 'is_active' => 1],
            ['name' => 'Dettol', 'is_active' => 1],
            ['name' => 'Lux', 'is_active' => 1],

            // Household
            ['name' => 'Surf Excel', 'is_active' => 1],
            ['name' => 'Vim', 'is_active' => 1],
            ['name' => 'Lizol', 'is_active' => 1],
            ['name' => 'Harpic', 'is_active' => 1],

            // Snacks
            ['name' => 'Lay\'s', 'is_active' => 1],
            ['name' => 'Kurkure', 'is_active' => 1],
            ['name' => 'Bingo', 'is_active' => 1],
            ['name' => 'Uncle Chipps', 'is_active' => 1],

            // Baby Care
            ['name' => 'Huggies', 'is_active' => 1],
            ['name' => 'Pampers', 'is_active' => 1],
            ['name' => 'Johnson\'s', 'is_active' => 1],
        ];

        foreach ($brands as $brand) {
            DB::table('brands')->insert([
                'name' => $brand['name'],
                'slug' => Str::slug($brand['name']),
                'is_active' => $brand['is_active'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
