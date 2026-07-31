<?php

namespace App\Http\Controllers;

use App\Services\StockService;

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

        return view('stocks.index', compact('items'));
    }
}
