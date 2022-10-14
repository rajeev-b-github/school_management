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
        $faker = Faker\Factory::create();
        $users = DB::table('users')
            ->select(DB::raw('id'))
            ->where('User_type', '!=', 'admin')
            ->get();


        foreach ($users as $user) {
            Address::create([
                'user_id' => $user->id,
                'address_1' => $faker->streetAddress(),
                'address_2' => $faker->streetAddress(),
                'city' => $faker->city(),
                'state' => $faker->city(),
                'country' => $faker->country(),
                'pin_code' => $faker->postcode(),
            ]);
        }
    }
}
