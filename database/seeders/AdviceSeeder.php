<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdviceSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('advice')->insert([
            'content' => 'very easy subject kkkkkkkkkkk',
            'teacher_id' => 1,
            'subject_id' => 1,
        ]);
        DB::table('advice')->insert([
            'content' => 'very easy subject kkkkkkkkkkk',
            'teacher_id' => 2,
            'subject_id' => 2,
        ]);
        DB::table('advice')->insert([
            'content' => 'very easy subject kkkkkkkkkkk',
            'teacher_id' => 3,
            'subject_id' => 3,
        ]);
        DB::table('advice')->insert([
            'content' => 'very easy subject kkkkkkkkkkk',
            'teacher_id' => 4,
            'subject_id' => 4,
        ]);
        DB::table('advice')->insert([
            'content' => 'very easy subject kkkkkkkkkkk',
            'teacher_id' => 5,
            'subject_id' => 5,
        ]);
        DB::table('advice')->insert([
            'content' => 'very easy subject kkkkkkkkkkk',
            'teacher_id' => 6,
            'subject_id' => 6,
        ]);
        DB::table('advice')->insert([
            'content' => 'very easy subject kkkkkkkkkkk',
            'teacher_id' => 7,
            'subject_id' => 7,
        ]);
        DB::table('advice')->insert([
            'content' => 'very easy subject kkkkkkkkkkk',
            'teacher_id' => 8,
            'subject_id' => 8,
        ]);
        DB::table('advice')->insert([
            'content' => 'very easy subject kkkkkkkkkkk',
            'teacher_id' => 9,
            'subject_id' => 9,
        ]);
        DB::table('advice')->insert([
            'content' => 'very easy subject kkkkkkkkkkk',
            'teacher_id' => 10,
            'subject_id' => 10,
        ]);
    }
}
