<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Faker\Factory as Faker;
use App\Models\Teacher;

class TeacherSeeder extends Seeder
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
            $user = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'role' => 'teacher', 
                'password' => bcrypt('password'),
                'address' => $faker->address,
                'gender' => $faker->randomElement(['male', 'female']),
                'birth_date' => $faker->date(),
                'bio' => $faker->text,
                'profile_photo_path' => $faker->imageUrl(),
            ]);

            Teacher::create([
                'user_id' => $user->id,
                'academic_qualification' => $faker->word,
                'years_of_experience' => $faker->numberBetween(1, 30),
                'university_degree' => $faker->word,
            ]);
        }
    }
}
