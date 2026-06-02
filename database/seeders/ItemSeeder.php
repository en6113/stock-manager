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
                'item_category' => '肉類',
                'unit' => 'kg',
                'storage_location' => '冷蔵',
                'vendor_id' => 3,
                'menu_id' => 1,
                'servings' => 50,
                'required_amount' => 3,
            ],
            [
                'name' => '玉ねぎ',
                'item_category' => '野菜類',
                'target_stock_qty' => 5,
                'unit' => '個',
                'capacity' => '250g/個',
                'storage_location' => '常温',
                'vendor_id' => 2,
                'menu_id' => 1,
                'servings' => 50,
                'required_amount' => 12,
            ],
            [
                'name' => 'にんじん',
                'item_category' => '野菜類',
                'unit' => '本',
                'capacity' => '180g/個',
                'storage_location' => '常温',
                'vendor_id' => 2,
                'menu_id' => 1,
                'servings' => 50,
                'required_amount' => 8,
            ],
            [
                'name' => 'じゃがいも',
                'item_category' => '野菜類',
                'unit' => '個',
                'capacity' => '150g/個',
                'storage_location' => '常温',
                'vendor_id' => 2,
                'menu_id' => 1,
                'servings' => 50,
                'required_amount' => 15,
            ],
            [
                'name' => 'カレールウ',
                'item_category' => '加工食品（調味料を含む）',
                'unit' => '箱',
                'storage_location' => '常温',
                'vendor_id' => 1,
                'menu_id' => 1,
                'servings' => 50,
                'required_amount' => 4,
            ],
            [
                'name' => 'サラダ油',
                'item_category' => '加工食品（調味料を含む）',
                'target_stock_qty' => 1000,
                'unit' => 'g',
                'capacity' => '1000g/本',
                'storage_location' => '常温',
                'vendor_id' => 1,
                'menu_id' => 1,
                'servings' => 50,
                'required_amount' => 45,
            ],
            [
                'name' => 'キャベツ',
                'item_category' => '野菜類',
                'unit' => '玉',
                'capacity' => '600g/玉',
                'storage_location' => '常温',
                'vendor_id' => 2,
                'menu_id' => 2,
                'servings' => 50,
                'required_amount' => 5,
            ],
            [
                'name' => 'ツナ缶',
                'item_category' => '加工食品（調味料を含む）',
                'target_stock_qty' => 4,
                'unit' => '缶',
                'capacity' => '140g/缶',
                'storage_location' => '常温',
                'vendor_id' => 1,
                'menu_id' => 2,
                'servings' => 50,
                'required_amount' => 4,
            ],
            [
                'name' => 'コーン缶',
                'item_category' => '加工食品（調味料を含む）',
                'unit' => '缶',
                'capacity' => '300g/缶',
                'storage_location' => '常温',
                'vendor_id' => 1,
                'menu_id' => 2,
                'servings' => 50,
                'required_amount' => 2,
            ],
            [
                'name' => 'ポン酢',
                'item_category' => '加工食品（調味料を含む）',
                'target_stock_qty' => 1,
                'unit' => '本',
                'capacity' => '300g/本',
                'storage_location' => '冷蔵',
                'vendor_id' => 1,
                'menu_id' => 2,
                'servings' => 50,
                'required_amount' => 1,
            ],
            [
                'name' => 'マヨネーズ',
                'item_category' => '加工食品（調味料を含む）',
                'target_stock_qty' => 1,
                'unit' => '本',
                'capacity' => '500g/本',
                'storage_location' => '冷蔵',
                'vendor_id' => 1,
                'allergens' => ['卵', '乳'],
                'menu_id' => 2,
                'servings' => 50,
                'required_amount' => 1,
            ],
        ];

        foreach ($itemsData as $data) {
            $item = Item::create([
                'name' => $data['name'],
                'item_category' => $data['item_category'],
                'target_stock_qty' => $data['target_stock_qty'] ?? null,
                'unit' => $data['unit'],
                'capacity' => $data['capacity'] ?? null,
                'storage_location' => $data['storage_location'],
                'vendor_id' => $data['vendor_id'] ?? null,
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
            $servings = $data['servings'] ?? null;
            $required_amount = $data['required_amount'] ?? [];

            if (!is_null($menu_id) && !is_null($servings)&& !is_null($required_amount)) {
                $item->menus()->attach([$menu_id => ['servings' => $servings, 'required_amount' => $required_amount]]);
            }
        }
    }
}
