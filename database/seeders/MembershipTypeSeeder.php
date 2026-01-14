<?php

namespace Database\Seeders;

use App\Models\MembershipType;
use Illuminate\Database\Seeder;

class MembershipTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $membershipTypes = [
            [
                'name' => 'Founder',
                'slug' => 'founder',
                'description' => 'Founding members with full access to all services',
                'entrance_fee' => 0,
                'capital_contribution' => 0,
                'minimum_shares' => 0,
                'maximum_shares' => null,
                'membership_interest_percentage' => 100.00,
                'access_permissions' => ['all'],
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Ordinary',
                'slug' => 'ordinary',
                'description' => 'Ordinary members with full service access',
                'entrance_fee' => 0,
                'capital_contribution' => 0,
                'minimum_shares' => 1,
                'maximum_shares' => null,
                'membership_interest_percentage' => 100.00,
                'access_permissions' => ['all'],
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Promoter',
                'slug' => 'promoter',
                'description' => 'Promoter members with enhanced access',
                'entrance_fee' => 0,
                'capital_contribution' => 0,
                'minimum_shares' => 1,
                'maximum_shares' => null,
                'membership_interest_percentage' => 100.00,
                'access_permissions' => ['loans', 'savings', 'investments', 'welfare', 'shares'],
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Associate',
                'slug' => 'associate',
                'description' => 'Associate members with limited access',
                'entrance_fee' => 0,
                'capital_contribution' => 0,
                'minimum_shares' => 0,
                'maximum_shares' => null,
                'membership_interest_percentage' => 50.00,
                'access_permissions' => ['savings', 'welfare'],
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Scholar',
                'slug' => 'scholar',
                'description' => 'Student members with basic access',
                'entrance_fee' => 0,
                'capital_contribution' => 0,
                'minimum_shares' => 0,
                'maximum_shares' => null,
                'membership_interest_percentage' => 25.00,
                'access_permissions' => ['savings'],
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'GROUP',
                'slug' => 'group',
                'description' => 'Group membership for registered groups',
                'entrance_fee' => 0,
                'capital_contribution' => 0,
                'minimum_shares' => 0,
                'maximum_shares' => null,
                'membership_interest_percentage' => 100.00,
                'access_permissions' => ['loans', 'savings', 'investments', 'welfare'],
                'is_active' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($membershipTypes as $type) {
            MembershipType::firstOrCreate(
                ['slug' => $type['slug']],
                $type
            );
        }

        $this->command->info('Membership types seeded successfully!');
    }
}

