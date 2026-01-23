<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        try {
            $settings = Setting::getByGroup('communication');
            
            if (isset($settings['mail_mailer']) && $settings['mail_mailer']->value) {
                Config::set('mail.default', $settings['mail_mailer']->value);
            }
            
            if (isset($settings['mail_host']) && $settings['mail_host']->value) {
                Config::set('mail.mailers.smtp.host', $settings['mail_host']->value);
            }
            
            if (isset($settings['mail_port']) && $settings['mail_port']->value) {
                Config::set('mail.mailers.smtp.port', $settings['mail_port']->value);
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
                // Default to TLS if not set
                Config::set('mail.mailers.smtp.encryption', 'tls');
            }
            
            if (isset($settings['mail_from_address']) && $settings['mail_from_address']->value) {
                Config::set('mail.from.address', $settings['mail_from_address']->value);
            }
            
            if (isset($settings['mail_from_name']) && $settings['mail_from_name']->value) {
                Config::set('mail.from.name', $settings['mail_from_name']->value);
            }
        } catch (\Exception $e) {
            // If settings table doesn't exist or there's an error, use default config
            \Log::warning('Failed to load mail config from database: ' . $e->getMessage());
        }
    }
}

