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

        $questions = Question::all();

        foreach ($questions as $question) {
            $correctOptionAdded = false;

            for ($i = 0; $i < 3; $i++) {
                $isCorrect = false;

                if (!$correctOptionAdded) {
                    $isCorrect = true;
                    $correctOptionAdded = true; 
                }

                Option::create([
                    'answer_text' => $faker->sentence(rand(3, 8)),
                    'is_correct' => $isCorrect, 
                    'question_id' => $question->id,
                ]);
            }
        }
    }
}
