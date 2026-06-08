<?php

namespace App\Http\Controllers;

use App\Models\DishCategory;
use App\Models\Item;
use App\Models\Menu;
use App\Http\Requests\IndexMenuRequest;
use App\Http\Requests\MenuRequest;

class MenuController extends Controller
{
    /**
     * メニュー一覧
     */
    public function index(IndexMenuRequest $request)
    {
        $categories = DishCategory::all();

        $menus = Menu::withCount('items')
            ->keywordSearch($request->keyword)
            ->categorySearch($request->dish_category)
            ->paginate(10);

        return view('menus.index', compact('categories','menus'));
    }

    /**
     * メニュー登録画面を表示
     */
    public function create()
    {
        $categories = DishCategory::all();
        $allItems = Item::all();

        // 食材（カテゴリーが1〜14のもの）
        $registered_items = $allItems->filter(function ($item) {
            return $item->item_category_id >= 1 && $item->category_id <= 14;
        });

        // 調味料（カテゴリーが15〜19のもの）
        $seasoning_items = $allItems->filter(function ($item) {
            return $item->item_category_id >= 15 && $item->category_id <= 19;
        });

        return view('menus.create', compact('categories', 'registered_items', 'seasoning_items'));
    }

    /**
     * メニューを新規作成
     */
    public function store(MenuRequest $request)
    {
        $menu = Menu::create($request->validated());

        $syncData = $request->getSyncData();
        $menu->items()->sync($syncData);

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
            }
        ]);
        
        $registered_items = Item::all();

        return view('menus.edit', compact('menu', 'registered_items'));
    }

    /**
     * メニューを更新
     */
    public function update(MenuRequest $request, Menu $menu)
    {
        $menu->update($request->validated());

        $syncData = $request->getSyncData();
        $menu->items()->sync($syncData);

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
