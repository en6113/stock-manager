<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class IsLowStockTest extends TestCase
{
    /**
     * @dataProvider lowstockDataProvider
     */
    public function test_isLowStockCalculation($calculatedStock, $ordered, $reserved, $targetStock, $expectedResult)
    {
        // arrange
        $item = Item::factory()->make([
            'calculated_stock_qty' => $calculatedStock,
            'target_stock_qty' => $targetStock,
        ]);

        // act
        $isLowStock = ($item->calculated_stock_qty + $ordered) <= ($reserved + ($item->target_stock_qty ?? 0));

        // assert
        $this->assertEquals($expectedResult, $isLowStock, "期待した判定結果と一致しません");
    }

    /**
     * テストデータを提供するデータプロバイダ
     */
    public static function lowStockDataProvider()
    {
        return [
            // [calculated_stock_qty, ordered, reserved, target_stock_qty, expectedResult]

            // 1. 左辺 < 右辺（在庫不十分：true）
            // 左辺: 5 + 2 = 7  <  右辺: 3 + 5 = 8
            '在庫不足のパターン' => [5, 2, 3, 5, true],

            // 2. 左辺 = 右辺（境界値：true）
            // 左辺: 5 + 3 = 8  ==  右辺: 3 + 5 = 8
            '境界値（同じ値ならtrue）' => [5, 3, 3, 5, true],

            // 3. 左辺 > 右辺（在庫十分：false）
            // 左辺: 5 + 4 = 9  >  右辺: 3 + 5 = 8
            '在庫が十分なパターン' => [5, 4, 3, 5, false],

            // 4. target_stock_qty が null の場合の検証
            // 左辺: 5 + 2 = 7  <  右辺: 8 + 0 = 8 （true）
            '目標在庫がnullかつ在庫不足' => [5, 2, 8, null, true],

            // 左辺: 5 + 4 = 9  >  右辺: 8 + 0 = 8 （false）
            '目標在庫がnullかつ在庫十分' => [5, 4, 8, null, false],
        ];
    }
}
