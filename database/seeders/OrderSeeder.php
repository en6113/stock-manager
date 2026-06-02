<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Order;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = Item::all();

        foreach ($items as $item) {
            Order::create([
                'item_id' => $item->id,
                'status' => 'pending',
                'ordered_qty' => 0,
                'ordered_date' => null,
                'vendor_id' => $item->vendor_id,
                'received_date' => null,
                'expiration_date' => null,
                'lot_number' => null,
            ]);
        }
    }
}
