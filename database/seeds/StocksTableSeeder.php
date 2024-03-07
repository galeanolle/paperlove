<?php

use Illuminate\Database\Seeder;

class StocksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('stocks')->insert([
            [
                'product_id'=>1,
                'variant_id'=>1,
                'quantity'=>100,
            ]
        ]);
    }
}