<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\Question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;


class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $examIds = Exam::pluck('id')->toArray();

        for ($i = 0; $i < 20; $i++) {
            Question::create([
                'question_text' => $faker->text,
                'photo' =>  $faker->imageUrl(),
                'mark' =>  $faker->numberBetween(0,100),
                'exam_id' => $faker->randomElement($examIds),
            ]);
        }
    }
}
