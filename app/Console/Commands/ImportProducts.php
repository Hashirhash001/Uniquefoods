<?php

namespace App\Console\Commands;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImportProducts extends Command
{
    protected $signature   = 'products:import {file : Path to the CSV file}';
    protected $description = 'Import products from the Zenero recategorized product list CSV';

    // ── Unit normalisation ────────────────────────────────────────────────────
    private array $units = [
        'kg'     => 'kg',
        'g'      => 'g',
        'gm'     => 'g',
        'gms'    => 'g',
        'ml'     => 'ml',
        'l'      => 'ml',
        'ltr'    => 'ml',
        'litre'  => 'ml',
        'nos'    => 'nos',
        'each'   => 'nos',
        'item'   => 'nos',
        'set'    => 'nos',
        'ft'     => 'nos',
        'bnh'    => 'nos',
        'pac'    => 'nos',
        'box'    => 'box',
        'bx'     => 'box',
        'b'      => 'box',
        'drm'    => 'drm',
        'drum'   => 'drm',
        'pkt'    => 'pkt',
        'packet' => 'pkt',
        'rol'    => 'rol',
        'roll'   => 'rol',
        'doz'    => 'doz',
        'dozen'  => 'doz',
        'willkg' => 'kg',
    ];

    // ── Brand detection prefixes ──────────────────────────────────────────────
    private array $brandPrefixes = [
        'QUALITY STREET' => 'Quality Street',
        'DAILY DELIGHT'  => 'Daily Delight',
        'MALABAR TREAT'  => 'Malabar Treat',
        'TROPICAL SUN'   => 'Tropical Sun',
        'WHITE PEARL'    => 'White Pearl',
        'AASHIRVAAD'     => 'Aashirvaad',
        'PACHRAANGA'     => 'Pachraanga',
        'HELLMANNS'      => "Hellmann's",
        'CARNATION'      => 'Carnation',
        'HALDIRAM'       => "Haldiram's",
        'ELEPHANT'       => 'Elephant',
        'HORLICKS'       => 'Horlicks',
        'MARIGOLD'       => 'Marigold',
        'PEGASUS'        => 'Pegasus',
        'MR NAGA'        => 'Mr Naga',
        'LAZZIZA'        => 'Lazziza',
        'RAINBOW'        => 'Rainbow',
        'CLEANUX'        => 'Cleanux',
        'VISWAAS'        => 'Viswas',
        'SHANKAR'        => 'Shankar',
        'INDUSRI'        => 'Indusri',
        'EASTERN'        => 'Eastern',
        'NESCAFE'        => 'Nescafe',
        'PASCALI'        => 'Pascali',
        'VANDEVI'        => 'Vandevi',
        'CHAOKOH'        => 'Chaokoh',
        'COLMAN'         => 'Colmans',
        'ZAFRON'         => 'Zafron',
        'LAZIZA'         => 'Laziza',
        'DEEPIO'         => 'Deepio',
        'DETTOL'         => 'Dettol',
        'HARPIC'         => 'Harpic',
        'KHANUM'         => 'Khanum',
        'VISWAS'         => 'Viswas',
        'ARMAAN'         => 'Armaan',
        'MARINE'         => 'Marine',
        'SAKTHI'         => 'Sakthi',
        'ASHOKA'         => 'Ashoka',
        'IDAYAM'         => 'Idayam',
        'TETLEY'         => 'Tetley',
        'PG TEA'         => 'PG Tips',
        'JAIMIN'         => 'Jaimin',
        'BOIRON'         => 'Boiron',
        'CIRIO'          => 'Cirio',
        'HEERA'          => 'Heera',
        'PATAK'          => 'Patak',
        'H-BOY'          => 'H-Boy',
        'TIGER'          => 'Tiger',
        'BIG K'          => 'Big K',
        'BIG-K'          => 'Big K',
        'FLASH'          => 'Flash',
        'NILCO'          => 'Nilco',
        'LAILA'          => 'Laila',
        'MAGGI'          => 'Maggi',
        'SHANA'          => 'Shana',
        'SUVAI'          => 'Suvai',
        'KINGS'          => 'Kings',
        'PRIYA'          => 'Priya',
        'GEETA'          => "Geeta's",
        'AHMED'          => 'Ahmed',
        'PRIDE'          => 'Pride',
        'DALDA'          => 'Dalda',
        'KNORR'          => 'Knorr',
        'AACHI'          => 'Aachi',
        'FUDCO'          => 'Fudco',
        'NATCO'          => 'Natco',
        'JOSH'           => 'Josh',
        'GITS'           => 'Gits',
        'VIS-'           => 'Viswas',
        'VIS '           => 'Viswas',
        'ARDO'           => 'Ardo',
        'BALA'           => 'Bala',
        'NAGA'           => 'Naga',
        'AMOY'           => 'Amoy',
        'TL -'           => 'Tate & Lyle',
        'LION'           => 'Lion',
        'SHAN'           => 'Shan',
        'AROY'           => 'Aroy-D',
        'TRS'            => 'TRS',
        'TS-'            => 'Tropical Sun',
        'T-S'            => 'Tropical Sun',
        'T/S'            => 'Tropical Sun',
        'KTC'            => 'KTC',
        'DD '            => 'Daily Delight',
        'DD-'            => 'Daily Delight',
        'VS-'            => 'Viswas',
        'TAJ'            => 'Taj',
        'TYJ'            => 'TYJ',
        'MTR'            => 'MTR',
        'BRU'            => 'Bru',
        'T&L'            => 'Tate & Lyle',
        'MDH'            => 'MDH',
        'VVR'            => 'VVR',
    ];

