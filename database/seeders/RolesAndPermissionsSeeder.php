<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'recipe:create',
            'recipe:edit',
            'recipe:delete',
            'recipe:publish',
            'category:manage',
            'comment:moderate',
            'user:manage',
            'media:upload',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions

        // Reader — can only comment (no special permissions needed)
        Role::firstOrCreate(['name' => 'reader']);

        // Author — can manage own recipes and upload media
        $author = Role::firstOrCreate(['name' => 'author']);
        $author->syncPermissions([
            'recipe:create',
            'recipe:edit',
            'recipe:delete',
            'media:upload',
        ]);

        // Admin — full access
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions($permissions);
    }
}