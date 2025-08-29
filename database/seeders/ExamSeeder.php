<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $faker = Faker::create();

        // $userIds = User::pluck('id')->toArray();
        // $subjectIds = Subject::pluck('id')->toArray();
        // $courseIds = Course::pluck('id')->toArray();
        // $course_contentIds = CourseContent::pluck('id')->toArray();


        // for ($i = 0; $i < 20; $i++) {
        //     Exam::create([
        //         'title' => $faker->title,
        //         'description' =>  $faker->text,
        //         'duration' =>  $faker->numberBetween(30,90),
        //         'user_id' => $faker->randomElement($userIds),
        //         'subject_id' => $faker->randomElement($subjectIds),
        //         'course_id' => $faker->randomElement($courseIds),
        //         'course_content_id' => $faker->randomElement($course_contentIds)
        //     ]);
        // }

        $ExamData = [
            [
                'title' => '1 امتحان البرمجة',
                'description' => 'اختبار بلغة البرمجة c++' ,
                'duration' => 180 ,
                'user_id' =>   2 ,
                'subject_id' => 5  
            ],
        ];
        foreach ($ExamData as $exam) {
            DB::table('exams')->insert([
                'title' => $exam['title'],
                'description' => $exam['description'],
                'duration' => $exam['duration'],
                'user_id' => $exam['user_id'],
                'subject_id' => $exam['subject_id'],
            ]);
        }
          
      
    }
}
