<?php

namespace Database\Seeders;

use App\Models\Allergen;
use Illuminate\Database\Seeder;

class AllergenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = [
            '卵',
            '乳',
            '小麦',
            '大豆',
        ];

        foreach ($names as $name) {
            Allergen::create(['name' => $name]);
        }
    }
}