    /**
     * Maps website_category (col 8) → parent Category name in DB.
     * These must exist in your categories table with parent_id = NULL.
     */
    private array $parentCategoryMap = [
        'BBQ & Outdoor'            => 'BBQ & Outdoor',
        'Baking & Desserts'        => 'Baking & Desserts',
        'Beverages'                => 'Beverages',
        'Canned & Packaged Foods'  => 'Canned & Packaged Foods',
        'Cleaning & Household'     => 'Cleaning & Household',
        'Condiments & Sauces'      => 'Condiments & Sauces',
        'Dairy & Eggs'             => 'Dairy & Eggs',
        'Flour & Baking'           => 'Flour & Baking',
        'Fresh Produce'            => 'Fresh Produce',
        'Frozen Foods'             => 'Frozen Foods',
        'General'                  => 'General',
        'Grocery'                  => 'Grocery',
        'Nuts & Dry Fruits'        => 'Nuts & Dry Fruits',
        'Oils & Fats'              => 'Oils & Fats',
        'Packaging & Disposables'  => 'Packaging & Disposables',
        'Pulses & Lentils'         => 'Pulses & Lentils',
        'Rice & Cereals'           => 'Rice & Cereals',
        'Snacks & Confectionery'   => 'Snacks & Confectionery',
        'Spices & Seasonings'      => 'Spices & Seasonings',
    ];

