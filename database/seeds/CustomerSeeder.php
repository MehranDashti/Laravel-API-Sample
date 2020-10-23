<?php

use App\Customer;
use Faker\Factory;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Customer::truncate();

        $faker = Factory::create();
        for ($i = 1; $i <= 100; $i++) {
            Customer::create([
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'phone' => '989' . $faker->randomNumber(9, true),
            ]);
        }
    }
}
