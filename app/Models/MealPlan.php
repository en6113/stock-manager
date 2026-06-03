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

    // この献立に属するメニューの食材を取得
    public function mealPlanMenuItems(): BelongsToMany
    {
        return $this->belongsToMany(MenuItem::class, 'meal_plan_menu_item');
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
}
