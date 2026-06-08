<?php

namespace App\Http\Controllers;

use App\Models\MealPlanMenuItem;
use App\Http\Requests\ExportCsvRequest;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

        // 指定期間に使用された食材とそれに紐づくカテゴリーをまとめて取得
        $items = MealPlanMenuItem::with('item.itemCategory')
            ->whereHas('mealPlanMenu.mealPlan', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            })->get();

        // 期間内の総食数を計算
        $totalMeals = MealPlan::whereBetween('date', [$startDate, $endDate])->sum('servings');
        if ($totalMeals <= 0) {
            return redirect()->back()->with('error', '指定された期間に献立データ（食数）が存在しません。');
        }

        // 食材データを食品群（カテゴリー）ごとにグループ化して計算し、$csvData を作る
        $csvData = $items->groupBy(function ($menuItem) {
            // もしカテゴリーが登録されていなければ「未分類」にする安全対策
            return $menuItem->item->itemCategory->code ?? 'unknown';
        })->map(function ($groupItems, $categoryCode) use ($totalMeals) {

            // この食品群の「adjust_amount（総重量）」の総合計を計算
            $totalAmountForGroup = $groupItems->sum('adjust_amount');

            // 1人あたりの平均（総合計量 / 総食数）を算出 小数点第一で四捨五入
            $averageAmount = round($totalAmountForGroup / $totalMeals, 1);

            // グループ内の最初のレコードから、食品群名を取得
            $categoryName = $groupItems->first()->item->itemCategory->name ?? '未分類食品群';

            // CSVの1行分に該当する [配列] を返します
            return [
                'code' => $categoryCode,
                'name' => $categoryName,
                'average' => $averageAmount,
            ];
        })->values()->toArray();

        // CSVをストリームとしてレスポンス（大容量になってもメモリを圧迫しない方法）
        return response()->streamDownload(function () use ($csvData) {
            $handle = fopen('php://output', 'w'); // 標準出力（ブラウザへのレスポンス）を開く
            fwrite($handle,"\xEF\xBB\xBF"); // 文字化け対策（Excelで開くためのBOMを追加）
            fputcsv($handle, ['食品群コード', '食品群名','食品群別給与量(g/人/日)']); // ヘッダー

            // データを1件ずつループしてCSVの行に変換
            foreach ($csvData as $row) {
                fputcsv($handle, $row);
            }

            fclose($handle);
        }, 'food_group_average_{$startDate}_to_{$endDate}.csv', [
            'Content-Type'=> 'text/csv',
        ]);
    }
}