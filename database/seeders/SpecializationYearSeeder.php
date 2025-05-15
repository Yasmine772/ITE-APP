<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Year;
use App\Models\Specialization;
class SpecializationYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    $year4 = Year::where('name', 'Fourth Year')->first();
    $year5 = Year::where('name', 'Fifth Year')->first();

    $specializations = Specialization::all();

    foreach ($specializations as $spec) {
        $spec->years()->attach([$year4->id, $year5->id]);
    }
    }
}
