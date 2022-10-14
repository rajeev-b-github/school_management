<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker;
use Illuminate\Support\Facades\DB;
use App\Models\User_profile;
use App\Models\User;
use App\Models\Student_profile;
use App\Models\Teacher_profile;

class User_profileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faker = Faker\Factory::create();

        for ($i = 0; $i <= 10; $i++) {
            User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->email,
                'password' => bcrypt('123456'),
                'user_type' => $faker->randomElement(['Teacher', 'Student']),
                'is_approved' => false,
            ]);
        }

        $students = User::select('id')
            ->where('user_type', 'Student')
            ->get();

        foreach ($students as $user) {
            Student_profile::create([
                'user_id' => $user->id,
                'profile_picture' => $faker->imageUrl(
                    $width = 200,
                    $height = 200
                ),
                'current_school' => $faker->company(),
                'previous_school' => $faker->company(),
                'assigned_teacher' => $faker->name(),
            ]);
        }

        $teachers = User::select('id')
            ->where('user_type', 'Teacher')
            ->get();

        foreach ($teachers as $user) {
            Teacher_profile::create([
                'user_id' => $user->id,
                'profile_picture' => $faker->imageUrl(
                    $width = 200,
                    $height = 200
                ),
                'current_school' => $faker->company(),
                'previous_school' => $faker->company(),
                'teacher_experience' => $faker->randomDigit(),
            ]);
        }
    }
}
