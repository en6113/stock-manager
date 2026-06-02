<?php

namespace App\Models;

use App\Models\Item;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'status',
        'ordered_qty',
        'ordered_date',
        'vendor_id',
        'received_date',
        'expiration_date',
        'lot_number',
    ];

    protected $casts = [
        'status' => 'integer',
        'ordered_date' => 'date',
        'received_date' => 'date',
    ];

    /**
     * この在庫管理に属する商品を取得
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * この在庫管理に属する発注業者を取得
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
}
