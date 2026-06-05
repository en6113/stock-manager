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
        $names = [
            '野菜・果物類',
            '肉類',
            '魚介類',
            '卵類',
            'きのこ類',
            '海藻類',
            '麺類',
            'いも類',
            '加工食品',
            '調味料',
            'その他',
        ];

        foreach ($names as $name) {
            ItemCategory::create(['name' => $name]);
        }
    }
}
