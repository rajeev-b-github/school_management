<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker;
use Illuminate\Support\Facades\DB;
use App\Models\ParentsDetail;

class ParentsDetailSeeder extends Seeder
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
            ->where('user_type', 'Student')
            ->get();

        foreach ($users as $user) {
            ParentsDetail::factory()->create(['user_id' => $user->id]);
        }
    }
}
