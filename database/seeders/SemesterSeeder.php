<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Semester;
class SemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $semesters = ['First Semester', 'Second Semester'];

        foreach ($semesters as $semester) {
            Semester::create(['name' => $semester]);
        }
    }
}
