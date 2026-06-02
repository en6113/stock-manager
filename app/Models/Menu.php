<?php

namespace App\Models;

use App\Models\Item;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
    public function items() : BelongsToMany
    {
        return $this->belongsToMany(Item::class,'item_menu', 'menu_id', 'item_id');
    }

    // カテゴリを数値から文字列に変換するアクセサ
    protected function dishCategoryLabel(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                $code = (int)$attributes['dish_category'];

                $categories = [
                    1 => '主菜',
                    2 => '副菜',
                    3 => '汁物',
                    4 => 'おやつ',
                ];
                return $categories[$code] ?? '不明';
            }
        );
    }

}
