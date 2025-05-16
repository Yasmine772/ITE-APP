<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesPermissionsSeeder extends Seeder
{
    public function run(): void
    {
<<<<<<< HEAD
        // إنشاء الأدوار
=======
        
>>>>>>> a74942e7baa9c99995047ffcc5334ae48c910eff
        $adminRole = Role::create(['name' => 'admin']);
        $teacherRole = Role::create(['name' => 'teacher']);
        $studentRole = Role::create(['name' => 'student']);


        $permissions = [
            'register','log in','log out','verify account','change password','view profile','update profile','view current courses',
            'track course progress','take exam','view exam result','write notes','course evaluation','writing an article',
            'view current path','view completed path','view completed courses','view completed exams',
            'view courses list','view course details','free course','paid course','payment','coupons','wallet','loyalty and points',
            'view the lowest rated course',
            'determine academic year','choose current chapter','view study materials','choose type','view references',
            'view lectures','download allowed files','view study tips','solve homework','subject exam','translate','complaint',
            'change application mode','view notifications',
            'user add','user delete','add paths','update paths','delete paths','add categories','delete categories',
            'add course exam','content management','send notifications',
            'add exam to subjects','update courses','delete courses','update lectures','delete lectures',
            'prepare tests','add reference','delete reference',
            'add study tips','update study tips','delete study tips',
            'open live broadcast','reports','submit correction file','advertisement','add lectures','add course'
        ];


        foreach ($permissions as $permissionName) {
            Permission::findOrCreate($permissionName, 'web');
        }


        $adminPermissions = [
            'log in','log out','user add','user delete','add paths','update paths','delete paths',
            'add categories','delete categories','add course exam','content management','send notifications'
        ];

        $teacherPermissions = [
            'log in','log out','send notifications','add exam to subjects','update courses','delete courses',
            'update lectures','delete lectures','prepare tests','add reference','delete reference',
            'add study tips','update study tips','delete study tips',
            'open live broadcast','reports','submit correction file','advertisement','add lectures','add course'
        ];

        $studentPermissions = [
            'register','log in','log out','verify account','change password','view profile','update profile','view current courses',
            'track course progress','take exam','view exam result','write notes','course evaluation','writing an article',
            'view current path','view completed path','view completed courses','view completed exams',
            'view courses list','view course details','free course','paid course','payment','coupons','wallet','loyalty and points',
            'view the lowest rated course',
            'determine academic year','choose current chapter','view study materials','choose type','view references',
            'view lectures','download allowed files','view study tips','solve homework','subject exam','translate','complaint',
            'change application mode','view notifications'
        ];


        $adminRole->givePermissionTo($adminPermissions);
        $teacherRole->givePermissionTo($teacherPermissions);
        $studentRole->givePermissionTo($studentPermissions);


        $adminUser = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
        ]);
        $adminUser->assignRole($adminRole);


        $teachers = [
            ['Rawan Qarouni', 'rawan@gmail.com'],
            ['George Karraz', 'george@gmail.com'],
            ['Hiba Hatem', 'hiba@gmail.com'],
            ['Mohamed Ihsan', 'mohamed@gmail.com'],
            ['Raghad Ali', 'raghad@gmail.com'],
        ];

        foreach ($teachers as [$name, $email]) {
            $teacher = User::factory()->create([
                'name' => $name,
                'email' => $email,
                'password' => bcrypt('password'),
            ]);
            $teacher->assignRole($teacherRole);
        }
    }
}
