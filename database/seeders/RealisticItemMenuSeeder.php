<?php

namespace Database\Seeders;

use App\Enums\DishCategory;
use App\Enums\ItemUnit;
use App\Enums\StorageLocation;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\Menu;
use Illuminate\Database\Seeder;

/**
 * 久留米市オープンデータ「料理の栄養価一覧」（CC BY 4.0）を元にした
 * 保育園向け献立マスタ（食材・メニュー）の追加シード
 * https://data.bodik.jp/dataset/402036_0009100_00005
 */
class RealisticItemMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categoryIds = ItemCategory::pluck('id', 'code')->toArray();

        $itemsData = [
            ['name' => '豚ロース肉', 'category' => 'A02', 'unit' => ItemUnit::Gram, 'gram_per_unit' => 1, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 3],
            ['name' => 'こいくちしょうゆ', 'category' => 'F01', 'unit' => ItemUnit::Bottle, 'gram_per_unit' => 1000, 'storage' => StorageLocation::PANTRY, 'vendor_id' => 1],
            ['name' => '清酒', 'category' => 'F01', 'unit' => ItemUnit::Bottle, 'gram_per_unit' => 1000, 'storage' => StorageLocation::PANTRY, 'vendor_id' => 1],
            ['name' => 'しょうが', 'category' => 'B02', 'unit' => ItemUnit::Gram, 'gram_per_unit' => 1, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 2],
            ['name' => '調合油', 'category' => 'E01', 'unit' => ItemUnit::Bottle, 'gram_per_unit' => 1000, 'storage' => StorageLocation::PANTRY, 'vendor_id' => 1],
            ['name' => '鶏もも肉', 'category' => 'A02', 'unit' => ItemUnit::Gram, 'gram_per_unit' => 1, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 3],
            ['name' => '本みりん', 'category' => 'F01', 'unit' => ItemUnit::Bottle, 'gram_per_unit' => 1000, 'storage' => StorageLocation::PANTRY, 'vendor_id' => 1],
            ['name' => '鶏むね肉（皮つき）', 'category' => 'A02', 'unit' => ItemUnit::Gram, 'gram_per_unit' => 1, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 3],
            ['name' => '米みそ', 'category' => 'D03', 'unit' => ItemUnit::Pack, 'gram_per_unit' => 1000, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 1],
            ['name' => 'みりん風調味料', 'category' => 'F01', 'unit' => ItemUnit::Bottle, 'gram_per_unit' => 1000, 'storage' => StorageLocation::PANTRY, 'vendor_id' => 1],
            ['name' => 'ごま', 'category' => 'F01', 'unit' => ItemUnit::Pack, 'gram_per_unit' => 100, 'storage' => StorageLocation::PANTRY, 'vendor_id' => 1],
            ['name' => '玉ねぎ', 'category' => 'B02', 'unit' => ItemUnit::Piece, 'gram_per_unit' => 250, 'storage' => StorageLocation::PANTRY, 'vendor_id' => 2],
            ['name' => 'ピーマン', 'category' => 'B01', 'unit' => ItemUnit::Piece, 'gram_per_unit' => 35, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 2],
            ['name' => 'にんじん', 'category' => 'B01', 'unit' => ItemUnit::Piece, 'gram_per_unit' => 180, 'storage' => StorageLocation::PANTRY, 'vendor_id' => 2],
            ['name' => '片栗粉', 'category' => 'F01', 'unit' => ItemUnit::Pack, 'gram_per_unit' => 200, 'storage' => StorageLocation::PANTRY, 'vendor_id' => 1],
            ['name' => '穀物酢', 'category' => 'F01', 'unit' => ItemUnit::Bottle, 'gram_per_unit' => 1000, 'storage' => StorageLocation::PANTRY, 'vendor_id' => 1],
            ['name' => '上白糖', 'category' => 'E02', 'unit' => ItemUnit::Pack, 'gram_per_unit' => 1000, 'storage' => StorageLocation::PANTRY, 'vendor_id' => 1],
            ['name' => '中華だし', 'category' => 'F01', 'unit' => ItemUnit::Pack, 'gram_per_unit' => 500, 'storage' => StorageLocation::PANTRY, 'vendor_id' => 1],
            ['name' => 'さといも', 'category' => 'B04', 'unit' => ItemUnit::Gram, 'gram_per_unit' => 1, 'storage' => StorageLocation::PANTRY, 'vendor_id' => 2],
            ['name' => 'ごぼう', 'category' => 'B02', 'unit' => ItemUnit::Gram, 'gram_per_unit' => 1, 'storage' => StorageLocation::PANTRY, 'vendor_id' => 2],
            ['name' => 'れんこん', 'category' => 'B02', 'unit' => ItemUnit::Gram, 'gram_per_unit' => 1, 'storage' => StorageLocation::PANTRY, 'vendor_id' => 2],
            ['name' => 'こんにゃく', 'category' => 'F01', 'unit' => ItemUnit::Piece, 'gram_per_unit' => 200, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 1],
            ['name' => '干ししいたけ', 'category' => 'B02', 'unit' => ItemUnit::Pack, 'gram_per_unit' => 50, 'storage' => StorageLocation::PANTRY, 'vendor_id' => 2],
            ['name' => 'かつお昆布だし', 'category' => 'F01', 'unit' => ItemUnit::Pack, 'gram_per_unit' => 500, 'storage' => StorageLocation::PANTRY, 'vendor_id' => 1],
            ['name' => 'さやいんげん', 'category' => 'B01', 'unit' => ItemUnit::Gram, 'gram_per_unit' => 1, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 2],
            ['name' => '牛肉（もも）', 'category' => 'A02', 'unit' => ItemUnit::Gram, 'gram_per_unit' => 1, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 3],
            ['name' => 'じゃがいも', 'category' => 'B04', 'unit' => ItemUnit::Piece, 'gram_per_unit' => 150, 'storage' => StorageLocation::PANTRY, 'vendor_id' => 2],
            ['name' => 'しらたき', 'category' => 'F01', 'unit' => ItemUnit::Gram, 'gram_per_unit' => 1, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 1],
            ['name' => '食塩', 'category' => 'F01', 'unit' => ItemUnit::Pack, 'gram_per_unit' => 1000, 'storage' => StorageLocation::PANTRY, 'vendor_id' => 1],
            ['name' => 'こしょう（混合）', 'category' => 'F01', 'unit' => ItemUnit::Pack, 'gram_per_unit' => 100, 'storage' => StorageLocation::PANTRY, 'vendor_id' => 1],
            ['name' => '薄力粉', 'category' => 'F01', 'unit' => ItemUnit::Pack, 'gram_per_unit' => 1000, 'storage' => StorageLocation::PANTRY, 'vendor_id' => 1],
            ['name' => '卵', 'category' => 'A04', 'unit' => ItemUnit::Piece, 'gram_per_unit' => 60, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 1],
            ['name' => 'パン粉', 'category' => 'C02', 'unit' => ItemUnit::Pack, 'gram_per_unit' => 200, 'storage' => StorageLocation::PANTRY, 'vendor_id' => 1],
            ['name' => '中濃ソース', 'category' => 'F01', 'unit' => ItemUnit::Bottle, 'gram_per_unit' => 500, 'storage' => StorageLocation::PANTRY, 'vendor_id' => 1],
            ['name' => '豚ヒレ肉', 'category' => 'A02', 'unit' => ItemUnit::Gram, 'gram_per_unit' => 1, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 3],
            ['name' => '黒こしょう', 'category' => 'F01', 'unit' => ItemUnit::Pack, 'gram_per_unit' => 100, 'storage' => StorageLocation::PANTRY, 'vendor_id' => 1],
            ['name' => 'あじ', 'category' => 'A01', 'unit' => ItemUnit::Gram, 'gram_per_unit' => 1, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 4],
            ['name' => 'さんま', 'category' => 'A01', 'unit' => ItemUnit::Fish, 'gram_per_unit' => 120, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 4],
            ['name' => 'ぶり（切り身）', 'category' => 'A01', 'unit' => ItemUnit::Gram, 'gram_per_unit' => 1, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 4],
            ['name' => 'たい（切り身）', 'category' => 'A01', 'unit' => ItemUnit::Gram, 'gram_per_unit' => 1, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 4],
            ['name' => '昆布だし', 'category' => 'F01', 'unit' => ItemUnit::Pack, 'gram_per_unit' => 500, 'storage' => StorageLocation::PANTRY, 'vendor_id' => 1],
            ['name' => 'かれい（切り身）', 'category' => 'A01', 'unit' => ItemUnit::Gram, 'gram_per_unit' => 1, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 4],
            ['name' => '油揚げ', 'category' => 'D01', 'unit' => ItemUnit::Pack, 'gram_per_unit' => 20, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 1],
            ['name' => 'かんぴょう', 'category' => 'F01', 'unit' => ItemUnit::Pack, 'gram_per_unit' => 50, 'storage' => StorageLocation::PANTRY, 'vendor_id' => 2],
            ['name' => 'かつおだし', 'category' => 'F01', 'unit' => ItemUnit::Pack, 'gram_per_unit' => 500, 'storage' => StorageLocation::PANTRY, 'vendor_id' => 1],
            ['name' => 'うすくちしょうゆ', 'category' => 'F01', 'unit' => ItemUnit::Bottle, 'gram_per_unit' => 1000, 'storage' => StorageLocation::PANTRY, 'vendor_id' => 1],
            ['name' => '鶏むね肉（皮なし）', 'category' => 'A02', 'unit' => ItemUnit::Gram, 'gram_per_unit' => 1, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 3],
            ['name' => 'かまぼこ', 'category' => 'A01', 'unit' => ItemUnit::Piece, 'gram_per_unit' => 200, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 4],
            ['name' => '生しいたけ', 'category' => 'B02', 'unit' => ItemUnit::Gram, 'gram_per_unit' => 1, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 2],
            ['name' => 'みつば', 'category' => 'B01', 'unit' => ItemUnit::Gram, 'gram_per_unit' => 1, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 2],
            ['name' => '絹ごし豆腐', 'category' => 'D01', 'unit' => ItemUnit::Piece, 'gram_per_unit' => 300, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 1],
            ['name' => '春菊', 'category' => 'B01', 'unit' => ItemUnit::Gram, 'gram_per_unit' => 1, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 2],
            ['name' => '小ねぎ', 'category' => 'B02', 'unit' => ItemUnit::Gram, 'gram_per_unit' => 1, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 2],
            ['name' => '枝豆', 'category' => 'D02', 'unit' => ItemUnit::Gram, 'gram_per_unit' => 1, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 2],
            ['name' => 'ほうれん草', 'category' => 'B01', 'unit' => ItemUnit::Gram, 'gram_per_unit' => 1, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 2],
            ['name' => 'かつお削り節', 'category' => 'F01', 'unit' => ItemUnit::Pack, 'gram_per_unit' => 50, 'storage' => StorageLocation::PANTRY, 'vendor_id' => 1],
            ['name' => '小松菜', 'category' => 'B01', 'unit' => ItemUnit::Gram, 'gram_per_unit' => 1, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 2],
            ['name' => 'なす', 'category' => 'B02', 'unit' => ItemUnit::Piece, 'gram_per_unit' => 80, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 2],
            ['name' => '赤ピーマン', 'category' => 'B01', 'unit' => ItemUnit::Piece, 'gram_per_unit' => 40, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 2],
            ['name' => '黄ピーマン', 'category' => 'B01', 'unit' => ItemUnit::Piece, 'gram_per_unit' => 40, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 2],
            ['name' => 'おろしにんにく', 'category' => 'F01', 'unit' => ItemUnit::Pack, 'gram_per_unit' => 100, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 2],
            ['name' => '豆板醤', 'category' => 'F01', 'unit' => ItemUnit::Pack, 'gram_per_unit' => 100, 'storage' => StorageLocation::PANTRY, 'vendor_id' => 1],
            ['name' => 'レタス', 'category' => 'B02', 'unit' => ItemUnit::Head, 'gram_per_unit' => 300, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 2],
            ['name' => 'きゅうり', 'category' => 'B02', 'unit' => ItemUnit::Bottle, 'gram_per_unit' => 100, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 2],
            ['name' => 'ミニトマト', 'category' => 'B01', 'unit' => ItemUnit::Gram, 'gram_per_unit' => 1, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 2],
            ['name' => 'コーン', 'category' => 'B02', 'unit' => ItemUnit::Pack, 'gram_per_unit' => 1000, 'storage' => StorageLocation::PANTRY, 'vendor_id' => 1],
            ['name' => '大根', 'category' => 'B02', 'unit' => ItemUnit::Bottle, 'gram_per_unit' => 1000, 'storage' => StorageLocation::PANTRY, 'vendor_id' => 2],
            ['name' => 'かぼちゃ', 'category' => 'B01', 'unit' => ItemUnit::Gram, 'gram_per_unit' => 1, 'storage' => StorageLocation::PANTRY, 'vendor_id' => 2],
            ['name' => '鶏ひき肉', 'category' => 'A02', 'unit' => ItemUnit::Gram, 'gram_per_unit' => 1, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 3],
            ['name' => 'はんぺん', 'category' => 'A01', 'unit' => ItemUnit::Gram, 'gram_per_unit' => 1, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 4],
            ['name' => '長ねぎ', 'category' => 'B02', 'unit' => ItemUnit::Bottle, 'gram_per_unit' => 100, 'storage' => StorageLocation::PANTRY, 'vendor_id' => 2],
            ['name' => '白こしょう', 'category' => 'F01', 'unit' => ItemUnit::Pack, 'gram_per_unit' => 100, 'storage' => StorageLocation::PANTRY, 'vendor_id' => 1],
            ['name' => 'しめじ', 'category' => 'B02', 'unit' => ItemUnit::Gram, 'gram_per_unit' => 1, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 2],
            ['name' => 'えのきたけ', 'category' => 'B02', 'unit' => ItemUnit::Gram, 'gram_per_unit' => 1, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 2],
            ['name' => 'さつまいも', 'category' => 'B04', 'unit' => ItemUnit::Gram, 'gram_per_unit' => 1, 'storage' => StorageLocation::PANTRY, 'vendor_id' => 2],
            ['name' => 'カットわかめ', 'category' => 'B03', 'unit' => ItemUnit::Pack, 'gram_per_unit' => 50, 'storage' => StorageLocation::PANTRY, 'vendor_id' => 2],
            ['name' => '煮干しだし', 'category' => 'F01', 'unit' => ItemUnit::Pack, 'gram_per_unit' => 500, 'storage' => StorageLocation::PANTRY, 'vendor_id' => 1],
            ['name' => 'もやし', 'category' => 'B02', 'unit' => ItemUnit::Pack, 'gram_per_unit' => 200, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 2],
            ['name' => 'コーン缶（クリーム）', 'category' => 'B02', 'unit' => ItemUnit::Box, 'gram_per_unit' => 180, 'storage' => StorageLocation::PANTRY, 'vendor_id' => 2],
            ['name' => '牛乳', 'category' => 'A03', 'unit' => ItemUnit::Bottle, 'gram_per_unit' => 1000, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 1],
            ['name' => '固形コンソメ', 'category' => 'F01', 'unit' => ItemUnit::Box, 'gram_per_unit' => 60, 'storage' => StorageLocation::PANTRY, 'vendor_id' => 1],
            ['name' => 'パセリ', 'category' => 'B01', 'unit' => ItemUnit::Gram, 'gram_per_unit' => 1, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 2],
            ['name' => 'ごはん（炊飯済み）', 'category' => 'C01', 'unit' => ItemUnit::Gram, 'gram_per_unit' => 1, 'storage' => StorageLocation::PANTRY, 'vendor_id' => 1],
            ['name' => '米', 'category' => 'C01', 'unit' => ItemUnit::Pack, 'gram_per_unit' => 5000, 'storage' => StorageLocation::PANTRY, 'vendor_id' => 1],
            ['name' => '押麦', 'category' => 'C01', 'unit' => ItemUnit::Pack, 'gram_per_unit' => 300, 'storage' => StorageLocation::PANTRY, 'vendor_id' => 1],
            ['name' => '食パン', 'category' => 'C02', 'unit' => ItemUnit::Pack, 'gram_per_unit' => 450, 'storage' => StorageLocation::PANTRY, 'vendor_id' => 1],
            ['name' => 'マーガリン', 'category' => 'E01', 'unit' => ItemUnit::Box, 'gram_per_unit' => 200, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 1],
            ['name' => 'マヨネーズ', 'category' => 'E01', 'unit' => ItemUnit::Bottle, 'gram_per_unit' => 1000, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 1],
            ['name' => 'トマト', 'category' => 'B01', 'unit' => ItemUnit::Piece, 'gram_per_unit' => 150, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 2],
            ['name' => 'ゆでうどん', 'category' => 'C03', 'unit' => ItemUnit::Gram, 'gram_per_unit' => 1, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 1],
            ['name' => '生わかめ', 'category' => 'B03', 'unit' => ItemUnit::Gram, 'gram_per_unit' => 1, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 2],
            ['name' => 'バナナ', 'category' => 'B05', 'unit' => ItemUnit::Bottle, 'gram_per_unit' => 100, 'storage' => StorageLocation::PANTRY, 'vendor_id' => 2],
            ['name' => 'みかん', 'category' => 'B05', 'unit' => ItemUnit::Piece, 'gram_per_unit' => 90, 'storage' => StorageLocation::PANTRY, 'vendor_id' => 2],
            ['name' => 'ヨーグルト（プレーン）', 'category' => 'A03', 'unit' => ItemUnit::Piece, 'gram_per_unit' => 70, 'storage' => StorageLocation::REFRIGERATOR, 'vendor_id' => 1],
        ];

        // 同名の食材が既に存在する場合はそれを再利用する（既存のItemSeederと重複させない）
        $itemIds = [];
        foreach ($itemsData as $data) {
            $item = Item::firstOrCreate(
                ['name' => $data['name']],
                [
                    'item_category_id' => $categoryIds[$data['category']],
                    'unit' => $data['unit'],
                    'gram_per_unit' => $data['gram_per_unit'],
                    'storage_location' => $data['storage'],
                    'vendor_id' => $data['vendor_id'],
                ]
            );
            $itemIds[$data['name']] = $item->id;
        }

        $menusData = [
            [
                'name' => '豚肉のしょうが焼き',
                'dish_category' => DishCategory::Main,
                'calorie' => 280,
                'ingredients' => [
                    ['name' => '豚ロース肉', 'required_amount' => 90],
                    ['name' => 'こいくちしょうゆ', 'required_amount' => 6],
                    ['name' => '清酒', 'required_amount' => 1],
                    ['name' => 'しょうが', 'required_amount' => 5],
                    ['name' => '調合油', 'required_amount' => 4],
                ],
            ],
            [
                'name' => '鶏もも肉の照り焼き',
                'dish_category' => DishCategory::Main,
                'calorie' => 225,
                'ingredients' => [
                    ['name' => '鶏もも肉', 'required_amount' => 80],
                    ['name' => 'こいくちしょうゆ', 'required_amount' => 9],
                    ['name' => '本みりん', 'required_amount' => 9],
                    ['name' => '調合油', 'required_amount' => 4],
                ],
            ],
            [
                'name' => '鶏むね肉の味噌ダレ焼き',
                'dish_category' => DishCategory::Main,
                'calorie' => 154,
                'ingredients' => [
                    ['name' => '鶏むね肉（皮つき）', 'required_amount' => 70],
                    ['name' => '米みそ', 'required_amount' => 4],
                    ['name' => 'こいくちしょうゆ', 'required_amount' => 2],
                    ['name' => 'みりん風調味料', 'required_amount' => 3],
                    ['name' => '清酒', 'required_amount' => 2.5],
                    ['name' => 'ごま', 'required_amount' => 0.3],
                ],
            ],
            [
                'name' => '鶏の甘酢あんかけ',
                'dish_category' => DishCategory::Main,
                'calorie' => 210,
                'ingredients' => [
                    ['name' => '鶏もも肉', 'required_amount' => 60],
                    ['name' => '玉ねぎ', 'required_amount' => 10],
                    ['name' => 'ピーマン', 'required_amount' => 10],
                    ['name' => 'にんじん', 'required_amount' => 15],
                    ['name' => '片栗粉', 'required_amount' => 5.25],
                    ['name' => '調合油', 'required_amount' => 4],
                    ['name' => 'こいくちしょうゆ', 'required_amount' => 4],
                    ['name' => '穀物酢', 'required_amount' => 5],
                    ['name' => '上白糖', 'required_amount' => 5],
                    ['name' => '中華だし', 'required_amount' => 50],
                ],
            ],
            [
                'name' => '筑前煮',
                'dish_category' => DishCategory::Main,
                'calorie' => 234,
                'ingredients' => [
                    ['name' => '鶏もも肉', 'required_amount' => 50],
                    ['name' => 'さといも', 'required_amount' => 50],
                    ['name' => 'ごぼう', 'required_amount' => 20],
                    ['name' => 'れんこん', 'required_amount' => 25],
                    ['name' => 'にんじん', 'required_amount' => 25],
                    ['name' => 'こんにゃく', 'required_amount' => 20],
                    ['name' => '干ししいたけ', 'required_amount' => 2],
                    ['name' => '調合油', 'required_amount' => 2],
                    ['name' => 'かつお昆布だし', 'required_amount' => 50],
                    ['name' => '上白糖', 'required_amount' => 2.2],
                    ['name' => '清酒', 'required_amount' => 7.5],
                    ['name' => '本みりん', 'required_amount' => 9],
                    ['name' => 'こいくちしょうゆ', 'required_amount' => 4.5],
                    ['name' => 'さやいんげん', 'required_amount' => 5],
                ],
            ],
            [
                'name' => '肉じゃが',
                'dish_category' => DishCategory::Main,
                'calorie' => 309,
                'ingredients' => [
                    ['name' => '牛肉（もも）', 'required_amount' => 40],
                    ['name' => 'じゃがいも', 'required_amount' => 100],
                    ['name' => 'にんじん', 'required_amount' => 25],
                    ['name' => '玉ねぎ', 'required_amount' => 40],
                    ['name' => 'しらたき', 'required_amount' => 20],
                    ['name' => '調合油', 'required_amount' => 6],
                    ['name' => 'かつお昆布だし', 'required_amount' => 50],
                    ['name' => '上白糖', 'required_amount' => 4.5],
                    ['name' => '清酒', 'required_amount' => 7.5],
                    ['name' => '本みりん', 'required_amount' => 9],
                    ['name' => 'こいくちしょうゆ', 'required_amount' => 9],
                ],
            ],
            [
                'name' => 'とんカツ（ソース付き）',
                'dish_category' => DishCategory::Main,
                'calorie' => 396,
                'ingredients' => [
                    ['name' => '豚ロース肉', 'required_amount' => 90],
                    ['name' => '食塩', 'required_amount' => 0.5],
                    ['name' => 'こしょう（混合）', 'required_amount' => 0.01],
                    ['name' => '薄力粉', 'required_amount' => 5],
                    ['name' => '卵', 'required_amount' => 5],
                    ['name' => 'パン粉', 'required_amount' => 5],
                    ['name' => '調合油', 'required_amount' => 11],
                    ['name' => '中濃ソース', 'required_amount' => 10],
                ],
            ],
            [
                'name' => 'ヒレカツ（ソース付き）',
                'dish_category' => DishCategory::Main,
                'calorie' => 233,
                'ingredients' => [
                    ['name' => '豚ヒレ肉', 'required_amount' => 80],
                    ['name' => '食塩', 'required_amount' => 0.4],
                    ['name' => '黒こしょう', 'required_amount' => 0.01],
                    ['name' => '薄力粉', 'required_amount' => 4],
                    ['name' => '卵', 'required_amount' => 4],
                    ['name' => 'パン粉', 'required_amount' => 4],
                    ['name' => '調合油', 'required_amount' => 10],
                    ['name' => '中濃ソース', 'required_amount' => 10],
                ],
            ],
            [
                'name' => 'あじの塩焼き',
                'dish_category' => DishCategory::Main,
                'calorie' => 85,
                'ingredients' => [
                    ['name' => 'あじ', 'required_amount' => 70],
                    ['name' => '食塩', 'required_amount' => 0.7],
                ],
            ],
            [
                'name' => 'サンマの塩焼き',
                'dish_category' => DishCategory::Main,
                'calorie' => 279,
                'ingredients' => [
                    ['name' => 'さんま', 'required_amount' => 90],
                    ['name' => '食塩', 'required_amount' => 1],
                ],
            ],
            [
                'name' => 'ブリの照り焼き',
                'dish_category' => DishCategory::Main,
                'calorie' => 286,
                'ingredients' => [
                    ['name' => 'ぶり（切り身）', 'required_amount' => 90],
                    ['name' => 'こいくちしょうゆ', 'required_amount' => 6],
                    ['name' => 'みりん風調味料', 'required_amount' => 6],
                    ['name' => '調合油', 'required_amount' => 4],
                ],
            ],
            [
                'name' => '鯛の煮付け',
                'dish_category' => DishCategory::Main,
                'calorie' => 162,
                'ingredients' => [
                    ['name' => 'たい（切り身）', 'required_amount' => 70],
                    ['name' => '昆布だし', 'required_amount' => 50],
                    ['name' => '清酒', 'required_amount' => 15],
                    ['name' => '上白糖', 'required_amount' => 3.75],
                    ['name' => '本みりん', 'required_amount' => 9],
                    ['name' => 'こいくちしょうゆ', 'required_amount' => 12],
                ],
            ],
            [
                'name' => 'カレイの煮つけ',
                'dish_category' => DishCategory::Main,
                'calorie' => 137,
                'ingredients' => [
                    ['name' => 'かれい（切り身）', 'required_amount' => 85],
                    ['name' => 'しょうが', 'required_amount' => 3],
                    ['name' => '昆布だし', 'required_amount' => 50],
                    ['name' => '清酒', 'required_amount' => 7.5],
                    ['name' => '上白糖', 'required_amount' => 4.5],
                    ['name' => '本みりん', 'required_amount' => 9],
                    ['name' => 'こいくちしょうゆ', 'required_amount' => 9],
                ],
            ],
            [
                'name' => '鯵フライ（ソース付き）',
                'dish_category' => DishCategory::Main,
                'calorie' => 259,
                'ingredients' => [
                    ['name' => 'あじ', 'required_amount' => 70],
                    ['name' => '食塩', 'required_amount' => 0.3],
                    ['name' => 'こしょう（混合）', 'required_amount' => 0.03],
                    ['name' => '薄力粉', 'required_amount' => 4.5],
                    ['name' => '卵', 'required_amount' => 12.5],
                    ['name' => 'パン粉', 'required_amount' => 10],
                    ['name' => '調合油', 'required_amount' => 9.7],
                    ['name' => '中濃ソース', 'required_amount' => 9],
                ],
            ],
            [
                'name' => 'たまごの袋煮',
                'dish_category' => DishCategory::Main,
                'calorie' => 213,
                'ingredients' => [
                    ['name' => '卵', 'required_amount' => 50],
                    ['name' => '油揚げ', 'required_amount' => 30],
                    ['name' => 'かんぴょう', 'required_amount' => 1],
                    ['name' => 'かつお昆布だし', 'required_amount' => 180],
                    ['name' => '上白糖', 'required_amount' => 2],
                    ['name' => 'こいくちしょうゆ', 'required_amount' => 5],
                    ['name' => '清酒', 'required_amount' => 4],
                ],
            ],
            [
                'name' => '茶碗蒸し',
                'dish_category' => DishCategory::Main,
                'calorie' => 103,
                'ingredients' => [
                    ['name' => '卵', 'required_amount' => 37],
                    ['name' => 'かつおだし', 'required_amount' => 100],
                    ['name' => 'みりん風調味料', 'required_amount' => 1.5],
                    ['name' => '食塩', 'required_amount' => 0.6],
                    ['name' => 'うすくちしょうゆ', 'required_amount' => 3.7],
                    ['name' => '鶏むね肉（皮なし）', 'required_amount' => 25],
                    ['name' => 'かまぼこ', 'required_amount' => 10],
                    ['name' => '生しいたけ', 'required_amount' => 10],
                    ['name' => 'みつば', 'required_amount' => 5],
                ],
            ],
            [
                'name' => '湯豆腐（3分の1丁）（味付けあり）',
                'dish_category' => DishCategory::Main,
                'calorie' => 103,
                'ingredients' => [
                    ['name' => '絹ごし豆腐', 'required_amount' => 100],
                    ['name' => '春菊', 'required_amount' => 25],
                    ['name' => '生しいたけ', 'required_amount' => 10],
                    ['name' => '昆布だし', 'required_amount' => 175],
                    ['name' => 'こいくちしょうゆ', 'required_amount' => 12],
                    ['name' => '本みりん', 'required_amount' => 9],
                    ['name' => '小ねぎ', 'required_amount' => 5],
                    ['name' => 'しょうが', 'required_amount' => 3],
                ],
            ],
            [
                'name' => '枝豆',
                'dish_category' => DishCategory::Side,
                'calorie' => 81,
                'ingredients' => [
                    ['name' => '枝豆', 'required_amount' => 60],
                    ['name' => '食塩', 'required_amount' => 1],
                ],
            ],
            [
                'name' => 'ほうれん草のお浸し',
                'dish_category' => DishCategory::Side,
                'calorie' => 20,
                'ingredients' => [
                    ['name' => 'ほうれん草', 'required_amount' => 70],
                    ['name' => 'かつお削り節', 'required_amount' => 1],
                    ['name' => 'こいくちしょうゆ', 'required_amount' => 4],
                    ['name' => 'かつお昆布だし', 'required_amount' => 4],
                ],
            ],
            [
                'name' => '小松菜のお浸し',
                'dish_category' => DishCategory::Side,
                'calorie' => 16,
                'ingredients' => [
                    ['name' => '小松菜', 'required_amount' => 70],
                    ['name' => 'かつお削り節', 'required_amount' => 1],
                    ['name' => 'こいくちしょうゆ', 'required_amount' => 4],
                    ['name' => 'かつお昆布だし', 'required_amount' => 4],
                ],
            ],
            [
                'name' => 'なすの中華お浸し',
                'dish_category' => DishCategory::Side,
                'calorie' => 21,
                'ingredients' => [
                    ['name' => 'なす', 'required_amount' => 30],
                    ['name' => '赤ピーマン', 'required_amount' => 10],
                    ['name' => '黄ピーマン', 'required_amount' => 10],
                    ['name' => 'おろしにんにく', 'required_amount' => 0.5],
                    ['name' => '豆板醤', 'required_amount' => 0.75],
                    ['name' => 'こいくちしょうゆ', 'required_amount' => 4.5],
                    ['name' => '穀物酢', 'required_amount' => 1.6],
                    ['name' => '上白糖', 'required_amount' => 1],
                    ['name' => '中華だし', 'required_amount' => 15],
                ],
            ],
            [
                'name' => 'レタスサラダ（ドレッシングなし）',
                'dish_category' => DishCategory::Side,
                'calorie' => 15,
                'ingredients' => [
                    ['name' => 'レタス', 'required_amount' => 50],
                    ['name' => 'きゅうり', 'required_amount' => 20],
                    ['name' => 'ミニトマト', 'required_amount' => 20],
                ],
            ],
            [
                'name' => 'コーンサラダ（ドレッシングなし）',
                'dish_category' => DishCategory::Side,
                'calorie' => 38,
                'ingredients' => [
                    ['name' => 'レタス', 'required_amount' => 40],
                    ['name' => 'きゅうり', 'required_amount' => 20],
                    ['name' => 'ミニトマト', 'required_amount' => 20],
                    ['name' => 'コーン', 'required_amount' => 30],
                ],
            ],
            [
                'name' => '大根の煮物',
                'dish_category' => DishCategory::Side,
                'calorie' => 53,
                'ingredients' => [
                    ['name' => '大根', 'required_amount' => 150],
                    ['name' => 'かつお昆布だし', 'required_amount' => 100],
                    ['name' => '上白糖', 'required_amount' => 4.5],
                    ['name' => 'こいくちしょうゆ', 'required_amount' => 10],
                ],
            ],
            [
                'name' => 'かぼちゃの煮物',
                'dish_category' => DishCategory::Side,
                'calorie' => 124,
                'ingredients' => [
                    ['name' => 'かぼちゃ', 'required_amount' => 100],
                    ['name' => '昆布だし', 'required_amount' => 70],
                    ['name' => 'こいくちしょうゆ', 'required_amount' => 6],
                    ['name' => '清酒', 'required_amount' => 7.5],
                    ['name' => '上白糖', 'required_amount' => 4.5],
                ],
            ],
            [
                'name' => 'れんこん挟み焼き＿きのこ餡かけ',
                'dish_category' => DishCategory::Side,
                'calorie' => 213,
                'ingredients' => [
                    ['name' => 'れんこん', 'required_amount' => 40],
                    ['name' => '鶏ひき肉', 'required_amount' => 50],
                    ['name' => 'はんぺん', 'required_amount' => 10],
                    ['name' => '長ねぎ', 'required_amount' => 10],
                    ['name' => 'しょうが', 'required_amount' => 1],
                    ['name' => '片栗粉', 'required_amount' => 5.75],
                    ['name' => '食塩', 'required_amount' => 0.2],
                    ['name' => '白こしょう', 'required_amount' => 0.01],
                    ['name' => '調合油', 'required_amount' => 7],
                    ['name' => 'しめじ', 'required_amount' => 5],
                    ['name' => 'えのきたけ', 'required_amount' => 5],
                    ['name' => 'かつお昆布だし', 'required_amount' => 40],
                    ['name' => 'うすくちしょうゆ', 'required_amount' => 3],
                    ['name' => '本みりん', 'required_amount' => 1.5],
                ],
            ],
            [
                'name' => '里芋の含め煮',
                'dish_category' => DishCategory::Side,
                'calorie' => 104,
                'ingredients' => [
                    ['name' => 'さといも', 'required_amount' => 130],
                    ['name' => '昆布だし', 'required_amount' => 100],
                    ['name' => '上白糖', 'required_amount' => 4.5],
                    ['name' => '清酒', 'required_amount' => 5],
                    ['name' => '食塩', 'required_amount' => 0.3],
                    ['name' => 'こいくちしょうゆ', 'required_amount' => 2],
                ],
            ],
            [
                'name' => 'さつま芋の天ぷら（輪切り1枚）',
                'dish_category' => DishCategory::Side,
                'calorie' => 127,
                'ingredients' => [
                    ['name' => 'さつまいも', 'required_amount' => 25],
                    ['name' => '卵', 'required_amount' => 4],
                    ['name' => '薄力粉', 'required_amount' => 4],
                    ['name' => '食塩', 'required_amount' => 0.1],
                    ['name' => '調合油', 'required_amount' => 8],
                ],
            ],
            [
                'name' => '豆腐とわかめのみそ汁',
                'dish_category' => DishCategory::Soup,
                'calorie' => 32,
                'ingredients' => [
                    ['name' => '絹ごし豆腐', 'required_amount' => 20],
                    ['name' => 'カットわかめ', 'required_amount' => 1],
                    ['name' => '小ねぎ', 'required_amount' => 3],
                    ['name' => '煮干しだし', 'required_amount' => 150],
                    ['name' => '米みそ', 'required_amount' => 9],
                ],
            ],
            [
                'name' => 'もやしと油揚げのみそ汁',
                'dish_category' => DishCategory::Soup,
                'calorie' => 53,
                'ingredients' => [
                    ['name' => 'もやし', 'required_amount' => 40],
                    ['name' => '油揚げ', 'required_amount' => 7],
                    ['name' => '小ねぎ', 'required_amount' => 3],
                    ['name' => '煮干しだし', 'required_amount' => 150],
                    ['name' => '米みそ', 'required_amount' => 9],
                ],
            ],
            [
                'name' => '大根と油揚げのみそ汁',
                'dish_category' => DishCategory::Soup,
                'calorie' => 56,
                'ingredients' => [
                    ['name' => '大根', 'required_amount' => 50],
                    ['name' => '油揚げ', 'required_amount' => 7],
                    ['name' => '小ねぎ', 'required_amount' => 3],
                    ['name' => '煮干しだし', 'required_amount' => 150],
                    ['name' => '米みそ', 'required_amount' => 9],
                ],
            ],
            [
                'name' => 'コーンスープ',
                'dish_category' => DishCategory::Soup,
                'calorie' => 109,
                'ingredients' => [
                    ['name' => 'コーン缶（クリーム）', 'required_amount' => 40],
                    ['name' => '玉ねぎ', 'required_amount' => 15],
                    ['name' => '牛乳', 'required_amount' => 100],
                    ['name' => '固形コンソメ', 'required_amount' => 1],
                    ['name' => '食塩', 'required_amount' => 0.3],
                    ['name' => '白こしょう', 'required_amount' => 0.01],
                    ['name' => 'パセリ', 'required_amount' => 2],
                ],
            ],
            [
                'name' => 'ごはん（中茶碗1杯）',
                'dish_category' => DishCategory::Staple,
                'calorie' => 252,
                'ingredients' => [
                    ['name' => 'ごはん（炊飯済み）', 'required_amount' => 150],
                ],
            ],
            [
                'name' => '麦ごはん',
                'dish_category' => DishCategory::Staple,
                'calorie' => 248,
                'ingredients' => [
                    ['name' => '米', 'required_amount' => 60],
                    ['name' => '押麦', 'required_amount' => 10],
                ],
            ],
            [
                'name' => '卵野菜サンドイッチ',
                'dish_category' => DishCategory::Staple,
                'calorie' => 260,
                'ingredients' => [
                    ['name' => '食パン', 'required_amount' => 36],
                    ['name' => 'マーガリン', 'required_amount' => 6],
                    ['name' => '卵', 'required_amount' => 25],
                    ['name' => 'マヨネーズ', 'required_amount' => 10],
                    ['name' => '食塩', 'required_amount' => 0.2],
                    ['name' => 'こしょう（混合）', 'required_amount' => 0.01],
                    ['name' => 'きゅうり', 'required_amount' => 30],
                    ['name' => 'レタス', 'required_amount' => 10],
                    ['name' => 'トマト', 'required_amount' => 30],
                ],
            ],
            [
                'name' => 'かけうどん',
                'dish_category' => DishCategory::Staple,
                'calorie' => 325,
                'ingredients' => [
                    ['name' => 'ゆでうどん', 'required_amount' => 230],
                    ['name' => 'かつお昆布だし', 'required_amount' => 300],
                    ['name' => 'こいくちしょうゆ', 'required_amount' => 24],
                    ['name' => '本みりん', 'required_amount' => 24],
                    ['name' => '長ねぎ', 'required_amount' => 10],
                ],
            ],
            [
                'name' => 'わかめうどん',
                'dish_category' => DishCategory::Staple,
                'calorie' => 327,
                'ingredients' => [
                    ['name' => 'ゆでうどん', 'required_amount' => 230],
                    ['name' => 'かつお昆布だし', 'required_amount' => 300],
                    ['name' => 'こいくちしょうゆ', 'required_amount' => 24],
                    ['name' => '本みりん', 'required_amount' => 24],
                    ['name' => '長ねぎ', 'required_amount' => 10],
                    ['name' => '生わかめ', 'required_amount' => 20],
                ],
            ],
            [
                'name' => 'バナナ（M1本）',
                'dish_category' => DishCategory::Other,
                'calorie' => 101,
                'ingredients' => [
                    ['name' => 'バナナ', 'required_amount' => 117],
                ],
            ],
            [
                'name' => 'みかん（M1個）',
                'dish_category' => DishCategory::Other,
                'calorie' => 40,
                'ingredients' => [
                    ['name' => 'みかん', 'required_amount' => 88],
                ],
            ],
            [
                'name' => '牛乳（コップ1杯）',
                'dish_category' => DishCategory::Other,
                'calorie' => 101,
                'ingredients' => [
                    ['name' => '牛乳', 'required_amount' => 150],
                ],
            ],
            [
                'name' => 'ヨーグルト（プレーン）（ミニカップ1個）',
                'dish_category' => DishCategory::Other,
                'calorie' => 43,
                'ingredients' => [
                    ['name' => 'ヨーグルト（プレーン）', 'required_amount' => 70],
                ],
            ],
        ];

        foreach ($menusData as $menuData) {
            $menu = Menu::create([
                'name' => $menuData['name'],
                'dish_category' => $menuData['dish_category'],
                'calorie' => $menuData['calorie'],
            ]);

            $syncData = [];
            foreach ($menuData['ingredients'] as $ingredient) {
                $syncData[$itemIds[$ingredient['name']]] = ['required_amount' => $ingredient['required_amount']];
            }
            $menu->items()->attach($syncData);
        }
    }
}
