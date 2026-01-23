<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
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
            Log::warning('Failed to reload mail config: ' . $e->getMessage());
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
            $additionalEmails = array_filter($additionalEmails, function($email) {
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
            Log::error('Failed to send loan approval email: ' . $e->getMessage());
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
            Log::error('Failed to send loan disbursement email: ' . $e->getMessage());
            return false;
        }
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
            Log::error('Failed to send payment reminder email: ' . $e->getMessage());
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
            Log::error('Failed to send savings account email: ' . $e->getMessage());
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
            $address = $this->getFormattedAddress();
            
            $subjects = [
                'created' => "Investment Enrollment Confirmed - {$orgInfo['name']}",
                'matured' => "Investment Matured - {$orgInfo['name']}",
                'profit' => "Profit Distribution - {$orgInfo['name']}",
                'topup' => "Investment Top-up Confirmed - {$orgInfo['name']}",
            ];
            
            $subject = $subjects[$event] ?? "Investment Update - {$orgInfo['name']}";
            $message = $this->formatInvestmentEmail($user, $event, $investmentDetails, $address);
            
            Mail::raw($message, function ($mail) use ($user, $subject, $orgInfo) {
                $mail->to($user->email, $user->name)
                     ->subject($subject)
                     ->from($orgInfo['from_email'], $orgInfo['from_name']);
            });
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send investment email: ' . $e->getMessage());
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
            $address = $this->getFormattedAddress();
            
            $subjects = [
                'contribution' => "Welfare Contribution Received - {$orgInfo['name']}",
                'claim_approved' => "Welfare Claim Approved - {$orgInfo['name']}",
                'claim_disbursed' => "Welfare Benefit Disbursed - {$orgInfo['name']}",
            ];
            
            $subject = $subjects[$event] ?? "Welfare Update - {$orgInfo['name']}";
            $message = $this->formatWelfareEmail($user, $event, $welfareDetails, $address);
            
            Mail::raw($message, function ($mail) use ($user, $subject, $orgInfo) {
                $mail->to($user->email, $user->name)
                     ->subject($subject)
                     ->from($orgInfo['from_email'], $orgInfo['from_name']);
            });
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send welfare email: ' . $e->getMessage());
            return false;
        }
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
Purpose:            " . ($loanDetails['purpose'] ?? 'N/A') . "
Disbursement Date:  " . ($loanDetails['disbursement_date'] ?? 'To be scheduled') . "

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
Transaction Reference: " . ($loanDetails['transaction_ref'] ?? 'N/A') . "

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

REPAYMENT INFORMATION:
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

First Payment Due:     " . ($loanDetails['first_payment_due'] ?? 'N/A') . "
Monthly Payment:       " . number_format($loanDetails['monthly_payment'] ?? 0, 0) . " TZS
Total Amount to Repay:  " . number_format($loanDetails['total_repayment'] ?? 0, 0) . " TZS

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
            : "";
        
        return "Dear {$user->name},

This is a reminder that you have an upcoming loan payment.

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

