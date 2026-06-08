<?php

namespace Database\Seeders;

use App\Models\ItemCategory;
use Illuminate\Database\Seeder;

class ItemCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => '魚介類',
                'code' => 'A01', // A:動物性食品
            ],
            [
                'name' => '肉類',
                'code' => 'A02',
            ],
            [
                'name' => '乳類',
                'code' => 'A03',
            ],
            [
                'name' => '卵類',
                'code' => 'A04',
            ],
            [
                'name' => '緑黄色野菜類',
                'code' => 'B01', // B:野菜、果実類
            ],
            [
                'name' => '淡色野菜類',
                'code' => 'B02',
            ],
            [
                'name' => '海藻類',
                'code' => 'B03',
            ],
            [
                'name' => 'いも類',
                'code' => 'B04',
            ],
            [
                'name' => '果実類',
                'code' => 'B05',
            ],
            [
                'name' => '米',
                'code' => 'C01', // C:穀類
            ],
            [
                'name' => 'パン類',
                'code' => 'C02',
            ],
            [
                'name' => 'めん類',
                'code' => 'C03',
            ],
            [
                'name' => '大豆製品',
                'code' => 'D01', // D:豆類
            ],
            [
                'name' => '豆類',
                'code' => 'D02',
            ],
            [
                'name' => 'みそ類',
                'code' => 'D03',
            ],
            [
                'name' => '油脂類',
                'code' => 'E01', // E:油脂類調味料
            ],
            [
                'name' => '砂糖類',
                'code' => 'E02',
            ],
            [
                'name' => '菓子類',
                'code' => 'E03',
            ],
            [
                'name' => 'その他',
                'code' => 'F01', // F:その他
            ],
        ];

        foreach ($categories as $category) {
            ItemCategory::create($category);
        }
    }
}
