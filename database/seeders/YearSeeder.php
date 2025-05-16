<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Year;

class YearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          $years = ['First Year', 'Second Year', 'Third Year', 'Fourth Year', 'Fifth Year'];

        foreach ($years as $year) {
            Year::create(['name' => $year]);
        }
    }
}
