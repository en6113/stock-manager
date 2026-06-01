<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            VendorSeeder::class,
            AllergenSeeder::class,
            MenuSeeder::class,
            ItemSeeder::class,
            MealPlanSeeder::class,
            StockSeeder::class,
        ]);
    }
}
