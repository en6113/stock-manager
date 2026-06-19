<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockRequest;
use App\Services\StockService;
use App\Models\Item;
use App\Models\Stock;

class StockController extends Controller
{
    // Serviceをクラス全体で使えるようにプロパティを定義
    protected StockService $stockService;

    /**
     * コンストラクタでServiceを注入
     */
    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * 在庫管理一覧
     */
    public function index()
    {
        $items = $this->stockService->getStockList();

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
