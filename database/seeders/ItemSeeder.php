<?php

namespace Database\Seeders;

use App\Models\Allergen;
use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $allergenMap = Allergen::pluck('id', 'name')->toArray();

        $itemsData = [
            [
                'name' => '豚肉（こま切れ）',
                'item_category_id' => 2,
                'unit' => 'g',
                'storage_location' => '冷蔵',
                'menu_id' => 1,
                'required_amount' => 60,
            ],
            [
                'name' => '玉ねぎ',
                'item_category_id' => 6,
                'unit' => 'g',
                'capacity' => '250g/個',
                'storage_location' => '常温',
                'menu_id' => 1,
                'required_amount' => 60,
            ],
            [
                'name' => 'にんじん',
                'item_category_id' => 5,
                'unit' => 'g',
                'capacity' => '180g/個',
                'storage_location' => '常温',
                'menu_id' => 1,
                'required_amount' => 25,
            ],
            [
                'name' => 'じゃがいも',
                'item_category_id' => 8,
                'unit' => 'g',
                'capacity' => '150g/個',
                'storage_location' => '常温',
                'menu_id' => 1,
                'required_amount' => 25,
            ],
            [
                'name' => 'カレールウ',
                'item_category_id' => 19,
                'unit' => '箱',
                'storage_location' => '常温',
                'menu_id' => 1,
                'required_amount' => 0.1,
            ],
            [
                'name' => 'サラダ油',
                'item_category_id' => 16,
                'unit' => 'g',
                'capacity' => '1000g/本',
                'storage_location' => '常温',
                'menu_id' => 1,
                'required_amount' => 1,
            ],
            [
                'name' => 'キャベツ',
                'item_category_id' => 6,
                'unit' => 'g',
                'capacity' => '600g/玉',
                'storage_location' => '常温',
                'menu_id' => 2,
                'required_amount' => 70,
            ],
            [
                'name' => 'ツナ缶',
                'item_category_id' => 1,
                'unit' => 'g',
                'capacity' => '140g/缶',
                'storage_location' => '常温',
                'menu_id' => 2,
                'required_amount' => 11,
            ],
            [
                'name' => 'コーン缶',
                'item_category_id' => 5,
                'unit' => 'g',
                'capacity' => '300g/缶',
                'storage_location' => '常温',
                'menu_id' => 2,
                'required_amount' => 12,
            ],
            [
                'name' => 'ポン酢',
                'item_category_id' => 19,
                'unit' => 'ml',
                'capacity' => '500ml/本',
                'storage_location' => '冷蔵',
                'menu_id' => 2,
                'required_amount' => 2,
            ],
            [
                'name' => 'マヨネーズ',
                'item_category_id' => 16,
                'unit' => 'g',
                'capacity' => '500g/本',
                'storage_location' => '冷蔵',
                'allergens' => ['卵', '乳'],
                'menu_id' => 2,
                'required_amount' => 6,
            ],
            [
                'name' => '牛肉（こま切れ）',
                'item_category_id' => 2,
                'unit' => 'g',
                'storage_location' => '冷蔵',
                'menu_id' => 3,
                'required_amount' => 60,
            ],
            [
                'name' => 'ごぼう',
                'item_category_id' => 6,
                'unit' => 'g',
                'capacity' => '100g/本',
                'storage_location' => '常温',
                'menu_id' => 3,
                'required_amount' => 20,
            ],
            [
                'name' => 'こんにゃく',
                'item_category_id' => 8,
                'unit' => 'g',
                'capacity' => '100g/個',
                'storage_location' => '冷蔵',
                'menu_id' => 3,
                'required_amount' => 20,
            ],
            [
                'name' => 'きゅうり',
                'item_category_id' => 5,
                'unit' => 'g',
                'capacity' => '90g/本',
                'storage_location' => '冷蔵',
                'menu_id' => 4,
                'required_amount' => 30,
            ],
            [
                'name' => 'しらす干し',
                'item_category_id' => 1,
                'unit' => 'g',
                'storage_location' => '冷蔵',
                'menu_id' => 4,
                'required_amount' => 30,
            ],
            [
                'name' => '乾燥わかめ',
                'item_category_id' => 7,
                'unit' => 'g',
                'storage_location' => '常温',
                'menu_id' => 4,
                'required_amount' => 10,
            ],
            [
                'name' => '豆腐',
                'item_category_id' => 13,
                'unit' => 'g',
                'capacity' => '200g/丁',
                'storage_location' => '冷蔵',
                'menu_id' => 5,
                'required_amount' => 30,
            ],
            [
                'name' => 'みそ',
                'item_category_id' => 15,
                'unit' => 'g',
                'capacity' => '1000g/パック',
                'storage_location' => '冷蔵',
                'menu_id' => 5,
                'required_amount' => 10,
            ],
        ];

        foreach ($itemsData as $data) {
            $item = Item::create([
                'name' => $data['name'],
                'item_category_id' => $data['item_category_id'],
                'target_stock_qty' => $data['target_stock_qty'] ?? null,
                'unit' => $data['unit'],
                'capacity' => $data['capacity'] ?? null,
                'storage_location' => $data['storage_location'],
            ]);

            // アレルゲン物質がある場合は、中間テーブルに保存
            $allergens = $data['allergens'] ?? [];
            $allergenIds = []; //毎回初期化する

            foreach ($allergens as $allergenName) {
                if (isset($allergenMap[$allergenName])) {
                    $allergenIds[] = $allergenMap[$allergenName];
                }
            }

            if (!empty($allergenIds)) {
                $item->allergens()->attach($allergenIds);
            }

            // メニューと必要分量を中間テーブルに保存
            $menu_id = $data['menu_id'] ?? null;
            $required_amount = $data['required_amount'] ?? [];

            if (!is_null($menu_id) && !is_null($required_amount)) {
                $item->menus()->attach([$menu_id => ['required_amount' => $required_amount]]);
            }
        }
    }
}
