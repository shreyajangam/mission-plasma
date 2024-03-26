<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class StatesTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        foreach (range(1, 10) as $index) {
            DB::table('states')->insert([
                'name' => $faker->state,
                'country_id' => $faker->numberBetween(1, 10), // Assuming 10 countries in the database
            ]);
        }
    }
}
