<?php

namespace Database\Seeders;

use App\Enums\PermissionsEnum;
use App\Enums\RolesEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder {
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void {
        /**
         * NOTICE: If you have CACHE_STORE=database set in your .env,
         * remember that you must install Laravel's cache tables via a migration before performing any cache operations.
         */

        // flush cache before creating roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions using PermissionsEnum
        foreach (PermissionsEnum::cases() as $permission) {
            Permission::firstOrCreate(['name' => $permission->value]);
        }

        // create roles using RolesEnum
        $admin_role = Role::firstOrCreate(['name' => RolesEnum::ADMINISTRATOR->value]);
        $user_manager_role = Role::firstOrCreate(['name' => RolesEnum::USER_MANAGER->value]);
        $registered_user_role = Role::firstOrCreate(['name' => RolesEnum::REGISTERED_USER->value]);
        // flush cache after creating roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // assign permissions to roles
        $user_manager_role->givePermissionTo([
            PermissionsEnum::VIEW_USERS->value,
            PermissionsEnum::CREATE_USERS->value,
            PermissionsEnum::UPDATE_USERS->value,
            PermissionsEnum::DELETE_USERS->value,
            PermissionsEnum::RESTORE_USERS->value,
        ]);

        $admin_role->givePermissionTo(Permission::all());
    }
}
