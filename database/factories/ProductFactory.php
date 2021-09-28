<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' =>  ucwords($this->faker->words(3, true)),
            'price' => $this->faker->randomFloat(2, 0.99, 200),
            'image' => $this->faker->imageUrl(),
            'description' => $this->faker->paragraph()
        ];
    }
}
