<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker;
use Illuminate\Support\Facades\DB;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $users = DB::table('user_profiles')
            ->select(DB::raw('user_id'))
            ->where('role', 'Teacher')
            ->get();

        foreach ($users as $user) {
            Subject::create([
                'user_id' => $user->user_id,
                'subject_1' => $faker->name(),
                'subject_2' => $faker->name(),
                'subject_3' => $faker->name(),
                'subject_4' => $faker->name(),
                'subject_5' => $faker->name(),
                'subject_6' => $faker->name(),
            ]);
        }
    }
}
