<?php

namespace Database\Seeders;

use App\Models\MealPlan;
use Illuminate\Database\Seeder;

class MealPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mealPlans = [
            [
                'date' => now()->subDay(),
            ],
            [
                'date' => now()->addDay(),
            ],
        ];

        foreach ($mealPlans as $mealPlan) {
            MealPlan::create($mealPlan);
        }
    }
}
