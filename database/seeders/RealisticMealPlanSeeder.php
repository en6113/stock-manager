<?php

namespace Database\Seeders;

use App\Models\MealPlan;
use App\Models\MealPlanMenu;
use App\Models\MealPlanMenuItem;
use App\Models\Menu;
use Illuminate\Database\Seeder;

/**
 * 直近30日分の献立カレンダーを、日によって人数（servings）が異なる
 * 現実に近い形で生成する追加シード
 */
class RealisticMealPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = Menu::with('items')->get();

        if ($menus->isEmpty()) {
            return;
        }

        $startDate = now()->subDays(29)->startOfDay();

        for ($i = 0; $i < 30; $i++) {
            $mealPlan = MealPlan::create([
                'date' => $startDate->copy()->addDays($i),
            ]);

            // 日によって人数が変動するようにする（欠席・行事等を想定）
            $servings = rand(40, 50);

            foreach ($menus->random(min(3, $menus->count())) as $menu) {
                $mealPlanMenu = MealPlanMenu::create([
                    'meal_plan_id' => $mealPlan->id,
                    'menu_id' => $menu->id,
                    'servings' => $servings,
                ]);

                foreach ($menu->items as $item) {
                    $perPersonAmount = $item->pivot->servings > 0
                        ? $item->pivot->required_amount / $item->pivot->servings
                        : 0;

                    MealPlanMenuItem::create([
                        'meal_plan_menu_id' => $mealPlanMenu->id,
                        'item_id' => $item->id,
                        'adjust_amount' => round($perPersonAmount * $servings, 1),
                    ]);
                }
            }
        }
    }
}
