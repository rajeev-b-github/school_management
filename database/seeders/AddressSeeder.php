<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Address;
use Faker;
use Illuminate\Support\Facades\DB;

class AddressSeeder extends Seeder
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
            ->where('User_type', '!=', 'admin')
            ->get();


        foreach ($users as $user) {
            Address::factory()->create(['user_id' => $user->id]);
        }
    }
}
