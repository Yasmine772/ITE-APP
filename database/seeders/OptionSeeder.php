<?php

namespace Database\Seeders;

use App\Models\Option;
use App\Models\Question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;


class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $questionIds = Question::pluck('id')->toArray();

        for ($i = 0; $i < 20; $i++) {
            Option::create([
                'answer_text' => $faker->text,
                'is_correct' =>  $faker->boolean,
                'option_order' => $faker->numberBetween(1,3),
                'question_id' => $faker->randomElement($questionIds),
            ]);
        }
    }
}
