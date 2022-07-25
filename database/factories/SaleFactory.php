<?php

namespace Database\Factories;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sale>
 */
class SaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Sale::class;

    public function definition()
    {
        return [
            'user_id' => 1, 'store_id' => 1, 'product_id' => 1,
            'customer_id' => $this->faker->numberBetween(1, 100),
            'quantity' => 1/* $this->faker->boolean(95) ? 1 : 2 */,
            'nominal' => ($this->faker->boolean(60) ? ($this->faker->boolean(25) ? 10000 : 12000) : 15000),
            'imei_filename' => $this->faker->text(),
            'photo_filename' => $this->faker->text(),
            'created_at' => $this->faker->dateTimeBetween('-30 days', '+30 days'),
        ];
    }
}
