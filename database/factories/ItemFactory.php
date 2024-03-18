<?php

namespace Database\Factories;

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    protected $faker;

    public function __construct()
    {
        $this->faker = new Faker();
    }
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->sentence(20),
            'height' => $this->faker->numberBetween(3, 200),
            'weight' => $this->faker->numberBetween(6, 100),
            'print_price' => $this->faker->numberBetween(0, 10000000),
            'sheet_price' => $this->faker->numberBetween(0, 10000000),
            'cover_print_price' => $this->faker->numberBetween(0, 10000000),
        ];
    }
}
