<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminAndTeacherSeeder extends Seeder
{

    public function run(): void
    {
        $admin = User::firstOrCreate([
            'email' => 'admin@gmail.com',
        ], [
            'name' => 'admin',
            'password' => bcrypt('111111'),
        ]);

        $adminRole = Role::firstOrcreate(['name'=> 'admin']);
        $adminPermission = [];
        foreach (config('permission.roles_permissions.admin') as $group => $actions) {
                foreach ($actions as $action) {
                $adminPermission[] = "$group.$action";
             }
            foreach ($adminPermission as $permissionName) {
                $permission = Permission::firstOrCreate(['name' => $permissionName]);
                if (!$admin->hasPermissionTo($permission)) {
                    $adminRole->givePermissionTo($permission);
                }
            }
          $admin->syncRoles('admin');
          $admin->getRoleNames();
          $admin->syncPermissions($adminPermission);

    }
}
}
