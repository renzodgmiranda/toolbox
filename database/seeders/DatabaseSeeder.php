<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Customer;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\Workorder;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

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

        $faker = Faker::create();
        //Create users automatically for testing
        $user = User::create([
            'name' => 'Renzo Miranda',
            'email' => 'renzo.miranda@teamspan.com',
            'password' => bcrypt('Renzo973!#'),
            'user_preferred' => false,
            'user_lat' => $faker->latitude(40.49, 40.92),
            'user_long' => $faker->longitude(-74.26, -73.68),
            'user_address' => $faker->address
        ]);
        $user->assignRole('Admin');

        $user2 = User::create([
            'name' => 'John Doe',
            'email' => 'john.doe@teamspan.com',
            'password' => bcrypt('renzo973'),
            'user_preferred' => true,
            'user_lat' => $faker->latitude(40.49, 40.92),
            'user_long' => $faker->longitude(-74.26, -73.68),
            'user_address' => $faker->address
        ]);
        $user2->assignRole('Vendor');

        $user3 = User::create([
            'name' => 'Jane Doe',
            'email' => 'jane.doe@teamspan.com',
            'password' => bcrypt('renzo973'),
            'user_preferred' => true,
            'user_lat' => $faker->latitude(40.49, 40.92),
            'user_long' => $faker->longitude(-74.26, -73.68),
            'user_address' => $faker->address
        ]);
        $user3->assignRole('Vendor');

        $user4 = User::create([
            'name' => 'Peter Stan',
            'email' => 'peter.stan@teamspan.com',
            'password' => bcrypt('renzo973'),
            'user_preferred' => true,
            'user_lat' => $faker->latitude(40.49, 40.92),
            'user_long' => $faker->longitude(-74.26, -73.68),
            'user_address' => $faker->address
        ]);
        $user4->assignRole('Client');

        $faker = Faker::create();
        // Array of company names
        $companies = [
            'Starbucks', 'Apple', 'Microsoft', 'Google', 'Amazon', 'Facebook', 'Netflix', 'Tesla', 'Adobe', 'Oracle'
            // ... add more company names as needed
        ];
        
        foreach ($companies as $company) {
            $latitude = $faker->latitude(40.49, 40.92);  // Latitude range that approximately covers New York
            $longitude = $faker->longitude(-74.26, -73.68);  // Longitude range that approximately covers New York
        
            Customer::create([
                'cus_name' => $company,
                'cus_store_number' => $faker->unique()->numberBetween(1000, 9999),  
                'cus_facility_coordinator' => $faker->name,
                'cus_facility_coordinator_contact' => $faker->phoneNumber,
                'cus_district_coordinator' => $faker->name,
                'cus_district_coordinator_contact' => $faker->phoneNumber,
                'cus_lat' => $latitude,
                'cus_long' => $longitude,
                'cus_address' => $faker->address,  // This address won't necessarily match the latitude and longitude.
            ]);
        }        

        for ($i = 0; $i < 230; $i++) {
            $randomCreatedAt = $faker->dateTimeBetween('-90 days', 'now');
    
            Workorder::create([
                'customer_id' => $faker->numberBetween(1, 10),
                'wo_number' => 'WO' . $faker->numberBetween(100000, 999999),
                'wo_problem' => $faker->randomElement(['Leaky faucet', 'Broken window', 'Damaged roof', 'Electrical issue', 'Heating malfunction']),
                'wo_problem_type' => $faker->randomElement(['Leaky faucet', 'Broken window', 'Damaged roof', 'Electrical issue', 'Heating malfunction']),
                'wo_description' => 'Description ' . $i,
                'wo_customer_po' => 'PO' . $faker->numberBetween(100000, 999999),
                'wo_asset' => 'Asset ' . $i,
                'wo_priority' => $faker->randomElement(['High', 'Medium', 'Low']),
                'wo_trade' => 'Trade ' . $i,
                'wo_category' => 'Category ' . $i,
                'wo_tech_nte' => 'Technical note ' . $i,
                'wo_schedule' => $faker->dateTimeBetween('now', '+30 days'),
                'wo_status' => 'Pending',
                'created_at' => $randomCreatedAt,
            ]);
        }
    }
}
