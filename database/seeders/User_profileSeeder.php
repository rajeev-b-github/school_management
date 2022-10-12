<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker;
use Illuminate\Support\Facades\DB;
use App\Models\User_profile;

class User_profileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(10)->create();

        $faker = Faker\Factory::create();
        $users = DB::table('users')
            ->select(DB::raw('id'))
            ->where('user_type', null)
            ->get();

        foreach ($users as $user) {
            User_profile::create([
                'user_id' => $user->id,
                'profile_picture' => $faker->imageUrl(
                    $width = 200,
                    $height = 200
                ),
                'role' => $faker->randomElement(['Teacher', 'Student']),
                'current_school' => $faker->company(),
                'previous_school' => $faker->company(),
                'assigned_teacher' => $faker->name(),
                'teacher_experience' => $faker->randomDigit(),
                'is_approved' => false,
            ]);
        }
    }
}
