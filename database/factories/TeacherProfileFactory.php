<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TeacherProfileFactory extends Factory
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
            'profile_picture' => $this->faker->imageUrl(
                $width = 200,
                $height = 200
            ),
            'current_school' => $this->faker->company(),
            'previous_school' => $this->faker->company(),
            'teacher_experience' => $this->faker->randomDigit(),
        ];
    }
}
