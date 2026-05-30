<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemRequest;
use App\Models\Allergen;
use App\Models\Item;
use App\Models\Vendor;

class ItemController extends Controller
{
    /**
     * 食材一覧を表示
     */
    public function index()
    {
        $items = Item::all();

         return view('items.index', compact('items'));
    }

    /**
     * 食材登録フォームを表示
     */
    public function create()
    {
        $vendors = Vendor::orderBy('name')->get();
        $allergens = Allergen::orderBy('name')->get();

        return view('items.create', compact('vendors', 'allergens'));
    }

    /**
     * 食材を新規登録
     */
    public function store(ItemRequest $request)
    {
        $validated = $request->validated();

        $item = Item::create($validated);

        //　input('キー名', デフォルト値) ：データが空の場合はデフォルト値(空の配列)を使う
        $item->allergens()->sync($request->input('allergen_ids', []));

        return redirect()->route('stocks.index')->with('success', '食材を登録しました。');
    }

    /**
     * 食材編集ページを表示
     */
    public function edit(Item $item)
    {
        $vendors = Vendor::orderBy('name')->get();
        $allergens = Allergen::orderBy('name')->get();

        return view('items.edit', compact('item', 'vendors', 'allergens'));
    }

    /**
     * 食材を更新
     */
    public function update(ItemRequest $request, Item $item)
    {
        $item->update($request->validated());

        $item->allergens()->sync($request->input('allergen_ids', []));

        return redirect()->route('stocks.index')->with('success', '食材を編集しました。');
    }

    /**
     * 食材を削除
     */
    public function destroy(Item $item)
    {
        $item->delete();

        return redirect()->route('stocks.index')->with('success', '食材を削除しました。');
    }
}
