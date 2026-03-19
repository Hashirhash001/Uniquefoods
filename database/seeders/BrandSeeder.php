<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('brands')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $now = now();

        DB::table('brands')->insert([
            ['id'=>1,'name'=>'TRS','slug'=>'trs','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>2,'name'=>'Tropical Sun','slug'=>'tropical-sun','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>3,'name'=>'Heera','slug'=>'heera','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>4,'name'=>'KTC','slug'=>'ktc','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>5,'name'=>'Viswas','slug'=>'viswas','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>6,'name'=>'Daily Delight','slug'=>'daily-delight','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>7,'name'=>'Shankar','slug'=>'shankar','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>8,'name'=>'Patak','slug'=>'patak','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>9,'name'=>'MDH','slug'=>'mdh','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>10,'name'=>'Idayam','slug'=>'idayam','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>11,'name'=>'Pride','slug'=>'pride','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>12,'name'=>'Natco','slug'=>'natco','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>13,'name'=>'Bala','slug'=>'bala','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>14,'name'=>'Aashirvaad','slug'=>'aashirvaad','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>15,'name'=>'Elephant','slug'=>'elephant','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>16,'name'=>'Laila','slug'=>'laila','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>17,'name'=>'Khanum','slug'=>'khanum','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>18,'name'=>'Maggi','slug'=>'maggi','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>19,'name'=>'Dettol','slug'=>'dettol','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>20,'name'=>'Nilco','slug'=>'nilco','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>21,'name'=>'Cleanux','slug'=>'cleanux','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>22,'name'=>'Flash','slug'=>'flash','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>23,'name'=>'Harpic','slug'=>'harpic','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>24,'name'=>'Deepio','slug'=>'deepio','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>25,'name'=>'Rainbow','slug'=>'rainbow','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>26,'name'=>'Colmans','slug'=>'colmans','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>27,'name'=>'Pegasus','slug'=>'pegasus','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>28,'name'=>'Tiger','slug'=>'tiger','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>29,'name'=>'Big K','slug'=>'big-k','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>30,'name'=>'Quality Street','slug'=>'quality-street','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>31,'name'=>'Shana','slug'=>'shana','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>32,'name'=>'Ardo','slug'=>'ardo','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>33,'name'=>'Taj','slug'=>'taj','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>34,'name'=>'Suvai','slug'=>'suvai','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>35,'name'=>'TYJ','slug'=>'tyj','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>36,'name'=>'Gits','slug'=>'gits','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>37,'name'=>'Laziza','slug'=>'laziza','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>38,'name'=>'MTR','slug'=>'mtr','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>39,'name'=>'Eastern','slug'=>'eastern','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>40,'name'=>'Ashoka','slug'=>'ashoka','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>41,'name'=>'Priya','slug'=>'priya','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>42,'name'=>'Geeta\'s','slug'=>'geetas','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>43,'name'=>'Cirio','slug'=>'cirio','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>44,'name'=>'Chaokoh','slug'=>'chaokoh','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>45,'name'=>'Aroy-D','slug'=>'aroy-d','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>46,'name'=>'VVR','slug'=>'vvr','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>47,'name'=>'Sakthi','slug'=>'sakthi','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>48,'name'=>'Kings','slug'=>'kings','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>49,'name'=>'Naga','slug'=>'naga','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>50,'name'=>'Haldiram\'s','slug'=>'haldirams','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>51,'name'=>'Cycle Brand','slug'=>'cycle-brand','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>52,'name'=>'Dalda','slug'=>'dalda','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>53,'name'=>'Amoy','slug'=>'amoy','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>54,'name'=>'Hellmann\'s','slug'=>'hellmanns','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>55,'name'=>'Tetley','slug'=>'tetley','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>56,'name'=>'Nescafe','slug'=>'nescafe','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>57,'name'=>'Horlicks','slug'=>'horlicks','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>58,'name'=>'PG Tips','slug'=>'pg-tips','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>59,'name'=>'Bru','slug'=>'bru','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>60,'name'=>'Marigold','slug'=>'marigold','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>61,'name'=>'Carnation','slug'=>'carnation','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>62,'name'=>'Tate & Lyle','slug'=>'tate-lyle','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>63,'name'=>'Lion','slug'=>'lion','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>64,'name'=>'Knorr','slug'=>'knorr','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>65,'name'=>'Pascali','slug'=>'pascali','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>66,'name'=>'Vandevi','slug'=>'vandevi','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>67,'name'=>'Aachi','slug'=>'aachi','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>68,'name'=>'Jaimin','slug'=>'jaimin','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>69,'name'=>'Armaan','slug'=>'armaan','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>70,'name'=>'Marine','slug'=>'marine','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>71,'name'=>'Boiron','slug'=>'boiron','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>72,'name'=>'Fudco','slug'=>'fudco','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>73,'name'=>'Shan','slug'=>'shan','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>74,'name'=>'Ahmed','slug'=>'ahmed','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>75,'name'=>'Josh','slug'=>'josh','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>76,'name'=>'Indusri','slug'=>'indusri','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>77,'name'=>'White Pearl','slug'=>'white-pearl','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>78,'name'=>'Lazziza','slug'=>'lazziza','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>79,'name'=>'H-Boy','slug'=>'h-boy','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>80,'name'=>'Zafron','slug'=>'zafron','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>81,'name'=>'Mr Naga','slug'=>'mr-naga','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>82,'name'=>'Pachraanga','slug'=>'pachraanga','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>83,'name'=>'Papa','slug'=>'papa','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>84,'name'=>'Malabar Treat','slug'=>'malabar-treat','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
        ]);

        $this->command->info('✅ '.DB::table('brands')->count().' brands seeded.');
    }
}
