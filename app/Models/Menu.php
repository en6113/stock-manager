<?php

namespace App\Models;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'menu',
        'dish_category',
        'calorie',
    ];

    // このメニューに属する食材を取得
    public function items() : HasMany
    {
        return $this->hasMany(Item::class);
    }

}
