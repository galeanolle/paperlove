<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            [
                'id'=>1,
                'name'=>'AIR JORDAN 1 X OFF-WHITE NRG "OFF WHITE UNC"',
                'price'=>1375,
                'image'=>'products/1.jpg',
                'category_id'=>4,
                'quantity'=>1,
                'slug'=>Str::slug('AIR JORDAN 1 X OFF-WHITE NRG "OFF WHITE UNC"')
            ]
        ]);
    }
}