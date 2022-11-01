<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SubjectFactory extends Factory
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
            'subject_1' => $this->faker->word(),
            'subject_2' => $this->faker->word(),
            'subject_3' => $this->faker->word(),
            'subject_4' => $this->faker->word(),
            'subject_5' => $this->faker->word(),
            'subject_6' => $this->faker->word(),
        ];
    }
}
