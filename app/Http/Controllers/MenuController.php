<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexMenuRequest;
use App\Http\Requests\MenuRequest;
use App\Models\Item;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    /**
     * メニュー一覧
     */
    public function index(IndexMenuRequest $request)
    {
        $menus = Menu::withCount('items')
            ->keywordSearch($request->keyword)
            ->categorySearch($request->dish_category)
            ->paginate(10);

        return view('menus.index', compact('menus'));
    }

    /**
     * メニュー登録画面を表示
     */
    public function create()
    {
        // 食材（カテゴリーが1〜14のもの）
        $registered_items = Item::whereBetween('item_category_id', [1, 14])->orderBy('name')->get();

        // 調味料（カテゴリーが13〜19のもの）
        $seasoning_items = Item::whereBetween('item_category_id', [13, 19])->orderBy('name')->get();

        return view('menus.create', compact('registered_items', 'seasoning_items'));
    }

    /**
     * メニューを新規作成
     */
    public function store(MenuRequest $request)
    {
        DB::transaction(function () use ($request) {
            $menu = Menu::create($request->safe()->only(['name', 'dish_category', 'calories']));
            $menu->items()->sync($request->getSyncData());
        });

        return redirect()->route('menus.index')->with('success', 'メニューを登録しました。');
    }

    /**
     * メニュー編集画面を表示
     */
    public function edit(Menu $menu)
    {
        $menu->load([
            'items' => function ($query) {
                $query->withPivot('servings', 'required_amount');
            },
        ]);

        $registered_items = Item::all();

        return view('menus.edit', compact('menu', 'registered_items'));
    }

    /**
     * メニューを更新
     */
    public function update(MenuRequest $request, Menu $menu)
    {
        DB::transaction(function () use ($request, $menu) {
            $menu->update($request->safe()->only(['name', 'dish_category', 'calories']));
            $menu->items()->sync($request->getSyncData());
        });

        return redirect()->route('menus.index')->with('success', 'メニューを更新しました。');
    }

    /**
     * メニューを削除
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();

        return redirect()->route('menus.index')->with('success', 'メニューを削除しました。');
    }
}
