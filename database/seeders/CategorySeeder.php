<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $now = now();

        // ── Parent categories ─────────────────────────────────
        DB::table('categories')->insert([
            ['id'=>1, 'name'=>'Fragrance',              'slug'=>'fragrance',               'parent_id'=>null,'is_active'=>1,'sort_order'=>1, 'created_at'=>$now,'updated_at'=>$now],
            ['id'=>2, 'name'=>'Canned Foods',            'slug'=>'canned-foods',            'parent_id'=>null,'is_active'=>1,'sort_order'=>2, 'created_at'=>$now,'updated_at'=>$now],
            ['id'=>3, 'name'=>'Charcoal & Flames',       'slug'=>'charcoal-flames',         'parent_id'=>null,'is_active'=>1,'sort_order'=>3, 'created_at'=>$now,'updated_at'=>$now],
            ['id'=>4, 'name'=>'Chocolates',              'slug'=>'chocolates',              'parent_id'=>null,'is_active'=>1,'sort_order'=>4, 'created_at'=>$now,'updated_at'=>$now],
            ['id'=>5, 'name'=>'Cleaning Products',       'slug'=>'cleaning-products',       'parent_id'=>null,'is_active'=>1,'sort_order'=>5, 'created_at'=>$now,'updated_at'=>$now],
            ['id'=>6, 'name'=>'Custards & Pudding Mix',  'slug'=>'custards-pudding-mix',    'parent_id'=>null,'is_active'=>1,'sort_order'=>6, 'created_at'=>$now,'updated_at'=>$now],
            ['id'=>7, 'name'=>'Dairy',                   'slug'=>'dairy',                   'parent_id'=>null,'is_active'=>1,'sort_order'=>7, 'created_at'=>$now,'updated_at'=>$now],
            ['id'=>8, 'name'=>'Dals',                    'slug'=>'dals',                    'parent_id'=>null,'is_active'=>1,'sort_order'=>8, 'created_at'=>$now,'updated_at'=>$now],
            ['id'=>9, 'name'=>'Dry Fruits',              'slug'=>'dry-fruits',              'parent_id'=>null,'is_active'=>1,'sort_order'=>9, 'created_at'=>$now,'updated_at'=>$now],
            ['id'=>10,'name'=>'Edible Oil',              'slug'=>'edible-oil',              'parent_id'=>null,'is_active'=>1,'sort_order'=>10,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>11,'name'=>'Egg',                     'slug'=>'egg',                     'parent_id'=>null,'is_active'=>1,'sort_order'=>11,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>12,'name'=>'Essence',                 'slug'=>'essence',                 'parent_id'=>null,'is_active'=>1,'sort_order'=>12,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>13,'name'=>'Frozen',                  'slug'=>'frozen',                  'parent_id'=>null,'is_active'=>1,'sort_order'=>13,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>14,'name'=>'Frozen Fish',             'slug'=>'frozen-fish',             'parent_id'=>null,'is_active'=>1,'sort_order'=>14,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>15,'name'=>'Grocery',                 'slug'=>'grocery',                 'parent_id'=>null,'is_active'=>1,'sort_order'=>15,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>16,'name'=>'Grocery 2',               'slug'=>'grocery-2',               'parent_id'=>null,'is_active'=>1,'sort_order'=>16,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>17,'name'=>'Kitchen Utility',         'slug'=>'kitchen-utility',         'parent_id'=>null,'is_active'=>1,'sort_order'=>17,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>18,'name'=>'Noodles',                 'slug'=>'noodles',                 'parent_id'=>null,'is_active'=>1,'sort_order'=>18,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>19,'name'=>'Nuts',                    'slug'=>'nuts',                    'parent_id'=>null,'is_active'=>1,'sort_order'=>19,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>20,'name'=>'Office Accessories',      'slug'=>'office-accessories',      'parent_id'=>null,'is_active'=>1,'sort_order'=>20,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>21,'name'=>'Packing',                 'slug'=>'packing',                 'parent_id'=>null,'is_active'=>1,'sort_order'=>21,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>22,'name'=>'Rice',                    'slug'=>'rice',                    'parent_id'=>null,'is_active'=>1,'sort_order'=>22,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>23,'name'=>'Spices',                  'slug'=>'spices',                  'parent_id'=>null,'is_active'=>1,'sort_order'=>23,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>24,'name'=>'Vegetables',              'slug'=>'vegetables',              'parent_id'=>null,'is_active'=>1,'sort_order'=>24,'created_at'=>$now,'updated_at'=>$now],
            // ── New parent categories ──────────────────────────
            ['id'=>53,'name'=>'Pickles',                 'slug'=>'pickles',                 'parent_id'=>null,'is_active'=>1,'sort_order'=>25,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>54,'name'=>'Tea Bags',                'slug'=>'tea-bags',                'parent_id'=>null,'is_active'=>1,'sort_order'=>26,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>55,'name'=>'Hot Drinks',              'slug'=>'hot-drinks',              'parent_id'=>null,'is_active'=>1,'sort_order'=>27,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>56,'name'=>'Soft Drinks',             'slug'=>'soft-drinks',             'parent_id'=>null,'is_active'=>1,'sort_order'=>28,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>57,'name'=>'Water',                   'slug'=>'water',                   'parent_id'=>null,'is_active'=>1,'sort_order'=>29,'created_at'=>$now,'updated_at'=>$now],
        ]);

        // ── Sub-categories ────────────────────────────────────
        DB::table('categories')->insert([
            ['id'=>25,'name'=>'Vegetables',     'slug'=>'vegetables-2',       'parent_id'=>2, 'is_active'=>1,'sort_order'=>1, 'created_at'=>$now,'updated_at'=>$now],
            ['id'=>26,'name'=>'Fruits',         'slug'=>'fruits-2',           'parent_id'=>2, 'is_active'=>1,'sort_order'=>2, 'created_at'=>$now,'updated_at'=>$now],
            ['id'=>27,'name'=>'Others',         'slug'=>'others-2',           'parent_id'=>2, 'is_active'=>1,'sort_order'=>3, 'created_at'=>$now,'updated_at'=>$now],
            ['id'=>28,'name'=>'Ghee',           'slug'=>'ghee-7',             'parent_id'=>7, 'is_active'=>1,'sort_order'=>4, 'created_at'=>$now,'updated_at'=>$now],
            ['id'=>29,'name'=>'Yogurt',         'slug'=>'yogurt-7',           'parent_id'=>7, 'is_active'=>1,'sort_order'=>5, 'created_at'=>$now,'updated_at'=>$now],
            ['id'=>30,'name'=>'Oil',            'slug'=>'oil-10',             'parent_id'=>10,'is_active'=>1,'sort_order'=>6, 'created_at'=>$now,'updated_at'=>$now],
            ['id'=>31,'name'=>'Vegetables',     'slug'=>'vegetables-13',      'parent_id'=>13,'is_active'=>1,'sort_order'=>7, 'created_at'=>$now,'updated_at'=>$now],
            ['id'=>32,'name'=>'Porotta',        'slug'=>'porotta-13',         'parent_id'=>13,'is_active'=>1,'sort_order'=>8, 'created_at'=>$now,'updated_at'=>$now],
            ['id'=>33,'name'=>'Coconut Items',  'slug'=>'coconut-items-13',   'parent_id'=>13,'is_active'=>1,'sort_order'=>9, 'created_at'=>$now,'updated_at'=>$now],
            ['id'=>34,'name'=>'Others',         'slug'=>'others-13',          'parent_id'=>13,'is_active'=>1,'sort_order'=>10,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>35,'name'=>'Flour',          'slug'=>'flour-15',           'parent_id'=>15,'is_active'=>1,'sort_order'=>11,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>36,'name'=>'Powders',        'slug'=>'powders-15',         'parent_id'=>15,'is_active'=>1,'sort_order'=>12,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>37,'name'=>'Pickles',        'slug'=>'pickles-15',         'parent_id'=>15,'is_active'=>1,'sort_order'=>13,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>38,'name'=>'Chutney',        'slug'=>'chutney-15',         'parent_id'=>15,'is_active'=>1,'sort_order'=>14,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>39,'name'=>'Pappadam',       'slug'=>'pappadam-15',        'parent_id'=>15,'is_active'=>1,'sort_order'=>15,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>40,'name'=>'Coconut Items',  'slug'=>'coconut-items-15',   'parent_id'=>15,'is_active'=>1,'sort_order'=>16,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>41,'name'=>'Dal',            'slug'=>'dal-15',             'parent_id'=>15,'is_active'=>1,'sort_order'=>17,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>42,'name'=>'Dairy',          'slug'=>'dairy-15',           'parent_id'=>15,'is_active'=>1,'sort_order'=>18,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>43,'name'=>'Dry Fruits',     'slug'=>'dry-fruits-15',      'parent_id'=>15,'is_active'=>1,'sort_order'=>19,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>44,'name'=>'Others',         'slug'=>'others-15',          'parent_id'=>15,'is_active'=>1,'sort_order'=>20,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>45,'name'=>'Sev Items',      'slug'=>'sev-items-16',       'parent_id'=>16,'is_active'=>1,'sort_order'=>21,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>46,'name'=>'Food Colour',    'slug'=>'food-colour-16',     'parent_id'=>16,'is_active'=>1,'sort_order'=>22,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>47,'name'=>'Others',         'slug'=>'others-16',          'parent_id'=>16,'is_active'=>1,'sort_order'=>23,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>48,'name'=>'Containers',     'slug'=>'containers-21',      'parent_id'=>21,'is_active'=>1,'sort_order'=>24,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>49,'name'=>'Vegetables',     'slug'=>'vegetables-24',      'parent_id'=>24,'is_active'=>1,'sort_order'=>25,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>50,'name'=>'Fruits',         'slug'=>'fruits-24',          'parent_id'=>24,'is_active'=>1,'sort_order'=>26,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>51,'name'=>'Leaves & Herbs', 'slug'=>'leaves-herbs-24',    'parent_id'=>24,'is_active'=>1,'sort_order'=>27,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>52,'name'=>'Noodles',        'slug'=>'noodles-18',         'parent_id'=>18,'is_active'=>1,'sort_order'=>28,'created_at'=>$now,'updated_at'=>$now],
        ]);

        $this->command->info('✅ '.DB::table('categories')->count().' categories seeded.');
    }
}
