<?php

use Illuminate\Database\Seeder;

class VariantGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('variant_groups')->insert([
            [
                'id'=>1,
                'name'=>'General'
            ],
            [
                'id'=>2,
                'name'=>'Modelos IPhone'
            ]
        ]);
    }
}
