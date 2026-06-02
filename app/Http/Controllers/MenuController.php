<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Menu;
use App\Http\Requests\MenuRequest;

class MenuController extends Controller
{
    /**
     * メニュー一覧
     */
    public function index()
    {
        $menus = Menu::withCount('items')->get();

        return view('menus.index', compact('menus'));
    }

    /**
     * メニュー登録画面を表示
     */
    public function create()
    {
        $registered_items = Item::all();

        return view('menus.create', compact('registered_items'));
    }

    /**
     * メニューを新規作成
     */
    public function store(MenuRequest $request)
    {
        $menu = Menu::create($request->validated());

        $syncData = [];

        foreach ($request->input('item_ids', []) as $key => $itemName) {
            // 空白の入力枠は無視するロジック（エラー防止）
            if (empty($itemName)) {
                continue;
            }

            // $itemName を使ってItemを探す
            $item = Item::where('name', $itemName)->first();

            if ($item) {
                // 「必要量」の配列から、同じ番号（$key）のデータを取り出す
                $amount = $request->input('required_amounts')[$key] ?? 0;

                $servings = $request->input('servings');

                // sync用の配列に【食材ID】をキーにしてデータを詰め込む
                $syncData[$item->id] = [
                    'servings' => $servings,
                    'required_amount' => $amount
                ];
            }
        }

        // 中間テーブルに保存
        $menu->items()->sync($syncData);
        
        return redirect()->route('menus.index')->with('success', 'メニューを登録しました。');
    }

    /**
     * メニュー編集画面を表示
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * メニューを更新
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * メニューを削除
     */
    public function destroy(string $id)
    {
        //
    }
}
