<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Spatie\Permission\Models\Role;
class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissionsConfig = config('permission.roles_permissions');
        foreach ($permissionsConfig as $roleName => $groups) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $rolePermissions = [];
            foreach ($groups as $group => $actions) {
                foreach ($actions as $action) {
                    $permissionName = "$group.$action";
                    $rolePermissions[] = $permissionName;
                }
            } 
        }
    }
}
