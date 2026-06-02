<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Stock;
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = Item::all();

        foreach ($items as $item) {
            $totalOrderAmount = $item->orders()->sum('ordered_qty');

            Stock::create([
                'item_id' => $item->id,
                'stock' => $totalOrderAmount,
            ]);
        }
    }
}
