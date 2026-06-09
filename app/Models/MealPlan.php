<?php

namespace App\Models;

use App\Models\Menu;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MealPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
    ];

    // この献立に属するメニューを取得
    public function menus() : BelongsToMany
    {
        return $this->belongsToMany(Menu::class, 'meal_plan_menu');
    }

    /**
     * カレンダー表示に必要な日付データを一斉に計算する
     * * @param string|null $monthParam (例: "2026-06")
     * @return array
     */
    public static function generateCalendarData(?string $monthParam)
    {
        // 引数がなければ当月
        $monthParam = $monthParam ?? now()->format('Y-m');

        try {
            $currentMonth = Carbon::parse($monthParam . '-01');
        } catch (\Exception $e) {
            $currentMonth = now()->startOfMonth();
        }

        // カレンダーの「開始日」と「終了日」を計算する
        $startOfMonth = $currentMonth->copy()->startOfMonth();
        $endOfMonth = $currentMonth->copy()->endOfMonth();
        $startOfCalendar = $startOfMonth->copy()->startOfWeek(Carbon::SUNDAY);
        $endOfCalendar = $endOfMonth->copy()->endOfWeek(Carbon::SATURDAY);

        // 開始日から終了日まで1日ずつループして日付を詰め込む
        $calendarWeeks = [];
        $currentDay = $startOfCalendar->copy();
        $weekIndex = 0;

        while ($currentDay->lte($endOfCalendar)) {
            $dayData = new \stdClass();
            $dayData->carbon = $currentDay->copy();
            $dayData->isCurrentMonth = ($dayData->carbon->format('Y-m') === $currentMonth->format('Y-m'));
            $dayData->isToday = $dayData->carbon->isToday();

            $calendarWeeks[$weekIndex][] = $dayData;
            if ($currentDay->dayOfWeek === Carbon::SATURDAY) {
                $weekIndex++;
            }
            $currentDay->addDay();
        }

        return [
            'calendarWeeks' => $calendarWeeks,
            'currentMonth' => $currentMonth,
            'startOfCalendar' => $startOfCalendar,
            'endOfCalendar' => $endOfCalendar,
        ];
    }

    // 中間テーブルのデータを一括同期するためのファンクション(@storeと@updateで使用)
    // MealPlanRequestでデータ型を成型している（menuData）
    public function syncMenusAndIngredients(array $menuDataList, int $globalServings): void
    {
        // update時は古い紐づきを一旦すべて削除する(storeと共通ロジックにするため)
        if ($this->menus()->exists()) {
            // 中間テーブルのIDを引っ張ってきて、紐づく食材（3階層目）を削除
            $mealPlanMenuIds = \DB::table('meal_plan_menu')->where('meal_plan_id', $this->id)->pluck('id');
            \DB::table('meal_plan_menu_item')->whereIn('meal_plan_menu_id', $mealPlanMenuIds)->delete();

            // 紐づくメニュー（2階層目）を削除
            $this->menus()->detach();
        }

        // 新たに保存しなおす
        foreach ($menuDataList as $menuData) {
            // 中間テーブル（meal_plan_menu）にメニューを保存
            $this->menus()->attach($menuData['menu_id'], [
                'servings' => $globalServings,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 今保存した meal_plan_menu の ID を取得
            $mealPlanMenuId = \DB::table('meal_plan_menu')
                ->where('meal_plan_id', $this->id)
                ->where('menu_id', $menuData['menu_id'])
                ->value('id');

            // 中間テーブル（meal_plan_menu_item）に調整後の食材を保存
            foreach ($menuData['ingredients'] as $ingredient) {
                \DB::table('meal_plan_menu_item')->insert([
                    'meal_plan_menu_id' => $mealPlanMenuId,
                    'item_id' => $ingredient['item_id'],
                    'adjust_amount' => $ingredient['required_amount'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
