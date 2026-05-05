<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Loan;
use App\Models\SavingsAccount;
use App\Models\Payment;
use App\Models\MembershipType;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SampleMembersAndLoansSeeder extends Seeder
{
    public function run(): void
    {
        // Get membership types
        $membershipTypes = MembershipType::all();
        if ($membershipTypes->isEmpty()) {
            $this->command->warn('No membership types found. Please run MembershipTypeSeeder first.');
            return;
        }

        // Sample member data
        $sampleMembers = [
            [
                'name' => 'John Mwangi Kamau',
                'email' => 'john.mwangi@example.com',
                'phone' => '+254712345678',
                'date_of_birth' => '1985-03-15',
                'gender' => 'male',
                'national_id' => '12345678',
                'member_number' => 'FD-2023-001',
                'status' => 'active',
                'membership_type_id' => $membershipTypes->where('name', 'Regular')->first()?->id ?? 1,
                'address' => '123 Thika Road, Nairobi',
                'city' => 'Nairobi',
                'region' => 'Nairobi County',
                'postal_code' => '00100',
                'occupation' => 'employed',
                'employer' => 'ABC Company Ltd',
                'monthly_income' => 85000.00,
                'bank_name' => 'Equity Bank',
                'bank_account_number' => '0034567890123',
                'bank_branch' => 'Thika Road Branch',
                'payment_reference_number' => 'PAY-001234',
                'kyc_status' => 'verified'
            ],
            [
                'name' => 'Mary Njoki Wanjiru',
                'email' => 'mary.njoki@example.com',
                'phone' => '+254723456789',
                'date_of_birth' => '1990-07-22',
                'gender' => 'female',
                'national_id' => '23456789',
                'member_number' => 'FD-2023-002',
                'status' => 'active',
                'membership_type_id' => $membershipTypes->where('name', 'Premium')->first()?->id ?? 2,
                'address' => '456 Mombasa Road, Nairobi',
                'city' => 'Nairobi',
                'region' => 'Nairobi County',
                'postal_code' => '00200',
                'occupation' => 'self-employed',
                'employer' => 'Njoki Enterprises',
                'monthly_income' => 120000.00,
                'bank_name' => 'KCB Bank',
                'bank_account_number' => '0045678901234',
                'bank_branch' => 'Mombasa Road Branch',
                'payment_reference_number' => 'PAY-002345',
                'kyc_status' => 'verified'
            ],
            [
                'name' => 'James Ochieng Otieno',
                'email' => 'james.ochieng@example.com',
                'phone' => '+254734567890',
                'date_of_birth' => '1988-11-08',
                'gender' => 'male',
                'national_id' => '34567890',
                'member_number' => 'FD-2023-003',
                'status' => 'active',
                'membership_type_id' => $membershipTypes->where('name', 'Regular')->first()?->id ?? 1,
                'address' => '789 Ngong Road, Nairobi',
                'city' => 'Nairobi',
                'region' => 'Nairobi County',
                'postal_code' => '00500',
                'occupation' => 'employed',
                'employer' => 'XYZ Industries',
                'monthly_income' => 95000.00,
                'bank_name' => 'Cooperative Bank',
                'bank_account_number' => '0056789012345',
                'bank_branch' => 'Ngong Road Branch',
                'payment_reference_number' => 'PAY-003456',
                'kyc_status' => 'verified'
            ],
            [
                'name' => 'Grace Achieng Okello',
                'email' => 'grace.achieng@example.com',
                'phone' => '+254745678901',
                'date_of_birth' => '1992-04-30',
                'gender' => 'female',
                'national_id' => '45678901',
                'member_number' => 'FD-2023-004',
                'status' => 'pending',
                'membership_type_id' => $membershipTypes->where('name', 'Basic')->first()?->id ?? 3,
                'address' => '321 Waiyaki Way, Nairobi',
                'city' => 'Nairobi',
                'region' => 'Nairobi County',
                'postal_code' => '00600',
                'occupation' => 'employed',
                'employer' => 'Ministry of Health',
                'monthly_income' => 75000.00,
                'bank_name' => 'Standard Chartered',
                'bank_account_number' => '0067890123456',
                'bank_branch' => 'Waiyaki Way Branch',
                'payment_reference_number' => 'PAY-004567',
                'kyc_status' => 'pending'
            ],
            [
                'name' => 'Peter Kiprop Chebet',
                'email' => 'peter.kiprop@example.com',
                'phone' => '+254756789012',
                'date_of_birth' => '1987-09-12',
                'gender' => 'male',
                'national_id' => '56789012',
                'member_number' => 'FD-2023-005',
                'status' => 'active',
                'membership_type_id' => $membershipTypes->where('name', 'Premium')->first()?->id ?? 2,
                'address' => '654 Langata Road, Nairobi',
                'city' => 'Nairobi',
                'region' => 'Nairobi County',
                'postal_code' => '00501',
                'occupation' => 'business',
                'employer' => 'Chebet Transport Ltd',
                'monthly_income' => 150000.00,
                'bank_name' => 'NCBA Bank',
                'bank_account_number' => '0078901234567',
                'bank_branch' => 'Langata Road Branch',
                'payment_reference_number' => 'PAY-005678',
                'kyc_status' => 'verified'
            ]
        ];

        // Create members if they don't exist
        $createdMembers = [];
        foreach ($sampleMembers as $memberData) {
            $existingMember = User::where('email', $memberData['email'])->first();
            
            if ($existingMember) {
                $createdMembers[] = $existingMember;
                $this->command->info("Found existing member: {$existingMember->name} ({$existingMember->member_number})");
            } else {
                $password = Hash::make('password123'); // Default password for all sample members
                
                $member = User::create(array_merge($memberData, [
                    'password' => $password,
                    'email_verified_at' => now(),
                    'created_at' => now()->subDays(rand(1, 180)), // Random creation date
                    'updated_at' => now()
                ]));

                $createdMembers[] = $member;
                $this->command->info("Created member: {$member->name} ({$member->member_number})");
            }
        }

        // Create loans for members
        $loanData = [
            [
                'member_index' => 0, // John Mwangi
                'principal_amount' => 50000.00,
                'interest_rate' => 15.00,
                'term_months' => 12,
                'purpose' => 'Business expansion - small shop inventory',
                'status' => 'active',
                'application_date' => now()->subMonths(6),
                'approval_date' => now()->subMonths(5),
                'disbursement_date' => now()->subMonths(5),
                'paid_amount' => 25000.00,
                'payment_frequency' => 'monthly'
            ],
            [
                'member_index' => 1, // Mary Njoki
                'principal_amount' => 100000.00,
                'interest_rate' => 14.50,
                'term_months' => 18,
                'purpose' => 'School fees payment for children',
                'status' => 'active',
                'application_date' => now()->subMonths(8),
                'approval_date' => now()->subMonths(7),
                'disbursement_date' => now()->subMonths(7),
                'paid_amount' => 45000.00,
                'payment_frequency' => 'monthly'
            ],
            [
                'member_index' => 2, // James Ochieng
                'principal_amount' => 75000.00,
                'interest_rate' => 16.00,
                'term_months' => 15,
                'purpose' => 'Home renovation and furniture',
                'status' => 'active',
                'application_date' => now()->subMonths(4),
                'approval_date' => now()->subMonths(3),
                'disbursement_date' => now()->subMonths(3),
                'paid_amount' => 20000.00,
                'payment_frequency' => 'monthly'
            ],
            [
                'member_index' => 3, // Grace Achieng
                'principal_amount' => 30000.00,
                'interest_rate' => 18.00,
                'term_months' => 9,
                'purpose' => 'Emergency medical expenses',
                'status' => 'pending',
                'application_date' => now()->subMonths(1),
                'approval_date' => null,
                'disbursement_date' => null,
                'paid_amount' => 0.00,
                'payment_frequency' => 'monthly'
            ],
            [
                'member_index' => 4, // Peter Kiprop
                'principal_amount' => 150000.00,
                'interest_rate' => 13.50,
                'term_months' => 24,
                'purpose' => 'Vehicle purchase for business',
                'status' => 'active',
                'application_date' => now()->subMonths(10),
                'approval_date' => now()->subMonths(9),
                'disbursement_date' => now()->subMonths(9),
                'paid_amount' => 85000.00,
                'payment_frequency' => 'monthly'
            ],
            [
                'member_index' => 0, // John Mwangi - Second loan
                'principal_amount' => 25000.00,
                'interest_rate' => 15.50,
                'term_months' => 6,
                'purpose' => 'Agricultural inputs for farming',
                'status' => 'completed',
                'application_date' => now()->subMonths(14),
                'approval_date' => now()->subMonths(13),
                'disbursement_date' => now()->subMonths(13),
                'maturity_date' => now()->subMonths(2),
                'paid_amount' => 28750.00,
                'payment_frequency' => 'monthly'
            ],
            [
                'member_index' => 1, // Mary Njoki - Second loan
                'principal_amount' => 40000.00,
                'interest_rate' => 17.00,
                'term_months' => 8,
                'purpose' => 'Emergency home repairs',
                'status' => 'overdue',
                'application_date' => now()->subMonths(12),
                'approval_date' => now()->subMonths(11),
                'disbursement_date' => now()->subMonths(11),
                'maturity_date' => now()->subMonths(3),
                'paid_amount' => 35000.00,
                'payment_frequency' => 'monthly'
            ]
        ];

        foreach ($loanData as $loanInfo) {
            $member = $createdMembers[$loanInfo['member_index']];
            
            $totalAmount = $loanInfo['principal_amount'] + ($loanInfo['principal_amount'] * ($loanInfo['interest_rate'] / 100) * ($loanInfo['term_months'] / 12));
            $remainingAmount = $totalAmount - $loanInfo['paid_amount'];
            
            $loan = Loan::create([
                'user_id' => $member->id,
                'loan_number' => 'LN-' . date('Y') . '-' . str_pad(Loan::count() + 1, 4, '0', STR_PAD_LEFT),
                'principal_amount' => $loanInfo['principal_amount'],
                'interest_rate' => $loanInfo['interest_rate'],
                'total_amount' => $totalAmount,
                'paid_amount' => $loanInfo['paid_amount'],
                'remaining_amount' => $remainingAmount,
                'term_months' => $loanInfo['term_months'],
                'application_date' => $loanInfo['application_date'],
                'approval_date' => $loanInfo['approval_date'] ?? null,
                'disbursement_date' => $loanInfo['disbursement_date'] ?? null,
                'maturity_date' => $loanInfo['disbursement_date'] && $loanInfo['status'] !== 'pending' ? 
                    (clone $loanInfo['disbursement_date'])->addMonths($loanInfo['term_months']) : null,
                'status' => $loanInfo['status'],
                'payment_frequency' => $loanInfo['payment_frequency'],
                'purpose' => $loanInfo['purpose'],
                'approved_by' => 1, // Admin user
                'created_at' => $loanInfo['application_date'],
                'updated_at' => now()
            ]);

            $this->command->info("Created loan: {$loan->loan_number} for {$member->name} - KES {$loan->principal_amount}");

            // Create payment confirmations for active loans
            if ($loanInfo['paid_amount'] > 0 && in_array($loanInfo['status'], ['active', 'completed', 'overdue'])) {
                $this->createPaymentConfirmations($loan, $loanInfo['paid_amount']);
            }
        }

        // Create savings accounts for all members
        foreach ($createdMembers as $member) {
            $savingsAccount = SavingsAccount::create([
                'user_id' => $member->id,
                'account_number' => 'SA-' . date('Y') . '-' . str_pad($member->id, 6, '0', STR_PAD_LEFT),
                'account_type' => 'flex', // Use valid account type
                'balance' => rand(5000, 50000), // Random balance
                'interest_rate' => 5.00, // Default interest rate
                'minimum_balance' => 1000, // Minimum balance
                'opening_date' => $member->created_at->format('Y-m-d'),
                'status' => $member->status === 'active' ? 'active' : 'frozen',
                'created_at' => $member->created_at,
                'updated_at' => now()
            ]);

            $this->command->info("Created savings account: {$savingsAccount->account_number} for {$member->name}");
        }

        $this->command->info('Sample members and loans created successfully!');
    }

    private function createPaymentConfirmations(Loan $loan, float $totalPaid): void
    {
        // Create a single payment confirmation record for the loan
        \App\Models\PaymentConfirmation::create([
            'user_id' => $loan->user_id,
            'member_id' => $loan->user->member_number,
            'member_name' => $loan->user->name,
            'member_type' => 'regular',
            'amount_to_pay' => $loan->total_amount,
            'deposit_balance' => 0,
            'swf_contribution' => 0,
            're_deposit' => 0,
            'fia_investment' => 0,
            'capital_contribution' => 0,
            'loan_repayment' => $totalPaid,
            'member_email' => $loan->user->email,
            'notes' => 'Sample payment confirmation for loan ' . $loan->loan_number,
            'created_at' => $loan->disbursement_date,
            'updated_at' => now()
        ]);

        $this->command->info("Created payment confirmation for loan {$loan->loan_number}");
    }
}