PAYMENT REMINDER:
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Loan Number:        {$loanNumber}
Amount Due:         {$amount} TZS
Due Date:           {$dueDate}
" . ($daysOverdue > 0 ? "Days Overdue:      {$daysOverdue} days" : "") . "
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
            'deposit' => "Your deposit has been successfully processed.",
            'withdrawal' => "Your withdrawal request has been processed.",
            'interest' => "Interest has been posted to your savings account.",
        ];
        
        $mainMessage = $messages[$event] ?? "Your savings account has been updated.";
        
        $details = match($event) {
            'created' => "Account Number:     {$accountNumber}\nAccount Type:       {$accountType}\nOpening Balance:    " . number_format($accountDetails['opening_balance'] ?? 0, 0) . " TZS",
            'deposit' => "Account Number:     {$accountNumber}\nDeposit Amount:     " . number_format($accountDetails['amount'] ?? 0, 0) . " TZS\nNew Balance:        " . number_format($accountDetails['new_balance'] ?? 0, 0) . " TZS\nTransaction Date:   " . ($accountDetails['transaction_date'] ?? date('Y-m-d')),
            'withdrawal' => "Account Number:     {$accountNumber}\nWithdrawal Amount:  " . number_format($accountDetails['amount'] ?? 0, 0) . " TZS\nNew Balance:        " . number_format($accountDetails['new_balance'] ?? 0, 0) . " TZS\nTransaction Date:   " . ($accountDetails['transaction_date'] ?? date('Y-m-d')),
            'interest' => "Account Number:     {$accountNumber}\nInterest Amount:    " . number_format($accountDetails['interest_amount'] ?? 0, 0) . " TZS\nNew Balance:        " . number_format($accountDetails['new_balance'] ?? 0, 0) . " TZS\nInterest Period:    " . ($accountDetails['period'] ?? 'N/A'),
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
            'matured' => "Congratulations! Your investment has matured.",
            'profit' => "Profit has been distributed to your investment account.",
            'topup' => "Your investment top-up has been processed successfully.",
        ];
        
        $mainMessage = $messages[$event] ?? "Your investment has been updated.";
        
        $details = match($event) {
            'created' => "Investment Number:  {$investmentNumber}\nPlan Type:          {$planType}\nPrincipal Amount:   " . number_format($investmentDetails['principal_amount'] ?? 0, 0) . " TZS\nMaturity Date:      " . ($investmentDetails['maturity_date'] ?? 'N/A') . "\nExpected Return:    " . number_format($investmentDetails['expected_return'] ?? 0, 0) . " TZS",
            'matured' => "Investment Number:  {$investmentNumber}\nPrincipal Amount:   " . number_format($investmentDetails['principal_amount'] ?? 0, 0) . " TZS\nTotal Profit:       " . number_format($investmentDetails['total_profit'] ?? 0, 0) . " TZS\nTotal Return:       " . number_format($investmentDetails['total_return'] ?? 0, 0) . " TZS\nMaturity Date:      " . ($investmentDetails['maturity_date'] ?? date('Y-m-d')),
            'profit' => "Investment Number:  {$investmentNumber}\nProfit Amount:      " . number_format($investmentDetails['profit_amount'] ?? 0, 0) . " TZS\nNew Balance:        " . number_format($investmentDetails['new_balance'] ?? 0, 0) . " TZS\nDistribution Date:  " . ($investmentDetails['distribution_date'] ?? date('Y-m-d')),
            'topup' => "Investment Number:  {$investmentNumber}\nTop-up Amount:      " . number_format($investmentDetails['topup_amount'] ?? 0, 0) . " TZS\nNew Principal:      " . number_format($investmentDetails['new_principal'] ?? 0, 0) . " TZS\nTransaction Date:   " . ($investmentDetails['transaction_date'] ?? date('Y-m-d')),
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
            'contribution' => "Your welfare contribution has been received and recorded.",
            'claim_approved' => "Congratulations! Your welfare claim has been approved.",
            'claim_disbursed' => "Your welfare benefit has been disbursed to your account.",
        ];
        
        $mainMessage = $messages[$event] ?? "Your welfare account has been updated.";
        
        $details = match($event) {
            'contribution' => "Contribution Amount: " . number_format($welfareDetails['amount'] ?? 0, 0) . " TZS\nNew Fund Balance:   " . number_format($welfareDetails['fund_balance'] ?? 0, 0) . " TZS\nContribution Date:  " . ($welfareDetails['contribution_date'] ?? date('Y-m-d')),
            'claim_approved' => "Claim Type:         " . ($welfareDetails['claim_type'] ?? 'N/A') . "\nApproved Amount:    " . number_format($welfareDetails['approved_amount'] ?? 0, 0) . " TZS\nApproval Date:      " . ($welfareDetails['approval_date'] ?? date('Y-m-d')),
            'claim_disbursed' => "Claim Type:         " . ($welfareDetails['claim_type'] ?? 'N/A') . "\nDisbursed Amount:   " . number_format($welfareDetails['disbursed_amount'] ?? 0, 0) . " TZS\nDisbursement Date:  " . ($welfareDetails['disbursement_date'] ?? date('Y-m-d')) . "\nTransaction Ref:    " . ($welfareDetails['transaction_ref'] ?? 'N/A'),
            default => "",
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
}

