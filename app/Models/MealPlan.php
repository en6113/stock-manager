<?php

namespace App\Models;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MealPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
    ];

    // この献立に属するメニューを取得
    public function menus() : BelongsToMany
    {
        return $this->belongsToMany(Menu::class, 'meal_plan_menu');
    }
}
