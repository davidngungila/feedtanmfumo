<?php

namespace Database\Seeders;

use App\Models\SmsSetting;
use Illuminate\Database\Seeder;

class SmsSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Organization Settings
            ['category' => 'Organization', 'key' => 'Organization Name', 'value' => 'FeedTan CMG', 'description' => 'Name of the organization'],
            ['category' => 'Organization', 'key' => 'Organization Address', 'value' => 'P.O.Box 7744, Ushirika Sokoine Road, Moshi, Kilimanjaro, Tanzania', 'description' => 'Physical address'],
            ['category' => 'Organization', 'key' => 'Organization Email', 'value' => 'feedtan15@gmail.com', 'description' => 'Contact email'],
            ['category' => 'Organization', 'key' => 'Organization Website', 'value' => 'feedtancmg.org', 'description' => 'Website URL'],
            ['category' => 'Organization', 'key' => 'Organization Phone', 'value' => '+255622239304', 'description' => 'Contact phone number'],

            // Security Settings
            ['category' => 'Security', 'key' => 'Blacklisted Numbers', 'value' => '255612345678, 255687654321, 255711223344', 'description' => 'Comma-separated list of numbers to block'],

            // API Settings
            ['category' => 'API', 'key' => 'Rate Limits', 'value' => '{"max_per_minute":20,"delay_ms":1500,"max_per_hour":300}', 'description' => 'Rate limiting settings for API'],
            ['category' => 'API', 'key' => 'API Settings', 'value' => '{"retry_attempts":0,"timeout":30000}', 'description' => 'API connection settings'],

            // Scheduling Settings
            ['category' => 'Scheduling', 'key' => 'Scheduled Tasks', 'value' => '{"daily_send_time":"10:00","weekdays_only":true,"max_messages_per_day":200}', 'description' => 'Automated sending schedule'],

            // Messaging Settings
            ['category' => 'Messaging', 'key' => 'Message Settings', 'value' => '{"auto_truncate":true,"unicode_support":true,"max_length":160}', 'description' => 'Message handling settings'],
            ['category' => 'Messaging', 'key' => 'Auto Reply Messages', 'value' => '{"stop":"Reply STOP to unsubscribe","balance":"Reply BAL for account balance","help":"Reply HELP for assistance"}', 'description' => 'Auto-response messages'],
            ['category' => 'Messaging', 'key' => 'Message Customization', 'value' => '{"include_contact_info":true,"include_unsubscribe":true,"include_organization_info":true}', 'description' => 'Message content settings'],

            // Business Settings
            ['category' => 'Business', 'key' => 'Business Hours', 'value' => '{"start":"06:00","timezone":"Africa/Dar_es_Salaam","end":"21:00"}', 'description' => 'Allowed sending times'],
            ['category' => 'Business', 'key' => 'Holidays', 'value' => '["2024-01-01","2024-04-10","2024-12-25"]', 'description' => 'JSON array of holiday dates'],

            // System Settings
            ['category' => 'System', 'key' => 'Log Settings', 'value' => '{"retention_days":90,"archive_threshold":5000}', 'description' => 'Log management settings'],
            ['category' => 'System', 'key' => 'Backup Settings', 'value' => '{"auto_backup_days":7,"max_backups":10}', 'description' => 'Backup configuration'],

            // Notifications Settings
            ['category' => 'Notifications', 'key' => 'Notification Settings', 'value' => '{"email_alerts":true,"success_threshold":0.85,"admin_email":"admin@feedtancmg.org"}', 'description' => 'Alert settings'],

            // User Settings
            ['category' => 'User', 'key' => 'User Settings', 'value' => '{"default_language":"sw","timezone":"Africa/Dar_es_Salaam","currency":"TZS"}', 'description' => 'User preferences'],

            // Behavior Settings
            ['category' => 'Behavior', 'key' => 'Saving Behavior Settings', 'value' => '{"inconsistent_interval":7,"nonsaver_interval":3,"regular_interval":30,"sporadic_interval":14}', 'description' => 'Reminder intervals for different saving behaviors'],

            // Loans Settings
            ['category' => 'Loans', 'key' => 'Loan Settings', 'value' => '{"minimum_savings_period":3,"eligibility_multiplier":10}', 'description' => 'Loan eligibility parameters'],

            // Savings Settings
            ['category' => 'Savings', 'key' => 'Saving Targets', 'value' => '{"weekly_minimum":5000,"monthly_minimum":20000,"quarterly_bonus":5000}', 'description' => 'Saving target settings'],
        ];

        foreach ($settings as $setting) {
            SmsSetting::updateOrCreate(
                ['key' => $setting['key']],
                [
                    'category' => $setting['category'],
                    'value' => $setting['value'],
                    'description' => $setting['description'],
                    'last_updated' => now(),
                ]
            );
        }
    }
}
