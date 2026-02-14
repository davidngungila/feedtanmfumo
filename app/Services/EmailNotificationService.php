<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;

class EmailNotificationService
{
    /**
     * Reload mail configuration from database before sending
     */
    protected function reloadMailConfig(): void
    {
        try {
            $settings = Setting::getByGroup('communication');

            if (isset($settings['mail_mailer']) && $settings['mail_mailer']->value) {
                Config::set('mail.default', $settings['mail_mailer']->value);
            }

            if (isset($settings['mail_host']) && $settings['mail_host']->value) {
                Config::set('mail.mailers.smtp.host', $settings['mail_host']->value);
            }

            if (isset($settings['mail_port'])) {
                Config::set('mail.mailers.smtp.port', $settings['mail_port']->value ?? 587);
            }

            if (isset($settings['mail_username']) && $settings['mail_username']->value) {
                Config::set('mail.mailers.smtp.username', $settings['mail_username']->value);
            }

            if (isset($settings['mail_password']) && $settings['mail_password']->value) {
                Config::set('mail.mailers.smtp.password', $settings['mail_password']->value);
            }

            if (isset($settings['mail_encryption']) && $settings['mail_encryption']->value) {
                Config::set('mail.mailers.smtp.encryption', $settings['mail_encryption']->value);
            } else {
                Config::set('mail.mailers.smtp.encryption', 'tls');
            }

            if (isset($settings['mail_from_address']) && $settings['mail_from_address']->value) {
                Config::set('mail.from.address', $settings['mail_from_address']->value);
            }

            if (isset($settings['mail_from_name']) && $settings['mail_from_name']->value) {
                Config::set('mail.from.name', $settings['mail_from_name']->value);
            }
        } catch (\Exception $e) {
            Log::warning('Failed to reload mail config: '.$e->getMessage());
        }
    }

    /**
     * Get organization information for email headers
     */
    public function getOrganizationInfo(): array
    {
        $settings = Setting::getByGroup('communication');

        // Use primary email if available, otherwise fallback to mail_from_address
        $primaryEmail = $settings['mail_primary_email']->value ?? null;
        $fallbackEmail = $settings['mail_from_address']->value ?? config('mail.from.address');
        $fromEmail = $primaryEmail ?: $fallbackEmail;

        // Get additional emails
        $additionalEmails = [];
        if (isset($settings['mail_additional_emails']) && $settings['mail_additional_emails']->value) {
            $additionalEmails = array_map('trim', explode(',', $settings['mail_additional_emails']->value));
            $additionalEmails = array_filter($additionalEmails, function ($email) {
                return filter_var($email, FILTER_VALIDATE_EMAIL);
            });
        }

        return [
            'name' => $settings['organization_name']->value ?? 'FeedTan Community Microfinance Group',
            'po_box' => $settings['organization_po_box']->value ?? 'P.O.Box 7744',
            'address' => $settings['organization_address']->value ?? 'Ushirika Sokoine Road',
            'city' => $settings['organization_city']->value ?? 'Moshi',
            'region' => $settings['organization_region']->value ?? 'Kilimanjaro',
            'country' => $settings['organization_country']->value ?? 'Tanzania',
            'from_name' => $settings['mail_from_name']->value ?? 'FeedTan Community Microfinance Group',
            'from_email' => $fromEmail,
            'primary_email' => $primaryEmail,
            'additional_emails' => $additionalEmails,
        ];
    }

    /**
     * Get formatted organization address for email footer
     */
    public function getFormattedAddress(): string
    {
        $info = $this->getOrganizationInfo();

        return "{$info['name']}\n{$info['po_box']}, {$info['address']}\n{$info['city']}, {$info['region']}, {$info['country']}";
    }

    /**
     * Send OTP notification via email
     */
    public function sendOtpNotification(User $user, string $otpCode): bool
    {
        try {
            $this->reloadMailConfig();
            $orgInfo = $this->getOrganizationInfo();
            $address = $this->getFormattedAddress();

            $subject = "Login OTP Code - {$orgInfo['name']}";
            $htmlBody = $this->formatOtpEmail($user, $otpCode, $address, $orgInfo);

            Mail::html($htmlBody, function ($mail) use ($user, $subject, $orgInfo) {
                $mail->to($user->email, $user->name)
                    ->subject($subject)
                    ->from($orgInfo['from_email'], $orgInfo['from_name']);
            });

            Log::info("OTP email sent to {$user->email} for user ID {$user->id}.");

            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send OTP email to {$user->email}: ".$e->getMessage());
            Log::error('Stack trace: '.$e->getTraceAsString());

            return false;
        }
    }

