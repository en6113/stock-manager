<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockRequest;
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
        $items = Item::with('stocks')
            ->withSum(['menus as reserved_qty' => function ($query) {
                $query->join('meal_plan_menu', 'menus.id', '=', 'meal_plan_menu.menu_id')
                      ->join('meal_plans', 'meal_plan_menu.meal_plan_id', '=', 'meal_plans.id')
                      ->where('meal_plans.date', '>=', now()->toDateString());
            }], 'item_menu.required_amount')->get();

        // 在庫数(stock)を食材の発注数(ordered.qty)-調理済メニュー量(reserved_qty)で自動計算するようにしたい

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
