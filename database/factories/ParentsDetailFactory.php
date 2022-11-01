<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ParentsDetailFactory extends Factory
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
            'father_name' => $this->faker->name(),
            'mother_name' => $this->faker->name(),
            'father_occupation' => $this->faker->name(),
            'mother_occupation' => $this->faker->name(),
            'father_contact_no' => $this->faker->phoneNumber(),
            'mother_contact_no' => $this->faker->phoneNumber(),
        ];
    }
}
