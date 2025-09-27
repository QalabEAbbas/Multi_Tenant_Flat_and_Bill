<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
            $guard = 'web';

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Permissions for Admin
        $permissions = [
            'manage house owners',
            'manage tenants',
            'view tenants',
            'remove tenants',
            'assign tenants to buildings',
            'create flats',
            'manage flats',
            'create bill categories',
            'create bills',
            'add dues',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Roles
        // Roles
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin', 'guard_name' => 'web']
        );

        $ownerRole = Role::firstOrCreate(
            ['name' => 'house_owner', 'guard_name' => 'web']
        );

        $tenantRole = Role::firstOrCreate(
            ['name' => 'tenant', 'guard_name' => 'web']
        );

        // Assign permissions
        $adminRole->givePermissionTo([
            'manage house owners',
            'manage tenants',
            'view tenants',
            'remove tenants',
            'assign tenants to buildings',
        ]);

        $ownerRole->givePermissionTo([
            'create flats',
            'manage flats',
            'create bill categories',
            'create bills',
            'add dues',
        ]);

        // tenants usually don’t have system permissions, just use system
    }
}
