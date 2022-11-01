<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Address;
use Faker;
use Illuminate\Support\Facades\DB;

class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [

            'user_id' => 'overridden',
            'address_1' => $this->faker->streetAddress(),
            'address_2' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'state' => $this->faker->city(),
            'country' => $this->faker->country(),
            'pin_code' => $this->faker->postcode(),

        ];
    }
}
