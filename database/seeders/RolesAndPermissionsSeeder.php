<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'users' => [
                'show',
                'edit',
                'delete',
            ],
            'leads' => [
                'show',
                'edit',
                'delete',
            ],
            'source' => [
                'show',
                'edit',
                'delete',
            ],
            'campaign' => [
                'show',
                'edit',
                'delete',
            ]
        ];

        $permgroups = [
            'users' => [],
            'leads' => [],
            'source' => [],
            'campaign' => [],
        ];

        foreach ($permissions as $permgroup => $permission) {
            foreach ($permission as $perm) {
                echo "[+] Creating Permission: $permgroup.$perm\n";

                $permgroups[$permgroup][] = "$permgroup.$perm";

                Permission::create(['name' => "$permgroup.$perm"]);
            }
        }
        // create roles and assign created permissions

        echo "PERMGROUPS: \n";
        print_r($permgroups);

        $roles = [
            'super-admin' => [
                Permission::all(),
            ],
            'sales' => [
                ...$permgroups['leads'],
//                ...$permgroups['source'],
            ],
        ];

        echo "Roles: \n";
        print_r($roles);

        foreach ( $roles as $role => $permissions ) {
            echo "[+] Creating Role: $role\n";
//            echo "With Permission: \n";
//            print_r($permissions);
//            echo "\n";

            $_role = Role::create(['name' => $role]);
            $_role->givePermissionTo($permissions);
        }
    }
}
