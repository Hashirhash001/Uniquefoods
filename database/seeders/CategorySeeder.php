<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            // 1. Fruits & Vegetables
            [
                'name' => 'Fruits & Vegetables',
                'parent_id' => null,
                'is_active' => 1,
                'children' => [
                    ['name' => 'Fresh Fruits', 'is_active' => 1],
                    ['name' => 'Fresh Vegetables', 'is_active' => 1],
                    ['name' => 'Exotic Fruits', 'is_active' => 1],
                    ['name' => 'Exotic Vegetables', 'is_active' => 1],
                    ['name' => 'Organic Fruits', 'is_active' => 1],
                    ['name' => 'Organic Vegetables', 'is_active' => 1],
                    ['name' => 'Herbs & Seasonings', 'is_active' => 1],
                ]
            ],

            // 2. Dairy & Bakery
            [
                'name' => 'Dairy & Bakery',
                'parent_id' => null,
                'is_active' => 1,
                'children' => [
                    ['name' => 'Milk', 'is_active' => 1],
                    ['name' => 'Butter & Ghee', 'is_active' => 1],
                    ['name' => 'Cheese', 'is_active' => 1],
                    ['name' => 'Yogurt & Curd', 'is_active' => 1],
                    ['name' => 'Paneer', 'is_active' => 1],
                    ['name' => 'Bread', 'is_active' => 1],
                    ['name' => 'Cakes & Pastries', 'is_active' => 1],
                    ['name' => 'Cookies & Biscuits', 'is_active' => 1],
                ]
            ],

            // 3. Staples
            [
                'name' => 'Staples',
                'parent_id' => null,
                'is_active' => 1,
                'children' => [
                    ['name' => 'Rice & Rice Products', 'is_active' => 1],
                    ['name' => 'Atta, Flours & Sooji', 'is_active' => 1],
                    ['name' => 'Dals & Pulses', 'is_active' => 1],
                    ['name' => 'Edible Oils', 'is_active' => 1],
                    ['name' => 'Masalas & Spices', 'is_active' => 1],
                    ['name' => 'Salt, Sugar & Jaggery', 'is_active' => 1],
                    ['name' => 'Dry Fruits', 'is_active' => 1],
                ]
            ],

            // 4. Snacks & Beverages
            [
                'name' => 'Snacks & Beverages',
                'parent_id' => null,
                'is_active' => 1,
                'children' => [
                    ['name' => 'Chips & Namkeen', 'is_active' => 1],
                    ['name' => 'Biscuits & Cookies', 'is_active' => 1],
                    ['name' => 'Chocolates & Candies', 'is_active' => 1],
                    ['name' => 'Tea & Coffee', 'is_active' => 1],
                    ['name' => 'Soft Drinks', 'is_active' => 1],
                    ['name' => 'Juices', 'is_active' => 1],
                    ['name' => 'Energy Drinks', 'is_active' => 1],
                    ['name' => 'Water', 'is_active' => 1],
                ]
            ],

            // 5. Packaged Food
            [
                'name' => 'Packaged Food',
                'parent_id' => null,
                'is_active' => 1,
                'children' => [
                    ['name' => 'Noodles & Pasta', 'is_active' => 1],
                    ['name' => 'Breakfast Cereals', 'is_active' => 1],
                    ['name' => 'Ready to Cook', 'is_active' => 1],
                    ['name' => 'Ready to Eat', 'is_active' => 1],
                    ['name' => 'Sauces & Spreads', 'is_active' => 1],
                    ['name' => 'Pickles & Chutneys', 'is_active' => 1],
                    ['name' => 'Canned Food', 'is_active' => 1],
                ]
            ],

            // 6. Personal Care
            [
                'name' => 'Personal Care',
                'parent_id' => null,
                'is_active' => 1,
                'children' => [
                    ['name' => 'Bath & Body', 'is_active' => 1],
                    ['name' => 'Hair Care', 'is_active' => 1],
                    ['name' => 'Skin Care', 'is_active' => 1],
                    ['name' => 'Oral Care', 'is_active' => 1],
                    ['name' => 'Fragrances & Deodorants', 'is_active' => 1],
                    ['name' => 'Shaving & Grooming', 'is_active' => 1],
                    ['name' => 'Feminine Hygiene', 'is_active' => 1],
                ]
            ],

            // 7. Household Care
            [
                'name' => 'Household Care',
                'parent_id' => null,
                'is_active' => 1,
                'children' => [
                    ['name' => 'Detergents & Fabric Care', 'is_active' => 1],
                    ['name' => 'Cleaning Supplies', 'is_active' => 1],
                    ['name' => 'Dishwashing', 'is_active' => 1],
                    ['name' => 'Air Fresheners', 'is_active' => 1],
                    ['name' => 'Insect Repellents', 'is_active' => 1],
                    ['name' => 'Disposables', 'is_active' => 1],
                ]
            ],

            // 8. Baby Care
            [
                'name' => 'Baby Care',
                'parent_id' => null,
                'is_active' => 1,
                'children' => [
                    ['name' => 'Baby Food', 'is_active' => 1],
                    ['name' => 'Diapers', 'is_active' => 1],
                    ['name' => 'Baby Bath & Skin Care', 'is_active' => 1],
                    ['name' => 'Baby Accessories', 'is_active' => 1],
                    ['name' => 'Baby Health & Safety', 'is_active' => 1],
                ]
            ],

            // 9. Frozen Foods
            [
                'name' => 'Frozen Foods',
                'parent_id' => null,
                'is_active' => 1,
                'children' => [
                    ['name' => 'Frozen Vegetables', 'is_active' => 1],
                    ['name' => 'Frozen Snacks', 'is_active' => 1],
                    ['name' => 'Frozen Non-Veg', 'is_active' => 1],
                    ['name' => 'Ice Creams & Desserts', 'is_active' => 1],
                ]
            ],

            // 10. Pet Care
            [
                'name' => 'Pet Care',
                'parent_id' => null,
                'is_active' => 1,
                'children' => [
                    ['name' => 'Pet Food', 'is_active' => 1],
                    ['name' => 'Pet Accessories', 'is_active' => 1],
                    ['name' => 'Pet Grooming', 'is_active' => 1],
                ]
            ],
        ];

        $this->createCategories($categories);
    }

    private function createCategories($categories, $parentId = null)
    {
        foreach ($categories as $category) {
            $categoryId = DB::table('categories')->insertGetId([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'parent_id' => $parentId,
                'is_active' => $category['is_active'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create subcategories if they exist
            if (isset($category['children']) && count($category['children']) > 0) {
                foreach ($category['children'] as $child) {
                    DB::table('categories')->insert([
                        'name' => $child['name'],
                        'slug' => Str::slug($child['name']),
                        'parent_id' => $categoryId,
                        'is_active' => $child['is_active'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
