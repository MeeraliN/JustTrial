<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'property.view_any',
            'property.create',
            'property.edit_own',
            'property.edit_any',
            'property.approve',
            'property.reject',
            'property.publish',
            'property.delete',
            'property.export',
            'complaint.view_assigned',
            'complaint.view_any',
            'complaint.resolve',
            'support.view_assigned',
            'support.view_any',
            'support.reply',
            'category.view',
            'category.create',
            'category.edit',
            'category.delete',
            'language.view',
            'language.create',
            'language.edit',
            'language.delete',
            'city.view',
            'city.create',
            'city.edit',
            'city.delete',
            'analytics.view',
            'advertisement.manage',
            'staff.manage',
            'audit.view',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
        }

        $roles = [
            'super_admin' => $permissions,
            'property_entry_operator' => ['property.create', 'property.edit_own'],
            'property_manager' => ['property.view_any', 'property.edit_any', 'property.approve', 'property.reject', 'property.publish'],
            'complaint_manager' => ['complaint.view_any', 'complaint.resolve'],
            'support_executive' => ['support.view_assigned', 'support.reply'],
            'category_manager' => ['category.view', 'category.create', 'category.edit', 'category.delete'],
            'advertisement_manager' => ['advertisement.manage'],
            'content_manager' => ['language.view', 'language.create', 'language.edit', 'language.delete', 'city.view', 'city.create', 'city.edit'],
            'analyst' => ['analytics.view'],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($rolePermissions);
        }
    }
}
