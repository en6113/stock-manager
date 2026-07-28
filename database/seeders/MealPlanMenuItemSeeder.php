<?php

namespace Database\Seeders;

use App\Models\MealPlanMenuItem;
use Illuminate\Database\Seeder;

class MealPlanMenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mealPlanMenuItems = [
            [
                'meal_plan_menu_id' => 1,
                'item_id' => 1,
                'adjust_amount' => 3000,
            ],
            [
                'meal_plan_menu_id' => 1,
                'item_id' => 2,
                'adjust_amount' => 3000,
            ],
            [
                'meal_plan_menu_id' => 1,
                'item_id' => 3,
                'adjust_amount' => 750,
            ],
            [
                'meal_plan_menu_id' => 1,
                'item_id' => 4,
                'adjust_amount' => 750,
            ],
            [
                'meal_plan_menu_id' => 1,
                'item_id' => 5,
                'adjust_amount' => 5,
            ],
            [
                'meal_plan_menu_id' => 1,
                'item_id' => 6,
                'adjust_amount' => 50,
            ],
            [
                'meal_plan_menu_id' => 2,
                'item_id' => 7,
                'adjust_amount' => 3500,
            ],
            [
                'meal_plan_menu_id' => 2,
                'item_id' => 8,
                'adjust_amount' => 550,
            ],
            [
                'meal_plan_menu_id' => 2,
                'item_id' => 9,
                'adjust_amount' => 600,
            ],
            [
                'meal_plan_menu_id' => 2,
                'item_id' => 10,
                'adjust_amount' => 100,
            ],
            [
                'meal_plan_menu_id' => 2,
                'item_id' => 11,
                'adjust_amount' => 300,
            ],
            [
                'meal_plan_menu_id' => 3,
                'item_id' => 1,
                'adjust_amount' => 3000,
            ],
            [
                'meal_plan_menu_id' => 3,
                'item_id' => 2,
                'adjust_amount' => 3000,
            ],
            [
                'meal_plan_menu_id' => 3,
                'item_id' => 3,
                'adjust_amount' => 750,
            ],
            [
                'meal_plan_menu_id' => 3,
                'item_id' => 4,
                'adjust_amount' => 750,
            ],
            [
                'meal_plan_menu_id' => 3,
                'item_id' => 5,
                'adjust_amount' => 5,
            ],
            [
                'meal_plan_menu_id' => 3,
                'item_id' => 6,
                'adjust_amount' => 50,
            ],
            [
                'meal_plan_menu_id' => 4,
                'item_id' => 7,
                'adjust_amount' => 3500,
            ],
            [
                'meal_plan_menu_id' => 4,
                'item_id' => 8,
                'adjust_amount' => 550,
            ],
            [
                'meal_plan_menu_id' => 4,
                'item_id' => 9,
                'adjust_amount' => 600,
            ],
            [
                'meal_plan_menu_id' => 4,
                'item_id' => 10,
                'adjust_amount' => 100,
            ],
            [
                'meal_plan_menu_id' => 4,
                'item_id' => 11,
                'adjust_amount' => 300,
            ],
        ];

        foreach ($mealPlanMenuItems as $mealPlanMenuItem) {
            MealPlanMenuItem::create($mealPlanMenuItem);
        }
    }
}
