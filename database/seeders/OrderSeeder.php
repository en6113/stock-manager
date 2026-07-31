<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = [
            [
                'item_id' => 1,
                'ordered_qty' => 3000,
                'ordered_date' => now()->subDays(5),
                'vendor_id' => 3,
                'received_qty' => 3000,
                'received_date' => now()->subDays(3),
            ],
            [
                'item_id' => 2,
                'ordered_qty' => 3000,
                'ordered_date' => now()->subDays(5),
                'vendor_id' => 2,
                'received_qty' => 3000,
                'received_date' => now()->subDays(3),
            ],
            [
                'item_id' => 3,
                'ordered_qty' => 750,
                'ordered_date' => now()->subDays(5),
                'vendor_id' => 2,
                'received_qty' => 750,
                'received_date' => now()->subDays(3),
            ],
            [
                'item_id' => 4,
                'ordered_qty' => 750,
                'ordered_date' => now()->subDays(5),
                'vendor_id' => 2,
                'received_qty' => 750,
                'received_date' => now()->subDays(3),
            ],
            [
                'item_id' => 5,
                'ordered_qty' => 5,
                'ordered_date' => now()->subDays(5),
                'vendor_id' => 1,
                'received_qty' => 5,
                'received_date' => now()->subDays(3),
            ],
            [
                'item_id' => 6,
                'ordered_qty' => 1000,
                'ordered_date' => now()->subDays(5),
                'vendor_id' => 1,
                'received_qty' => 1000,
                'received_date' => now()->subDays(3),
            ],
            [
                'item_id' => 7,
                'ordered_qty' => 3500,
                'ordered_date' => now()->subDays(5),
                'vendor_id' => 2,
                'received_qty' => 3500,
                'received_date' => now()->subDays(3),
            ],
            [
                'item_id' => 8,
                'ordered_qty' => 550,
                'ordered_date' => now()->subDays(5),
                'vendor_id' => 1,
                'received_qty' => 550,
                'received_date' => now()->subDays(3),
            ],
            [
                'item_id' => 9,
                'ordered_qty' => 600,
                'ordered_date' => now()->subDays(5),
                'vendor_id' => 1,
                'received_qty' => 600,
                'received_date' => now()->subDays(3),
            ],
            [
                'item_id' => 10,
                'ordered_qty' => 1000,
                'ordered_date' => now()->subDays(5),
                'vendor_id' => 1,
                'received_qty' => 1000,
                'received_date' => now()->subDays(3),
            ],
            [
                'item_id' => 11,
                'ordered_qty' => 1000,
                'ordered_date' => now()->subDays(5),
                'vendor_id' => 1,
                'received_qty' => 1000,
                'received_date' => now()->subDays(3),
            ],
        ];

        foreach ($orders as $order) {
            Order::create($order);
        }
    }
}
