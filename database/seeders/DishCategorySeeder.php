<?php

namespace Database\Seeders;

use App\Models\DishCategory;
use Illuminate\Database\Seeder;

class DishCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = [
            '主菜',
            '副菜',
            '汁物',
            'その他',
        ];
    
        foreach($names as $name) {
            DishCategory::create(['name' => $name]);
        }
    }
}
