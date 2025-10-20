<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = fake('pt_BR'); // usa dados no formato brasileiro

        return [
            'user_id' => User::factory(),
            'zipcode' => preg_replace('/[^0-9]/', '', $faker->postcode()), // remove o hífen
            'street' => $faker->streetName(),
            'number' => $faker->buildingNumber(),
            'city' => $faker->city(),
            'state' => $faker->stateAbbr(), // exemplo: SP, RJ, MG
            'country' => 'Brasil',
            'complement' => $faker->optional()->secondaryAddress(),
        ];
    }
}
