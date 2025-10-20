<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = ['pending', 'paid', 'expired', 'cancelled'];
        $faker = fake('pt_BR'); 

        return [
            'user_id' => User::factory(),
            'status' => fake()->randomElement($status),
            'total' => $faker->randomFloat(2, 100, 2000),
            'shipping_cost' => $faker->randomFloat(2, 10, 100),
            'shipping_days' => $faker->numberBetween(1, 10),
            'shipping_zipcode' => preg_replace('/[^0-9]/', '', $faker->postcode()), 
            'shipping_street' => $faker->streetName(),
            'shipping_number' => $faker->buildingNumber(),
            'shipping_city' => $faker->city(),
            'shipping_state' => $faker->stateAbbr(), 
            'shipping_country' => 'Brasil',
            'shipping_complement' => $faker->optional()->secondaryAddress(),
        ];
    }
}
