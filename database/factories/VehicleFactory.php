<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->words(2, true),
            'description' => $this->faker->sentence(10),
            'category' => 'personenauto',
            'price_per_day' => $this->faker->numberBetween(50, 150),
            'region' => 'Rotterdam',
            'transmission' => 'automaat',
            'image' => null,
        ];
    }
}
