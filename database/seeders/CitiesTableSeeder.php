<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CitiesTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        foreach (range(1, 10) as $index) {
            DB::table('cities')->insert([
                'name' => $faker->city,
                'state_id' => $faker->numberBetween(1, 10), // Assuming 10 states in the database
            ]);
        }
    }
}
