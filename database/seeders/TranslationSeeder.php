<?php

namespace Database\Seeders;

use App\Models\Translation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class TranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $translations = [
            // Dashboard
            ['key' => 'dashboard', 'en' => 'Dashboard', 'sw' => 'Dashibodi'],
            ['key' => 'welcome_back', 'en' => 'Welcome Back', 'sw' => 'Karibu Tena'],
            ['key' => 'loans', 'en' => 'Loans', 'sw' => 'Mikopo'],
            ['key' => 'savings', 'en' => 'Savings', 'sw' => 'Akiba'],
            ['key' => 'investments', 'en' => 'Investments', 'sw' => 'Uwekezaji'],
            ['key' => 'welfare', 'en' => 'Welfare', 'sw' => 'Mfuko wa Jamii'],
            ['key' => 'issues', 'en' => 'Issues', 'sw' => 'Kero'],
            ['key' => 'statements', 'en' => 'Statements', 'sw' => 'Taarifa za Hesabu'],
            ['key' => 'my_profile', 'en' => 'My Profile', 'sw' => 'Wasifu Wangu'],
            ['key' => 'settings', 'en' => 'Settings', 'sw' => 'Mipangilio'],
            ['key' => 'logout', 'en' => 'Logout', 'sw' => 'Ondoka'],
            
            // Deposit Statements
            ['key' => 'deposit_statements', 'en' => 'Deposit Statements', 'sw' => 'Taarifa za Akiba'],
            ['key' => 'view_history', 'en' => 'View your monthly contribution history', 'sw' => 'Angalia historia ya michango yako ya kila mwezi'],
            ['key' => 'total_statements', 'en' => 'Total Statements', 'sw' => 'Jumla ya Taarifa'],
            ['key' => 'months', 'en' => 'Months', 'sw' => 'Miezi'],
            ['key' => 'monthly_report', 'en' => 'Monthly Report', 'sw' => 'Ripoti ya Mwezi'],
            ['key' => 'savings_and_shares', 'en' => 'Savings & Shares', 'sw' => 'Akiba na Hisa'],
            ['key' => 'total_contribution', 'en' => 'Total Contribution', 'sw' => 'Jumla ya Mchango'],
            ['key' => 'preview_statement', 'en' => 'Preview Statement', 'sw' => 'Hakiki Taarifa'],
            ['key' => 'no_statements', 'en' => 'No statements yet', 'sw' => 'Bado huna taarifa'],
            
            // Statement Details
            ['key' => 'back_to_statements', 'en' => 'Back to Statements', 'sw' => 'Rudi kwenye Taarifa'],
            ['key' => 'print_statement', 'en' => 'Print Statement', 'sw' => 'Chapa Taarifa'],
            ['key' => 'download_official_pdf', 'en' => 'Download Official PDF', 'sw' => 'Pakua PDF Rasmi'],
            ['key' => 'member_details', 'en' => 'Member Details', 'sw' => 'Maelezo ya Mwanachama'],
            ['key' => 'full_name', 'en' => 'Full Name', 'sw' => 'Jina Kamili'],
            ['key' => 'member_id', 'en' => 'Member ID', 'sw' => 'Namba ya Mwanachama'],
            ['key' => 'email_address', 'en' => 'Email Address', 'sw' => 'Barua Pepe'],
            ['key' => 'issuing_entity', 'en' => 'Issuing Entity', 'sw' => 'Taasisi Inayotoa'],
            ['key' => 'contribution_breakdown', 'en' => 'Contribution Breakdown', 'sw' => 'Mchanganuo wa Mchango'],
            ['key' => 'description', 'en' => 'Description', 'sw' => 'Maelezo'],
            ['key' => 'amount_tzs', 'en' => 'Amount (TZS)', 'sw' => 'Kiasi (TZS)'],
            ['key' => 'monthly_savings_akiba', 'en' => 'Monthly Savings (Akiba)', 'sw' => 'Akiba ya Mwezi'],
            ['key' => 'shares_hisa', 'en' => 'Shares (Hisa)', 'sw' => 'Hisa'],
            ['key' => 'welfare_fund', 'en' => 'Welfare Fund (Mfuko wa Jamii)', 'sw' => 'Mfuko wa Jamii'],
            ['key' => 'loan_principal_repayment', 'en' => 'Loan Principal Repayment', 'sw' => 'Rejesho la Mkopo (Mali)'],
            ['key' => 'loan_interest_repayment', 'en' => 'Loan Interest Repayment', 'sw' => 'Rejesho la Mkopo (Riba)'],
            ['key' => 'fines_penalties', 'en' => 'Fines / Penalties', 'sw' => 'Faini'],
            ['key' => 'system_message', 'en' => 'Message from External System', 'sw' => 'Ujumbe kutoka Mfumo wa Nje'],
            ['key' => 'digitally_verified', 'en' => 'Digitally Verified', 'sw' => 'Imethibitishwa Kidijitali'],
            ['key' => 'back_to_dashboard', 'en' => 'Back to Dashboard', 'sw' => 'Rudi Dashibodi'],
        ];

        foreach ($translations as $t) {
            Translation::updateOrCreate(['key' => $t['key']], $t);
        }

        Cache::forget('translations');
    }
}
