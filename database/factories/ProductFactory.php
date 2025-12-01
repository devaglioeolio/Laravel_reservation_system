<?php

namespace Database\Factories;

use App\Models\Facility;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        return [
            'facility_id' => Facility::factory(),
            'product_name' => $this->faker->word(),
            'type' => $this->faker->randomElement(['room', 'ticket', 'pass']),
            'description' => $this->faker->sentence(),
            'status' => $this->faker->boolean(),
        ];
    }
}
