<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Specialization;
class SpecializationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $specializations = ['Artificial Intelligence', 'Software Engineering', 'Computer Networks'];

        foreach ($specializations as $spec) {
            Specialization::create(['name' => $spec]);
        }
    }
}
