<?php

namespace App\Models;

use App\Models\Item;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class MealPlanMenuItem extends Model
{
    protected $table = 'meal_plan_menu_item';

    protected $fillable = [
        'meal_plan_menu_id',
        'item_id',
        'servings',
        'adjust_amount',
    ];

    // この献立に紐づく食材を取得する
    public function item() : belongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
