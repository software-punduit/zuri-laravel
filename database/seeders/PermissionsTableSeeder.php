<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Role => Permissions
        // e.g Admin => view users, edit user, etc...
        // Customer => create restuarant, edit restuarant etc..
        // Define the roles and permissions
        $seeds = [
            User::SUPER_ADMIN => [
                'users.create,update,view,activate,deactivate',
            ],
            User::ADMIN => [
                'users.create,update,view',
            ],
            User::RESTUARANT_OWNER => [
                'staff.create,update,view,activate,deactivate',
            ],
            User::RESTUARANT_STAFF => [
            ],
            User::CUSTOMER => [
                'restuarant.create,update,view,activate,deactivate',
            ],
        ];
        // Populate the database roles
        // and permissions

        foreach($seeds as $roleName => $permissionNames){
            // Get or create the role
            $role = Role::firstOrCreate([
                'name' => $roleName
            ]);

            foreach($permissionNames as $permissionName){
                // Get or create the permission
                Permission::firstOrCreate([
                    'name' => $permissionName
                ]);
            }

            $role->syncPermissions($permissionNames);
        }
    }
}
