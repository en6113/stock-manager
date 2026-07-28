<?php

namespace Database\Seeders;

use App\Enums\StorageLocation;
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
                'storage_location' => StorageLocation::REFRIGERATOR,
                'vendor_id' => 3,
                'menu_id' => [1, 3],
                'required_amount' => 60,
            ],
            [
                'name' => '玉ねぎ',
                'item_category_id' => 6,
                'unit' => 'g',
                'purchase_unit' => '個',
                'unit_to_gram' => '250',
                'storage_location' => StorageLocation::PANTRY,
                'vendor_id' => 2,
                'menu_id' => [1, 3, 5],
                'required_amount' => 60,
            ],
            [
                'name' => 'にんじん',
                'item_category_id' => 5,
                'unit' => 'g',
                'purchase_unit' => '個',
                'unit_to_gram' => '180',
                'storage_location' => StorageLocation::PANTRY,
                'vendor_id' => 2,
                'menu_id' => [1, 3],
                'required_amount' => 25,
            ],
            [
                'name' => 'じゃがいも',
                'item_category_id' => 8,
                'unit' => 'g',
                'purchase_unit' => '個',
                'unit_to_gram' => '150',
                'storage_location' => StorageLocation::PANTRY,
                'vendor_id' => 2,
                'menu_id' => [1, 3],
                'required_amount' => 25,
            ],
            [
                'name' => 'カレールウ',
                'item_category_id' => 19,
                'unit' => 'g',
                'purchase_unit' => '袋',
                'unit_to_gram' => '1000',
                'storage_location' => StorageLocation::PANTRY,
                'vendor_id' => 1,
                'menu_id' => 1,
                'required_amount' => 0.1,
            ],
            [
                'name' => 'サラダ油',
                'item_category_id' => 16,
                'unit' => 'g',
                'purchase_unit' => '本',
                'unit_to_gram' => '1000',
                'storage_location' => StorageLocation::PANTRY,
                'vendor_id' => 1,
                'menu_id' => [1, 3],
                'required_amount' => 1,
            ],
            [
                'name' => 'キャベツ',
                'item_category_id' => 6,
                'unit' => 'g',
                'purchase_unit' => '個',
                'unit_to_gram' => '600',
                'storage_location' => StorageLocation::PANTRY,
                'vendor_id' => 2,
                'menu_id' => 2,
                'required_amount' => 70,
            ],
            [
                'name' => 'ツナ',
                'item_category_id' => 1,
                'unit' => 'g',
                'purchase_unit' => '袋',
                'unit_to_gram' => '1000',
                'storage_location' => StorageLocation::PANTRY,
                'vendor_id' => 1,
                'menu_id' => 2,
                'required_amount' => 11,
            ],
            [
                'name' => 'コーン',
                'item_category_id' => 5,
                'unit' => 'g',
                'purchase_unit' => '袋',
                'unit_to_gram' => '1000',
                'storage_location' => StorageLocation::PANTRY,
                'vendor_id' => 1,
                'menu_id' => 2,
                'required_amount' => 12,
            ],
            [
                'name' => 'ポン酢',
                'item_category_id' => 19,
                'unit' => 'ml',
                'purchase_unit' => '本',
                'unit_to_gram' => '1000',
                'storage_location' => StorageLocation::REFRIGERATOR,
                'vendor_id' => 1,
                'menu_id' => [2, 4],
                'required_amount' => 2,
            ],
            [
                'name' => 'マヨネーズ',
                'item_category_id' => 16,
                'unit' => 'g',
                'purchase_unit' => '本',
                'unit_to_gram' => '1000',
                'storage_location' => StorageLocation::REFRIGERATOR,
                'vendor_id' => 1,
                'allergens' => ['卵', '乳'],
                'menu_id' => 2,
                'required_amount' => 6,
            ],
        ];

        foreach ($itemsData as $data) {
            $item = Item::create([
                'name' => $data['name'],
                'item_category_id' => $data['item_category_id'],
                'proper_inventory' => $data['proper_inventory'] ?? null,
                'unit' => $data['unit'],
                'purchase_unit' => $data['purchase_unit'] ?? null,
                'unit_to_gram' => $data['unit_to_gram'] ?? null,
                'storage_location' => $data['storage_location'],
                'vendor_id' => $data['vendor_id'],
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
            $menuIds = (array) ($data['menu_id'] ?? []);
            $requiredAmount = $data['required_amount'] ?? [];

            if (!empty($menuIds) && !is_null($requiredAmount)) {
                $syncData = [];
                foreach ($menuIds as $menuId) {
                    $syncData[$menuId] = ['required_amount' => $requiredAmount];
                }
                $item->menus()->attach($syncData);
            }
        }
    }
}
