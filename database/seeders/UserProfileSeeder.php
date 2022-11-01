<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker;
use App\Models\User;
use App\Models\StudentProfile;
use App\Models\TeacherProfile;

class UserProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        User::factory()->count(10)->create();

        $students = User::select('id')
            ->where('user_type', 'Student')
            ->get();

        foreach ($students as $user) {
            StudentProfile::factory()->create(['user_id' => $user->id]);
        }

        $teachers = User::select('id')
            ->where('user_type', 'Teacher')
            ->get();

        foreach ($teachers as $user) {
            TeacherProfile::factory()->create(['user_id' => $user->id]);
        }
    }
}
