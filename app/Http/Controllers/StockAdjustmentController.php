<?php

namespace App\Http\Controllers;

use App\Enums\AdjustmentReason;
use App\Http\Requests\IndexStockAdjustmentRequest;
use App\Http\Requests\StockAdjustmentRequest;
use App\Models\Item;
use App\Models\StockAdjustment;
use App\Services\StockService;

class StockAdjustmentController extends Controller
{
    protected StockService $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * 在庫調整一覧表示
     */
    public function index(IndexStockAdjustmentRequest $request)
    {
        $items = Item::orderBy('name')->get();

        $reasons = AdjustmentReason::cases();

        $adjustments = StockAdjustment::with(['item', 'user'])
            ->adjustedOnSearch($request->adjusted_on)
            ->itemSearch($request->item_id)
            ->reasonFilter($request->reason)
            ->orderByDesc('adjusted_on')
            ->orderByDesc('id')
            ->paginate(15);

        return view('stock_adjustments.index', compact('adjustments', 'items', 'reasons'));
    }

    /**
     * 在庫調整の登録フォーム（ポップアップ表示）
     */
    public function create(Item $item)
    {
        return view('stock_adjustments.create', [
            'item' => $item,
            'currentStock' => $this->stockService->getCurrentStock($item),
            'reasons' => AdjustmentReason::cases(),
        ]);
    }

    /**
     * 在庫調整の登録
     */
    public function store(StockAdjustmentRequest $request, Item $item)
    {
        $currentStock = $this->stockService->getCurrentStock($item);

        StockAdjustment::create([
            'item_id' => $item->id,
            'quantity_g' => $request->actual_qty - $currentStock, // 差分に変換。減ったらマイナス
            'reason' => $request->reason,
            'note' => $request->note,
            'user_id' => auth()->id(),
            'adjusted_on' => $request->adjusted_on ?? today(),
        ]);

        return redirect()->route('stocks.index')->with('success', '在庫を調整しました。');
    }
}
