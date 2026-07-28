<?php

namespace App\Models;

use App\Enums\StorageLocation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
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
        'proper_inventory',
        'unit',
        'capacity',
        'storage_location',
        'vendor_id',
    ];

    protected $casts = [
        'storage_location' => StorageLocation::class,
    ];

    /**
     * この食材が属するカテゴリー（親）
     */
    public function itemCategory(): BelongsTo
    {
        return $this->BelongsTo(ItemCategory::class);
    }

    /**
     * この食材が属する業者（親）
     */
    public function vendor(): BelongsTo
    {
        return $this->BelongsTo(Vendor::class);
    }

    /**
     * この食材に関連するアレルゲン（多対多）
     */
    public function allergens(): BelongsToMany
    {
        return $this->belongsToMany(Allergen::class,'allergen_item');
    }

    /**
     * この食材が登録されているメニュー（多対多）
     */
    public function menus(): BelongsToMany
    {
        return $this->belongsToMany(Menu::class,'item_menu');
    }

    /**
     * この食材に紐づく献立メニュー詳細（1対多）
     */
    public function mealPlanMenuItems(): HasMany
    {
        return $this->HasMany(MealPlanMenuItem::class);
    }

    /**
     * この食材の在庫調整（1対1）
     */
    public function stockAdjustments(): HasMany
    {
        return $this->hasMany(StockAdjustment::class);
    }

    /**
     * この食材が紐づく発注履歴（1対多）
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * キーワード検索スコープ
     */
    public function scopeKeywordSearch(Builder $query, ?string $keyword) :Builder
    {
        if (blank($keyword)) {
            return $query;
        }

        return $query->where(function ($q) use ($keyword) {
            $q->where('name', 'like', '%' . $keyword . '%');
        });
    }

    /**
     * カテゴリー検索スコープ
     */
    public function scopeCategorySearch(Builder $query, ?int $category) :Builder
    {
        if(blank($category)) {
            return $query;
        }

        return $query->where('item_category_id', $category);
    }

    /**
     * スコープ：必要量（明日以降の献立メニューで使用する量）
     */
    public function scopeWithRequiredQty($query) :Builder
    {
        return $query->withSum(['mealPlanMenuItems as required_qty' => function($q) {
            $q->whereHas('mealPlanMenu.mealPlan', function($subQ) {
                $subQ->where('date', '>', now()->toDateString());
            });
        }], 'adjust_amount');
    }

    /**
     * スコープ：発注中の量（発注量のうち、ステータスが1:発注中（未納品）のもの）
     */
    public function scopeWithOrderedQty($query): Builder
    {
        return $query->withSum(['orders as pending_ordered_qty' => function ($q) {
                $q->whereNull('received_date')->whereNotNull('ordered_date');
        }], 'ordered_qty');
    }

    /**
     * スコープ：納品済の数量（発注量のうち、ステータスが2:納品済のもの）
     */
    public function scopeWithReceivedQty($query) :Builder
    {
        return $query->withSum(['orders as received_qty' => function ($q) {
            $q->whereNotNull('received_date');
        }], 'ordered_qty');
    }

    /**
     * スコープ：調理済の数量（今日までの献立メニューで使用した量）
     */
    public function scopeWithCookedQty($query) :Builder
    {
        return $query->withSum(['mealPlanMenuItems as cooked_qty' => function ($q) {
                $q->whereHas('mealPlanMenu.mealPlan', function ($subQ) {
                    $subQ->where('date', '<', now()->toDateString());
                });
        }], 'adjust_amount');
    }

    /**
     * スコープ：調整合計
     */
    public function scopeWithAdjustedQty($query): Builder
    {
        return $query->withSum('stockAdjustments as adjusted_qty', 'quantity_g');
    }
}
