<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
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
            Category::create(['name' => $name]);
        }
    }
}
