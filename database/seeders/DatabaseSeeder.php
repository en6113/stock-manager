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
            MenuSeeder::class,
            ItemSeeder::class,
            MealPlanSeeder::class,
            MealPlanMenuSeeder::class,
            MealPlanMenuItemSeeder::class,
            RealisticItemMenuSeeder::class,
            RealisticMealPlanSeeder::class,
            OrderSeeder::class,
        ]);
    }
}
