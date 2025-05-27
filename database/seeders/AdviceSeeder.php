<?php

namespace Database\Seeders;

use App\Models\Advice;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

use Illuminate\Support\Facades\DB;

class AdviceSeeder extends Seeder
{
    public function run(): void
     {
        $faker = Faker::create();

        $teacherIds = Teacher::pluck('id')->toArray();
        $subjectIds = Subject::pluck('id')->toArray();

        for ($i = 0; $i < 20; $i++) {
            Advice::create([
                'content' => $faker->sentence(12),
                'teacher_id' => $faker->randomElement($teacherIds),
                'subject_id' => $faker->randomElement($subjectIds),
            ]);
        }
    //     DB::table('advice')->insert([
    //         'content' => 'very easy subject kkkkkkkkkkk',
    //         'teacher_id' => 1,
    //         'subject_id' => 1,
    //     ]);
    
}
}
