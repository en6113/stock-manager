<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Item;
use App\Models\Vendor;
use App\Http\Requests\OrderRequest;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * 発注履歴一覧を表示
     */
    public function index(Request $request)
    {
        $orders = Order::with('item','vendor')
            ->statusSearch($request->status)
            ->vendorSearch($request->vendor_id)
            ->latest('ordered_date',)
            ->paginate(10);

        $vendors = Vendor::all();

        return view('orders.index', compact('orders','vendors'));
    }

    /**
     * 発注・納品記録作成フォームを表示
     */
    public function create(Item $item)
    {
        $vendors = Vendor::all();

        return view('orders.create', compact('item', 'vendors'));
    }

    /**
     * 発注・納品記録を新規登録
     */
    public function store(OrderRequest $request)
    {
        $order = new Order();
        $order->fill($request->validated());

        if (!empty($order->received_date)) {
            $order->status = '2'; // 納品済
        } elseif (!empty($order->ordered_date)) {
            $order->status = '1'; // 発注済
        } else {
            $order->status = '0'; // 未発注
        }

        $order->save();

        return redirect()->route('orders.index')->with('success', '発注・納品記録を登録しました。');
    }

    /**
     * 発注・納品記録編集画面の表示
     */
    public function edit(Order $order)
    {
        $vendors = Vendor::all();

        // item_id に紐づく全てのstockデータを取得し、発注順で並べる
        $orders = Order::with('item')
            ->where('item_id', $order->item_id)
            ->orderBy('ordered_date', 'desc')
            ->get();

        $item = $order->item;

        return view('orders.edit', compact('vendors','orders', 'item'));
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
