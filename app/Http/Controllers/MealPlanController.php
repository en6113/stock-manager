<?php

namespace App\Http\Controllers;

use App\Enums\DishCategory;
use App\Http\Requests\MealPlanRequest;
use App\Models\Item;
use App\Models\MealPlan;
use App\Models\MealPlanMenuItem;
use App\Models\Menu;
use Carbon\Carbon;

class MealPlanController extends Controller
{
    public function index(MealPlanRequest $request)
    {
        $monthParam = $request->query('month'); // モデルに定義したカレンダーロジックに渡すための引数

        $calendarData = MealPlan::generateCalendarData($monthParam);

        $calendarWeeks = $calendarData['calendarWeeks'];
        $currentMonth = $calendarData['currentMonth'];
        $startOfCalendar = $calendarData['startOfCalendar'];
        $endOfCalendar = $calendarData['endOfCalendar'];

        $mealPlans = MealPlan::with('menus')
            ->whereBetween('date', [$startOfCalendar->format('Y-m-d'), $endOfCalendar->format('Y-m-d')])
            ->get()
            ->keyBy(function ($item) {
                return Carbon::parse($item->date)->format('Y-m-d'); // 取得したデータを日付をkeyにした連想配列にする
            });

        $prevMonth = $currentMonth->copy()->subMonth()->format('Y-m'); // リンク用文字列(前月)
        $nextMonth = $currentMonth->copy()->addMonth()->format('Y-m'); // リンク用文字列(次月)

        return view('meal_plans.index', compact(
            'calendarWeeks',
            'currentMonth',
            'prevMonth',
            'nextMonth',
            'mealPlans'
        ));
    }

    public function create()
    {
        $menus = Menu::with('items.allergens')->get();
        $items = Item::orderBy('name')->get(); // メニューに無い食材を追加するための一覧

        // メニューに紐づく食材とその中間テーブルのデータをJavaScriptが扱いやすい配列の形で取得する
        $menuIngredientsData = $menus->mapWithKeys(function ($menu) {
            $ingredients = $menu->items->map(function ($item) {
                return [
                    'item_id' => $item->id,
                    'item_name' => $item->name,
                    'unit' => $item->unit,
                    'allergens' => $item->allergens->pluck('name')->implode('、'),
                    'perServing' => $item->pivot->servings > 0
                        ? $item->pivot->required_amount / $item->pivot->servings
                        : 0,
                ];
            });

            return [$menu->id => $ingredients];
        });

        return view('meal_plans.create', compact('menus', 'menuIngredientsData', 'items'));
    }

    public function store(MealPlanRequest $request)
    {
        $mealPlan = \DB::transaction(function () use ($request) {
            $mealPlan = MealPlan::create($request->validated());

            $syncData = $request->getFormattedMenuData(); // リクエストでデータを成型
            $mealPlan->syncMenusAndIngredients($syncData, (int) $request->input('servings', 50)); // モデルに中間テーブルへの保存ロジックあり

            return $mealPlan;
        });

        return redirect()->route('meal_plans.index')->with('success', '献立を登録しました！');
    }