    public function handle(): int
    {
        $file = $this->argument('file');

        if (! file_exists($file)) {
            $this->error("File not found: {$file}");
            return self::FAILURE;
        }

        $handle = fopen($file, 'r');
        if (! $handle) {
            $this->error("Cannot open file: {$file}");
            return self::FAILURE;
        }

        $headers = fgetcsv($handle); // skip/read header row
        $this->info('CSV columns: ' . implode(', ', $headers));

        $allRows = [];
        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 8) continue;
            $allRows[] = [
                'seq'              => trim($row[0] ?? ''),
                'category'         => trim($row[1] ?? ''),   // raw PDF category (unused for DB lookup)
                'sub_category'     => trim($row[2] ?? ''),   // raw PDF sub-category (unused for DB lookup)
                'barcode'          => trim($row[3] ?? ''),   // product code / barcode
                'name'             => trim($row[4] ?? ''),
                'unit'             => trim($row[5] ?? ''),
                'price'            => trim($row[6] ?? ''),
                'tax'              => trim($row[7] ?? ''),
                'website_category' => trim($row[8] ?? ''),   // parent category for DB
                'website_sub'      => trim($row[9] ?? ''),   // child category for DB
            ];
        }
        fclose($handle);

        $this->info("Total rows to process: " . count($allRows));

        $this->info("\n── First 3 rows preview ──");
        foreach (array_slice($allRows, 0, 3) as $i => $r) {
            $brand = $this->detectBrand($r['name'], '');
            $this->line(sprintf(
                "Row %d: name=[%s] brand=[%s] unit=[%s] price=[%s] cat=[%s > %s]",
                $i + 1,
                $r['name'],
                $brand ?? 'none',
                $r['unit'],
                $r['price'],
                $r['website_category'],
                $r['website_sub'],
            ));
        }
        $this->newLine();

        if (! $this->confirm('Proceed with import?')) {
            return self::SUCCESS;
        }

        // ── Skipped CSV output ────────────────────────────────────────────────
        $csvDir      = dirname($file);
        $skippedPath = $csvDir . '/skipped_products.csv';
        $skippedFH   = fopen($skippedPath, 'w');
        fputcsv($skippedFH, ['seq', 'reason', 'website_category', 'website_sub', 'barcode', 'name', 'unit', 'price', 'tax']);

        $created     = $updated = $skipped = 0;
        $skipReasons = [];

        // ── Pre-load all categories into memory for performance ───────────────
        // Structure: $catCache['Parent Name']['Child Name'] = Category $child
        //            $catCache['Parent Name']['__self__']   = Category $parent
        $catCache = [];
        Category::with('children')->get()->each(function ($cat) use (&$catCache) {
            if (is_null($cat->parent_id)) {
                $catCache[$cat->name]['__self__'] = $cat;
                foreach ($cat->children as $child) {
                    $catCache[$cat->name][$child->name] = $child;
                }
            }
        });

        $bar = $this->output->createProgressBar(count($allRows));
        $bar->start();

        DB::beginTransaction();
        try {
            foreach ($allRows as $idx => $r) {

                $rowNum        = (int)($r['seq'] ?: $idx + 2);
                $name          = $r['name'];
                $barcode       = $r['barcode'];
                $unitRaw       = strtolower(trim($r['unit']));
                $priceV        = $r['price'];
                $taxRaw        = $r['tax'];
                $websiteCat    = $r['website_category'];
                $websiteSub    = $r['website_sub'];

                $skip = function (string $reason) use (
                    &$skipped, &$skipReasons, $skippedFH, $rowNum, $r, $bar
                ) {
                    $skipReasons[] = "Row {$rowNum}: {$reason}";
                    fputcsv($skippedFH, [
                        $rowNum, $reason,
                        $r['website_category'], $r['website_sub'],
                        $r['barcode'], $r['name'], $r['unit'], $r['price'], $r['tax'],
                    ]);
                    $skipped++;
                    $bar->advance();
                };

                // ── Validate name ─────────────────────────────────────────────
                if (strlen($name) < 2) {
                    $skip('blank name'); continue;
                }

                // ── Validate price ────────────────────────────────────────────
                if (! is_numeric($priceV)) {
                    $skip("non-numeric price ['{$priceV}']"); continue;
                }

                $price   = (float) $priceV;
                $taxRate = is_numeric($taxRaw) ? (float) $taxRaw : 0;
                $barcode = strlen($barcode) > 2 ? $barcode : null;

                // ── Normalize unit ────────────────────────────────────────────
                $unit = $this->units[$unitRaw] ?? 'nos';

                // ── Resolve parent category ───────────────────────────────────
                $parentName = $this->parentCategoryMap[$websiteCat] ?? $websiteCat;

                if (! isset($catCache[$parentName]['__self__'])) {
                    $skip("parent category '{$parentName}' not found in DB"); continue;
                }

                $parentCat = $catCache[$parentName]['__self__'];

                // ── Resolve child (sub) category ──────────────────────────────
                // If the sub-category doesn't exist yet, auto-create it under the parent
                if (! empty($websiteSub)) {
                    if (! isset($catCache[$parentName][$websiteSub])) {
                        $newChild = Category::create([
                            'name'      => $websiteSub,
                            'slug'      => Str::slug($parentName) . '-' . Str::slug($websiteSub),
                            'parent_id' => $parentCat->id,
                            'is_active' => 1,
                        ]);
                        $catCache[$parentName][$websiteSub] = $newChild;
                    }
                    $categoryId = $catCache[$parentName][$websiteSub]->id;
                } else {
                    // No sub-category — assign directly to parent
                    $categoryId = $parentCat->id;
                }

                // ── Detect brand ──────────────────────────────────────────────
                $brandId   = null;
                $brandName = $this->detectBrand($name, '');
                if ($brandName) {
                    $brand   = Brand::firstOrCreate(
                        ['name' => $brandName],
                        ['slug' => Str::slug($brandName), 'is_active' => 1]
                    );
                    $brandId = $brand->id;
                }

                // ── Build payload ─────────────────────────────────────────────
                $payload = [
                    'name'            => $name,
                    'description'     => $name,
                    'category_id'     => $categoryId,
                    'brand_id'        => $brandId,
                    'price'           => $price,
                    'mrp'             => $price,
                    'tax_rate'        => $taxRate,
                    'unit'            => $unit,
                    'is_weight_based' => in_array($unit, ['kg', 'g']) ? 1 : 0,
                    'stock'           => 0,
                    'is_active'       => 1,
                    'barcode'         => $barcode,
                ];

                // ── Upsert ────────────────────────────────────────────────────
                $existing = $barcode
                    ? Product::withTrashed()->where('barcode', $barcode)->first()
                    : Product::withTrashed()->where('name', $name)->first();

                if ($existing) {
                    $existing->restore();
                    $existing->update($payload);
                    $updated++;
                } else {
                    $payload['sku'] = $this->genSKU();
                    Product::create($payload);
                    $created++;
                }

                $bar->advance();
            }

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();
            fclose($skippedFH);
            $this->newLine();
            $this->error('Import failed: ' . $e->getMessage());
            $this->error('At: ' . $e->getFile() . ':' . $e->getLine());
            return self::FAILURE;
        }

        fclose($skippedFH);
        $bar->finish();
        $this->newLine(2);

        if (count($skipReasons) > 0) {
            $this->warn("All skip reasons (" . count($skipReasons) . " rows):");
            foreach ($skipReasons as $sr) $this->line("  - {$sr}");
            $this->newLine();
            $this->warn("Full skipped rows saved to: {$skippedPath}");
            $this->newLine();
        }

        $this->table(['Created', 'Updated', 'Skipped'], [[$created, $updated, $skipped]]);
        $this->info('✅ Import complete!');

        return self::SUCCESS;
    }

    private function detectBrand(string $name, string $brandCol): ?string
    {
        $brandCol = trim($brandCol);
        if (strlen($brandCol) > 1 && ! str_contains(strtolower($brandCol), 'brand might')) {
            return $brandCol;
        }

        $nameUpper = strtoupper(trim($name));
        foreach ($this->brandPrefixes as $prefix => $brand) {
            if (str_starts_with($nameUpper, $prefix)) {
                return $brand;
            }
        }

        return null;
    }

    private function genSKU(): string
    {
        do {
            $sku = 'UF-' . strtoupper(Str::random(6));
        } while (Product::where('sku', $sku)->exists());
        return $sku;
    }
}
