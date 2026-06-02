<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Item;
use App\Models\Vendor;
use App\Http\Requests\OrderRequest;

class OrderController extends Controller
{
    /**
     * 発注・納品記録一覧を表示
     */
    public function index()
    {
        $orders = Order::all();

        return view('orders.index', compact('orders'));
    }

    /**
     * 発注・納品記録作成フォームを表示
     */
    public function create()
    {
        $items = Item::orderBy('name')->get();
        $vendor = Vendor::orderBy('name')->get();

        return view('orders.create', compact('items', 'vendor'));
    }

    /**
     * 発注・納品記録を新規登録
     */
    public function store(OrderRequest $request)
    {
        $validated = $request->validated();

        $order = Order::create($validated);

        return redirect()->route('orders.index')->with('success', '発注・納品記録を登録しました。');
    }

    /**
     * 発注・納品記録編集画面の表示
     */
    public function edit(Order $order)
    {
        // item_id に紐づく全てのstockデータを取得し、発注順で並べる
        $orders = Order::with('item')
            ->where('item_id', $order->item_id)
            ->orderBy('ordered_date', 'desc')
            ->get();

        $item = $order->item;

        return view('orders.edit', compact('orders', 'item'));
    }

    /**
     * 発注・納品記録を更新
     */
    public function update(OrderRequest $request, Order $order)
    {
        $order->fill($request->validated()); // statusの自動判定ロジックを動かすためにfill()を使用

        if (!empty($order->received_date)) {
            $order->status = '2'; // 納品済
        } elseif (!empty($order->ordered_date)) {
            $order->status = '1'; // 発注済
        } else {
            $order->status = '0'; // 未発注
        }

        $order->save();

        return redirect()->route('stocks.index')->with('success', '発注・納品記録を更新しました。');
    }

    /**
     * 食材を削除
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('stocks.index')->with('success', '発注・納品記録を削除しました。');
    }
}
