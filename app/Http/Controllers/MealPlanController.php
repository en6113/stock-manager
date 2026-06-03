<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\MealPlan;
use App\Models\MealPlanMenuItem;
use App\Models\Menu;
use App\Http\Requests\MealPlanRequest;

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
        $menus = Menu::all();

        // メニューに紐づく食材とその中間テーブルのデータをJavaScriptが扱いやすい配列の形で取得する
        $menusWithItems = Menu::with('items')->get();
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

        return view('meal_plans.create', compact('menus', 'menuIngredientsData'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MealPlanRequest $request)
    {
        \DB::transaction(function () use ($request) {

            $mealPlan = MealPlan::create([
                'data' = $request->input('data');
            ]);

            // 中間テーブル(meal_plan_menu)に保存するデータを取得
            foreach ($request->input('menus', []) as $catId => $menuData) {
                if (empty($menuData['menu_id'])) { // メニューが選択されていないカテゴリはスキップ
                    continue;
                }

                // 3. 親側の中間テーブル「meal_plan_menu」にメニューを紐づける
                // attach() の第2引数を使うことで、中間テーブルのカラム（dish_categoryなど）も同時に保存できます
                $mealPlan->menus()->attach($menuData['menu_id'], [
                    'dish_category' => $catId, // 必要に応じて（カテゴリを保存するカラム名に合わせてください）
                ]);

                // 4. 【重要】今attachした「meal_plan_menu」のレコード（ID）をデータベースから特定する
                // 理由：子の中間テーブル（meal_plan_menu_item）の外部キーとして使うため
                $mealPlanMenuId = DB::table('meal_plan_menu')
                    ->where('meal_plan_id', $mealPlan->id)
                    ->where('menu_id', $menuData['menu_id'])
                    ->value('id'); // レコードの id カラムの値だけをピンポイントで取得

                // 5. 子側の中間テーブル「meal_plan_menu_item」に微調整された食材たちを保存する
                if (!empty($menuData['ingredients'])) {
                    foreach ($menuData['ingredients'] as $ingredient) {
                        
                        // 先ほど作ったモデルを使って、1行ずつ直感的に保存！
                        MealPlanMenuItem::create([
                            'meal_plan_menu_id' => $mealPlanMenuId, // 手順4で取ったID
                            'item_id'           => $ingredient['item_id'],
                            'adjust_amount'     => $ingredient['required_amount'], // 画面で微調整された値
                        ]);
                    }
                }
            }
        });

    return redirect()->route('meal_plans.index')->with('success', '献立と微調整された食材データを登録しました！');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
