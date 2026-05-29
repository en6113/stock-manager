<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        return $this->belongsToMany(Allergen::class);
    }
}
