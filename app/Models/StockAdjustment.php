<?php

namespace App\Models;

use App\Enums\AdjustmentReason;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockAdjustment extends Model
{
    protected $fillable = [
        'item_id',
        'quantity_g',
        'reason',
        'note',
        'user_id',
        'adjusted_on',
    ];

    protected $casts = [
        'reason' => AdjustmentReason::class,
        'adjusted_on' => 'date',
        'quantity_g' => 'decimal:3',
    ];

    /**
     * この在庫調整は商品に属する
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * この在庫調整はユーザーに属する
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * スコープ:調整日検索
     */
    public function scopeAdjustedOnSearch($query, ?string $date)
    {
        return blank($date) ? $query : $query->whereDate('adjusted_on', $date);
    }

    /**
     * スコープ:食品名検索
     */
    public function scopeItemSearch($query, ?int $itemId)
    {
        return blank($itemId) ? $query : $query->where('item_id', $itemId);
    }

    /**
     * スコープ:調整理由フィルター
     */
    public function scopeReasonFilter($query, ?string $reason)
    {
        return blank($reason) ? $query : $query->where('reason', $reason);
    }
}
