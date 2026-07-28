<?php

namespace Database\Seeders;

use App\Models\MealPlanMenu;
use Illuminate\Database\Seeder;

class MealPlanMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mealPlanMenus = [
            [
                'meal_plan_id' => 1,
                'menu_id' => 1,
                'servings' => 50,
            ],
            [
                'meal_plan_id' => 1,
                'menu_id' => 2,
                'servings' => 50,
            ],
            [
                'meal_plan_id' => 2,
                'menu_id' => 1,
                'servings' => 50,
            ],
            [
                'meal_plan_id' => 2,
                'menu_id' => 2,
                'servings' => 50,
            ],
        ];

        foreach ($mealPlanMenus as $mealPlanMenu) {
            MealPlanMenu::create($mealPlanMenu);
        }
    }
}
