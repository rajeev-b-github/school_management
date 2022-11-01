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

        $users = DB::table('users')
            ->select(DB::raw('id'))
            ->where('user_type', 'Teacher')
            ->get();

        foreach ($users as $user) {
            Subject::factory()->create(['user_id' => $user->id]);
        }
    }
}
