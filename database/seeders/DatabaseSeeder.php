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
        $userView = Permission::create(['name' => 'userView', 'description' => 'Allow users to view all users']);
        $userCreate = Permission::create(['name' => 'userCreate', 'description' => 'Allow users to create new users']);
        $userEdit = Permission::create(['name' => 'userEdit', 'description' => 'Allow users to edit existing users']);
        $userDelete = Permission::create(['name' => 'userDelete', 'description' => 'Allow users to delete users']);
        
        $roleView = Permission::create(['name' => 'roleView', 'description' => 'Allow users to view roles']);
        $roleCreate = Permission::create(['name' => 'roleCreate', 'description' => 'Allow users to create new roles']);
        $roleEdit = Permission::create(['name' => 'roleEdit', 'description' => 'Allow users to edit existing roles']);
        $roleDelete = Permission::create(['name' => 'roleDelete', 'description' => 'Allow users to delete roles']);
        
        $permissionView = Permission::create(['name' => 'permissionView', 'description' => 'Allow users to view permissions']);
        $permissionCreate = Permission::create(['name' => 'permissionCreate', 'description' => 'Allow users to create new permissions']);
        $permissionEdit = Permission::create(['name' => 'permissionEdit', 'description' => 'Allow users to edit existing permissions']);
        $permissionDelete = Permission::create(['name' => 'permissionDelete', 'description' => 'Allow users to delete permissions']);
        
        $customerView = Permission::create(['name' => 'customerView', 'description' => 'Allow users to view all customers']);
        $customerCreate = Permission::create(['name' => 'customerCreate', 'description' => 'Allow users to create new customers']);
        $customerEdit = Permission::create(['name' => 'customerEdit', 'description' => 'Allow users to edit existing customers']);
        $customerDelete = Permission::create(['name' => 'customerDelete', 'description' => 'Allow users to delete customers']);   
        
        $workorderView = Permission::create(['name' => 'workorderView', 'description' => 'Allow users to view all workorders']);
        $workorderCreate = Permission::create(['name' => 'workorderCreate', 'description' => 'Allow users to create new workorders']);
        $workorderEdit = Permission::create(['name' => 'workorderEdit', 'description' => 'Allow users to edit existing workorders']);
        $workorderDelete = Permission::create(['name' => 'workorderDelete', 'description' => 'Allow users to delete workorders']);   

        // Assign permissions to Admin role
        $adminRole->givePermissionTo($userView);
        $adminRole->givePermissionTo($userCreate);
        $adminRole->givePermissionTo($userEdit);
        $adminRole->givePermissionTo($userDelete);
        $adminRole->givePermissionTo($roleView);
        $adminRole->givePermissionTo($roleCreate);
        $adminRole->givePermissionTo($roleEdit);
        $adminRole->givePermissionTo($roleDelete);
        $adminRole->givePermissionTo($permissionView);
        $adminRole->givePermissionTo($permissionCreate);
        $adminRole->givePermissionTo($permissionEdit);
        $adminRole->givePermissionTo($permissionDelete);
        $adminRole->givePermissionTo($customerView);
        $adminRole->givePermissionTo($customerCreate);
        $adminRole->givePermissionTo($customerEdit);
        $adminRole->givePermissionTo($customerDelete);
        $adminRole->givePermissionTo($workorderView);
        $adminRole->givePermissionTo($workorderCreate);
        $adminRole->givePermissionTo($workorderEdit);
        $adminRole->givePermissionTo($workorderDelete);

        // Assign permissions to Vendor role
        $vendorRole->givePermissionTo($customerView);
        $vendorRole->givePermissionTo($workorderView);
        $vendorRole->givePermissionTo($workorderEdit);

        // Assign permissions to Client role
        $clientRole->givePermissionTo($userView);
        $clientRole->givePermissionTo($userCreate);
        $clientRole->givePermissionTo($userEdit);
        $clientRole->givePermissionTo($userDelete);
        $clientRole->givePermissionTo($roleView);
        $clientRole->givePermissionTo($roleCreate);
        $clientRole->givePermissionTo($roleEdit);
        $clientRole->givePermissionTo($roleDelete);
        $clientRole->givePermissionTo($permissionView);
        $clientRole->givePermissionTo($permissionCreate);
        $clientRole->givePermissionTo($permissionEdit);
        $clientRole->givePermissionTo($permissionDelete);
        $clientRole->givePermissionTo($customerView);
        $clientRole->givePermissionTo($customerCreate);
        $clientRole->givePermissionTo($customerEdit);
        $clientRole->givePermissionTo($customerDelete);
        $clientRole->givePermissionTo($workorderView);
        $clientRole->givePermissionTo($workorderCreate);
        $clientRole->givePermissionTo($workorderEdit);
        $clientRole->givePermissionTo($workorderDelete);

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
            $status = $faker->randomElement(['Pending', 'Ongoing', 'Completed']);
        
            // Assign a user for 'Ongoing' and 'Completed' statuses.
            $userId = null;
            if ($status !== 'Pending') {
                $userId = $faker->numberBetween(1, 4); // Assuming you have users with ids from 1 to 50. Adjust this range accordingly.
            }
        
            Workorder::create([
                'customer_id' => $faker->numberBetween(1, 10),
                'user_id' => $userId, // Assign user here.
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
                'wo_status' => $status, // Use the status generated above.
                'created_at' => $randomCreatedAt,
            ]);
        }        
    }
}
