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
        // $users = DB::table('user_profiles')
        //     ->select(DB::raw('user_id'))
        //     ->where('role', 'Teacher')
        //     ->get();
        $users = DB::table('users')
            ->select(DB::raw('id'))
            ->where('user_type', 'Teacher')
            ->get();

        foreach ($users as $user) {
            Subject::create([
                'user_id' => $user->id,
                'subject_1' => $faker->word(),
                'subject_2' => $faker->word(),
                'subject_3' => $faker->word(),
                'subject_4' => $faker->word(),
                'subject_5' => $faker->word(),
                'subject_6' => $faker->word(),
            ]);
        }
    }
}