    public function edit(MealPlan $mealPlan)
    {
        $categories = DishCategory::cases();
        $menus = Menu::all();
        $items = Item::orderBy('name')->get(); // メニューに無い食材を追加するための一覧

        $mealPlan->load('menus.items.allergens');

        // 1. 保存済の提供人数と食材リストを取得
        $currentServings = \DB::table('meal_plan_menu')
            ->where('meal_plan_id', $mealPlan->id)
            ->value('servings') ?? 50; // デフォルト50

        $adjustedItems = MealPlanMenuItem::with('item.allergens')
            ->whereIn('meal_plan_menu_id', function ($query) use ($mealPlan) {
                $query->select('id')->from('meal_plan_menu')->where('meal_plan_id', $mealPlan->id);
            })->get();

        // 2. ディッシュカテゴリごとのデータ構造を作る
        $structuredData = collect($categories)->map(function ($category) use ($mealPlan, $currentServings, $adjustedItems) {
            $currentMenu = $mealPlan->menus->where('dish_category', $category)->first();
            $ingredientsForView = [];

            if ($currentMenu) {
                // 中間テーブル「meal_plan_menu」のレコードを特定
                $currentMealPlanMenu = \DB::table('meal_plan_menu')
                    ->where('meal_plan_id', $mealPlan->id)
                    ->where('menu_id', $currentMenu->id)
                    ->first();

                if ($currentMealPlanMenu) {
                    // そのメニューに紐づく保存済みの食材リストを抽出
                    $categoryAdjustedItems = $adjustedItems->where('meal_plan_menu_id', $currentMealPlanMenu->id);

                    foreach ($categoryAdjustedItems as $adjustedItem) {
                        // 現在処理している食材（item_id）と一致するレコードを検索する
                        $masterItem = $currentMenu->items->where('id', $adjustedItem->item_id)->first();

                        // マスタに登録されている1人分の量を取得（万が一マスタから消えていた場合は0）
                        $perPersonAmount = ($masterItem && $masterItem->pivot->servings > 0)
                            ? $masterItem->pivot->required_amount / $masterItem->pivot->servings
                            : 0;

                        $ingredientsForView[] = [
                            'item_id' => $adjustedItem->item_id,
                            'name' => $adjustedItem->item->name,
                            'unit' => $adjustedItem->item->unit,
                            'allergens' => $adjustedItem->item->allergens->pluck('name')->implode('、'),
                            'per_person_amount' => $perPersonAmount, // 1人分
                            'total_amount' => $adjustedItem->adjust_amount, // 保存されている総重量
                        ];
                    }
                }
            }

            // もしメニューはあるのに中間データがない場合はマスターから取得
            if ($currentMenu && empty($ingredientsForView)) {
                foreach ($currentMenu->items as $item) {
                    $perPersonAmount = $item->pivot->servings > 0
                        ? $item->pivot->required_amount / $item->pivot->servings
                        : 0;

                    $ingredientsForView[] = [
                        'item_id' => $item->id,
                        'name' => $item->name,
                        'unit' => $item->unit,
                        'allergens' => $item->allergens->pluck('name')->implode('、'),
                        'per_person_amount' => $perPersonAmount,
                        'total_amount' => $perPersonAmount * $currentServings,
                    ];
                }
            }

            return [
                'category' => $category,
                'current_menu' => $currentMenu,
                'ingredients' => $ingredientsForView,
            ];
        });

        // 3. JavaScript用のマスターデータ(新規メニュー切り替え用)
        $menuIngredientsData = Menu::with('items.allergens')->get()->mapWithKeys(function ($menu) {
            $ingredients = $menu->items->map(function ($item) {
                return [
                    'item_id' => $item->id,
                    'item_name' => $item->name,
                    'unit' => $item->unit,
                    'allergens' => $item->allergens->pluck('name')->implode('、'),
                    'perServing' => $item->pivot->servings > 0 // 必要量の1人分
                        ? $item->pivot->required_amount / $item->pivot->servings
                        : 0,
                ];
            });

            return [$menu->id => $ingredients];
        });

        return view('meal_plans.edit', compact('mealPlan', 'menus', 'currentServings', 'structuredData', 'menuIngredientsData', 'items'));
    }

    public function update(MealPlanRequest $request, MealPlan $mealPlan)
    {
        \DB::transaction(function () use ($request, $mealPlan) {
            $mealPlan->update($request->validated());

            $syncData = $request->getFormattedMenuData();

            $mealPlan->syncMenusAndIngredients($syncData, (int) $request->input('servings', 50));
        });

        return redirect()->route('meal_plans.index')->with('success', '献立を更新しました！');
    }

    public function destroy(MealPlan $mealPlan)
    {
        $mealPlan->delete();

        return redirect()->route('meal_plans.index')->with('success', '献立を削除しました！');
    }
}
