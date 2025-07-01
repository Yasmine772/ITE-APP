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

        $exams = Exam::all();
        $questionsPerExam = 5;

        foreach ($exams as $exam) {
            for ($i = 0; $i < $questionsPerExam; $i++) {
                Question::create([
                    'question_text' => $faker->sentence(rand(5, 15), true), 
                    'photo' => $faker->imageUrl(640, 480, 'questions', true), 
                    'mark' => $faker->numberBetween(5, 20), 
                    'exam_id' => $exam->id, 
                ]);
            }
        }
    }
}
