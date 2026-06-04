<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Category;
use App\Models\MealPlan;
use App\Models\MealPlanMenuItem;
use App\Models\Menu;
use App\Http\Requests\MealPlanRequest;
use Illuminate\Support\Facades\DB;

class MealPlanController extends Controller
{
    /**
     * 献立一覧画面
     */
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();

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

        $menus = Menu::with('category')->get();

        return view('meal_plans.create', compact('categories','menus', 'menuIngredientsData'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MealPlanRequest $request)
    {
        \DB::transaction(function () use ($request) {

            $mealPlan = MealPlan::create([
                'date' => $request->input('date'),
            ]);

            foreach ($request->input('menus', []) as $catId => $menuData) {
                // メニューが選択されていないカテゴリはスキップ
                if (empty($menuData['menu_id'])) {
                    continue;
                }

                // 中間テーブル(meal_plan_menu)にmenu_idを保存
                $mealPlan->menus()->attach($menuData['menu_id']);

                // meal_plan_menu_idを取得する
                $mealPlanMenuId = \DB::table('meal_plan_menu')
                    ->where('meal_plan_id', $mealPlan->id)
                    ->where('menu_id', $menuData['menu_id'])
                    ->value('id');

                // 中間テーブル（meal_plan_menu_item）に調整後の食材とその必要量を保存
                if (!empty($menuData['ingredients'])) {
                    foreach ($menuData['ingredients'] as $ingredient) {
                        MealPlanMenuItem::create([
                            'meal_plan_menu_id' => $mealPlanMenuId,
                            'item_id'           => $ingredient['item_id'],
                            'adjust_amount'     => $ingredient['required_amount'],
                        ]);
                    }
                }
            }
        });

    return redirect()->route('meal_plans.index')->with('success', '献立を登録しました！');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MealPlan $mealPlan)
    {
        $categories = Category::all();
        $menus = Menu::all();


        $adjustedItems = MealPlanMenuItem::with('item')
            ->whereIn('meal_plan_menu_id', function($query) use ($mealPlan) {
                $query->select('id')
                      ->from('meal_plan_menu')
                      ->where('meal_plan_id', $mealPlan->id);
            })->get();

        // JavaScript用の全食材データ
        $menuIngredientsData = Menu::with('items')->get()->mapWithKeys(function ($menu) {
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

        return view('meal_plans.edit', compact('categories','mealPlan','menus','adjustedItems','menuIngredientsData'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MealPlanRequest $request, MealPlan $mealPlan)
    {
        $mealPlan->update($request->validated());

        $MenuSyncData = $request->getMenuSyncData();
        $mealPlan->menus()->sync($MenuSyncData);

        $ItemSyncData = $request->getItemSyncData();
        $mealPlan->menu.items()->sync($ItemSyncData); //リレーション未設定


        return redirect()->route('meal_plans.index')->with('success', '献立を更新しました！');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
