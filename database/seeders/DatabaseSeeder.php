<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {


       $this->call([
        PermissionsSeeder::class ,
        RolesSeeder::class,
        AdminAndTeacherSeeder::class ,
        YearSeeder::class,
        SemesterSeeder::class,
        SpecializationSeeder::class,
        SpecializationYearSeeder::class,
        // StudentSeeder::class,
        // TeacherSeeder::class,
        // SubjectSeeder::class,
         CategorySeeder::class,
        // AdviceSeeder::class,
        StudentSeeder::class,
        //TeacherSeeder::class,
        SubjectSeeder::class,
        CategorySeeder::class,
        AdviceSeeder::class,
        ExamSeeder::class,
        QuestionSeeder::class,
        OptionSeeder::class,

        ]);

          $this->call([
          ]);


    }
}
