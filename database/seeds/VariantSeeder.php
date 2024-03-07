<?php

use Illuminate\Database\Seeder;

class VariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('variants')->insert([
            [
                'id'=>1,
                'variant_group_id'=>1,
                'name'=>'Genérico'
            ],
            [
                'id'=>2,
                'variant_group_id'=>2,
                'name'=>'12pro MAX'
            ],
            [
                'id'=>3,
                'variant_group_id'=>2,
                'name'=>'13'
            ]
        ]);

        
    }
}
