<?php

namespace App\Models;

use App\Models\Item;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'order_id',
        'vendor_id',
        'stock',
    ];

    /**
     * この在庫管理に属する商品を取得
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * この在庫管理に属する発注歴を取得
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
