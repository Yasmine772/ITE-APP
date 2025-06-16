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

        if (!$year4 || !$year5) {
            $this->command->error('Fourth or Fifth Year not found. Run YearSeeder first.');
            return;
        }

        $specializations = Specialization::all();

        foreach ($specializations as $spec) {
            $spec->years()->syncWithoutDetaching([$year4->id, $year5->id]);
        }

        $this->command->info('Specializations assigned to 4th and 5th years successfully.');
    }
    
}
