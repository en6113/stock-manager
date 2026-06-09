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

    // この食材調整データが属する食材を取得する
    public function item() : belongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
