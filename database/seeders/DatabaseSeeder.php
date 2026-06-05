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
            ItemCategorySeeder::class,
            DishCategorySeeder::class,
            MenuSeeder::class,
            MealPlanSeeder::class,
            ItemSeeder::class,
            StockSeeder::class,
        ]);
    }
}
