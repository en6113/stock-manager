<?php

namespace App\Models;

use App\Enums\DishCategory;
use App\Models\Item;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'dish_category',
        'calorie',
    ];

    protected $casts = [
        'dish_category' => DishCategory::class,
    ];

    // このメニューに関連する食材（多対多）
    public function items() : BelongsToMany
    {
        return $this->belongsToMany(Item::class,'item_menu', 'menu_id', 'item_id')
            ->withPivot('required_amount', 'servings');
    }

    /**
     * キーワード検索スコープ
     */
    public function scopeKeywordSearch(Builder $query, ?string $keyword): Builder
    {
        if (blank($keyword)) { // blank():値が空文字やnullの場合にクエリをそのまま返す
            return $query;
        }

        return $query->where(function ($q) use ($keyword) {
            $q->where('name', 'like', '%' . $keyword . '%');
        });
    }

    /**
     * カテゴリー検索スコープ
     */
    public function scopeCategorySearch(Builder $query, ?int $category): Builder
    {
        if (blank($category)) {
            return $query;
        }

        return $query->where('dish_category_id', $category);
    }
}
