<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Models\Item;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongsTo;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'item_id',
        'ordered_qty',
        'ordered_date',
        'vendor_id',
        'received_date',
        'expiration_date',
        'lot_number',
    ];

    protected $casts = [
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

    /**
     * ステータス検索スコープ
     */
    public function scopeStatusSearch(Builder $query, ?string $status): Builder
    {
        return match ($status) {
            'received' => $query->whereNotNull('received_date'),
            'ordered' => $query->whereNull('received_date')->whereNotNull('ordered_date'),
            'pending' => $query->whereNull('ordered_date'),
            default => $query,
        };
    }

    /**
     * 業者検索スコープ
     */
    public function scopeVendorSearch(Builder $query, ?int $vendor): Builder
    {
        if (blank($vendor)) {
            return $query;
        }

        return $query->where('vendor_id', $vendor);
    }

    /**
     * ステータスを自動変更するロジック
     */
    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn () => match(true) {
                filled($this->received_date) => OrderStatus::Received,
                filled($this->ordered_date) => OrderStatus::Ordered,
                default => OrderStatus::Pending,
            },
        );
    }
}
