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
        ];

        foreach ($menus as $menu) {
            Menu::create($menu);
        }
    }
}
