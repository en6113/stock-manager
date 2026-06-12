<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExportCsvRequest;
use App\Models\ItemCategory;
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

        $categoryMap = ItemCategory::orderBy('code')
            ->pluck('name', 'code')
            ->toArray();

        $categoryCodes = array_keys($categoryMap);
        $categoryNames = array_values($categoryMap);

        // 指定期間に使用した食材とそのカテゴリーを日別に取得
        $menuItems = MealPlanMenuItem::with('item.itemCategory', 'mealPlanMenu.mealPlan')
            ->whereHas('mealPlanMenu.mealPlan', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            })
            ->get();

        // 「日付」⇒「カテゴリーコード」の順にグルーピングして集計
        $groupedData = $menuItems->groupBy(function ($menuItem) {
            return $menuItem->mealPlanMenu->mealPlan->date;
        })->map(function ($dateItem) use($categoryCodes) {
            $groupedItem = $dateItem->groupBy('item.itemCategory.code');

            $row = [];
            foreach ($categoryCodes as $code) {
                $row[$code] = $groupedItem->has($code)
                    ? $groupedItem->get($code)->sum('adjust_amount')
                    : 0;
            }
            return $row;
        });

        // CSVデータの定義（出力形式への変換）
        $csvData = [];
        foreach ($groupedData as $data => $amounts) {
            $csvData[] = array_merge([$data], array_values($amounts));
        }

        if ($groupedData->isNotEmpty()) {
            $averageRow = ['期間平均'];
            foreach ($categoryCodes as $code) {
                $avg = $groupedData->avg($code);
                $averageRow[] = round($avg, 1);
            }
            $csvData[] = $averageRow;
        }

        // CSVの出力＆詳細設定
        return response()->streamDownload(function () use ($csvData, $categoryNames) {
            $handle = fopen('php://output', 'w');
            fwrite($handle,"\xEF\xBB\xBF");

            fputcsv($handle, array_merge(['日付'], $categoryNames));

            foreach ($csvData as $row) {
                fputcsv($handle, $row);
            }

            fclose($handle);
        }, "food_group_average_{$startDate}_to_{$endDate}.csv");
    }
}