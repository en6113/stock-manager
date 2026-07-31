<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => '食材'.fake()->unique()->numberBetween(1, 9999),
            'item_category' => ItemCategory::factory(),
            'target_stock_qty' => fake()->numberBetween(0, 3000),
            'unit' => fake()->randomElement(['g', 'kg', 'ml', 'L']),
            'capacity' => fake()->numberBetween(100, 500).'g/個',
            'storage_location' => fake()->randomElement(['常温', '冷蔵', '冷凍']),
            'vendor_id' => Vendor::factory(),
        ];
    }
}
