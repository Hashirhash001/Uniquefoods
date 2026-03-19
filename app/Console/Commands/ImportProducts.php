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
    protected $description = 'One-time import of products from the Zenero product list CSV';

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
        'EAST-'          => 'Eastern',
        'PRIYA'          => 'Priya',
        'GEETA'          => "Geeta's",
        'AHMED'          => 'Ahmed',
        'PAPA-'          => 'Papa',
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
        'EAS-'           => 'Eastern',
        'EST-'           => 'Eastern',
        'EST.'           => 'Eastern',
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
     * Maps CSV raw category (uppercased) → exact name in your categories table.
     */
    private array $categoryMap = [
        'CANNED FOODS'                     => 'Canned Foods',
        'CHARCOAL FLAMES'                  => 'Charcoal & Flames',
        'CHARCOAL'                         => 'Charcoal & Flames',
        'CHOCOLATES'                       => 'Chocolates',
        'CLEANING PRODUCT'                 => 'Cleaning Products',
        'CUSTARDS AND PUDDING MIX'         => 'Custards & Pudding Mix',
        'DAIRY'                            => 'Dairy',
        'DAIRY GHEE'                       => 'Dairy',
        'DAIRY YOGURT'                     => 'Dairy',
        'DALS'                             => 'Dals',
        'DRY FRUITS 02'                    => 'Dry Fruits',
        'DRY FRUITS'                       => 'Dry Fruits',
        'EDIBLE OIL'                       => 'Edible Oil',
        'EGG'                              => 'Egg',
        'EGGS'                             => 'Egg',
        'ESSENCE'                          => 'Essence',
        'FRAGRANCE'                        => 'Fragrance',
        'FROZEN'                           => 'Frozen',
        'FROZEN FISH'                      => 'Frozen Fish',
        'GROCERY'                          => 'Grocery',
        'GROCERY 2'                        => 'Grocery 2',
        'HOT DRINKS'                       => 'Hot Drinks',
        'KITCHEN UTILITY PRODUCT'          => 'Kitchen Utility',
        'NOODLES'                          => 'Noodles',
        'NOODLES NOODLES'                  => 'Noodles',
        'NUTS'                             => 'Nuts',
        'OFFICE ACCESSORIES'               => 'Office Accessories',
        'OFFICE ACCESSORIES PRINTING ROLL' => 'Office Accessories',
        'PACKING'                          => 'Packing',
        'PICKLES'                          => 'Pickles',
        'RICE CEREALS'                     => 'Rice',
        'RICE'                             => 'Rice',
        'SOFT DRING'                       => 'Soft Drinks',
        'SOFT DRINKS'                      => 'Soft Drinks',
        'SPICES'                           => 'Spices',
        'TEA BAGS'                         => 'Tea Bags',
        'VEGETABLES'                       => 'Vegetables',
        'WATER'                            => 'Water',
        'WHOLE DALS AND SPLIT DALS'        => 'Dals',
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

        fgetcsv($handle); // skip header row

        $allRows = [];
        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 6) continue;
            $allRows[] = [
                'category'    => trim($row[0] ?? ''),
                'sub_category'=> trim($row[1] ?? ''),
                'barcode'     => trim($row[2] ?? ''),
                'name'        => trim($row[3] ?? ''),
                'unit'        => trim($row[4] ?? ''),
                'price'       => trim($row[5] ?? ''),
                'tax'         => trim($row[6] ?? ''),
                'brand_col'   => trim($row[7] ?? ''),
                'description' => trim($row[8] ?? ''),
            ];
        }
        fclose($handle);

        $this->info("Total rows to process: " . count($allRows));

        $this->info("\n── First 3 rows preview ──");
        foreach (array_slice($allRows, 0, 3) as $i => $r) {
            $brand = $this->detectBrand($r['name'], $r['brand_col']);
            $this->line(sprintf(
                "Row %d: cat=[%s] name=[%s] brand=[%s] unit=[%s] price=[%s]",
                $i + 1,
                $r['category'], $r['name'],
                $brand ?? 'none', $r['unit'], $r['price']
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
        fputcsv($skippedFH, ['row', 'reason', 'category', 'barcode', 'name', 'unit', 'price', 'tax']);

        $created     = $updated = $skipped = 0;
        $skipReasons = [];

        $bar = $this->output->createProgressBar(count($allRows));
        $bar->start();

        DB::beginTransaction();
        try {
            foreach ($allRows as $idx => $r) {

                $rawCat  = strtoupper(trim($r['category']));
                $barcode = $r['barcode'];
                $name    = $r['name'];
                $unitRaw = strtolower(trim($r['unit']));
                $priceV  = $r['price'];
                $taxRaw  = $r['tax'];

                $rowNum = $idx + 2;

                $skip = function (string $reason) use (&$skipped, &$skipReasons, $skippedFH, $rowNum, $r, $bar) {
                    $skipReasons[] = "Row {$rowNum}: {$reason}";
                    fputcsv($skippedFH, [
                        $rowNum, $reason,
                        $r['category'], $r['barcode'],
                        $r['name'], $r['unit'], $r['price'], $r['tax'],
                    ]);
                    $skipped++;
                    $bar->advance();
                };

                // ── Validate name ─────────────────────────────────────────────
                if (strlen($name) < 2) {
                    $skip('blank name'); continue;
                }

                // ── Validate price (0 is allowed) ─────────────────────────────
                if (! is_numeric($priceV)) {
                    $skip("non-numeric price ['{$priceV}']"); continue;
                }

                $price   = (float)$priceV;
                $taxRate = is_numeric($taxRaw) ? (float)$taxRaw : 0;
                $barcode = strlen($barcode) > 2 ? $barcode : null;

                // ── Normalize unit ────────────────────────────────────────────
                $unit = $this->units[$unitRaw] ?? 'nos';

                // ── Resolve category ──────────────────────────────────────────
                $catName = $this->categoryMap[$rawCat] ?? null;

                if (! $catName) {
                    // Strip trailing word and retry (handles "RICE & CEREALS Rice" etc.)
                    $catName = $this->categoryMap[preg_replace('/\s+\S+$/', '', $rawCat)] ?? null;
                }

                if (! $catName) {
                    $skip("unknown category '{$rawCat}'"); continue;
                }

                $cat = Category::where('name', $catName)
                    ->whereNull('parent_id')
                    ->first();

                if (! $cat) {
                    $skip("category '{$catName}' not found in DB"); continue;
                }

                // ── Detect brand ──────────────────────────────────────────────
                $brandId   = null;
                $brandName = $this->detectBrand($name, $r['brand_col']);
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
                    'description'     => $r['description'] ?: $name,
                    'category_id'     => $cat->id,
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
