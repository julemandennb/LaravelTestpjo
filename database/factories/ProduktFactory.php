<?php

namespace Database\Factories;
use App\Models\Produkt;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produkt>
 */
class ProduktFactory extends Factory
{
    protected $model = Produkt::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'prise' => $this->faker->randomFloat(2, 10, 1000), // random float between 10 and 1000
            'descript' => $this->faker->sentence(),
        ];
    }
}
