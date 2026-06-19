<?php

namespace App\Models;

use App\Models\Item;
use App\Models\MealPlan;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class MealPlanMenuItem extends Model
{
    protected $table = 'meal_plan_menu_item';

    protected $fillable = [
        'meal_plan_menu_id',
        'item_id',
        'adjust_amount',
    ];

    /*
     * この献立メニュー詳細が属する食材
    */
    public function item() : BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /*
     * この献立メニュー詳細が属する献立メニュー
     */
    public function mealPlanMenu() : BelongsTo
    {
        return $this->belongsTo(MealPlanMenu::class, 'meal_plan_menu_id');
    }
}
