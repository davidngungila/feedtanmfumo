<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'Member', 'slug' => 'member', 'description' => 'Access to own issues and services'],
            ['name' => 'Loan Officer', 'slug' => 'loan_officer', 'description' => 'Manage all loan operations'],
            ['name' => 'Deposit Officer', 'slug' => 'deposit_officer', 'description' => 'Manage all deposit operations'],
            ['name' => 'Investment Officer', 'slug' => 'investment_officer', 'description' => 'Manage all investment operations'],
            ['name' => 'Chairperson', 'slug' => 'chairperson', 'description' => 'View all details, reports, and manage issues'],
            ['name' => 'Secretary', 'slug' => 'secretary', 'description' => 'Access to all details and operations except system settings'],
            ['name' => 'Accountant', 'slug' => 'accountant', 'description' => 'Access to all accounting operations'],
            ['name' => 'Admin', 'slug' => 'admin', 'description' => 'Full system access including settings'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['slug' => $role['slug']],
                $role
            );
        }

        $this->command->info('Roles seeded successfully!');
    }
}
