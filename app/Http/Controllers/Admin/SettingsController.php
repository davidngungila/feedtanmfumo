<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $stats = [
            'total_settings' => Setting::count(),
            'system_settings' => Setting::where('group', 'system')->count(),
            'organization_settings' => Setting::where('group', 'organization')->count(),
            'communication_settings' => Setting::where('group', 'communication')->count(),
        ];
        return view('admin.settings.index', compact('stats'));
    }

    public function system()
    {
        $settings = Setting::getByGroup('system');
        return view('admin.settings.system', compact('settings'));
    }

    public function organization()
    {
        $settings = Setting::getByGroup('organization');
        return view('admin.settings.organization', compact('settings'));
    }

    public function communication()
    {
        $settings = Setting::getByGroup('communication');
        $primaryProvider = \App\Models\SmsProvider::getPrimary();
        return view('admin.settings.communication', compact('settings', 'primaryProvider'));
    }

    public function updateSystem(Request $request)
    {
        $validated = $request->validate([
            'app_name' => 'nullable|string|max:255',
            'app_url' => 'nullable|url',
            'timezone' => 'nullable|string',
            'currency' => 'nullable|string|max:10',
            'currency_symbol' => 'nullable|string|max:10',
            'date_format' => 'nullable|string',
            'maintenance_mode' => 'nullable|boolean',
            'language' => 'nullable|string|in:en,sw',
            'number_format' => 'nullable|string',
            'cache_duration' => 'nullable|integer|min:1',
            'session_timeout' => 'nullable|integer|min:5',
        ]);

        foreach ($validated as $key => $value) {
            $type = is_bool($value) ? 'boolean' : (is_numeric($value) ? 'number' : 'text');
            Setting::set($key, $value, 'system', $type);
        }

        return redirect()->route('admin.settings.system')->with('success', 'System settings updated successfully.');
    }

    public function updateOrganization(Request $request)
    {
        $validated = $request->validate([
            'organization_name' => 'nullable|string|max:255',
            'registration_number' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'tax_id' => 'nullable|string|max:255',
            'logo' => 'nullable|string',
            'founded_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'member_count' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'facebook_url' => 'nullable|url',
            'twitter_handle' => 'nullable|string|max:255',
            'linkedin_url' => 'nullable|url',
            'whatsapp_number' => 'nullable|string|max:50',
        ]);

        foreach ($validated as $key => $value) {
            $type = in_array($key, ['founded_year', 'member_count']) ? 'number' : 'text';
            Setting::set($key, $value, 'organization', $type);
        }

        return redirect()->route('admin.settings.organization')->with('success', 'Organization settings updated successfully.');
    }

    public function updateCommunication(Request $request)
    {
        $validated = $request->validate([
            // Email Settings
            'mail_mailer' => 'nullable|string',
            'mail_host' => 'nullable|string',
            'mail_port' => 'nullable|integer',
            'mail_username' => 'nullable|string',
            'mail_password' => 'nullable|string',
            'mail_encryption' => 'nullable|string',
            'mail_from_address' => 'nullable|email',
            'mail_from_name' => 'nullable|string',
            'mail_primary_email' => 'nullable|email',
            'mail_additional_emails' => 'nullable|string',
            
            // Organization Information for Email Headers
            'organization_name' => 'nullable|string|max:255',
            'organization_po_box' => 'nullable|string|max:255',
            'organization_address' => 'nullable|string|max:255',
            'organization_city' => 'nullable|string|max:255',
            'organization_region' => 'nullable|string|max:255',
            'organization_country' => 'nullable|string|max:255',
            'organization_phone' => 'nullable|string|max:255',
            
            // SMS Settings
            'sms_provider' => 'nullable|string',
            'sms_api_key' => 'nullable|string',
            'sms_api_secret' => 'nullable|string',
            'sms_sender_id' => 'nullable|string',
            'sms_enabled' => 'nullable|boolean',
            
            // SMS Provider Configuration
            'provider_name' => 'nullable|string|max:255',
            'provider_username' => 'nullable|string|max:255',
            'provider_password' => 'nullable|string',
            'provider_from' => 'nullable|string|max:11',
            'provider_api_url' => 'nullable|url',
            'provider_description' => 'nullable|string',
            'provider_active' => 'nullable|boolean',
            'provider_is_primary' => 'nullable|boolean',
        ]);

        foreach ($validated as $key => $value) {
            if (strpos($key, 'provider_') === 0) {
                continue; // Skip provider fields, handle separately
            }
            $type = in_array($key, ['mail_port', 'sms_enabled']) ? (is_bool($value) ? 'boolean' : 'number') : 'text';
            Setting::set($key, $value, 'communication', $type);
        }
        
        // Handle SMS Provider Configuration
        if ($request->filled('provider_name') && $request->filled('provider_api_url')) {
            $primaryProvider = \App\Models\SmsProvider::getPrimary();
            
            if ($primaryProvider) {
                // Update existing provider
                $updateData = [
                    'name' => $request->provider_name,
                    'username' => $request->provider_username,
                    'from' => $request->provider_from ?? $request->sms_sender_id ?? 'FEEDTAN',
                    'api_url' => $request->provider_api_url,
                    'description' => $request->provider_description,
                    'active' => $request->has('provider_active'),
                    'is_primary' => $request->has('provider_is_primary'),
                ];
                
                // Only update password if provided
                if ($request->filled('provider_password')) {
                    $updateData['password'] = $request->provider_password;
                }
                
                $primaryProvider->update($updateData);
                
                // If set as primary, ensure it's the only primary
                if ($request->has('provider_is_primary')) {
                    $primaryProvider->setAsPrimary();
                }
            } else {
                // Create new provider
                $provider = \App\Models\SmsProvider::create([
                    'name' => $request->provider_name,
                    'username' => $request->provider_username,
                    'password' => $request->provider_password,
                    'from' => $request->provider_from ?? $request->sms_sender_id ?? 'FEEDTAN',
                    'api_url' => $request->provider_api_url,
                    'description' => $request->provider_description,
                    'active' => $request->has('provider_active'),
                    'is_primary' => $request->has('provider_is_primary'),
                ]);
                
                // If set as primary, ensure it's the only primary
                if ($request->has('provider_is_primary')) {
                    $provider->setAsPrimary();
                }
            }
        }
        
        // If primary email is set, also update mail_from_address as fallback
        if (isset($validated['mail_primary_email']) && $validated['mail_primary_email']) {
            Setting::set('mail_from_address', $validated['mail_primary_email'], 'communication', 'text');
        }

        // Clear config cache to reload mail settings
        \Artisan::call('config:clear');

        return redirect()->route('admin.settings.communication')->with('success', 'Communication settings updated successfully.');
    }

    /**
     * Send test email
     */
    public function sendTestEmail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        try {
            // Reload mail config from database
            $this->reloadMailConfig();
            
            $settings = Setting::getByGroup('communication');
            $orgInfo = [
                'name' => $settings['organization_name']->value ?? 'FeedTan Community Microfinance Group',
                'po_box' => $settings['organization_po_box']->value ?? 'P.O.Box 7744',
                'address' => $settings['organization_address']->value ?? 'Ushirika Sokoine Road',
                'city' => $settings['organization_city']->value ?? 'Moshi',
                'region' => $settings['organization_region']->value ?? 'Kilimanjaro',
                'country' => $settings['organization_country']->value ?? 'Tanzania',
                'from_name' => $settings['mail_from_name']->value ?? 'FeedTan Community Microfinance Group',
                'from_email' => $settings['mail_from_address']->value ?? config('mail.from.address'),
            ];
            
            $address = "{$orgInfo['name']}\n{$orgInfo['po_box']}, {$orgInfo['address']}\n{$orgInfo['city']}, {$orgInfo['region']}, {$orgInfo['country']}";
            
            $subject = "Test Email - {$orgInfo['name']}";
            $message = "This is a test email from {$orgInfo['name']}.

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

EMAIL CONFIGURATION TEST
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

If you received this email, your email configuration is working correctly!

Mail Settings:
• Mailer: " . ($settings['mail_mailer']->value ?? 'SMTP') . "
• Host: " . ($settings['mail_host']->value ?? 'N/A') . "
• Port: " . ($settings['mail_port']->value ?? 'N/A') . "
• Encryption: " . ($settings['mail_encryption']->value ?? 'TLS') . "
• From: {$orgInfo['from_email']} ({$orgInfo['from_name']})

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

This email was sent at: " . now()->format('Y-m-d H:i:s') . "

Best regards,
{$address}";

            \Mail::raw($message, function ($mail) use ($request, $subject, $orgInfo) {
                $mail->to($request->test_email)
                     ->subject($subject)
                     ->from($orgInfo['from_email'], $orgInfo['from_name']);
            });

            return redirect()->route('admin.settings.communication')
                ->with('success', "Test email sent successfully to {$request->test_email}!");
        } catch (\Exception $e) {
            \Log::error('Failed to send test email: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->route('admin.settings.communication')
                ->with('error', 'Failed to send test email: ' . $e->getMessage());
        }
    }

    /**
     * Send test SMS
     */
    public function sendTestSms(Request $request)
    {
        $request->validate([
            'test_phone' => 'required|string|max:20',
            'test_message' => 'nullable|string|max:500',
        ]);

        try {
            $smsService = new \App\Services\SmsNotificationService();
            
            $message = $request->test_message ?? "Test message from FeedTan SMS System. This confirms your SMS gateway is working properly.";
            
            $result = $smsService->sendSms($request->test_phone, $message);
            $success = $result['success'] ?? false;
            
            if ($success) {
                return redirect()->route('admin.settings.communication')
                    ->with('sms_success', "Test SMS sent successfully to {$request->test_phone}!");
            } else {
                return redirect()->route('admin.settings.communication')
                    ->with('sms_error', 'Failed to send test SMS. Please check your SMS configuration and try again.');
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send test SMS: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->route('admin.settings.communication')
                ->with('sms_error', 'Failed to send test SMS: ' . $e->getMessage());
        }
    }

    /**
     * Reload mail configuration from database
     */
    protected function reloadMailConfig(): void
    {
        try {
            $settings = Setting::getByGroup('communication');
            
            if (isset($settings['mail_mailer']) && $settings['mail_mailer']->value) {
                config(['mail.default' => $settings['mail_mailer']->value]);
            }
            
            if (isset($settings['mail_host']) && $settings['mail_host']->value) {
                config(['mail.mailers.smtp.host' => $settings['mail_host']->value]);
            }
            
            if (isset($settings['mail_port'])) {
                config(['mail.mailers.smtp.port' => $settings['mail_port']->value ?? 587]);
            }
            
            if (isset($settings['mail_username']) && $settings['mail_username']->value) {
                config(['mail.mailers.smtp.username' => $settings['mail_username']->value]);
            }
            
            if (isset($settings['mail_password']) && $settings['mail_password']->value) {
                config(['mail.mailers.smtp.password' => $settings['mail_password']->value]);
            }
            
            if (isset($settings['mail_encryption']) && $settings['mail_encryption']->value) {
                config(['mail.mailers.smtp.encryption' => $settings['mail_encryption']->value]);
            } else {
                config(['mail.mailers.smtp.encryption' => 'tls']);
            }
            
            if (isset($settings['mail_from_address']) && $settings['mail_from_address']->value) {
                config(['mail.from.address' => $settings['mail_from_address']->value]);
            }
            
            if (isset($settings['mail_from_name']) && $settings['mail_from_name']->value) {
                config(['mail.from.name' => $settings['mail_from_name']->value]);
            }
        } catch (\Exception $e) {
            \Log::warning('Failed to reload mail config: ' . $e->getMessage());
        }
    }

    public function productConfiguration()
    {
        $settings = Setting::getByGroup('product');
        return view('admin.settings.product-configuration', compact('settings'));
    }

    public function updateProductConfiguration(Request $request)
    {
        $validated = $request->validate([
            'loan_min_amount' => 'nullable|numeric|min:0',
            'loan_max_amount' => 'nullable|numeric|min:0',
            'loan_default_interest_rate' => 'nullable|numeric|min:0|max:100',
            'loan_default_term_months' => 'nullable|integer|min:1',
            'savings_minimum_balance' => 'nullable|numeric|min:0',
            'savings_default_interest_rate' => 'nullable|numeric|min:0|max:100',
            'investment_min_amount' => 'nullable|numeric|min:0',
            'investment_default_term_months' => 'nullable|integer|min:1',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value, 'product', 'number');
        }

        return redirect()->route('admin.settings.product-configuration')->with('success', 'Product configuration updated successfully.');
    }

    public function security()
    {
        $settings = Setting::getByGroup('security');
        return view('admin.settings.security', compact('settings'));
    }

    public function updateSecurity(Request $request)
    {
        $validated = $request->validate([
            'password_min_length' => 'nullable|integer|min:6|max:32',
            'password_require_uppercase' => 'nullable|boolean',
            'password_require_lowercase' => 'nullable|boolean',
            'password_require_numbers' => 'nullable|boolean',
            'password_require_symbols' => 'nullable|boolean',
            'password_expiry_days' => 'nullable|integer|min:0',
            'max_login_attempts' => 'nullable|integer|min:1|max:10',
            'lockout_duration_minutes' => 'nullable|integer|min:1',
            'two_factor_enabled' => 'nullable|boolean',
            'session_timeout_minutes' => 'nullable|integer|min:5',
        ]);

        foreach ($validated as $key => $value) {
            $type = is_bool($value) ? 'boolean' : 'number';
            Setting::set($key, $value, 'security', $type);
        }

        return redirect()->route('admin.settings.security')->with('success', 'Security settings updated successfully.');
    }

    public function smsTemplates()
    {
        $settings = Setting::getByGroup('sms_templates');
        return view('admin.settings.sms-templates', compact('settings'));
    }

    public function updateSmsTemplates(Request $request)
    {
        $validated = $request->validate([
            'sms_loan_approval' => 'nullable|string|max:500',
            'sms_loan_disbursement' => 'nullable|string|max:500',
            'sms_payment_reminder' => 'nullable|string|max:500',
            'sms_payment_confirmation' => 'nullable|string|max:500',
            'sms_password_reset' => 'nullable|string|max:500',
            'sms_welcome_message' => 'nullable|string|max:500',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value, 'sms_templates', 'text');
        }

        return redirect()->route('admin.settings.sms-templates')->with('success', 'SMS templates updated successfully.');
    }

    public function emailSettings()
    {
        $settings = Setting::getByGroup('email');
        return view('admin.settings.email-settings', compact('settings'));
    }

    public function updateEmailSettings(Request $request)
    {
        $validated = $request->validate([
            'email_loan_approval_subject' => 'nullable|string|max:255',
            'email_loan_approval_body' => 'nullable|string',
            'email_payment_reminder_subject' => 'nullable|string|max:255',
            'email_payment_reminder_body' => 'nullable|string',
            'email_welcome_subject' => 'nullable|string|max:255',
            'email_welcome_body' => 'nullable|string',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value, 'email', 'text');
        }

        return redirect()->route('admin.settings.email-templates')->with('success', 'Email templates updated successfully.');
    }

    public function notificationPreferences()
    {
        $settings = Setting::getByGroup('notifications');
        return view('admin.settings.notification-preferences', compact('settings'));
    }

    public function updateNotificationPreferences(Request $request)
    {
        $validated = $request->validate([
            'notify_loan_approval' => 'nullable|boolean',
            'notify_loan_disbursement' => 'nullable|boolean',
            'notify_payment_due' => 'nullable|boolean',
            'notify_payment_received' => 'nullable|boolean',
            'notify_account_created' => 'nullable|boolean',
            'notify_password_changed' => 'nullable|boolean',
            'notification_channels' => 'nullable|array',
        ]);

        foreach ($validated as $key => $value) {
            $type = is_bool($value) ? 'boolean' : (is_array($value) ? 'json' : 'text');
            Setting::set($key, $value, 'notifications', $type);
        }

        return redirect()->route('admin.settings.notification-preferences')->with('success', 'Notification preferences updated successfully.');
    }

    public function reminderSettings()
    {
        $settings = Setting::getByGroup('reminders');
        return view('admin.settings.reminder-settings', compact('settings'));
    }

    public function updateReminderSettings(Request $request)
    {
        $validated = $request->validate([
            'reminder_enabled' => 'nullable|boolean',
            'reminder_days_before_due' => 'nullable|integer|min:0|max:30',
            'reminder_frequency' => 'nullable|string|in:daily,weekly',
            'reminder_time' => 'nullable|string',
            'overdue_reminder_enabled' => 'nullable|boolean',
            'overdue_reminder_frequency' => 'nullable|string|in:daily,weekly',
        ]);

        foreach ($validated as $key => $value) {
            $type = is_bool($value) ? 'boolean' : (is_numeric($value) ? 'number' : 'text');
            Setting::set($key, $value, 'reminders', $type);
        }

        return redirect()->route('admin.settings.reminder-settings')->with('success', 'Reminder settings updated successfully.');
    }
}