    /**
     * Format OTP email message (HTML)
     */
    protected function formatOtpEmail(User $user, string $otpCode, string $address, array $orgInfo): string
    {
        return View::make('emails.otp', [
            'name' => $user->name,
            'otpCode' => $otpCode,
            'organizationInfo' => $orgInfo,
        ])->render();
    }

    /**
     * Send loan approval notification
     */
    public function sendLoanApprovalNotification(User $user, array $loanDetails): bool
    {
        try {
            $this->reloadMailConfig();
            $orgInfo = $this->getOrganizationInfo();
            $address = $this->getFormattedAddress();

            $subject = "Loan Application Approved - {$orgInfo['name']}";
            $message = $this->formatLoanApprovalEmail($user, $loanDetails, $address);

            Mail::raw($message, function ($mail) use ($user, $subject, $orgInfo) {
                $mail->to($user->email, $user->name)
                    ->subject($subject)
                    ->from($orgInfo['from_email'], $orgInfo['from_name']);
            });

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send loan approval email: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Send loan disbursement notification
     */
    public function sendLoanDisbursementNotification(User $user, array $loanDetails): bool
    {
        try {
            $this->reloadMailConfig();
            $orgInfo = $this->getOrganizationInfo();
            $address = $this->getFormattedAddress();

            $subject = "Loan Disbursed - {$orgInfo['name']}";
            $message = $this->formatLoanDisbursementEmail($user, $loanDetails, $address);

            Mail::raw($message, function ($mail) use ($user, $subject, $orgInfo) {
                $mail->to($user->email, $user->name)
                    ->subject($subject)
                    ->from($orgInfo['from_email'], $orgInfo['from_name']);
            });

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send loan disbursement email: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Send loan application notification
     */
    public function sendLoanApplicationNotification(User $user, array $loanDetails): bool
    {
        try {
            $this->reloadMailConfig();
            $orgInfo = $this->getOrganizationInfo();
            $address = $this->getFormattedAddress();

            $subject = "Loan Application Submitted - {$orgInfo['name']}";
            $message = $this->formatLoanApplicationEmail($user, $loanDetails, $address);

            Mail::raw($message, function ($mail) use ($user, $subject, $orgInfo) {
                $mail->to($user->email, $user->name)
                    ->subject($subject)
                    ->from($orgInfo['from_email'], $orgInfo['from_name']);
            });

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send loan application email: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Format loan application email
     */
    protected function formatLoanApplicationEmail(User $user, array $loanDetails, string $address): string
    {
        $amount = number_format($loanDetails['principal_amount'] ?? 0, 0);
        $loanNumber = $loanDetails['loan_number'] ?? 'N/A';
        $interestRate = $loanDetails['interest_rate'] ?? 0;
        $term = $loanDetails['term_months'] ?? 0;
        $totalAmount = number_format($loanDetails['total_amount'] ?? 0, 0);

        return "Dear {$user->name},

Thank you for submitting your loan application to {$this->getOrganizationInfo()['name']}.

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

LOAN APPLICATION DETAILS:
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Loan Number:        {$loanNumber}
Principal Amount:   {$amount} TZS
Interest Rate:      {$interestRate}% per annum
Loan Term:          {$term} months
Total Amount:       {$totalAmount} TZS
Purpose:            ".($loanDetails['purpose'] ?? 'N/A')."

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

NEXT STEPS:
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Your loan application has been received and is now pending review by our loan committee.

We will review your application and notify you of the decision within 3-5 business days.

If you have any questions, please contact us at:
Email: {$this->getOrganizationInfo()['email']}
Phone: {$this->getOrganizationInfo()['phone']}

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Thank you for choosing {$this->getOrganizationInfo()['name']}.

Best regards,
{$this->getOrganizationInfo()['name']}
{$address}";
    }

    /**
     * Send payment reminder notification
     */
    public function sendPaymentReminderNotification(User $user, array $paymentDetails): bool
    {
        try {
            $this->reloadMailConfig();
            $orgInfo = $this->getOrganizationInfo();
            $address = $this->getFormattedAddress();

            $subject = "Payment Reminder - {$orgInfo['name']}";
            $message = $this->formatPaymentReminderEmail($user, $paymentDetails, $address);

            Mail::raw($message, function ($mail) use ($user, $subject, $orgInfo) {
                $mail->to($user->email, $user->name)
                    ->subject($subject)
                    ->from($orgInfo['from_email'], $orgInfo['from_name']);
            });

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send payment reminder email: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Send savings account notification
     */
    public function sendSavingsAccountNotification(User $user, string $event, array $accountDetails): bool
    {
        try {
            $this->reloadMailConfig();
            $orgInfo = $this->getOrganizationInfo();
            $address = $this->getFormattedAddress();

            $subjects = [
                'created' => "Savings Account Opened - {$orgInfo['name']}",
                'deposit' => "Deposit Confirmation - {$orgInfo['name']}",
                'withdrawal' => "Withdrawal Confirmation - {$orgInfo['name']}",
                'interest' => "Interest Posted - {$orgInfo['name']}",
            ];

            $subject = $subjects[$event] ?? "Savings Account Update - {$orgInfo['name']}";
            $message = $this->formatSavingsAccountEmail($user, $event, $accountDetails, $address);

            Mail::raw($message, function ($mail) use ($user, $subject, $orgInfo) {
                $mail->to($user->email, $user->name)
                    ->subject($subject)
                    ->from($orgInfo['from_email'], $orgInfo['from_name']);
            });

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send savings account email: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Send investment notification
     */
    public function sendInvestmentNotification(User $user, string $event, array $investmentDetails): bool
    {
        try {
            $this->reloadMailConfig();
            $orgInfo = $this->getOrganizationInfo();

            $subjects = [
                'created' => "Investment Enrollment Confirmed - {$orgInfo['name']}",
                'matured' => "Investment Matured - {$orgInfo['name']}",
                'profit' => "Profit Distribution - {$orgInfo['name']}",
                'topup' => "Investment Top-up Confirmed - {$orgInfo['name']}",
            ];

            $messages = [
                'created' => 'Uwekezaji wako umeandikishwa kwa mafanikio.',
                'matured' => 'Hongera! Uwekezaji wako umekamilika.',
                'profit' => 'Faida imegawanywa kwenye akaunti yako ya uwekezaji.',
                'topup' => 'Ongezeko la uwekezaji wako limetekelezwa kwa mafanikio.',
            ];

            $icons = [
                'created' => '✅',
                'matured' => '🎉',
                'profit' => '💰',
                'topup' => '📈',
            ];

            $cardTitles = [
                'created' => 'Uwekezaji Umeandikishwa',
                'matured' => 'Uwekezaji Umekamilika',
                'profit' => 'Faida Imegawanywa',
                'topup' => 'Ongezeko la Uwekezaji',
            ];

            $subject = $subjects[$event] ?? "Investment Update - {$orgInfo['name']}";
            $mainMessage = $messages[$event] ?? 'Uwekezaji wako umesasishwa.';

            $details = $this->formatInvestmentDetails($event, $investmentDetails);

            $htmlBody = View::make('emails.investment', [
                'name' => $user->name,
                'mainMessage' => $mainMessage,
                'details' => $details,
                'icon' => $icons[$event] ?? '📈',
                'cardTitle' => $cardTitles[$event] ?? 'Uwekezaji Wako',
                'organizationInfo' => $orgInfo,
            ])->render();

            Mail::html($htmlBody, function ($mail) use ($user, $subject, $orgInfo) {
                $mail->to($user->email, $user->name)
                    ->subject($subject)
                    ->from($orgInfo['from_email'], $orgInfo['from_name']);

                // Add additional emails as CC if configured
                if (! empty($orgInfo['additional_emails'])) {
                    foreach ($orgInfo['additional_emails'] as $additionalEmail) {
                        $mail->cc($additionalEmail);
                    }
                }
            });

            Log::info("Investment email sent to {$user->email} for user ID {$user->id}.");

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send investment email: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Format investment details for email
     */
    protected function formatInvestmentDetails(string $event, array $investmentDetails): array
    {
        $details = [];

        switch ($event) {
            case 'created':
                $details['Namba ya Uwekezaji'] = $investmentDetails['investment_number'] ?? 'N/A';
                $details['Aina ya Mpango'] = $investmentDetails['plan_type'] ?? 'N/A';
                $details['Kiasi cha Msingi'] = number_format($investmentDetails['principal_amount'] ?? 0, 0).' TZS';
                $details['Tarehe ya Kukamilika'] = isset($investmentDetails['maturity_date']) ? date('d/m/Y', strtotime($investmentDetails['maturity_date'])) : 'N/A';
                $details['Faida Inayotarajiwa'] = number_format($investmentDetails['expected_return'] ?? 0, 0).' TZS';
                break;
            case 'matured':
                $details['Namba ya Uwekezaji'] = $investmentDetails['investment_number'] ?? 'N/A';
                $details['Kiasi cha Msingi'] = number_format($investmentDetails['principal_amount'] ?? 0, 0).' TZS';
                $details['Jumla ya Faida'] = number_format($investmentDetails['total_profit'] ?? 0, 0).' TZS';
                $details['Jumla ya Kurudi'] = number_format($investmentDetails['total_return'] ?? 0, 0).' TZS';
                $details['Tarehe ya Kukamilika'] = isset($investmentDetails['maturity_date']) ? date('d/m/Y', strtotime($investmentDetails['maturity_date'])) : date('d/m/Y');
                break;
            case 'profit':
                $details['Namba ya Uwekezaji'] = $investmentDetails['investment_number'] ?? 'N/A';
                $details['Kiasi cha Faida'] = number_format($investmentDetails['profit_amount'] ?? 0, 0).' TZS';
                $details['Salio Jipya'] = number_format($investmentDetails['new_balance'] ?? 0, 0).' TZS';
                $details['Tarehe ya Mgawanyo'] = isset($investmentDetails['distribution_date']) ? date('d/m/Y', strtotime($investmentDetails['distribution_date'])) : date('d/m/Y');
                break;
            case 'topup':
                $details['Namba ya Uwekezaji'] = $investmentDetails['investment_number'] ?? 'N/A';
                $details['Kiasi cha Ongezeko'] = number_format($investmentDetails['topup_amount'] ?? 0, 0).' TZS';
                $details['Msingi Mpya'] = number_format($investmentDetails['new_principal'] ?? 0, 0).' TZS';
                $details['Tarehe ya Muamala'] = isset($investmentDetails['transaction_date']) ? date('d/m/Y', strtotime($investmentDetails['transaction_date'])) : date('d/m/Y');
                break;
            default:
                $details['Namba ya Uwekezaji'] = $investmentDetails['investment_number'] ?? 'N/A';
        }

        return $details;
    }

    /**
     * Send deposit statement email
     */
    public function sendDepositStatementEmail(User $user, string $pdfLink, string $period, ?string $periodStart = null): bool
    {
        try {
            $this->reloadMailConfig();
            $orgInfo = $this->getOrganizationInfo();

            $subject = "Deposit Statement for {$user->name} - {$period}";

            // Format period date
            try {
                $periodDate = \Carbon\Carbon::parse($period);
                $formattedPeriod = $periodDate->format('D M d, Y');
            } catch (\Exception $e) {
                $formattedPeriod = $period; // Use as-is if parsing fails
            }

            // Use the statement email template
            $htmlBody = View::make('emails.statement', [
                'name' => $user->name,
                'pdfLink' => $pdfLink,
                'period' => $formattedPeriod,
                'periodStart' => $periodStart ?? 'Machi 2025',
                'organizationInfo' => $orgInfo,
            ])->render();

            Mail::html($htmlBody, function ($mail) use ($user, $subject, $orgInfo) {
                $mail->to($user->email, $user->name)
                    ->subject($subject)
                    ->from($orgInfo['from_email'], $orgInfo['from_name'] ?? 'FeedTan CMG-Deposit Statement');

                // Add additional emails as CC if configured
                if (! empty($orgInfo['additional_emails'])) {
                    foreach ($orgInfo['additional_emails'] as $additionalEmail) {
                        $mail->cc($additionalEmail);
                    }
                }
            });

            Log::info("Deposit statement email sent to {$user->email} for user ID {$user->id}.");

            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send deposit statement email to {$user->email}: ".$e->getMessage());

            return false;
        }
    }

    /**
     * Send welfare notification
     */
    public function sendWelfareNotification(User $user, string $event, array $welfareDetails): bool
    {
        try {
            $this->reloadMailConfig();
            $orgInfo = $this->getOrganizationInfo();

            $subjects = [
                'contribution' => "Welfare Contribution Received - {$orgInfo['name']}",
                'claim_approved' => "Welfare Claim Approved - {$orgInfo['name']}",
                'claim_disbursed' => "Welfare Benefit Disbursed - {$orgInfo['name']}",
            ];

            $messages = [
                'contribution' => 'Mchango wako wa ustawi umepokelewa kwa mafanikio.',
                'claim_approved' => 'Ombi lako la ustawi limeidhinishwa.',
                'claim_disbursed' => 'Faida ya ustawi imetolewa kwenye akaunti yako.',
            ];

            $icons = [
                'contribution' => '💳',
                'claim_approved' => '✅',
                'claim_disbursed' => '💰',
            ];

            $cardTitles = [
                'contribution' => 'Mchango Umeripokelewa',
                'claim_approved' => 'Ombi Limeidhinishwa',
                'claim_disbursed' => 'Faida Imetolewa',
            ];

            $subject = $subjects[$event] ?? "Welfare Update - {$orgInfo['name']}";
            $mainMessage = $messages[$event] ?? 'Hali ya ustawi yako imesasishwa.';

            $details = $this->formatWelfareDetails($event, $welfareDetails);

            $htmlBody = View::make('emails.welfare', [
                'name' => $user->name,
                'mainMessage' => $mainMessage,
                'details' => $details,
                'icon' => $icons[$event] ?? '🤝',
                'cardTitle' => $cardTitles[$event] ?? 'Ustawi wa Jamii',
                'organizationInfo' => $orgInfo,
            ])->render();

            Mail::html($htmlBody, function ($mail) use ($user, $subject, $orgInfo) {
                $mail->to($user->email, $user->name)
                    ->subject($subject)
                    ->from($orgInfo['from_email'], $orgInfo['from_name']);

                // Add additional emails as CC if configured
                if (! empty($orgInfo['additional_emails'])) {
                    foreach ($orgInfo['additional_emails'] as $additionalEmail) {
                        $mail->cc($additionalEmail);
                    }
                }
            });

            Log::info("Welfare email sent to {$user->email} for user ID {$user->id}.");

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send welfare email: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Format welfare details for email
     */
    protected function formatWelfareDetails(string $event, array $welfareDetails): array
    {
        $details = [];

        switch ($event) {
            case 'contribution':
                $details['Kiasi cha Mchango'] = number_format($welfareDetails['amount'] ?? 0, 0).' TZS';
                $details['Tarehe ya Mchango'] = isset($welfareDetails['contribution_date']) ? date('d/m/Y', strtotime($welfareDetails['contribution_date'])) : date('d/m/Y');
                $details['Jumla ya Michango'] = number_format($welfareDetails['total_contributions'] ?? 0, 0).' TZS';
                break;
            case 'claim_approved':
                $details['Namba ya Ombi'] = $welfareDetails['claim_number'] ?? 'N/A';
                $details['Aina ya Ombi'] = $welfareDetails['claim_type'] ?? 'N/A';
                $details['Kiasi'] = number_format($welfareDetails['amount'] ?? 0, 0).' TZS';
                $details['Tarehe ya Idhini'] = isset($welfareDetails['approval_date']) ? date('d/m/Y', strtotime($welfareDetails['approval_date'])) : date('d/m/Y');
                break;
            case 'claim_disbursed':
                $details['Namba ya Ombi'] = $welfareDetails['claim_number'] ?? 'N/A';
                $details['Kiasi Kilichotolewa'] = number_format($welfareDetails['amount'] ?? 0, 0).' TZS';
                $details['Tarehe ya Kutolewa'] = isset($welfareDetails['disbursement_date']) ? date('d/m/Y', strtotime($welfareDetails['disbursement_date'])) : date('d/m/Y');
                $details['Njia ya Malipo'] = $welfareDetails['payment_method'] ?? 'N/A';
                break;
            default:
                $details['Hali'] = 'Imehakikishwa';
        }

        return $details;
    }

    /**
     * Format loan approval email
     */
    protected function formatLoanApprovalEmail(User $user, array $loanDetails, string $address): string
    {
        $amount = number_format($loanDetails['amount'] ?? 0, 0);
        $loanNumber = $loanDetails['loan_number'] ?? 'N/A';
        $interestRate = $loanDetails['interest_rate'] ?? 0;
        $term = $loanDetails['term_months'] ?? 0;
        $monthlyPayment = number_format($loanDetails['monthly_payment'] ?? 0, 0);

        return "Dear {$user->name},

We are pleased to inform you that your loan application has been APPROVED.

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

LOAN DETAILS:
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Loan Number:        {$loanNumber}
Loan Amount:        {$amount} TZS
Interest Rate:      {$interestRate}% per annum
Loan Term:          {$term} months
Monthly Payment:    {$monthlyPayment} TZS
Purpose:            ".($loanDetails['purpose'] ?? 'N/A').'
Disbursement Date:  '.($loanDetails['disbursement_date'] ?? 'To be scheduled')."

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

NEXT STEPS:
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

1. Please visit our office within 7 days to complete the loan processing
2. Bring valid identification documents
3. Review and sign the loan agreement
4. Complete any additional requirements as specified

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

IMPORTANT REMINDERS:
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

• Ensure timely monthly payments to maintain good standing
• Contact us immediately if you encounter any repayment difficulties
• Keep your loan account information confidential

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

If you have any questions or need assistance, please contact us.

Best regards,
{$address}";
    }

    /**
     * Format loan disbursement email
     */
    protected function formatLoanDisbursementEmail(User $user, array $loanDetails, string $address): string
    {
        $amount = number_format($loanDetails['amount'] ?? 0, 0);
        $loanNumber = $loanDetails['loan_number'] ?? 'N/A';
        $disbursementDate = $loanDetails['disbursement_date'] ?? date('Y-m-d');
        $accountNumber = $loanDetails['account_number'] ?? 'N/A';

        return "Dear {$user->name},

Your loan has been successfully disbursed to your account.

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

DISBURSEMENT DETAILS:
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Loan Number:           {$loanNumber}
Disbursed Amount:       {$amount} TZS
Disbursement Date:      {$disbursementDate}
Credited to Account:    {$accountNumber}
Transaction Reference: ".($loanDetails['transaction_ref'] ?? 'N/A').'

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

REPAYMENT INFORMATION:
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

First Payment Due:     '.($loanDetails['first_payment_due'] ?? 'N/A').'
Monthly Payment:       '.number_format($loanDetails['monthly_payment'] ?? 0, 0).' TZS
Total Amount to Repay:  '.number_format($loanDetails['total_repayment'] ?? 0, 0)." TZS

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Please ensure timely payments to maintain your credit standing.

Best regards,
{$address}";
    }

    /**
     * Format payment reminder email
     */
    protected function formatPaymentReminderEmail(User $user, array $paymentDetails, string $address): string
    {
        $amount = number_format($paymentDetails['amount_due'] ?? 0, 0);
        $dueDate = $paymentDetails['due_date'] ?? 'N/A';
        $loanNumber = $paymentDetails['loan_number'] ?? 'N/A';
        $daysOverdue = $paymentDetails['days_overdue'] ?? 0;

        $overdueText = $daysOverdue > 0
            ? "\n⚠️  This payment is {$daysOverdue} day(s) OVERDUE. Please make payment immediately to avoid penalties."
            : '';

        return "Dear {$user->name},

This is a reminder that you have an upcoming loan payment.

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

PAYMENT REMINDER:
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Loan Number:        {$loanNumber}
Amount Due:         {$amount} TZS
Due Date:           {$dueDate}
".($daysOverdue > 0 ? "Days Overdue:      {$daysOverdue} days" : '')."
{$overdueText}

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

PAYMENT METHODS:
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

• Visit our office during business hours
• Mobile money transfer (M-Pesa, Tigo Pesa, Airtel Money)
• Bank transfer to our official account

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Please make your payment on or before the due date to avoid late fees and maintain your credit standing.

Best regards,
{$address}";
    }

    /**
     * Format savings account email
     */
    protected function formatSavingsAccountEmail(User $user, string $event, array $accountDetails, string $address): string
    {
        $accountNumber = $accountDetails['account_number'] ?? 'N/A';
        $accountType = $accountDetails['account_type'] ?? 'N/A';

        $messages = [
            'created' => "Your {$accountType} savings account has been successfully opened.",
            'deposit' => 'Your deposit has been successfully processed.',
            'withdrawal' => 'Your withdrawal request has been processed.',
            'interest' => 'Interest has been posted to your savings account.',
        ];

        $mainMessage = $messages[$event] ?? 'Your savings account has been updated.';

        $details = match ($event) {
            'created' => "Account Number:     {$accountNumber}\nAccount Type:       {$accountType}\nOpening Balance:    ".number_format($accountDetails['opening_balance'] ?? 0, 0).' TZS',
            'deposit' => "Account Number:     {$accountNumber}\nDeposit Amount:     ".number_format($accountDetails['amount'] ?? 0, 0)." TZS\nNew Balance:        ".number_format($accountDetails['new_balance'] ?? 0, 0)." TZS\nTransaction Date:   ".($accountDetails['transaction_date'] ?? date('Y-m-d')),
            'withdrawal' => "Account Number:     {$accountNumber}\nWithdrawal Amount:  ".number_format($accountDetails['amount'] ?? 0, 0)." TZS\nNew Balance:        ".number_format($accountDetails['new_balance'] ?? 0, 0)." TZS\nTransaction Date:   ".($accountDetails['transaction_date'] ?? date('Y-m-d')),
            'interest' => "Account Number:     {$accountNumber}\nInterest Amount:    ".number_format($accountDetails['interest_amount'] ?? 0, 0)." TZS\nNew Balance:        ".number_format($accountDetails['new_balance'] ?? 0, 0)." TZS\nInterest Period:    ".($accountDetails['period'] ?? 'N/A'),
            default => "Account Number:     {$accountNumber}",
        };

        return "Dear {$user->name},

{$mainMessage}

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

ACCOUNT DETAILS:
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

{$details}

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

If you have any questions, please contact us.

Best regards,
{$address}";
    }

    /**
     * Format investment email
     */
    protected function formatInvestmentEmail(User $user, string $event, array $investmentDetails, string $address): string
    {
        $investmentNumber = $investmentDetails['investment_number'] ?? 'N/A';
        $planType = $investmentDetails['plan_type'] ?? 'N/A';

        $messages = [
            'created' => "Your {$planType} investment has been successfully enrolled.",
            'matured' => 'Congratulations! Your investment has matured.',
            'profit' => 'Profit has been distributed to your investment account.',
            'topup' => 'Your investment top-up has been processed successfully.',
        ];

        $mainMessage = $messages[$event] ?? 'Your investment has been updated.';

        $details = match ($event) {
            'created' => "Investment Number:  {$investmentNumber}\nPlan Type:          {$planType}\nPrincipal Amount:   ".number_format($investmentDetails['principal_amount'] ?? 0, 0)." TZS\nMaturity Date:      ".($investmentDetails['maturity_date'] ?? 'N/A')."\nExpected Return:    ".number_format($investmentDetails['expected_return'] ?? 0, 0).' TZS',
            'matured' => "Investment Number:  {$investmentNumber}\nPrincipal Amount:   ".number_format($investmentDetails['principal_amount'] ?? 0, 0)." TZS\nTotal Profit:       ".number_format($investmentDetails['total_profit'] ?? 0, 0)." TZS\nTotal Return:       ".number_format($investmentDetails['total_return'] ?? 0, 0)." TZS\nMaturity Date:      ".($investmentDetails['maturity_date'] ?? date('Y-m-d')),
            'profit' => "Investment Number:  {$investmentNumber}\nProfit Amount:      ".number_format($investmentDetails['profit_amount'] ?? 0, 0)." TZS\nNew Balance:        ".number_format($investmentDetails['new_balance'] ?? 0, 0)." TZS\nDistribution Date:  ".($investmentDetails['distribution_date'] ?? date('Y-m-d')),
            'topup' => "Investment Number:  {$investmentNumber}\nTop-up Amount:      ".number_format($investmentDetails['topup_amount'] ?? 0, 0)." TZS\nNew Principal:      ".number_format($investmentDetails['new_principal'] ?? 0, 0)." TZS\nTransaction Date:   ".($investmentDetails['transaction_date'] ?? date('Y-m-d')),
            default => "Investment Number:  {$investmentNumber}",
        };

        return "Dear {$user->name},

{$mainMessage}

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

INVESTMENT DETAILS:
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

{$details}

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

If you have any questions, please contact us.

Best regards,
{$address}";
    }

    /**
     * Format welfare email
     */
    protected function formatWelfareEmail(User $user, string $event, array $welfareDetails, string $address): string
    {
        $messages = [
            'contribution' => 'Your welfare contribution has been received and recorded.',
            'claim_approved' => 'Congratulations! Your welfare claim has been approved.',
            'claim_disbursed' => 'Your welfare benefit has been disbursed to your account.',
        ];

        $mainMessage = $messages[$event] ?? 'Your welfare account has been updated.';

        $details = match ($event) {
            'contribution' => 'Contribution Amount: '.number_format($welfareDetails['amount'] ?? 0, 0)." TZS\nNew Fund Balance:   ".number_format($welfareDetails['fund_balance'] ?? 0, 0)." TZS\nContribution Date:  ".($welfareDetails['contribution_date'] ?? date('Y-m-d')),
            'claim_approved' => 'Claim Type:         '.($welfareDetails['claim_type'] ?? 'N/A')."\nApproved Amount:    ".number_format($welfareDetails['approved_amount'] ?? 0, 0)." TZS\nApproval Date:      ".($welfareDetails['approval_date'] ?? date('Y-m-d')),
            'claim_disbursed' => 'Claim Type:         '.($welfareDetails['claim_type'] ?? 'N/A')."\nDisbursed Amount:   ".number_format($welfareDetails['disbursed_amount'] ?? 0, 0)." TZS\nDisbursement Date:  ".($welfareDetails['disbursement_date'] ?? date('Y-m-d'))."\nTransaction Ref:    ".($welfareDetails['transaction_ref'] ?? 'N/A'),
            default => '',
        };

        return "Dear {$user->name},

{$mainMessage}

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

WELFARE DETAILS:
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

{$details}

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

If you have any questions, please contact us.

Best regards,
{$address}";
    }

    /**
     * Send welcome email with login credentials
     */
    public function sendWelcomeEmail(User $user, string $plainPassword): bool
    {
        try {
            $this->reloadMailConfig();
            $orgInfo = $this->getOrganizationInfo();

            $subject = "Welcome to {$orgInfo['name']} - Your Account Credentials";
            $htmlBody = $this->formatWelcomeEmail($user, $plainPassword, $orgInfo);

            Mail::html($htmlBody, function ($mail) use ($user, $subject, $orgInfo) {
                $mail->to($user->email, $user->name)
                    ->subject($subject)
                    ->from($orgInfo['from_email'], $orgInfo['from_name']);
            });

            Log::info("Welcome email sent to {$user->email} for user ID {$user->id}.");

            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send welcome email to {$user->email}: ".$e->getMessage());
            Log::error('Stack trace: '.$e->getTraceAsString());

            return false;
        }
    }

    /**
     * Format welcome email message (HTML)
     */
    protected function formatWelcomeEmail(User $user, string $plainPassword, array $orgInfo): string
    {
        $loginUrl = route('login');

        return View::make('emails.welcome', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $plainPassword,
            'loginUrl' => $loginUrl,
            'organizationInfo' => $orgInfo,
        ])->render();
    }

    /**
     * Send membership application submission email
     */
    public function sendMembershipSubmissionEmail(User $user): bool
    {
        try {
            $this->reloadMailConfig();
            $orgInfo = $this->getOrganizationInfo();

            $subject = "Thank You for Your Membership Application - {$orgInfo['name']}";
            $htmlBody = $this->formatMembershipSubmissionEmail($user, $orgInfo);

            Mail::html($htmlBody, function ($mail) use ($user, $subject, $orgInfo) {
                $mail->to($user->email, $user->name)
                    ->subject($subject)
                    ->from($orgInfo['from_email'], $orgInfo['from_name']);
            });

            Log::info("Membership submission email sent to {$user->email} for user ID {$user->id}.");

            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send membership submission email to {$user->email}: ".$e->getMessage());
            Log::error('Stack trace: '.$e->getTraceAsString());

            return false;
        }
    }

    /**
     * Format membership submission email message (HTML)
     */
    protected function formatMembershipSubmissionEmail(User $user, array $orgInfo): string
    {
        $dashboardUrl = route('member.dashboard');

        return View::make('emails.membership-submission', [
            'name' => $user->name,
            'membershipCode' => $user->membership_code ?? 'Pending',
            'dashboardUrl' => $dashboardUrl,
            'organizationInfo' => $orgInfo,
        ])->render();
    }

    /**
     * Send payment confirmation email
     */
    public function sendPaymentConfirmationEmail(\App\Models\PaymentConfirmation $paymentConfirmation): bool
    {
        try {
            $this->reloadMailConfig();
            $orgInfo = $this->getOrganizationInfo();

            $subject = "Uthibitisho wa Malipo - {$paymentConfirmation->member_name}";
            $htmlBody = $this->formatPaymentConfirmationEmail($paymentConfirmation, $orgInfo);

            // Convert header image to base64 for PDF
            $headerImagePath = public_path('header-mfumo.png');
            $headerBase64 = '';
            if (file_exists($headerImagePath)) {
                $type = pathinfo($headerImagePath, PATHINFO_EXTENSION);
                $data = file_get_contents($headerImagePath);
                $headerBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }

            // Generate PDF
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.payment-confirmation', [
                'paymentConfirmation' => $paymentConfirmation,
                'organizationInfo' => $orgInfo,
                'headerBase64' => $headerBase64
            ]);
            $pdfContent = $pdf->output();

            Mail::html($htmlBody, function ($mail) use ($paymentConfirmation, $subject, $orgInfo, $pdfContent) {
                $mail->to($paymentConfirmation->member_email, $paymentConfirmation->member_name)
                    ->subject($subject)
                    ->from($orgInfo['from_email'], $orgInfo['from_name'])
                    ->attachData($pdfContent, "Uthibitisho_wa_Malipo_{$paymentConfirmation->member_id}.pdf", [
                        'mime' => 'application/pdf',
                    ]);

                // Add CC emails
                $mail->cc('sigfridngereza@gmail.com');
                $mail->cc('elulandala@gmail.com');
                $mail->cc('witneysam21@gmail.com');
            });

            Log::info("Payment confirmation email with PDF sent to {$paymentConfirmation->member_email} for payment confirmation ID {$paymentConfirmation->id}.");

            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send payment confirmation email to {$paymentConfirmation->member_email}: ".$e->getMessage());
            Log::error('Stack trace: '.$e->getTraceAsString());

            return false;
        }
    }

    /**
     * Format payment confirmation email message (HTML)
     */
    protected function formatPaymentConfirmationEmail(\App\Models\PaymentConfirmation $paymentConfirmation, array $orgInfo): string
    {
        return View::make('emails.payment-confirmation', [
            'paymentConfirmation' => $paymentConfirmation,
            'organizationInfo' => $orgInfo,
        ])->render();
    }

    /**
     * Send bulk generated password notification
     */
    public function sendBulkPasswordNotification(User $user, string $password): bool
    {
        try {
            $this->reloadMailConfig();
            $orgInfo = $this->getOrganizationInfo();
            $address = $this->getFormattedAddress();

            $subject = "Taarifa za Kuingia Mfumo wa FeedTan - {$user->name}";
            
            $htmlBody = View::make('emails.password-reset', [
                'name' => $user->name,
                'email' => $user->email,
                'password' => $password,
                'organizationInfo' => $orgInfo,
            ])->render();

            Mail::html($htmlBody, function ($mail) use ($user, $subject, $orgInfo) {
                $mail->to($user->email, $user->name)
                    ->subject($subject)
                    ->from($orgInfo['from_email'], $orgInfo['from_name']);
            });

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send bulk password email: '.$e->getMessage());

            return false;
        }
    }
}

