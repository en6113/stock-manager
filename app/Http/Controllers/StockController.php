<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Item;
use App\Models\Stock;

class StockController extends Controller
{
    /**
     * 在庫管理一覧
     */
    public function index()
    {
        // 食材ごとに必要な計算をサブクエリ（withSum等）でまとめて取得
        $items = Item::with(['allergens', 'stock'])
            // 使用予定量(明日以降のメニューのadjust_amountの合計)
            ->withSum(['menus as reserved_qty' => function ($query) {
                    $query->join('meal_plan_menu', 'menus.id', '=', 'meal_plan_menu.menu_id')
                        ->join('meal_plans', 'meal_plan_menu.meal_plan_id', '=', 'meal_plans.id')
                        ->join('meal_plan_menu_item', 'meal_plan_menu.id', '=', 'meal_plan_menu_item.meal_plan_menu_id')
                        ->where('meal_plans.date', '>=', now()->toDateString());
            }], 'meal_plan_menu_item.adjust_amount')

            // 納品済の数量（status = 2 の合計）
            ->withSum([
                'orders as received_qty' => function ($query) {
                    $query->where('status', '2');
            }], 'ordered_qty')

            // 調理済みの数量（今日より前の adjust_amount の合計）
            ->withSum(['menus as cooked_adjust_amount' => function ($query) {
                $query->join('meal_plan_menu', 'menus.id', '=', 'meal_plan_menu.menu_id')
                    ->join('meal_plans', 'meal_plan_menu.meal_plan_id', '=', 'meal_plans.id')
                    ->join('meal_plan_menu_item', 'meal_plan_menu.id', '=', 'meal_plan_menu_item.meal_plan_menu_id')
                    ->where('meal_plans.date', '<', now()->toDateString());
            }], 'meal_plan_menu_item.adjust_amount')

            // 発注中の数量(status = 1 の合計)
            ->withSum(['orders as ordered_qty' => function($query) {
                $query->where('status', '1');
            }],'ordered_qty')

            // 在庫データがあるかどうかを判定するためのカウント
            ->withCount('stock')
            ->get();
        
        // 取得した各食材のコレクションに対して、引き算や発注必要性の判定（ロジック）を適用
        $items->transform(function ($item) {
            $reserved = $item->reserved_qty ?? 0; // 使用予定量
            $received = $item->received_qty ?? 0; // 納品済
            $cooked = $item->cooked_adjust_amount ?? 0; // 調理済み量
            $ordered = $item->ordered_qty ?? 0; // 発注済

            // 在庫数 = 納品済 - 調理済
            $item->calculated_stock_qty = $received - $cooked;

            // 発注の必要性判定 (在庫数 + 発注中 < 使用予定量 + 目標在庫数)
            $item->is_low_stock = ($item->calculated_stock_qty + $ordered) < ($reserved + ($item->target_stock_qty ?? 0));

            // hasStock ロジック(storeかupdateかの判定に必要)
            $item->has_stock = $item->stock_count > 0;

            return $item;
        });

        return view('stocks.index',compact('items'));
    }

    /**
     * 在庫新規登録
     */
    public function store(StockRequest $request)
    {
        Stock::create($request->validated());

        return redirect()->route('stocks.index')->with('success', '在庫を登録しました。');
    }

    /**
     * 在庫更新
     */
    public function update(StockRequest $request, Stock $stock)
    {
        $stock->update($request->validated());

        return redirect()->route('stocks.index')->with('success', '在庫を更新しました。');
    }
}
