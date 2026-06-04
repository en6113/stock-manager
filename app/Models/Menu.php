<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'category_id',
        'name',
        'calorie',
    ];

    // メニューは特定のカテゴリに属する
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // このメニューに属する食材を取得
    public function items() : BelongsToMany
    {
        return $this->belongsToMany(Item::class,'item_menu', 'menu_id', 'item_id')
            ->withPivot('required_amount');
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

        return $query->where('category_id', $category);
    }

    // カテゴリを数値から文字列に変換するアクセサ
    protected function dishCategoryLabel(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                $code = (int)$attributes['category_id'];

                $categories = [
                    1 => '主菜',
                    2 => '副菜',
                    3 => '汁物',
                    4 => 'その他',
                ];
                return $categories[$code] ?? '不明';
            }
        );
    }

}
