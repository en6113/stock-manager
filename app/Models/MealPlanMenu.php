<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MealPlanMenu extends Model
{
    protected $table = 'meal_plan_menu';

    protected $fillable = [
        'meal_plan_id',
        'menu_id',
        'servings',
    ];

    /**
     * この献立メニューに紐づく日付（多対1）
     */
    public function mealPlan(): BelongsTo
    {
        return $this->belongsTo(MealPlan::class, 'meal_plan_id');
    }

    /**
     * この献立メニューで使用する食材とその調整後の量（1対多）
     */
    public function items(): HasMany
    {
        return $this->hasMany(MealPlanMenuItem::class);
    }
}
