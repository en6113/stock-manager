<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExportCsvRequest;
use App\Models\MealPlanMenuItem;

class ExportCsvController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }
    /**
     * CSVダウンロード処理
     */
    public function export(ExportCsvRequest $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // 指定期間に使用した食材とそのカテゴリーを取得
        $items = MealPlanMenuItem::with('item.itemCategory')
            ->whereIn('meal_plan_menu_id', function ($query) use ($startDate, $endDate) {
                $query->select('mpm.id')
                    ->from('meal_plan_menu as mpm')
                    ->join('meal_plans as mp','mp.id', '=', 'mpm.meal_plan_id')
                    ->whereBetween('mp.date', [$startDate, $endDate]);
            })->get();

        // 期間内の総食数（各メニューの提供人数の合計）を計算
        $totalMeals = \DB::table('meal_plan_menu as mpm')
            ->join('meal_plans as mp', 'mp.id', '=', 'mpm.meal_plan_id')
            ->whereBetween('mp.date', [$startDate, $endDate])
            ->sum('mpm.servings');
        
        if ($totalMeals <= 0) {
            return redirect()->back()->with('error', '指定された期間に献立データ（提供人数）が存在しません。');
        }

        // 各食品群の1日の平均給与量を計算
        $calculatedGroups = $items->groupBy(function ($menuItem) {
            return $menuItem->item->itemCategory->code ?? 'unknown';
        
        })->map(function ($groupItems, $categoryCode) use ($totalMeals) {
            $totalAmountForGroup = $groupItems->sum('adjust_amount');
            $averageAmount = round($totalAmountForGroup / $totalMeals, 1);
            $categoryName = $groupItems->first()->item->itemCategory->name ?? '未分類食品群';

            return [
                'category_code' => $categoryCode,
                'category_name' => $categoryName,
                'average_amount' => $averageAmount,
            ];
        });

        // CSVデータの定義（出力形式への変換）
        $csvData = [];

        foreach ($calculatedGroups as $group) {
            $csvData[] = [
                $group['category_code'], // 1列目：食品群コード
                $group['category_name'], // 2列目：食品群名
                $group['average_amount'],// 3列目：平均給与量
            ];
        }

        // CSVの出力＆詳細設定
        return response()->streamDownload(function () use ($csvData, $startDate, $endDate) {
            $handle = fopen('php://output', 'w');
            fwrite($handle,"\xEF\xBB\xBF");
            fputcsv($handle, ['食品群コード', '食品群名','食品群別給与量(g/人/日)']);

            foreach ($csvData as $row) {
                fputcsv($handle, $row);
            }

            fclose($handle);
        }, "food_group_average_{$startDate}_to_{$endDate}.csv");
    }
}