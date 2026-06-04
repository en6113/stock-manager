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
                'category_id' => 1, //1:main
            ],
            [
                'name' => 'ツナマヨコーンサラダ',
                'category_id' => 2, //2:side
            ]
        ];

        foreach($menus as $menu) {
            Menu::create($menu);
        }
    }
}
