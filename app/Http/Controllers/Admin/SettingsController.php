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
        $smsProviders = \App\Models\SmsProvider::orderBy('is_primary', 'desc')
            ->orderBy('active', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        $emailProviders = \App\Models\EmailProvider::orderBy('is_primary', 'desc')
            ->orderBy('active', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.settings.communication', compact('smsProviders', 'emailProviders'));
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
        ]);

        foreach ($validated as $key => $value) {
            $type = in_array($key, ['mail_port', 'sms_enabled']) ? (is_bool($value) ? 'boolean' : 'number') : 'text';
            Setting::set($key, $value, 'communication', $type);
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
        
        // Define predefined templates with their metadata
        $predefinedTemplates = [
            'sms_loan_approval' => [
                'name' => 'Loan Approval',
                'description' => 'Sent when loan is approved',
                'color' => 'green',
                'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
            ],
            'sms_loan_disbursement' => [
                'name' => 'Loan Disbursement',
                'description' => 'Sent when loan is disbursed',
                'color' => 'blue',
                'icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'
            ],
            'sms_payment_reminder' => [
                'name' => 'Payment Reminder',
                'description' => 'Sent for payment reminders',
                'color' => 'yellow',
                'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'
            ],
            'sms_payment_confirmation' => [
                'name' => 'Payment Confirmation',
                'description' => 'Sent when payment is received',
                'color' => 'purple',
                'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
            ],
            'sms_password_reset' => [
                'name' => 'Password Reset',
                'description' => 'Sent for password reset requests',
                'color' => 'red',
                'icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'
            ],
            'sms_welcome_message' => [
                'name' => 'Welcome Message',
                'description' => 'Sent to new members',
                'color' => 'indigo',
                'icon' => 'M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
            ]
        ];
        
        // Collect all templates (predefined + custom)
        $allTemplates = [];
        
        // Add predefined templates
        foreach ($predefinedTemplates as $key => $template) {
            if (isset($settings[$key])) {
                $allTemplates[$key] = array_merge($template, [
                    'content' => $settings[$key]->value,
                    'type' => 'predefined'
                ]);
            } else {
                // Add default content for predefined templates that don't exist in database
                $defaultContent = '';
                switch ($key) {
                    case 'sms_loan_approval':
                        $defaultContent = 'Dear {member_name}, your loan application of {loan_amount} TZS has been approved. Please visit our office to complete the process. - FeedTan CMG';
                        break;
                    case 'sms_loan_disbursement':
                        $defaultContent = 'Dear {member_name}, your loan of {loan_amount} TZS has been disbursed to your account. - FeedTan CMG';
                        break;
                    case 'sms_payment_reminder':
                        $defaultContent = 'Dear {member_name}, this is a reminder that your payment of {loan_amount} TZS is due on {due_date}. Please make payment to avoid penalties. - FeedTan CMG';
                        break;
                    case 'sms_payment_confirmation':
                        $defaultContent = 'Dear {member_name}, we have received your payment of {loan_amount} TZS. Your current balance is {balance} TZS. Thank you! - FeedTan CMG';
                        break;
                    case 'sms_password_reset':
                        $defaultContent = 'Dear {member_name}, your password reset code is {code}. Use this code to reset your password. Do not share this code with anyone. - FeedTan CMG';
                        break;
                    case 'sms_welcome_message':
                        $defaultContent = 'Welcome {member_name} to FeedTan CMG! Your account {account_number} has been created successfully. We are here to support your financial growth. - FeedTan CMG';
                        break;
                }
                $allTemplates[$key] = array_merge($template, [
                    'content' => $defaultContent,
                    'type' => 'predefined'
                ]);
            }
        }
        
        // Add custom templates (those that don't end with '_description' and aren't predefined)
        foreach ($settings as $key => $setting) {
            if (!isset($predefinedTemplates[$key]) && !str_ends_with($key, '_description')) {
                $templateName = ucfirst(str_replace('_', ' ', str_replace('sms_', '', $key)));
                $description = '';
                
                // Check if there's a description for this template
                $descriptionKey = $key . '_description';
                if (isset($settings[$descriptionKey])) {
                    $description = $settings[$descriptionKey]->value;
                }
                
                $allTemplates[$key] = [
                    'name' => $templateName,
                    'description' => $description ?: 'Custom SMS template',
                    'content' => $setting->value,
                    'color' => 'gray',
                    'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
                    'type' => 'custom'
                ];
            }
        }
        
        return view('admin.settings.sms-templates', compact('allTemplates'));
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

    public function createSmsTemplate()
    {
        return view('admin.settings.sms-templates-create');
    }

    public function storeSmsTemplate(Request $request)
    {
        $validated = $request->validate([
            'template_name' => 'required|string|max:255|unique:settings,key',
            'template_description' => 'nullable|string|max:255',
            'template_content' => 'required|string|max:500',
        ]);

        // Convert template name to a valid key format
        $key = 'sms_' . str_replace(' ', '_', strtolower($validated['template_name']));
        
        // Store the template
        Setting::set($key, $validated['template_content'], 'sms_templates', 'text');
        
        // Store description as metadata if provided
        if (!empty($validated['template_description'])) {
            Setting::set($key . '_description', $validated['template_description'], 'sms_templates', 'text');
        }

        return redirect()->route('admin.settings.sms-templates')->with('success', 'New SMS template created successfully.');
    }

    public function viewSmsTemplate($templateType)
    {
        $settings = Setting::getByGroup('sms_templates');
        
        // Map template types to their field names and default values
        $templateMap = [
            'sms_loan_approval' => [
                'name' => 'Loan Approval',
                'default' => 'Dear {member_name}, your loan application of {loan_amount} TZS has been approved. Please visit our office to complete the process. - FeedTan CMG'
            ],
            'sms_loan_disbursement' => [
                'name' => 'Loan Disbursement',
                'default' => 'Dear {member_name}, your loan of {loan_amount} TZS has been disbursed to your account. - FeedTan CMG'
            ],
            'sms_payment_reminder' => [
                'name' => 'Payment Reminder',
                'default' => 'Dear {member_name}, this is a reminder that your payment of {loan_amount} TZS is due on {due_date}. Please make payment to avoid penalties. - FeedTan CMG'
            ],
            'sms_payment_confirmation' => [
                'name' => 'Payment Confirmation',
                'default' => 'Dear {member_name}, we have received your payment of {loan_amount} TZS. Your current balance is {balance} TZS. Thank you! - FeedTan CMG'
            ],
            'sms_password_reset' => [
                'name' => 'Password Reset',
                'default' => 'Dear {member_name}, your password reset code is {code}. Use this code to reset your password. Do not share this code with anyone. - FeedTan CMG'
            ],
            'sms_welcome_message' => [
                'name' => 'Welcome Message',
                'default' => 'Welcome {member_name} to FeedTan CMG! Your account {account_number} has been created successfully. We are here to support your financial growth. - FeedTan CMG'
            ]
        ];

        // Check if this is a predefined template or custom template
        if (isset($templateMap[$templateType])) {
            // Predefined template
            $templateContent = isset($settings[$templateType]) ? $settings[$templateType]->value : $templateMap[$templateType]['default'];
            $templateName = $templateMap[$templateType]['name'];
            $fieldName = $templateType;
        } else {
            // Custom template
            if (!isset($settings[$templateType])) {
                abort(404);
            }
            $templateContent = $settings[$templateType]->value;
            $templateName = ucfirst(str_replace('_', ' ', str_replace('sms_', '', $templateType)));
            $fieldName = $templateType;
        }

        return view('admin.settings.sms-templates-view', compact('templateType', 'templateContent', 'fieldName', 'templateName'));
    }

    public function editSmsTemplate($templateType)
    {
        $settings = Setting::getByGroup('sms_templates');
        
        // Map template types to their field names and default values
        $templateMap = [
            'sms_loan_approval' => [
                'name' => 'Loan Approval',
                'default' => 'Dear {member_name}, your loan application of {loan_amount} TZS has been approved. Please visit our office to complete the process. - FeedTan CMG'
            ],
            'sms_loan_disbursement' => [
                'name' => 'Loan Disbursement',
                'default' => 'Dear {member_name}, your loan of {loan_amount} TZS has been disbursed to your account. - FeedTan CMG'
            ],
            'sms_payment_reminder' => [
                'name' => 'Payment Reminder',
                'default' => 'Dear {member_name}, this is a reminder that your payment of {loan_amount} TZS is due on {due_date}. Please make payment to avoid penalties. - FeedTan CMG'
            ],
            'sms_payment_confirmation' => [
                'name' => 'Payment Confirmation',
                'default' => 'Dear {member_name}, we have received your payment of {loan_amount} TZS. Your current balance is {balance} TZS. Thank you! - FeedTan CMG'
            ],
            'sms_password_reset' => [
                'name' => 'Password Reset',
                'default' => 'Dear {member_name}, your password reset code is {code}. Use this code to reset your password. Do not share this code with anyone. - FeedTan CMG'
            ],
            'sms_welcome_message' => [
                'name' => 'Welcome Message',
                'default' => 'Welcome {member_name} to FeedTan CMG! Your account {account_number} has been created successfully. We are here to support your financial growth. - FeedTan CMG'
            ]
        ];

        // Check if this is a predefined template or custom template
        if (isset($templateMap[$templateType])) {
            // Predefined template
            $templateContent = isset($settings[$templateType]) ? $settings[$templateType]->value : $templateMap[$templateType]['default'];
            $templateName = $templateMap[$templateType]['name'];
            $fieldName = $templateType;
        } else {
            // Custom template
            if (!isset($settings[$templateType])) {
                abort(404);
            }
            $templateContent = $settings[$templateType]->value;
            $templateName = ucfirst(str_replace('_', ' ', str_replace('sms_', '', $templateType)));
            $fieldName = $templateType;
        }

        return view('admin.settings.sms-templates-edit', compact('templateType', 'templateContent', 'fieldName', 'templateName'));
    }

    public function testSmsTemplate($templateType)
    {
        $settings = Setting::getByGroup('sms_templates');
        
        // Get template content similar to viewSmsTemplate method
        $templateMap = [
            'sms_loan_approval' => [
                'name' => 'Loan Approval',
                'default' => 'Dear {member_name}, your loan application of {loan_amount} TZS has been approved. Please visit our office to complete the process. - FeedTan CMG'
            ],
            'sms_loan_disbursement' => [
                'name' => 'Loan Disbursement',
                'default' => 'Dear {member_name}, your loan of {loan_amount} TZS has been disbursed to your account. - FeedTan CMG'
            ],
            'sms_payment_reminder' => [
                'name' => 'Payment Reminder',
                'default' => 'Dear {member_name}, this is a reminder that your payment of {loan_amount} TZS is due on {due_date}. Please make payment to avoid penalties. - FeedTan CMG'
            ],
            'sms_payment_confirmation' => [
                'name' => 'Payment Confirmation',
                'default' => 'Dear {member_name}, we have received your payment of {loan_amount} TZS. Your current balance is {balance} TZS. Thank you! - FeedTan CMG'
            ],
            'sms_password_reset' => [
                'name' => 'Password Reset',
                'default' => 'Dear {member_name}, your password reset code is {code}. Use this code to reset your password. Do not share this code with anyone. - FeedTan CMG'
            ],
            'sms_welcome_message' => [
                'name' => 'Welcome Message',
                'default' => 'Welcome {member_name} to FeedTan CMG! Your account {account_number} has been created successfully. We are here to support your financial growth. - FeedTan CMG'
            ]
        ];

        // Check if this is a predefined template or custom template
        if (isset($templateMap[$templateType])) {
            $templateContent = isset($settings[$templateType]) ? $settings[$templateType]->value : $templateMap[$templateType]['default'];
            $templateName = $templateMap[$templateType]['name'];
        } else {
            // Custom template
            if (!isset($settings[$templateType])) {
                abort(404);
            }
            $templateContent = $settings[$templateType]->value;
            $templateName = ucfirst(str_replace('_', ' ', str_replace('sms_', '', $templateType)));
        }

        return view('admin.settings.sms-templates-test', compact('templateType', 'templateName', 'templateContent'));
    }

    public function sendSmsTemplate(Request $request, $templateType)
    {
        $request->validate([
            'phone_number' => 'required|string|max:20',
            'member_name' => 'required|string|max:255',
            'test_variables' => 'nullable|array'
        ]);

        $settings = Setting::getByGroup('sms_templates');
        
        // Get template content
        $templateMap = [
            'sms_loan_approval' => ['default' => 'Dear {member_name}, your loan application of {loan_amount} TZS has been approved. Please visit our office to complete the process. - FeedTan CMG'],
            'sms_loan_disbursement' => ['default' => 'Dear {member_name}, your loan of {loan_amount} TZS has been disbursed to your account. - FeedTan CMG'],
            'sms_payment_reminder' => ['default' => 'Dear {member_name}, this is a reminder that your payment of {loan_amount} TZS is due on {due_date}. Please make payment to avoid penalties. - FeedTan CMG'],
            'sms_payment_confirmation' => ['default' => 'Dear {member_name}, we have received your payment of {loan_amount} TZS. Your current balance is {balance} TZS. Thank you! - FeedTan CMG'],
            'sms_password_reset' => ['default' => 'Dear {member_name}, your password reset code is {code}. Use this code to reset your password. Do not share this code with anyone. - FeedTan CMG'],
            'sms_welcome_message' => ['default' => 'Welcome {member_name} to FeedTan CMG! Your account {account_number} has been created successfully. We are here to support your financial growth. - FeedTan CMG']
        ];

        if (isset($templateMap[$templateType])) {
            $templateContent = isset($settings[$templateType]) ? $settings[$templateType]->value : $templateMap[$templateType]['default'];
        } else {
            if (!isset($settings[$templateType])) {
                return redirect()->back()->with('error', 'Template not found.');
            }
            $templateContent = $settings[$templateType]->value;
        }

        // Replace variables with test data
        $testVariables = $request->input('test_variables', []);
        $message = $templateContent;
        
        // Common variables
        $message = str_replace('{member_name}', $request->input('member_name'), $message);
        
        // Template-specific variables
        if (isset($testVariables['loan_amount'])) {
            $message = str_replace('{loan_amount}', $testVariables['loan_amount'], $message);
        }
        if (isset($testVariables['due_date'])) {
            $message = str_replace('{due_date}', $testVariables['due_date'], $message);
        }
        if (isset($testVariables['balance'])) {
            $message = str_replace('{balance}', $testVariables['balance'], $message);
        }
        if (isset($testVariables['account_number'])) {
            $message = str_replace('{account_number}', $testVariables['account_number'], $message);
        }
        if (isset($testVariables['code'])) {
            $message = str_replace('{code}', $testVariables['code'], $message);
        }

        // Use the actual SMS service to send the message
        try {
            $phoneNumber = $request->input('phone_number');
            
            // Import and use the SMS service
            $smsService = app(\App\Services\SmsNotificationService::class);
            $result = $smsService->sendSms($phoneNumber, $message);
            
            if ($result['success']) {
                return redirect()->back()->with('success', "Test SMS sent successfully to {$phoneNumber}. Message: {$message}");
            } else {
                return redirect()->back()->with('error', 'Failed to send SMS: ' . ($result['error'] ?? 'Unknown error'));
            }
        } catch (\Exception $e) {
            \Log::error('SMS test sending failed: ' . $e->getMessage(), [
                'phone' => $request->input('phone_number'),
                'template' => $templateType,
                'message' => $message
            ]);
            return redirect()->back()->with('error', 'Failed to send SMS: ' . $e->getMessage());
        }
    }

    public function emailSettings()
    {
        $settings = Setting::getByGroup('email');
        
        // Define predefined email templates with their metadata
        $emailTemplates = [
            'email_loan_approval' => [
                'name' => 'Loan Approval Email',
                'description' => 'Sent when loan is approved',
                'color' => 'green',
                'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                'subject_field' => 'email_loan_approval_subject',
                'body_field' => 'email_loan_approval_body',
                'default_subject' => 'Loan Application Approved - FeedTan CMG',
                'default_body' => 'Dear {member_name},

We are pleased to inform you that your loan application of {loan_amount} TZS has been approved.

Please visit our office within 7 days to complete the loan processing and disbursement.

If you have any questions, please contact us.

Best regards,
{organization_name}'
            ],
            'email_payment_reminder' => [
                'name' => 'Payment Reminder Email',
                'description' => 'Sent for payment reminders',
                'color' => 'yellow',
                'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                'subject_field' => 'email_payment_reminder_subject',
                'body_field' => 'email_payment_reminder_body',
                'default_subject' => 'Payment Reminder - {due_date}',
                'default_body' => 'Dear {member_name},

This is a friendly reminder that your payment of {loan_amount} TZS is due on {due_date}.

Your current balance is {balance} TZS.

Please make payment before the due date to avoid penalties and maintain your good standing with us.

Thank you for your prompt attention to this matter.

Best regards,
{organization_name}'
            ],
            'email_welcome' => [
                'name' => 'Welcome Email',
                'description' => 'Sent to new members',
                'color' => 'blue',
                'icon' => 'M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                'subject_field' => 'email_welcome_subject',
                'body_field' => 'email_welcome_body',
                'default_subject' => 'Welcome to {organization_name}',
                'default_body' => 'Dear {member_name},

Welcome to {organization_name}!

Your account has been successfully created with account number: {account_number}

We are delighted to have you as a member of our community. Our mission is to support your financial growth and prosperity.

You can now access your account online and manage your savings, loans, and investments.

If you have any questions or need assistance, please do not hesitate to contact us.

Welcome aboard!

Best regards,
{organization_name}'
            ]
        ];

        // Collect all email templates with their content
        $allEmailTemplates = [];
        
        foreach ($emailTemplates as $key => $template) {
            $subject = isset($settings[$template['subject_field']]) ? $settings[$template['subject_field']]->value : $template['default_subject'];
            $body = isset($settings[$template['body_field']]) ? $settings[$template['body_field']]->value : $template['default_body'];
            
            $allEmailTemplates[$key] = array_merge($template, [
                'subject' => $subject,
                'body' => $body,
                'type' => 'predefined'
            ]);
        }

        return view('admin.settings.email-settings', compact('allEmailTemplates'));
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

    public function viewEmailTemplate($templateType)
    {
        $settings = Setting::getByGroup('email');
        
        // Map template types to their field names and default values
        $templateMap = [
            'email_loan_approval' => [
                'name' => 'Loan Approval Email',
                'subject_field' => 'email_loan_approval_subject',
                'body_field' => 'email_loan_approval_body',
                'default_subject' => 'Loan Application Approved - FeedTan CMG',
                'default_body' => 'Dear {member_name},

We are pleased to inform you that your loan application of {loan_amount} TZS has been approved.

Please visit our office within 7 days to complete the loan processing and disbursement.

If you have any questions, please contact us.

Best regards,
{organization_name}'
            ],
            'email_payment_reminder' => [
                'name' => 'Payment Reminder Email',
                'subject_field' => 'email_payment_reminder_subject',
                'body_field' => 'email_payment_reminder_body',
                'default_subject' => 'Payment Reminder - {due_date}',
                'default_body' => 'Dear {member_name},

This is a friendly reminder that your payment of {loan_amount} TZS is due on {due_date}.

Your current balance is {balance} TZS.

Please make payment before the due date to avoid penalties and maintain your good standing with us.

Thank you for your prompt attention to this matter.

Best regards,
{organization_name}'
            ],
            'email_welcome' => [
                'name' => 'Welcome Email',
                'subject_field' => 'email_welcome_subject',
                'body_field' => 'email_welcome_body',
                'default_subject' => 'Welcome to {organization_name}',
                'default_body' => 'Dear {member_name},

Welcome to {organization_name}!

Your account has been successfully created with account number: {account_number}

We are delighted to have you as a member of our community. Our mission is to support your financial growth and prosperity.

You can now access your account online and manage your savings, loans, and investments.

If you have any questions or need assistance, please do not hesitate to contact us.

Welcome aboard!

Best regards,
{organization_name}'
            ]
        ];

        if (!isset($templateMap[$templateType])) {
            abort(404);
        }

        $template = $templateMap[$templateType];
        $subject = isset($settings[$template['subject_field']]) ? $settings[$template['subject_field']]->value : $template['default_subject'];
        $body = isset($settings[$template['body_field']]) ? $settings[$template['body_field']]->value : $template['default_body'];
        $templateName = $template['name'];

        return view('admin.settings.email-templates-view', compact('templateType', 'templateName', 'subject', 'body'));
    }

    public function editEmailTemplate($templateType)
    {
        $settings = Setting::getByGroup('email');
        
        // Map template types to their field names and default values
        $templateMap = [
            'email_loan_approval' => [
                'name' => 'Loan Approval Email',
                'subject_field' => 'email_loan_approval_subject',
                'body_field' => 'email_loan_approval_body',
                'default_subject' => 'Loan Application Approved - FeedTan CMG',
                'default_body' => 'Dear {member_name},

We are pleased to inform you that your loan application of {loan_amount} TZS has been approved.

Please visit our office within 7 days to complete the loan processing and disbursement.

If you have any questions, please contact us.

Best regards,
{organization_name}'
            ],
            'email_payment_reminder' => [
                'name' => 'Payment Reminder Email',
                'subject_field' => 'email_payment_reminder_subject',
                'body_field' => 'email_payment_reminder_body',
                'default_subject' => 'Payment Reminder - {due_date}',
                'default_body' => 'Dear {member_name},

This is a friendly reminder that your payment of {loan_amount} TZS is due on {due_date}.

Your current balance is {balance} TZS.

Please make payment before the due date to avoid penalties and maintain your good standing with us.

Thank you for your prompt attention to this matter.

Best regards,
{organization_name}'
            ],
            'email_welcome' => [
                'name' => 'Welcome Email',
                'subject_field' => 'email_welcome_subject',
                'body_field' => 'email_welcome_body',
                'default_subject' => 'Welcome to {organization_name}',
                'default_body' => 'Dear {member_name},

Welcome to {organization_name}!

Your account has been successfully created with account number: {account_number}

We are delighted to have you as a member of our community. Our mission is to support your financial growth and prosperity.

You can now access your account online and manage your savings, loans, and investments.

If you have any questions or need assistance, please do not hesitate to contact us.

Welcome aboard!

Best regards,
{organization_name}'
            ]
        ];

        if (!isset($templateMap[$templateType])) {
            abort(404);
        }

        $template = $templateMap[$templateType];
        $subject = isset($settings[$template['subject_field']]) ? $settings[$template['subject_field']]->value : $template['default_subject'];
        $body = isset($settings[$template['body_field']]) ? $settings[$template['body_field']]->value : $template['default_body'];
        $templateName = $template['name'];
        $subjectFieldName = $template['subject_field'];
        $bodyFieldName = $template['body_field'];

        return view('admin.settings.email-templates-edit', compact('templateType', 'templateName', 'subject', 'body', 'subjectFieldName', 'bodyFieldName'));
    }

    public function testEmailTemplate($templateType)
    {
        $settings = Setting::getByGroup('email');
        
        // Get template content similar to viewEmailTemplate method
        $templateMap = [
            'email_loan_approval' => [
                'name' => 'Loan Approval Email',
                'subject_field' => 'email_loan_approval_subject',
                'body_field' => 'email_loan_approval_body',
                'default_subject' => 'Loan Application Approved - FeedTan CMG',
                'default_body' => 'Dear {member_name},

We are pleased to inform you that your loan application of {loan_amount} TZS has been approved.

Please visit our office within 7 days to complete the loan processing and disbursement.

If you have any questions, please contact us.

Best regards,
{organization_name}'
            ],
            'email_payment_reminder' => [
                'name' => 'Payment Reminder Email',
                'subject_field' => 'email_payment_reminder_subject',
                'body_field' => 'email_payment_reminder_body',
                'default_subject' => 'Payment Reminder - {due_date}',
                'default_body' => 'Dear {member_name},

This is a friendly reminder that your payment of {loan_amount} TZS is due on {due_date}.

Your current balance is {balance} TZS.

Please make payment before the due date to avoid penalties and maintain your good standing with us.

Thank you for your prompt attention to this matter.

Best regards,
{organization_name}'
            ],
            'email_welcome' => [
                'name' => 'Welcome Email',
                'subject_field' => 'email_welcome_subject',
                'body_field' => 'email_welcome_body',
                'default_subject' => 'Welcome to {organization_name}',
                'default_body' => 'Dear {member_name},

Welcome to {organization_name}!

Your account has been successfully created with account number: {account_number}

We are delighted to have you as a member of our community. Our mission is to support your financial growth and prosperity.

You can now access your account online and manage your savings, loans, and investments.

If you have any questions or need assistance, please do not hesitate to contact us.

Welcome aboard!

Best regards,
{organization_name}'
            ]
        ];

        if (!isset($templateMap[$templateType])) {
            abort(404);
        }

        $template = $templateMap[$templateType];
        $subject = isset($settings[$template['subject_field']]) ? $settings[$template['subject_field']]->value : $template['default_subject'];
        $body = isset($settings[$template['body_field']]) ? $settings[$template['body_field']]->value : $template['default_body'];
        $templateName = $template['name'];

        return view('admin.settings.email-templates-test', compact('templateType', 'templateName', 'subject', 'body'));
    }

    public function sendEmailTemplate(Request $request, $templateType)
    {
        $request->validate([
            'email_address' => 'required|email',
            'member_name' => 'required|string|max:255',
            'test_variables' => 'nullable|array'
        ]);

        $settings = Setting::getByGroup('email');
        
        // Get template content
        $templateMap = [
            'email_loan_approval' => [
                'subject_field' => 'email_loan_approval_subject',
                'body_field' => 'email_loan_approval_body',
                'default_subject' => 'Loan Application Approved - FeedTan CMG',
                'default_body' => 'Dear {member_name},

We are pleased to inform you that your loan application of {loan_amount} TZS has been approved.

Please visit our office within 7 days to complete the loan processing and disbursement.

If you have any questions, please contact us.

Best regards,
{organization_name}'
            ],
            'email_payment_reminder' => [
                'subject_field' => 'email_payment_reminder_subject',
                'body_field' => 'email_payment_reminder_body',
                'default_subject' => 'Payment Reminder - {due_date}',
                'default_body' => 'Dear {member_name},

This is a friendly reminder that your payment of {loan_amount} TZS is due on {due_date}.

Your current balance is {balance} TZS.

Please make payment before the due date to avoid penalties and maintain your good standing with us.

Thank you for your prompt attention to this matter.

Best regards,
{organization_name}'
            ],
            'email_welcome' => [
                'subject_field' => 'email_welcome_subject',
                'body_field' => 'email_welcome_body',
                'default_subject' => 'Welcome to {organization_name}',
                'default_body' => 'Dear {member_name},

Welcome to {organization_name}!

Your account has been successfully created with account number: {account_number}

We are delighted to have you as a member of our community. Our mission is to support your financial growth and prosperity.

You can now access your account online and manage your savings, loans, and investments.

If you have any questions or need assistance, please do not hesitate to contact us.

Welcome aboard!

Best regards,
{organization_name}'
            ]
        ];

        if (!isset($templateMap[$templateType])) {
            return redirect()->back()->with('error', 'Template not found.');
        }

        $template = $templateMap[$templateType];
        $subject = isset($settings[$template['subject_field']]) ? $settings[$template['subject_field']]->value : $template['default_subject'];
        $body = isset($settings[$template['body_field']]) ? $settings[$template['body_field']]->value : $template['default_body'];

        // Replace variables with test data
        $testVariables = $request->input('test_variables', []);
        
        // Common variables
        $finalSubject = str_replace('{member_name}', $request->input('member_name'), $subject);
        $finalBody = str_replace('{member_name}', $request->input('member_name'), $body);
        
        // Template-specific variables
        if (isset($testVariables['loan_amount'])) {
            $finalSubject = str_replace('{loan_amount}', $testVariables['loan_amount'], $finalSubject);
            $finalBody = str_replace('{loan_amount}', $testVariables['loan_amount'], $finalBody);
        }
        if (isset($testVariables['due_date'])) {
            $finalSubject = str_replace('{due_date}', $testVariables['due_date'], $finalSubject);
            $finalBody = str_replace('{due_date}', $testVariables['due_date'], $finalBody);
        }
        if (isset($testVariables['balance'])) {
            $finalBody = str_replace('{balance}', $testVariables['balance'], $finalBody);
        }
        if (isset($testVariables['account_number'])) {
            $finalBody = str_replace('{account_number}', $testVariables['account_number'], $finalBody);
        }
        if (isset($testVariables['organization_name'])) {
            $finalSubject = str_replace('{organization_name}', $testVariables['organization_name'], $finalSubject);
            $finalBody = str_replace('{organization_name}', $testVariables['organization_name'], $finalBody);
        }

        // Use the actual email service to send the message
        try {
            $emailAddress = $request->input('email_address');
            $memberName = $request->input('member_name');
            
            // Import and use the Email service
            $emailService = app(\App\Services\EmailNotificationService::class);
            
            // Get organization info for email configuration
            $orgInfo = $emailService->getOrganizationInfo();
            
            // Send the email using Laravel Mail with the service's configuration
            \Illuminate\Support\Facades\Mail::raw($finalBody, function ($mail) use ($emailAddress, $memberName, $finalSubject, $orgInfo) {
                $mail->to($emailAddress, $memberName)
                    ->subject($finalSubject)
                    ->from($orgInfo['from_email'], $orgInfo['from_name']);
            });
            
            return redirect()->back()->with('success', "Test email sent successfully to {$emailAddress}. Subject: {$finalSubject}");
        } catch (\Exception $e) {
            \Log::error('Email test sending failed: ' . $e->getMessage(), [
                'email' => $request->input('email_address'),
                'template' => $templateType,
                'subject' => $finalSubject
            ]);
            return redirect()->back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
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
