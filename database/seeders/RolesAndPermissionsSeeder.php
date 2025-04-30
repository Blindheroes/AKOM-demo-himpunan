<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions for each model
        $modelPermissions = [
            'user' => ['view', 'create', 'update', 'delete'],
            'department' => ['view', 'create', 'update', 'delete'],
            'event' => ['view', 'create', 'update', 'delete', 'approve'],
            'lpj' => ['view', 'create', 'update', 'delete', 'approve'],
            'letter' => ['view', 'create', 'update', 'delete', 'approve'],
            'news' => ['view', 'create', 'update', 'delete', 'publish'],
            'gallery' => ['view', 'create', 'update', 'delete'],
            'signature' => ['view', 'create', 'update', 'delete'],
        ];

        $allPermissions = [];

        foreach ($modelPermissions as $model => $actions) {
            foreach ($actions as $action) {
                $permissionName = $action . ' ' . $model;
                $permission = Permission::create(['name' => $permissionName]);
                $allPermissions[] = $permission;
            }
        }

        // Create roles and assign permissions
        $superAdminRole = Role::create(['name' => 'super-admin']);
        // Super admin gets all permissions
        $superAdminRole->givePermissionTo(Permission::all());

        $adminRole = Role::create(['name' => 'admin']);
        // Admin gets all permissions except user management
        $adminPermissions = Permission::whereNotIn('name', [
            'create user',
            'update user',
            'delete user',
        ])->get();
        $adminRole->givePermissionTo($adminPermissions);

        $moderatorRole = Role::create(['name' => 'moderator']);
        // Moderators can view everything and manage content but not approve
        $moderatorPermissions = Permission::whereNotIn('name', [
            'create user',
            'update user',
            'delete user',
            'approve event',
            'approve lpj',
            'approve letter',
            'delete department',
            'delete event',
            'delete lpj',
            'delete letter',
        ])->get();
        $moderatorRole->givePermissionTo($moderatorPermissions);

        $memberRole = Role::create(['name' => 'member']);
        // Members can view content and create their own content
        $memberPermissions = [
            'view user',
            'view department',
            'view event',
            'view lpj',
            'view letter',
            'view news',
            'view gallery',
            'view signature',
            'create event',
            'create lpj',
            'create letter'
        ];
        $memberRole->givePermissionTo($memberPermissions);

        // Assign roles to existing users based on their current role column
        // Find admin users
        $adminUsers = User::where('role', 'admin')
            ->orWhere('role', 'administrator')
            ->orWhere('signature_authority', true)
            ->get();

        foreach ($adminUsers as $user) {
            $user->assignRole('super-admin');
        }

        // Regular members
        $regularUsers = User::whereNotIn('id', $adminUsers->pluck('id'))->get();
        foreach ($regularUsers as $user) {
            $user->assignRole('member');
        }
    }
}
