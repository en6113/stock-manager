<?php

namespace Database\Factories;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Vendor>
 */
class VendorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'address' => fake()->address(),
            'email' => fake()->email(),
            'phone_number' => fake()->phoneNumber(),
            'contact_person' => fake()->name(),
            'product_category' => fake()->randomElement(['精肉店', '精魚店', '八百屋', '総合卸業者']),
        ];
    }
}
