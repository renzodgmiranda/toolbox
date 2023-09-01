<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Customer;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\Workorder;
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

        //Create users automatically for testing
        $user = User::create([
            'name' => 'Renzo Miranda',
            'email' => 'renzo.miranda@teamspan.com',
            'password' => bcrypt('Renzo973!#'),
            'user_preferred' => false,
        ]);
        $user->assignRole('Admin');

        $user2 = User::create([
            'name' => 'John Doe',
            'email' => 'john.doe@teamspan.com',
            'password' => bcrypt('renzo973'),
            'user_preferred' => true,
        ]);
        $user2->assignRole('Vendor');

        $user3 = User::create([
            'name' => 'Jane Doe',
            'email' => 'jane.doe@teamspan.com',
            'password' => bcrypt('renzo973'),
            'user_preferred' => true,
        ]);
        $user3->assignRole('Vendor');

        // Array of company names
        $companies = [
            'Starbucks',
            'Apple',
            'Microsoft',
            'Google',
            'Amazon',
            'Facebook',
            'Netflix',
            'Tesla',
            'Adobe',
            'Oracle'
            // ... add more company names as needed
        ];

        // Loop through the array and seed each company into the customers table
        foreach ($companies as $company) {
            Customer::create([
                'cus_name' => $company,
            ]);
        }

        // Arrays of Sample Data
        $problems = ['Leaky faucet', 'Broken window', 'Damaged roof', 'Electrical issue', 'Heating malfunction'];
        $priorities = ['High', 'Medium', 'Low'];

        // Loop to create 10 workorder records with random data
        for ($i = 0; $i < 10; $i++) {
            Workorder::create([
                'customer_id' => rand(1, 10), // Assuming you have customers with IDs between 1 and 50
                'wo_number' => 'WO' . rand(100000, 999999),
                'wo_problem' => $problems[array_rand($problems)],
                'wo_problem_type' => $problems[array_rand($problems)], // Modify as needed
                'wo_description' => 'Description ' . $i, // Modify as needed
                'wo_customer_po' => 'PO' . rand(100000, 999999),
                'wo_asset' => 'Asset ' . $i, // Modify as needed
                'wo_priority' => $priorities[array_rand($priorities)],
                'wo_trade' => 'Trade ' . $i, // Modify as needed
                'wo_category' => 'Category ' . $i, // Modify as needed
                'wo_tech_nte' => 'Technical note ' . $i, // Modify as needed
                'wo_schedule' => date('Y-m-d H:i:s', strtotime('+'.rand(1,30).' days')), // Randomly scheduled within the next 30 days
                'wo_status' => 'Pending',
            ]);
        }
    }
}
