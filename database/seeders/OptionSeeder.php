<?php

namespace Database\Seeders;

use App\Models\Option;
use App\Models\Question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $faker = Faker::create();
        // $questions = Question::all();
        // foreach ($questions as $question) {
        //     $correctOptionAdded = false;

        //     for ($i = 0; $i < 3; $i++) {
        //         $isCorrect = false;

        //         if (!$correctOptionAdded) {
        //             $isCorrect = true;
        //             $correctOptionAdded = true; 
        //         }

        //         Option::create([
        //             'answer_text' => $faker->sentence(rand(3, 8)),
        //             'is_correct' => $isCorrect, 
        //             'question_id' => $question->id,
        //         ]);
        //     }
        // }

        $optiondata = [
            //1
            [
                'answer_text' => 'مصفوفة ثنائية البعد',
                'is_correct'  => 0,
                'question_id' => 1,
            ],
            [
                'answer_text' => 'مصفوفة أحادية البعد',
                'is_correct'  => 1,
                'question_id' => 1,
            ],
            [
                'answer_text' => 'متغير رقمي',
                'is_correct'  => 0,
                'question_id' => 1,
            ],
            //2
            [
                'answer_text' => 'مصفوفة ثنائية البعد',
                'is_correct'  => 0,
                'question_id' => 2,
            ],
            [
                'answer_text' => 'مصفوفة أحادية البعد',
                'is_correct'  => 1,
                'question_id' => 2,
            ],
            [
                'answer_text' => 'متغير رقمي',
                'is_correct'  => 0,
                'question_id' => 2,
            ],
            //3
            [
                'answer_text' => 'مصفوفتين أحاديتي البعد',
                'is_correct'  => 0,
                'question_id' => 3,
            ],
            [
                'answer_text' => 'مصفوفة ثنائية البعد 500x2',
                'is_correct'  => 0,
                'question_id' => 3,
            ],
            [
                'answer_text' => 'كل ما سبق مناسب',
                'is_correct'  => 1,
                'question_id' => 3,
            ], 
            //4
            [
                'answer_text' => 'مصفوفة ثنائية البعد',
                'is_correct'  => 1,
                'question_id' => 4,
            ],
            [
                'answer_text' => 'مصفوفة أحادية البعد',
                'is_correct'  => 0,
                'question_id' => 4,
            ],
            [
                'answer_text' => 'متغير رقمي',
                'is_correct'  => 0,
                'question_id' => 4,
            ], 
            //5
            [
                'answer_text' => 'صح',
                'is_correct'  => 1,
                'question_id' => 5,
            ],
            [
                'answer_text' => 'خطأ',
                'is_correct'  => 0,
                'question_id' => 5,
            ],
            // [
            //     'answer_text' => 'متغير رقمي',
            //     'is_correct'  => 0,
            //     'question_id' => 5,
            // ],
            //6
            [
                'answer_text' => 'خطوات منتهية',
                'is_correct'  => 0,
                'question_id' => 6,
            ],
            [
                'answer_text' => 'زمن تنفيذ منتهٍ',
                'is_correct'  => 0,
                'question_id' => 6,
            ],
            [
                'answer_text' => 'خطوات بسيطة',
                'is_correct'  => 1,
                'question_id' => 6,
            ], 
            //7
            [
                'answer_text' => 'زمن تنفيذ كل منهما فقط',
                'is_correct'  => 0,
                'question_id' => 7,
            ],
            [
                'answer_text' => 'الذاكرة اللازمة لتنفيذ كل منهما فقط',
                'is_correct'  => 0,
                'question_id' => 7,
            ],
            [
                'answer_text' => 'زمن التنفيذ والذاكرة اللازمة لتنفيذ كل منهما فقط',
                'is_correct'  => 1,
                'question_id' => 7,
            ],
             //8
            [
                'answer_text' => 'مجموعة تعليمات البرنامج الحاسوبي ترتبط بلغة البرمجة المستخدمة',
                'is_correct'  => 1,
                'question_id' => 8,
            ],
            [
                'answer_text' => 'الخوارزمية ترتبط بلغة البرمجة المستخدمة',
                'is_correct'  => 0,
                'question_id' => 8,
            ],
            [
                'answer_text' => 'مجموعة تعليمات البرنامج الحاسوبي ترتبط بالمواصفات المعتادة للحاسب للمستخدم',
                'is_correct'  => 0,
                'question_id' => 8,
            ], 
            //9
            [
                'answer_text' => 'عادة نقوم بكتابة برنامج حاسوبي ثم تحويله إلى خوارزمية',
                'is_correct'  => 0,
                'question_id' => 9,
            ],
            [
                'answer_text' => 'عادة نقوم بكتابة الخوارزمية ثم تحويلها إلى برنامج حاسوبي',
                'is_correct'  => 1,
                'question_id' => 9,
            ],
            [
                'answer_text' => 'عادة نقوم بكتابة البرنامج الحاسوبي بالتزامن مع كتابة الخوارزمية',
                'is_correct'  => 0,
                'question_id' => 9,
            ], 
            //10
            [
                'answer_text' => 'المدخل',
                'is_correct'  => 0,
                'question_id' => 10,
            ],
            [
                'answer_text' => 'الإسناد',
                'is_correct'  => 0,
                'question_id' => 10,
            ],
            [
                'answer_text' => 'التعليمة الشرطية',
                'is_correct'  => 1,
                'question_id' => 10,
            ],

        ];
        foreach ($optiondata as $option) {
            DB::table('options')->insert([
                'answer_text' => $option['answer_text'],
                'is_correct' => $option['is_correct'],
                'question_id' => $option['question_id'],
            ]);
        }



    }
}
