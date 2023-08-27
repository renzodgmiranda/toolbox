<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        /**
         * Create Roles
         */
        $adminRole = Role::create(['name' => 'Admin']);
        $vendorRole = Role::create(['name' => 'Vendor']);
        $clientRole = Role::create(['name' => 'Client']);

        /**
         * Create Permissions
         */
        $viewPermission = Permission::create(['name' => 'View']);
        $createPermission = Permission::create(['name' => 'Create']);
        $editPermission = Permission::create(['name' => 'Edit']);
        $deletePermission = Permission::create(['name' => 'Delete']);

        // Assign permissions to Admin role
        $adminRole->givePermissionTo($viewPermission);
        $adminRole->givePermissionTo($createPermission);
        $adminRole->givePermissionTo($editPermission);
        $adminRole->givePermissionTo($deletePermission);

        // Assign permissions to Vendor role
        $vendorRole->givePermissionTo($viewPermission);
        $vendorRole->givePermissionTo($editPermission);

        // Assign permissions to Client role
        $clientRole->givePermissionTo($viewPermission);
        $clientRole->givePermissionTo($createPermission);
        $clientRole->givePermissionTo($editPermission);
        $clientRole->givePermissionTo($deletePermission);
    }
}
