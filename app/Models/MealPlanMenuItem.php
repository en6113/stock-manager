<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MealPlanMenuItem extends Model
{
    protected $table = 'meal_plan_menu_item';

    protected $fillable = [
        'meal_plan_menu_id',
        'item_id',
        'adjust_amount',
    ];
}
