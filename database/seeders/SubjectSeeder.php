<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Year;
use App\Models\Specialization;
use App\Models\User;
use App\Models\Teacher;

class SubjectSeeder extends Seeder
{
   
public function run()
{
    $specializations = Specialization::all();
    $years = Year::all();
    $teachers = Teacher::has('user')->get();

    $subjects = [
        ['name' => 'الرياضيات 1', 'type' => 'theoretical', 'specializations' => ['Artificial Intelligence', 'Software Engineering'], 'semester_id' => 1],
        ['name' => 'الرياضيات 2', 'type' => 'theoretical', 'specializations' => ['Artificial Intelligence', 'Software Engineering'], 'semester_id' => 1],
        ['name' => 'البرمجة 1', 'type' => 'practical', 'specializations' => ['Artificial Intelligence'], 'semester_id' => 1],
        ['name' => 'البرمجة 2', 'type' => 'practical', 'specializations' => ['Software Engineering'], 'semester_id' => 2],
        ['name' => 'أنظمة التشغيل', 'type' => 'theoretical', 'specializations' => ['Computer Networks'], 'semester_id' => 1],
        ['name' => 'هياكل البيانات', 'type' => 'theoretical', 'specializations' => ['Software Engineering'], 'semester_id' => 2],
        ['name' => 'قواعد البيانات', 'type' => 'theoretical', 'specializations' => ['Artificial Intelligence', 'Software Engineering'], 'semester_id' => 2],
        ['name' => 'الشبكات الحاسوبية', 'type' => 'theoretical', 'specializations' => ['Computer Networks'], 'semester_id' => 1],
        ['name' => 'الذكاء الاصطناعي', 'type' => 'theoretical', 'specializations' => ['Artificial Intelligence'], 'semester_id' => 1],
        ['name' => 'مشروع التخرج', 'type' => 'project', 'specializations' => ['Artificial Intelligence', 'Software Engineering'], 'semester_id' => 2],
    ];

    foreach ($subjects as $subject) {
        foreach ($subject['specializations'] as $specName) {
            $specialization = $specializations->where('name', $specName)->first();

            if ($specialization) {
                DB::table('subjects')->insert([
                    'name' => $subject['name'],
                    'type' => $subject['type'],
                    'year_id' => $years->random()->id,
                    'specialization_id' => $specialization->id,
                    'semester_id' => $subject['semester_id'],
                    'teacher_id' => $teachers->isNotEmpty() ? $teachers->random()->id : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}

}