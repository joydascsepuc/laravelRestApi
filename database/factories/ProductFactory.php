<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'details' => $this->faker->paragraph(),
            'price' => $this->faker->numberBetween(100, 1000),
            'stock' => $this->faker->randomDigit(),
            'discount' => $this->faker->numberBetween(10, 30),
        ];
    }
}
