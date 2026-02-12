<?php

namespace Database\Seeders;

use App\Models\PaymentConfirmation;
use App\Models\User;
use Illuminate\Database\Seeder;

class PaymentConfirmationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample member IDs (some may exist, some may not)
        $sampleData = [
            [
                'member_id' => 'FND1',
                'member_name' => 'John Doe',
                'member_type' => 'Founder',
                'amount_to_pay' => 500000.00,
                'deposit_balance' => 750000.00,
                'member_email' => 'john.doe@example.com',
                'swf_contribution' => 200000.00,
                're_deposit' => 150000.00,
                'fia_investment' => 100000.00,
                'fia_type' => '4 Year',
                'capital_contribution' => 30000.00,
                'loan_repayment' => 20000.00,
                'notes' => 'Sample payment confirmation - Founder member',
            ],
            [
                'member_id' => 'ORD1',
                'member_name' => 'Jane Smith',
                'member_type' => 'Ordinary',
                'amount_to_pay' => 300000.00,
                'deposit_balance' => 450000.00,
                'member_email' => 'jane.smith@example.com',
                'swf_contribution' => 100000.00,
                're_deposit' => 100000.00,
                'fia_investment' => 50000.00,
                'fia_type' => '6 Year',
                'capital_contribution' => 30000.00,
                'loan_repayment' => 20000.00,
                'notes' => 'Sample payment confirmation - Ordinary member',
            ],
            [
                'member_id' => 'PRM1',
                'member_name' => 'Michael Johnson',
                'member_type' => 'Premium',
                'amount_to_pay' => 750000.00,
                'deposit_balance' => 1000000.00,
                'member_email' => 'michael.johnson@example.com',
                'swf_contribution' => 300000.00,
                're_deposit' => 200000.00,
                'fia_investment' => 150000.00,
                'fia_type' => '4 Year',
                'capital_contribution' => 70000.00,
                'loan_repayment' => 30000.00,
                'notes' => 'Sample payment confirmation - Premium member',
            ],
            [
                'member_id' => 'ASC1',
                'member_name' => 'Sarah Williams',
                'member_type' => 'Associate',
                'amount_to_pay' => 200000.00,
                'deposit_balance' => 350000.00,
                'member_email' => 'sarah.williams@example.com',
                'swf_contribution' => 50000.00,
                're_deposit' => 80000.00,
                'fia_investment' => 40000.00,
                'fia_type' => '6 Year',
                'capital_contribution' => 20000.00,
                'loan_repayment' => 10000.00,
                'notes' => 'Sample payment confirmation - Associate member',
            ],
            [
                'member_id' => 'FND2',
                'member_name' => 'David Brown',
                'member_type' => 'Founder',
                'amount_to_pay' => 600000.00,
                'deposit_balance' => 800000.00,
                'member_email' => 'david.brown@example.com',
                'swf_contribution' => 250000.00,
                're_deposit' => 150000.00,
                'fia_investment' => 120000.00,
                'fia_type' => '4 Year',
                'capital_contribution' => 50000.00,
                'loan_repayment' => 30000.00,
                'notes' => 'Sample payment confirmation - Founder member',
            ],
            [
                'member_id' => 'ORD2',
                'member_name' => 'Emily Davis',
                'member_type' => 'Ordinary',
                'amount_to_pay' => 250000.00,
                'deposit_balance' => 400000.00,
                'member_email' => 'emily.davis@example.com',
                'swf_contribution' => 0.00,
                're_deposit' => 0.00,
                'fia_investment' => 0.00,
                'fia_type' => null,
                'capital_contribution' => 0.00,
                'loan_repayment' => 0.00,
                'notes' => 'Imported from Excel sheet - Distribution pending',
            ],
            [
                'member_id' => 'PRM2',
                'member_name' => 'Robert Miller',
                'member_type' => 'Premium',
                'amount_to_pay' => 400000.00,
                'deposit_balance' => 600000.00,
                'member_email' => 'robert.miller@example.com',
                'swf_contribution' => 0.00,
                're_deposit' => 0.00,
                'fia_investment' => 0.00,
                'fia_type' => null,
                'capital_contribution' => 0.00,
                'loan_repayment' => 0.00,
                'notes' => 'Imported from Excel sheet - Distribution pending',
            ],
        ];

        foreach ($sampleData as $data) {
            // Try to find user by member_id (member_number or membership_code)
            $user = User::where('member_number', $data['member_id'])
                ->orWhere('membership_code', $data['member_id'])
                ->first();

            // Create payment confirmation
            PaymentConfirmation::create([
                'user_id' => $user?->id,
                'member_id' => $data['member_id'],
                'member_name' => $data['member_name'],
                'member_type' => $data['member_type'],
                'amount_to_pay' => $data['amount_to_pay'],
                'deposit_balance' => $data['deposit_balance'],
                'member_email' => $data['member_email'],
                'swf_contribution' => $data['swf_contribution'],
                're_deposit' => $data['re_deposit'],
                'fia_investment' => $data['fia_investment'],
                'fia_type' => $data['fia_type'],
                'capital_contribution' => $data['capital_contribution'],
                'loan_repayment' => $data['loan_repayment'],
                'notes' => $data['notes'],
            ]);
        }

        $this->command->info('✅ Created '.count($sampleData).' sample payment confirmation records.');
    }
}
