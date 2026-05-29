<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'target_stock_qty',
        'storage_location',
        'vendor_id',
    ];

    /**
     * このアイテムを販売する業者を取得
     */
    public function vendors(): belongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
}
