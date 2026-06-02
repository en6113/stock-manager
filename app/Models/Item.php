<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'target_stock_qty',
        'unit',
        'capacity',
        'storage_location',
        'vendor_id',
    ];

    /**
     * このアイテムを販売する業者を取得
     */
    public function vendors(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * このアイテムに含まれるアレルゲン物質を取得
     */
    public function allergens(): BelongsToMany
    {
        return $this->belongsToMany(Allergen::class,'allergen_item');
    }

    /**
     * このアイテムが属するメニューを取得
     */
    public function menus(): belongsToMany
    {
        return $this->belongsToMany(Menu::class,'item_menu');
    }

    /**
     * このアイテムの在庫を取得
     */
    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    /**
     * このアイテムの発注履歴を取得
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * この食材の、本日以降の使用予定量（reserved_qty）を計算する
     */
    public function getReservedQty(): float
    {
        // 既に eager load や withSum で 'reserved_qty' カラムが取得できている場合はそれを返す
        if (array_key_exists('reserved_qty', $this->attributes)) {
            return (float) $this->reserved_qty;
        }

        // 取得されていない場合（edit画面など単一取得時）は、その場でクエリを発行して計算する
        return (float) $this->menus()
            ->join('meal_plan_menu', 'menus.id', '=', 'meal_plan_menu.menu_id')
            ->join('meal_plans', 'meal_plan_menu.meal_plan_id', '=', 'meal_plans.id')
            ->where('meal_plans.date', '>=', now()->toDateString())
            ->sum('item_menu.required_amount');
    }
}
