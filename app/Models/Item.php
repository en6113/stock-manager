<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'item_category_id',
        'target_stock_qty',
        'unit',
        'capacity',
        'storage_location',
    ];

    /**
     * 食材は特定のカテゴリーに紐づく
     */
    public function itemCategory(): BelongsTo
    {
        return $this->BelongsTo(ItemCategory::class);
    }

    /**
     * この食材に含まれるアレルゲン物質を取得
     */
    public function allergens(): BelongsToMany
    {
        return $this->belongsToMany(Allergen::class,'allergen_item');
    }

    /**
     * この食材が属するメニューを取得
     */
    public function menus(): belongsToMany
    {
        return $this->belongsToMany(Menu::class,'item_menu');
    }

    /**
     * この食材の在庫を取得
     */
    public function stock(): HasOne
    {
        return $this->hasOne(Stock::class);
    }

    /**
     * この食材の発注履歴を取得
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
