<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MealPlanMenu extends Model
{
    protected $table = 'meal_plan_menu';

    public function mealPlan()
    {
        return $this->belongsTo(MealPlan::class, 'meal_plan_id');
    }
}
