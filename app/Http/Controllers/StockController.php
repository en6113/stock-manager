<?php

namespace App\Http\Controllers;

use Illuminate\Http\Requests\StockRequest;
use App\Models\Item;
use App\Models\Stock;

class StockController extends Controller
{
    /**
     * 在庫管理一覧
     */
    public function index()
    {
        // 使用予定量を計算してItemにもたせる（一覧画面なので重くならないようにアクセサではなくwithSumで取得）
        $items = Item::with('stock')
            ->withSum(['menus as reserved_qty' => function ($menuQuery) {
                $menuQuery->join('meal_plan_menu', 'menus.id', '=', 'meal_plan_menu.menu_id')
                          ->join('meal_plans', 'meal_plan_menu.meal_plan_id', '=', 'meal_plans.id')
                          ->where('meal_plans.date', '>=', now()->toDateString());
            }], 'item_menu.required_amount')->get();

        //ゆくゆくは$qtyを食材の発注数と同期させたい

        return view('stocks.index',compact('items'));
    }

    public function show()
    {
        // 賞味期限や製造番号等を確認できるようにしたい
    }

    /**
     * 在庫作成
     */
    public function show()
    {
        // 賞味期限や製造番号等を確認できるようにしたい
    }

    /**
     * 在庫更新
     */
    public function update(StockRequest $request, Stock $stock)
    {
        $stock->update($request->validated());

        return redirect()->route('stocks.index')->with('success', '在庫を編集しました。');
    }
}
