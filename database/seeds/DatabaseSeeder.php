<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            ProfilesTableSeeder::class,
            ProductsTableSeeder::class,
            StocksTableSeeder::class,
            RemindersTableSeeder::class,
            CategorySeeder::class,
            VariantGroupSeeder::class,
            VariantSeeder::class
        ]);
    }
}
