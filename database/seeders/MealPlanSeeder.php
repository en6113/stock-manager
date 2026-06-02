<?php

namespace Database\Seeders;

use App\Models\MealPlan;
use App\Models\Menu;
use Carbon\CarbonPeriod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class MealPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $curry = Menu::where('name', 'カレー')->first();
        $salad = Menu::where('name', 'ツナマヨコーンサラダ')->first();

        $mealPlan = MealPlan::forceCreate([
            'date' => Carbon::create(2026, 6, 30)->format('Y-m-d'),
        ]);

        // 中間テーブル（meal_plan_menu）にメニューのIDを紐付ける
        if ($curry && $salad) {
            $mealPlan->menus()->attach([$curry->id, $salad->id]);
        }
    }
}
