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
            ]
        ];

        foreach($menus as $menu) {
            Menu::create($menu);
        }
    }
}
