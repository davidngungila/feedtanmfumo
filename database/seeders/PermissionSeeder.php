<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Users
            ['name' => 'View Users', 'slug' => 'view_users', 'group' => 'users'],
            ['name' => 'Create Users', 'slug' => 'create_users', 'group' => 'users'],
            ['name' => 'Edit Users', 'slug' => 'edit_users', 'group' => 'users'],
            ['name' => 'Delete Users', 'slug' => 'delete_users', 'group' => 'users'],
            ['name' => 'Manage Roles', 'slug' => 'manage_roles', 'group' => 'users'],
            
            // Loans
            ['name' => 'View Loans', 'slug' => 'view_loans', 'group' => 'loans'],
            ['name' => 'Create Loans', 'slug' => 'create_loans', 'group' => 'loans'],
            ['name' => 'Approve Loans', 'slug' => 'approve_loans', 'group' => 'loans'],
            ['name' => 'Disburse Loans', 'slug' => 'disburse_loans', 'group' => 'loans'],
            ['name' => 'Manage Loan Payments', 'slug' => 'manage_loan_payments', 'group' => 'loans'],
            
            // Savings
            ['name' => 'View Savings', 'slug' => 'view_savings', 'group' => 'savings'],
            ['name' => 'Create Savings Accounts', 'slug' => 'create_savings', 'group' => 'savings'],
            ['name' => 'Manage Deposits', 'slug' => 'manage_deposits', 'group' => 'savings'],
            ['name' => 'Manage Withdrawals', 'slug' => 'manage_withdrawals', 'group' => 'savings'],
            
            // Investments
            ['name' => 'View Investments', 'slug' => 'view_investments', 'group' => 'investments'],
            ['name' => 'Create Investments', 'slug' => 'create_investments', 'group' => 'investments'],
            ['name' => 'Manage Investment Disbursements', 'slug' => 'manage_investment_disbursements', 'group' => 'investments'],
            
            // Issues
            ['name' => 'View Issues', 'slug' => 'view_issues', 'group' => 'issues'],
            ['name' => 'Create Issues', 'slug' => 'create_issues', 'group' => 'issues'],
            ['name' => 'Resolve Issues', 'slug' => 'resolve_issues', 'group' => 'issues'],
            ['name' => 'Assign Issues', 'slug' => 'assign_issues', 'group' => 'issues'],
            
            // Reports
            ['name' => 'View Reports', 'slug' => 'view_reports', 'group' => 'reports'],
            ['name' => 'Export Data', 'slug' => 'export_data', 'group' => 'reports'],
            
            // Settings
            ['name' => 'Manage System Settings', 'slug' => 'manage_settings', 'group' => 'settings'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }

        $this->command->info('Permissions seeded successfully!');
    }
}
