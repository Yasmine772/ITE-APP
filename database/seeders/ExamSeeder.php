<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseContent;
use App\Models\Exam;
use App\Models\Subject;
use App\Models\User;
use Faker\Factory as Faker;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $userIds = User::pluck('id')->toArray();
        $subjectIds = Subject::pluck('id')->toArray();
        $courseIds = Course::pluck('id')->toArray();
        $course_contentIds = CourseContent::pluck('id')->toArray();


        for ($i = 0; $i < 20; $i++) {
            Exam::create([
                'title' => $faker->title,
                'description' =>  $faker->text,
                'duration' =>  $faker->numberBetween(30,90),
                'user_id' => $faker->randomElement($userIds),
                'subject_id' => $faker->randomElement($subjectIds),
                'course_id' => $faker->randomElement($courseIds),
                'course_content_id' => $faker->randomElement($course_contentIds)
            ]);
        }
    }
}
