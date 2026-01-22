<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get categories and brands
        $categories = $this->ensureCategories();
        $brands = $this->ensureBrands();

        // Sample products data
        $products = [
            [
                'name' => 'Coca-Cola 330ml Can',
                'description' => 'Classic Coca-Cola carbonated soft drink in a convenient 330ml can. Perfect for refreshment on the go.',
                'sku' => 'COK-330ML-001',
                'barcode' => '8901030510151',
                'price' => 40.00,
                'mrp' => 45.00,
                'category' => 'soft-drinks',
                'brand' => 'coca-cola',
                'stock' => 500,
                'unit' => 'ml',
                'is_weight_based' => false,
                'is_featured' => true,
                'tax_rate' => 12.00,
            ],
            [
                'name' => 'Pepsi 1.5L Bottle',
                'description' => 'Refreshing Pepsi cola drink in a family-size 1.5 liter bottle. Great for sharing with friends and family.',
                'sku' => 'PEP-1.5L-002',
                'barcode' => '8901030510168',
                'price' => 75.00,
                'mrp' => 85.00,
                'category' => 'soft-drinks',
                'brand' => 'pepsi',
                'stock' => 300,
                'unit' => 'L',
                'is_weight_based' => false,
                'is_featured' => false,
                'tax_rate' => 12.00,
            ],
            [
                'name' => 'Red Bull Energy Drink 250ml',
                'description' => 'Red Bull energy drink gives you wings! Contains caffeine, taurine, and B-vitamins for an energy boost.',
                'sku' => 'RDB-250ML-003',
                'barcode' => '9002490100070',
                'price' => 125.00,
                'mrp' => 150.00,
                'category' => 'energy-drinks',
                'brand' => 'red-bull',
                'stock' => 250,
                'unit' => 'ml',
                'is_weight_based' => false,
                'is_featured' => true,
                'tax_rate' => 12.00,
            ],
            [
                'name' => 'Tropicana Orange Juice 1L',
                'description' => '100% pure orange juice with no added sugar or preservatives. Made from fresh oranges.',
                'sku' => 'TRO-1L-004',
                'barcode' => '8901030510175',
                'price' => 160.00,
                'mrp' => 180.00,
                'category' => 'juices',
                'brand' => 'tropicana',
                'stock' => 150,
                'unit' => 'L',
                'is_weight_based' => false,
                'is_featured' => false,
                'tax_rate' => 12.00,
            ],
            [
                'name' => 'Fresh Chicken Breast (Weight Based)',
                'description' => 'Premium quality fresh chicken breast. Boneless and skinless. Price calculated per kg.',
                'sku' => 'CHK-BRST-005',
                'barcode' => '2100000000001',
                'price_per_kg' => 280.00,
                'mrp' => 320.00,
                'category' => 'meat',
                'brand' => 'real',
                'stock' => 50,
                'unit' => 'kg',
                'min_weight' => 0.250,
                'max_weight' => 2.000,
                'is_weight_based' => true,
                'is_featured' => true,
                'tax_rate' => 0.00,
            ],
        ];

        // Create products with images
        foreach ($products as $productData) {
            $product = Product::create([
                'name' => $productData['name'],
                'sku' => $productData['sku'],
                'description' => $productData['description'],
                'barcode' => $productData['barcode'],
                'category_id' => $categories[$productData['category']]->id,
                'brand_id' => $brands[$productData['brand']]->id,
                'price' => $productData['price'] ?? 0,
                'mrp' => $productData['mrp'],
                'price_per_kg' => $productData['price_per_kg'] ?? null,
                'stock' => $productData['stock'],
                'unit' => $productData['unit'],
                'min_weight' => $productData['min_weight'] ?? null,
                'max_weight' => $productData['max_weight'] ?? null,
                'is_weight_based' => $productData['is_weight_based'],
                'is_featured' => $productData['is_featured'],
                'is_active' => true,
                'tax_rate' => $productData['tax_rate'],
            ]);

            // Create product images
            $this->createProductImages($product);
        }

        $this->command->info('5 products with images created successfully!');
    }

    /**
     * Create product images
     */
    private function createProductImages($product): void
    {
        $slug = Str::slug($product->name);

        // Primary image
        ProductImage::create([
            'product_id' => $product->id,
            'image_path' => "products/{$slug}-1.jpg",
            'is_primary' => true,
            'sort_order' => 1,
        ]);

        // Additional images
        for ($i = 2; $i <= 3; $i++) {
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => "products/{$slug}-{$i}.jpg",
                'is_primary' => false,
                'sort_order' => $i,
            ]);
        }
    }

    /**
     * Ensure categories exist
     */
    private function ensureCategories(): array
    {
        $categories = [];

        // Main category: Beverages
        $beverages = Category::firstOrCreate(
            ['slug' => 'beverages'],
            ['name' => 'Beverages', 'parent_id' => null, 'is_active' => true]
        );

        // Subcategories under Beverages
        $categories['soft-drinks'] = Category::firstOrCreate(
            ['slug' => 'soft-drinks'],
            ['name' => 'Soft Drinks', 'parent_id' => $beverages->id, 'is_active' => true]
        );

        $categories['energy-drinks'] = Category::firstOrCreate(
            ['slug' => 'energy-drinks'],
            ['name' => 'Energy Drinks', 'parent_id' => $beverages->id, 'is_active' => true]
        );

        $categories['juices'] = Category::firstOrCreate(
            ['slug' => 'juices'],
            ['name' => 'Juices', 'parent_id' => $beverages->id, 'is_active' => true]
        );

        // Main category: Meat & Seafood
        $categories['meat'] = Category::firstOrCreate(
            ['slug' => 'meat-seafood'],
            ['name' => 'Meat & Seafood', 'parent_id' => null, 'is_active' => true]
        );

        return $categories;
    }

    /**
     * Ensure brands exist
     */
    private function ensureBrands(): array
    {
        $brands = [];

        $brandNames = [
            'Coca-Cola' => 'coca-cola',
            'Pepsi' => 'pepsi',
            'Red Bull' => 'red-bull',
            'Tropicana' => 'tropicana',
            'Real' => 'real',
        ];

        foreach ($brandNames as $name => $slug) {
            $brands[$slug] = Brand::firstOrCreate(
                ['slug' => $slug],
                ['name' => $name, 'is_active' => true]
            );
        }

        return $brands;
    }
}
