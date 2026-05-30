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
                'dish_category' => 1, //1:main
            ],
            [
                'name' => 'ツナマヨコーンサラダ',
                'dish_category' => 2, //2:side
            ]
        ];

        foreach($menus as $menu) {
            Menu::create($menu);
        }
    }
}
