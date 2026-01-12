<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define Permissions
        $permissions = [
            // User Management
            'create user',
            'edit user',
            'delete user',
            'view users',
            'change password',
            
            // Role Management
            'view roles',
            'create role',
            'edit role',
            'delete role',
        ];

        // Create Permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Roles and Assign Permissions

        // Role Manager (Can manage roles)
        $roleManager = Role::firstOrCreate(['name' => 'role-manager']);
        $roleManager->givePermissionTo([
            'view roles',
            'create role',
            'edit role',
            'delete role',
        ]);

        // User Manager (Can manage users)
        $userManager = Role::firstOrCreate(['name' => 'user-manager']);
        $userManager->givePermissionTo([
            'create user',
            'edit user',
            'delete user',
            'view users',
            'change password',
        ]);

        // Admin (Can do everything)
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        // Assign 'admin' role to test user
        $user = User::where('email', 'test@example.com')->first();
        if ($user) {
            $user->assignRole($admin);
        }
    }
}
