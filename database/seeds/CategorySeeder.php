<?php

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            [
                'id'=>1,
                'id_parent'=>0,
                'name'=>'PACK CON FUNDA',
                'slug'=>'pack-con-funda'
            ],
            [
                'id'=>2,
                'id_parent'=>0,
                'name'=>'PACK SIN FUNDA',
                'slug'=>'pack-sin-funda'
            ],
            [
                'id'=>3,
                'id_parent'=>0,
                'name'=>'CÁPSULAS',
                'slug'=>'capsulas'
            ],
            [
                'id'=>4,
                'id_parent'=>1,
                'name'=>'IPHONE',
                'slug'=>'iphone'
            ],
            [
                'id'=>5,
                'id_parent'=>2,
                'name'=>'IPHONE',
                'slug'=>'iphone'
            ],
            [
                'id'=>6,
                'id_parent'=>3,
                'name'=>'IPHONE',
                'slug'=>'iphone'
            ],
            [
                'id'=>7,
                'id_parent'=>1,
                'name'=>'MOTOROLA',
                'slug'=>'motorola'
            ],
            [
                'id'=>8,
                'id_parent'=>1,
                'name'=>'SAMSUNG',
                'slug'=>'samsung'
            ],

        ]);
    }
}
