<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Config;


class PermissionsSeeder extends Seeder
{
    public function run()
    {
        $permissionsConfig = Config::get('permission.roles_permissions');
        if (!is_array($permissionsConfig)) {
        throw new \Exception("permissions.roles_permissions config/permissions.php");
}
        $allPermissions = [];

        foreach ($permissionsConfig as $role => $groups) {
            foreach ($groups as $group => $actions) {
                foreach ($actions as $action) {
                    $permissionName = "$group.$action";
                    $allPermissions[$permissionName] = $group;
                }
            }
        }

        foreach ($allPermissions as $name => $group) {
            Permission::updateOrCreate(
                ['name' => $name],
                ['group' => $group, 'guard_name' => 'web']
            );
        }
    }
} 
