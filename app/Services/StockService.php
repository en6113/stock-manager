<?php

namespace App\Services;

use App\Models\Item;
use Illuminate\Database\Eloquent\Collection;

class StockService
{
    /**
    * 在庫管理一覧用のデータを取得
    * * @return Collection
    */
    public function getStockList(): Collection
    {
        // モデルに定義したスコープをチェーンしてデータを取得
        $items = Item::with(['allergens', 'stock'])
            ->withRequiredQty() // 必要量（明日以降の使用予定量）
            ->withReceivedQty() // 納品済の数量
            ->withCookedQty() // 調理済みの数量
            ->withOrderedQty() // 発注中の数量
            ->withCount('stock')
            ->get();

        // 在庫数の計算や発注必要性の判定するロジック
        return $items->transform(function ($item) {
            $required = $item->required_qty ?? 0; // 必要量
            $received = $item->received_qty ?? 0; // 納品済
            $cooked = $item->cooked_qty ?? 0; // 調理済
            $ordered = $item->pending_ordered_qty ?? 0; // 発注中

            // 在庫数 = 納品済 - 調理済
            $item->calculated_stock_qty = $received - $cooked;

            // 発注の必要性判定
            $item->is_low_stock = ($item->calculated_stock_qty + $ordered) < ($required + ($item->proper_inventory ?? 0));

            // 在庫データの存在判定
            $item->has_stock = $item->stock_count > 0;

            return $item;
        });
    }
}