<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relation\BelongsTo;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'qty',
        'stock',
        'reserved_qty',
        'expiration_date',
        'lot_number',
        'delivery_date',
    ];

    /**
     * この在庫管理に属する商品を取得
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
