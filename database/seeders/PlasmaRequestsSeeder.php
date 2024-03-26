<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PlasmaRequest;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class PlasmaRequestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        foreach (range(1, 10) as $index) {
            DB::table('plasma_requests')->insert([
                'name' => $faker->name,
                'gender' => $faker->randomElement(['male', 'female']),
                'age' => $faker->numberBetween(18, 60),
                'blood_group' => $faker->randomElement(['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-']),
                'covid_positive_date' => $faker->date(),
                'covid_negative_date' => $faker->date(),
                'country_id' => $faker->numberBetween(1, 10), // Assuming 10 countries in the database
                'state_id' => $faker->numberBetween(1, 10), // Assuming 10 states in the database
                'city_id' => $faker->numberBetween(1, 10), // Assuming 10 cities in the database
                'phone_number' => $faker->phoneNumber,
            ]);
        }
    }
}
