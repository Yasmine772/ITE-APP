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
<<<<<<< HEAD
=======
        // User::factory(10)->withPersonalTeam()->create();
>>>>>>> a74942e7baa9c99995047ffcc5334ae48c910eff

        User::factory()->withPersonalTeam()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
       $this->call([
        RolesPermissionsSeeder::class,
        YearSeeder::class,
        SemesterSeeder::class,
        SpecializationSeeder::class,
        SpecializationYearSeeder::class,
       ]);

          $this->call([
          ]);


    }
}
