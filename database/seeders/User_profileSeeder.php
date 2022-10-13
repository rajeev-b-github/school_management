<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker;
use Illuminate\Support\Facades\DB;
use App\Models\User_profile;
use App\Models\User;

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
            ]);
        }

        $users = DB::table('users')
            ->select(DB::raw('id'))
            ->where('user_type', '!=', 'admin')
            ->get();

        foreach ($users as $user) {
            User_profile::create([
                'user_id' => $user->id,
                'profile_picture' => $faker->imageUrl(
                    $width = 200,
                    $height = 200
                ),
                'current_school' => $faker->company(),
                'previous_school' => $faker->company(),
                'assigned_teacher' => $faker->name(),
                'teacher_experience' => $faker->randomDigit(),
                'is_approved' => false,
            ]);
        }
    }
}
