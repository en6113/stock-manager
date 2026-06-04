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
        $mealPlan = \DB::transaction(function () use ($request) {
            $mealPlan = MealPlan::create($request->validated());
            
            $syncData = $request->getFormattedMenuData(); // リクエストでデータを成型
            $mealPlan->syncMenusAndIngredients($syncData); // モデルに中間テーブルへの保存ロジックあり

            return $mealPlan;
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
        \DB::transaction(function () use ($request, $mealPlan) {
            $mealPlan->update($request->validated());

            $syncData = $request->getFormattedMenuData(); // リクエストでデータを成型
            $mealPlan->syncMenusAndIngredients($syncData); // モデルに中間テーブルへの保存ロジックあり
        });

        return redirect()->route('meal_plans.index')->with('success', '献立を更新しました！');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MealPlan $mealPlan)
    {
        $mealPlan->delete();

        return redirect()->route('meal_plans.index')->with('success', '献立を削除しました！');
    }
}
