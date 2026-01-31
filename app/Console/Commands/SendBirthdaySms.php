<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\SmsMessageTemplate;
use App\Services\SmsNotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SendBirthdaySms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:send-birthday';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send birthday SMS to users with auto-check for SWF membership';

    protected SmsNotificationService $smsService;

    public function __construct(SmsNotificationService $smsService)
    {
        parent::__construct();
        $this->smsService = $smsService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting birthday SMS sending process...');

        $today = Carbon::today();
        $sentCount = 0;
        $skippedCount = 0;
        $failedCount = 0;

        // Find users whose birthday is today
        $users = User::whereNotNull('date_of_birth')
            ->whereNotNull('phone')
            ->where('sms_consent', true)
            ->where(function ($query) use ($today) {
                $query->whereRaw('DAY(date_of_birth) = ?', [$today->day])
                    ->whereRaw('MONTH(date_of_birth) = ?', [$today->month]);
            })
            ->where(function ($query) use ($today) {
                $query->whereNull('last_birthday_sms_date')
                    ->orWhere('last_birthday_sms_date', '!=', $today->toDateString());
            })
            ->get();

        $this->info("Found {$users->count()} users with birthdays today.");

        foreach ($users as $user) {
            try {
                // Check if user is SWF member
                $isSwfMember = $this->isSwfMember($user);

                // Get appropriate template
                $template = $this->getBirthdayTemplate($isSwfMember, $user->preferred_language ?? 'sw');

                // Replace variables
                $message = $this->formatBirthdayMessage($template, $user, $isSwfMember);

                // Send SMS
                $result = $this->smsService->sendSms(
                    $user->phone,
                    $message,
                    [
                        'template_id' => null,
                        'saving_behavior' => null,
                    ]
                );

                if ($result['success']) {
                    // Update last_birthday_sms_date
                    $user->update(['last_birthday_sms_date' => $today->toDateString()]);
                    $sentCount++;
                    $this->info("✓ Sent birthday SMS to {$user->name} ({$user->phone})");
                } else {
                    $failedCount++;
                    $this->error("✗ Failed to send SMS to {$user->name}: {$result['message'] ?? 'Unknown error'}");
                    Log::error("Birthday SMS failed for user {$user->id}: " . ($result['message'] ?? 'Unknown error'));
                }

            } catch (\Exception $e) {
                $failedCount++;
                $this->error("✗ Error processing {$user->name}: {$e->getMessage()}");
                Log::error("Birthday SMS error for user {$user->id}: " . $e->getMessage());
            }
        }

        $this->info("\n=== Summary ===");
        $this->info("Sent: {$sentCount}");
        $this->info("Skipped: {$skippedCount}");
        $this->info("Failed: {$failedCount}");

        return Command::SUCCESS;
    }

    /**
     * Check if user is a SWF Fund member
     */
    protected function isSwfMember(User $user): bool
    {
        // Check if user has swf_member flag set
        if ($user->swf_member) {
            return true;
        }

        // Check if user has active social welfare records
        return $user->socialWelfares()
            ->whereIn('status', ['approved', 'disbursed', 'completed'])
            ->exists();
    }

    /**
     * Get birthday SMS template
     */
    protected function getBirthdayTemplate(bool $isSwfMember, string $language = 'sw'): string
    {
        if ($isSwfMember) {
            // Member template
            return "🎉 Happy Birthday! 🎂\n\nFEEDTAN inakutakia afya njema, mafanikio na maisha yenye baraka tele.\n\nAsante kwa kuwa mwanachama wa SWF Fund.";
        } else {
            // Non-member template with invite
            return "🎉 Happy Birthday! 🎂\n\nFEEDTAN inakutakia afya njema, mafanikio na maisha yenye baraka tele.\n\n👉 Bado hujajiunga na SWF Fund. Jiunge leo unufaike na usalama wa kifedha na fursa zaidi za maendeleo.";
        }
    }

    /**
     * Format birthday message with user name
     */
    protected function formatBirthdayMessage(string $template, User $user, bool $isSwfMember): string
    {
        $firstName = explode(' ', $user->name)[0];
        
        // Replace name placeholder if exists
        $message = str_replace('{{name}}', $firstName, $template);
        $message = str_replace('{{full_name}}', $user->name, $message);

        return $message;
    }
}
