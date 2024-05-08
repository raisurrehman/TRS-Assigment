<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $adminData = [
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('Pass@123'),
        ];

        $subadminData = [
            'name' => 'Sub Admin',
            'email' => 'subadmin@admin.com',
            'password' => Hash::make('Pass@123'),
        ];

        $admin = User::create($adminData);
        $subadmin = User::create($subadminData);

        $adminRole = Role::create(['name' => 'admin']);
        $subAdminRole = Role::create(['name' => 'sub-admin']);

        $this->createPermissions();

        $admin->assignRole($adminRole);
        $subadmin->assignRole($subAdminRole);

        $this->assignAdminPermissions($adminRole);
        $this->assignSubAdminPermissions($subAdminRole);
    }

    /**
     * Create permissions.
     *
     * @return void
     */
    private function createPermissions(): void
    {
        $permissions = [
            'view-dashboard',
            'view-products', 'create-products', 'edit-products', 'delete-products',
            'view-categories', 'create-categories', 'edit-categories', 'delete-categories',
            'view-roles', 'create-roles', 'edit-roles', 'assign-roles',
            'view-users', 'create-users', 'edit-users',
            'manage-permissions',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    /**
     * Assign permissions to admin role.
     *
     * @param \Spatie\Permission\Models\Role $role
     * @return void
     */
    private function assignAdminPermissions(Role $role): void
    {
        $permissions = [
            'view-dashboard',
            'view-products', 'create-products', 'edit-products', 'delete-products',
            'view-categories', 'create-categories', 'edit-categories', 'delete-categories',
            'view-roles', 'create-roles', 'edit-roles', 'assign-roles',
            'view-users', 'create-users', 'edit-users',
            'manage-permissions',
        ];

        $role->givePermissionTo($permissions);
    }

    /**
     * Assign permissions to sub-admin role.
     *
     * @param \Spatie\Permission\Models\Role $role
     * @return void
     */
    private function assignSubAdminPermissions(Role $role): void
    {
        $permissions = [
            'view-products', 'create-products',
            'view-categories', 'create-categories',
        ];

        $role->givePermissionTo($permissions);
    }
}
