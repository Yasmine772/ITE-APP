<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Faker\Factory as Faker;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => bcrypt('password'),
                'address' => $faker->address,
                'gender' => $faker->randomElement(['male', 'female']),
                'birth_date' => $faker->date(),
                'bio' => $faker->text,
                'profile_photo_path' => $faker->imageUrl(),
            ]);
        }
    }
}
