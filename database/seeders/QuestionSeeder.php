<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\Question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
    //     $faker = Faker::create();

    //     $exams = Exam::all();
    //     $questionsPerExam = 5;

    //     foreach ($exams as $exam) {
    //         for ($i = 0; $i < $questionsPerExam; $i++) {
    //             Question::create([
    //                 'question_text' => $faker->sentence(rand(5, 15), true), 
    //                 'photo' => $faker->imageUrl(640, 480, 'questions', true), 
    //                 'mark' => $faker->numberBetween(5, 20), 
    //                 'exam_id' => $exam->id, 
    //             ]);
    //         }
    //     }

        $QuestionData = [
            [
                'question_text' => 'نريد تخزين جميع أسماء المرضى، ما هي أنسب بنية للبيانات للقيام بذلك مما يلي؟',
                'photo' => '',
                'mark' => 10,
                'exam_id' => 1,
            ],
            [
                'question_text' => 'نريد تخزين نتائج جميع تحاليل المرضى، ما هي أنسب بنية للبيانات للقيام بذلك مما يلي؟',
                'photo' => '',
                'mark' => 10,
                'exam_id' => 1,
            ],
            [
                'question_text' => 'نريد تخزين أسماء المرضى المقيمين في كل غرفة من غرف المشفى، علماً بأن المشفى يسمح بوجود 3 مرضى على الأكثر في كل غرفة، ما هي أنسب بنية للبيانات للقيام بذلك مما يلي؟',
                'photo' => '',
                'mark' => 10,
                'exam_id' => 1,
            ],
            [
                'question_text' => 'نريد تخزين رقم الهاتف الأرضي ورقم الموبايل لكل مريض، ما هي أنسب بنية للبيانات للقيام بذلك مما يلي؟',
                'photo' => '',
                'mark' => 10,
                'exam_id' => 1,
            ],
            [
                'question_text' => 'لا تسمح لغة C تغيير حجم المصفوفة بعد تعريفها',
                'photo' => '',
                'mark' => 10,
                'exam_id' => 1,
            ],
            [
                'question_text' => 'يجب أن تتمتع الخوارزمية الحاسوبية بكل ما يلي عدا:',
                'photo' => '',
                'mark' => 10,
                'exam_id' => 1,
            ],
            [
                'question_text' => 'لمقارنة أداء برنامجين حاسوبيين مكتوبين بلغة C، فإننا نلجأ إلى المقارنة على:',
                'photo' => '',
                'mark' => 10,
                'exam_id' => 1,
            ],
            [
                'question_text' => 'ما الفرق بين الخوارزمية ومجموعة التعليمات التي تصف البرنامج الحاسوبي؟',
                'photo' => '',
                'mark' => 10,
                'exam_id' => 1,
            ],
            [
                'question_text' => 'ما العلاقة بين الخوارزمية والبرنامج المحقق (المنفذ) لها؟',
                'photo' => '',
                'mark' => 10,
                'exam_id' => 1,
            ],
            [
                'question_text' => 'يمكننا تعديل سياق تنفيذ الخوارزمية من خلال استخدام:',
                'photo' => '',
                'mark' => 10,
                'exam_id' => 1,
            ],

            
        ];
        foreach ($QuestionData as $Question) {
            DB::table('questions')->insert([
                'question_text' => $Question['question_text'],
                'photo' => $Question['photo'],
                'mark' => $Question['mark'],
                'exam_id' => $Question['exam_id'],
            ]);
        }
    }
}
