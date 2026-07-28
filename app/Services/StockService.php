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
        $items = Item::with(['allergens'])
            ->withRequiredQty() // 必要量（明日以降の使用予定量）
            ->withReceivedQty() // 納品済の数量
            ->withCookedQty() // 調理済みの数量
            ->withOrderedQty() // 発注中の数量
            ->withAdjustedQty() // 調整合計
            ->get();

        // 在庫数の計算や発注必要性の判定するロジック
        return $items->transform(function ($item) {
            $required_qty = $item->required_qty ?? 0; // 必要量
            $pending_ordered_qty = $item->pending_ordered_qty ?? 0; // 発注済

            // 在庫の計算式(納品済 - 調理済 + 調整合計)
            $item->current_stock = $this->calculateCurrentStock($item);

            // 発注の必要性判定（現在の在庫 + 発注済 < 必要量 + 適正在庫）
            $item->is_low_stock = (($item->current_stock ?? 0) + $pending_ordered_qty) < ($required_qty + ($item->proper_inventory ?? 0));

            // 在庫データの存在判定
            $item->has_stock = $item->current_stock > 0;

            return $item;
        });
    }

    /**
     * 指定した食材1件の現在庫数を取得（在庫調整フォームなど単品計算用）
     */
    public function getCurrentStock(Item $item): float
    {
        $item = Item::withReceivedQty()->withCookedQty()->withAdjustedQty()
            ->findOrFail($item->id);

        return $this->calculateCurrentStock($item);
    }

    /**
     * 在庫の計算式(納品済 - 調理済 + 調整合計)
     */
    private function calculateCurrentStock(Item $item): float
    {
        $received_qty = $item->received_qty ?? 0;
        $cooked_qty = $item->cooked_qty ?? 0;
        $adjusted_qty = $item->adjusted_qty ?? 0;

        return $received_qty - $cooked_qty + $adjusted_qty;
    }
}