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

        $role = Role::create(['name' => 'Admin']);
        $role = Role::create(['name' => 'Vendor']);
        $role = Role::create(['name' => 'Client']);
        $permission = Permission::create(['name' => 'View']);
        $permission = Permission::create(['name' => 'Create']);
        $permission = Permission::create(['name' => 'Edit']);
        $permission = Permission::create(['name' => 'Delete']);
    }
}
