<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\DishCategory;
use App\Models\MealPlan;
use App\Models\MealPlanMenuItem;
use App\Models\Menu;
use App\Http\Requests\MealPlanRequest;
use Illuminate\Support\Facades\DB;

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
        $categories = DishCategory::all();

        $menusWithItems = Menu::with('items')->get();

        // メニューに紐づく食材とその中間テーブルのデータをJavaScriptが扱いやすい配列の形で取得する
        $menuIngredientsData = $menusWithItems->mapWithKeys(function ($menu) {
            $ingredients = $menu->items->map(function ($item) {
                return [
                    'item_id' => $item->id,
                    'item_name' => $item->name,
                    'unit' => $item->unit,
                    'required_amount' => $item->pivot->required_amount,
                ];
            });
            return [$menu->id => $ingredients];
        });

        $menus = Menu::with('dishCategory')->get();

        return view('meal_plans.create', compact('categories','menus', 'menuIngredientsData'));
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
        $categories = DishCategory::all();
        $menus = Menu::all();

        // 1. この献立に登録されている食材(総重量)をあらかじめ中間テーブルのIDごとに取得
        $adjustedItems = MealPlanMenuItem::with('item')
            ->whereIn('meal_plan_menu_id', function ($query) use ($mealPlan) {
                $query->select('id')->from('meal_plan_menu')->where('meal_plan_id', $mealPlan->id);
            })->get();

        // 既存の提供人数（servings）を取得（どこか1件でも登録されていればそれを、なければデフォルト50）
        $currentServings = $adjustedItems->first()->servings ?? 50;

        // 2. カテゴリごとのデータ構造を作る（）テゴリに紐づく現在のメニューを特定）
        $structuredData = $categories->map(function ($category) use ($mealPlan, $adjustedItems, $currentServings) {
            $currentMenu = $mealPlan->menus->where('dish_category_id', $category->id)->first();
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
                        // 提供人数の変更がある可能性があるため、1人分の量に復元して渡す（総重量(adjust_amount) ÷ 提供人数）
                        $perPersonAmount = $currentServings > 0 ? ($adjustedItem->adjust_amount / $currentServings) : 0;

                        $ingredientsForView[] = [
                            'item_id' => $adjustedItem->item_id,
                            'name' => $adjustedItem->item->name,
                            'unit' => $adjustedItem->item->unit,
                            'per_person_amount' => $perPersonAmount, // 1人分
                            'total_amount' => $adjustedItem->adjust_amount, // 総重量（初期表示用）
                        ];
                    }
                }
            }

            // もしメニューはあるのに中間データがない（レアケース）場合はマスターから取得
            if ($currentMenu && empty($ingredientsForView)) {
                foreach ($currentMenu->items as $item) {
                    $ingredientsForView[] = [
                        'item_id' => $item->id,
                        'name' => $item->name,
                        'unit' => $item->unit,
                        'per_person_amount' => $item->pivot->required_amount,
                        'total_amount' => $item->pivot->required_amount * $currentServings,
                    ];
                }
            }

            return [
                'category' => $category,
                'current_menu' => $currentMenu,
                'ingredients' => $ingredientsForView
            ];
        });

        // 3. JavaScript用のマスターデータ(新規メニュー切り替え用)
        $menuIngredientsData = Menu::with('items')->get()->mapWithKeys(function ($menu) {
            $ingredients = $menu->items->map(function ($item) {
                return [
                    'item_id' => $item->id,
                    'item_name' => $item->name,
                    'unit' => $item->unit,
                    'required_amount' => $item->pivot->required_amount, // マスタの1人分
                ];
            });
            return [$menu->id => $ingredients];
        });

        return view('meal_plans.edit', compact('mealPlan', 'menus', 'currentServings', 'structuredData', 'menuIngredientsData'));
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
