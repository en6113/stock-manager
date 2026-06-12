<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            [
                'name' => 'カレー',
                'dish_category_id' => 1, //1:主菜(main)
            ],
            [
                'name' => 'ツナマヨコーンサラダ',
                'dish_category_id' => 2, //2:副菜(side)
            ],
            [
                'name' => '牛肉のしぐれ煮',
                'dish_category_id' => 1,
            ],
            [
                'name' => 'きゅうりとじゃこの酢の物',
                'dish_category_id' => 2,
            ],
            [
                'name' => '玉ねぎの味噌汁',
                'dish_category_id' => 3,
            ],
            [
                'name' => 'スパゲッティサラダ',
                'dish_category_id' => 1,
            ],
            [
                'name' => 'わかめのすまし汁',
                'dish_category_id' => 3,
            ],
            [
                'name' => 'マーボー豆腐',
                'dish_category_id' => 1,
            ],
            [
                'name' => 'トマトのナムル',
                'dish_category_id' => 2,
            ],
            [
                'name' => '型抜きチーズ',
                'dish_category_id' => 2,
            ],
            [
                'name' => 'ハワイアンチキン',
                'dish_category_id' => 1,
            ],
            [
                'name' => 'ツナサラダ',
                'dish_category_id' => 2,
            ],
            [
                'name' => '野菜スープ',
                'dish_category_id' => 3,
            ],
            [
                'name' => 'あじの南蛮漬け',
                'dish_category_id' => 1,
            ],
            [
                'name' => 'バナナ',
                'dish_category_id' => 2,
            ],
            [
                'name' => '豆腐の味噌汁',
                'dish_category_id' => 3,
            ],
        ];

        foreach($menus as $menu) {
            Menu::create($menu);
        }
    }
}
