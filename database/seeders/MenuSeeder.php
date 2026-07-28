<?php

namespace Database\Seeders;

use App\Enums\DishCategory;
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
                'dish_category' => DishCategory::Main,
            ],
            [
                'name' => 'ツナマヨコーンサラダ',
                'dish_category' => DishCategory::Side,
            ],
            [
                'name' => '肉じゃが',
                'dish_category' => DishCategory::Main,
            ],
            [
                'name' => 'きゅうりとじゃこの酢の物',
                'dish_category' => DishCategory::Side,
            ],
            [
                'name' => '玉ねぎの味噌汁',
                'dish_category' => DishCategory::Soup,
            ],
        ];

        foreach($menus as $menu) {
            Menu::create($menu);
        }
    }
}
