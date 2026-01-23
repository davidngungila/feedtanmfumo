<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\LoginSession;
use App\Models\PasswordPolicy;
use App\Models\IpWhitelist;
use App\Models\AuditLog;
use App\Models\ActivityLog;
use App\Models\NotificationTemplate;
use App\Models\AlertRule;
use App\Models\Backup;
use App\Models\DocumentTemplate;
use App\Models\Integration;
use App\Models\Webhook;
use App\Models\CustomField;
use App\Models\Module;
use App\Models\SystemVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class SystemSettingsController extends Controller
{
    // ============================================
    // USER & ACCESS MANAGEMENT
    // ============================================
    
    public function users()
    {
        $users = User::with('roles')->paginate(20);
        return view('admin.settings.users.index', compact('users'));
    }

    public function roles()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all()->groupBy('group');
        return view('admin.settings.users.roles', compact('roles', 'permissions'));
    }

    public function permissions()
    {
        $permissions = Permission::with('roles')->get()->groupBy('group');
        return view('admin.settings.users.permissions', compact('permissions'));
    }

    public function roleAssignment()
    {
        $users = User::with('roles')->get();
        $roles = Role::all();
        return view('admin.settings.users.role-assignment', compact('users', 'roles'));
    }

    public function loginSessions()
    {
        $sessions = LoginSession::with('user')
            ->orderBy('login_at', 'desc')
            ->paginate(20);
        return view('admin.settings.users.login-sessions', compact('sessions'));
    }

    public function passwordPolicy()
    {
        $policy = PasswordPolicy::first() ?? new PasswordPolicy();
        return view('admin.settings.users.password-policy', compact('policy'));
    }

    public function updatePasswordPolicy(Request $request)
    {
        $validated = $request->validate([
            'min_length' => 'required|integer|min:6|max:32',
            'require_uppercase' => 'boolean',
            'require_lowercase' => 'boolean',
            'require_numbers' => 'boolean',
            'require_symbols' => 'boolean',
            'max_age_days' => 'nullable|integer|min:0',
            'min_age_days' => 'nullable|integer|min:0',
            'history_count' => 'nullable|integer|min:0|max:10',
            'lockout_attempts' => 'required|integer|min:1|max:10',
            'lockout_duration_minutes' => 'required|integer|min:1',
            'enforce_on_login' => 'boolean',
        ]);

        PasswordPolicy::updateOrCreate(['id' => 1], $validated);

        return redirect()->route('admin.settings.password-policy')
            ->with('success', 'Password policy updated successfully.');
    }

    // ============================================
    // ORGANIZATION / GENERAL
    // ============================================

    public function systemInformation()
    {
        $info = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'database' => DB::connection()->getDriverName(),
            'timezone' => config('app.timezone'),
            'environment' => config('app.env'),
            'debug' => config('app.debug'),
            'cache_driver' => config('cache.default'),
            'queue_driver' => config('queue.default'),
            'session_driver' => config('session.driver'),
        ];
        return view('admin.settings.organization.system-information', compact('info'));
    }

    public function organizationProfile()
    {
        $settings = Setting::getByGroup('organization');
        return view('admin.settings.organization.profile', compact('settings'));
    }

    public function contactDetails()
    {
        $settings = Setting::getByGroup('organization');
        return view('admin.settings.organization.contact-details', compact('settings'));
    }

    public function logoBranding()
    {
        $settings = Setting::getByGroup('organization');
        return view('admin.settings.organization.logo-branding', compact('settings'));
    }

    public function languageSettings()
    {
        $settings = Setting::getByGroup('system');
        $languages = ['en' => 'English', 'sw' => 'Swahili'];
        return view('admin.settings.organization.language', compact('settings', 'languages'));
    }

    public function timezoneDateFormat()
    {
        $settings = Setting::getByGroup('system');
        return view('admin.settings.organization.timezone', compact('settings'));
    }

    // ============================================
    // APPLICATION SETTINGS
    // ============================================

    public function generalSettings()
    {
        $settings = Setting::getByGroup('system');
        return view('admin.settings.application.general', compact('settings'));
    }

    public function featureToggles()
    {
        $settings = Setting::getByGroup('features');
        return view('admin.settings.application.feature-toggles', compact('settings'));
    }

    public function maintenanceMode()
    {
        $isMaintenance = app()->isDownForMaintenance();
        return view('admin.settings.application.maintenance', compact('isMaintenance'));
    }

    public function toggleMaintenanceMode(Request $request)
    {
        if (app()->isDownForMaintenance()) {
            Artisan::call('up');
            $message = 'Maintenance mode disabled.';
        } else {
            Artisan::call('down', ['--secret' => $request->input('secret', 'maintenance-secret')]);
            $message = 'Maintenance mode enabled.';
        }

        return redirect()->route('admin.settings.maintenance-mode')
            ->with('success', $message);
    }

    public function defaultValues()
    {
        $settings = Setting::getByGroup('defaults');
        return view('admin.settings.application.default-values', compact('settings'));
    }

    public function systemPreferences()
    {
        $settings = Setting::getByGroup('preferences');
        return view('admin.settings.application.preferences', compact('settings'));
    }

    // ============================================
    // NOTIFICATIONS
    // ============================================

    public function emailSettings()
    {
        $settings = Setting::getByGroup('email');
        return view('admin.settings.notifications.email', compact('settings'));
    }

    public function updateEmailSettings(Request $request)
    {
        $validated = $request->validate([
            'mail_mailer' => 'required|string',
            'mail_host' => 'required|string',
            'mail_port' => 'required|integer',
            'mail_username' => 'nullable|string',
            'mail_password' => 'nullable|string',
            'mail_encryption' => 'nullable|string',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value, 'email', 'text');
        }

        return redirect()->route('admin.settings.email-settings')
            ->with('success', 'Email settings updated successfully.');
    }

    public function smsSettings()
    {
        $settings = Setting::getByGroup('sms');
        return view('admin.settings.notifications.sms', compact('settings'));
    }

    public function pushNotifications()
    {
        $settings = Setting::getByGroup('push');
        return view('admin.settings.notifications.push', compact('settings'));
    }

    public function notificationTemplates()
    {
        $templates = NotificationTemplate::all();
        return view('admin.settings.notifications.templates', compact('templates'));
    }

    public function alertRules()
    {
        $rules = AlertRule::all();
        return view('admin.settings.notifications.alert-rules', compact('rules'));
    }

    // ============================================
    // DATA MANAGEMENT
    // ============================================

    public function backupRestore()
    {
        $backups = Backup::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.settings.data.backup-restore', compact('backups'));
    }

    public function createBackup(Request $request)
    {
        $type = $request->input('type', 'full');
        
        // Create backup record
        $backup = Backup::create([
            'name' => 'backup_' . date('Y-m-d_H-i-s'),
            'type' => $type,
            'status' => 'in_progress',
            'created_by' => auth()->id(),
        ]);

        // In a real implementation, you would run the actual backup process
        // This is a placeholder
        $backup->update([
            'status' => 'completed',
            'file_path' => 'backups/' . $backup->name . '.zip',
            'file_size' => '0 MB',
            'completed_at' => now(),
        ]);

        return redirect()->route('admin.settings.backup-restore')
            ->with('success', 'Backup created successfully.');
    }

    public function importData()
    {
        return view('admin.settings.data.import');
    }

    public function exportData()
    {
        return view('admin.settings.data.export');
    }

    public function databaseSettings()
    {
        $settings = [
            'connection' => config('database.default'),
            'host' => config('database.connections.mysql.host'),
            'database' => config('database.connections.mysql.database'),
            'charset' => config('database.connections.mysql.charset'),
        ];
        return view('admin.settings.data.database', compact('settings'));
    }

    public function dataRetentionPolicy()
    {
        $settings = Setting::getByGroup('data_retention');
        return view('admin.settings.data.retention', compact('settings'));
    }

    // ============================================
    // SECURITY
    // ============================================

    public function securitySettings()
    {
        $settings = Setting::getByGroup('security');
        return view('admin.settings.security.settings', compact('settings'));
    }

    public function twoFactorAuth()
    {
        $settings = Setting::getByGroup('2fa');
        return view('admin.settings.security.two-factor', compact('settings'));
    }

    public function ipWhitelisting()
    {
        $whitelist = IpWhitelist::where('type', 'whitelist')->get();
        $blacklist = IpWhitelist::where('type', 'blacklist')->get();
        return view('admin.settings.security.ip-whitelist', compact('whitelist', 'blacklist'));
    }

    public function addIpAddress(Request $request)
    {
        $validated = $request->validate([
            'ip_address' => 'required|ip',
            'type' => 'required|in:whitelist,blacklist',
            'description' => 'nullable|string',
            'expires_at' => 'nullable|date',
        ]);

        IpWhitelist::create([
            ...$validated,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.settings.ip-whitelisting')
            ->with('success', 'IP address added successfully.');
    }

    public function auditLogs()
    {
        $logs = AuditLog::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(50);
        return view('admin.settings.security.audit-logs', compact('logs'));
    }

    public function activityLogs()
    {
        $logs = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(50);
        return view('admin.settings.security.activity-logs', compact('logs'));
    }

    // ============================================
    // DOCUMENTS & TEMPLATES
    // ============================================

    public function pdfTemplates()
    {
        $templates = DocumentTemplate::where('type', 'pdf')->get();
        return view('admin.settings.templates.pdf', compact('templates'));
    }

    public function emailTemplates()
    {
        $templates = DocumentTemplate::where('type', 'email')->get();
        return view('admin.settings.templates.email', compact('templates'));
    }

    public function reportTemplates()
    {
        $templates = DocumentTemplate::where('type', 'report')->get();
        return view('admin.settings.templates.report', compact('templates'));
    }

    public function certificateTemplates()
    {
        $templates = DocumentTemplate::where('type', 'certificate')->get();
        return view('admin.settings.templates.certificate', compact('templates'));
    }

    // ============================================
    // INTEGRATIONS
    // ============================================

    public function apiSettings()
    {
        $settings = Setting::getByGroup('api');
        return view('admin.settings.integrations.api', compact('settings'));
    }

    public function paymentGateways()
    {
        $gateways = Integration::where('type', 'payment_gateway')->get();
        return view('admin.settings.integrations.payment-gateways', compact('gateways'));
    }

    public function thirdPartyServices()
    {
        $services = Integration::where('type', '!=', 'payment_gateway')->get();
        return view('admin.settings.integrations.third-party', compact('services'));
    }

    public function webhooks()
    {
        $webhooks = Webhook::all();
        return view('admin.settings.integrations.webhooks', compact('webhooks'));
    }

    // ============================================
    // SYSTEM TOOLS
    // ============================================

    public function cacheManagement()
    {
        return view('admin.settings.tools.cache');
    }

    public function clearCache(Request $request)
    {
        $type = $request->input('type', 'all');
        
        switch ($type) {
            case 'all':
                Artisan::call('cache:clear');
                Artisan::call('config:clear');
                Artisan::call('route:clear');
                Artisan::call('view:clear');
                break;
            case 'cache':
                Artisan::call('cache:clear');
                break;
            case 'config':
                Artisan::call('config:clear');
                break;
            case 'route':
                Artisan::call('route:clear');
                break;
            case 'view':
                Artisan::call('view:clear');
                break;
        }

        return redirect()->route('admin.settings.cache-management')
            ->with('success', ucfirst($type) . ' cache cleared successfully.');
    }

    public function systemLogs()
    {
        $logFile = storage_path('logs/laravel.log');
        $logs = [];
        
        if (File::exists($logFile)) {
            $logs = array_slice(file($logFile), -100); // Last 100 lines
        }
        
        return view('admin.settings.tools.logs', compact('logs'));
    }

    public function queueJobs()
    {
        $jobs = DB::table('jobs')->orderBy('id', 'desc')->limit(50)->get();
        $failed = DB::table('failed_jobs')->orderBy('id', 'desc')->limit(50)->get();
        return view('admin.settings.tools.queue', compact('jobs', 'failed'));
    }

    public function cronJobs()
    {
        return view('admin.settings.tools.cron');
    }

    public function debugSettings()
    {
        $settings = [
            'debug' => config('app.debug'),
            'environment' => config('app.env'),
        ];
        return view('admin.settings.tools.debug', compact('settings'));
    }

    // ============================================
    // UPDATES & MAINTENANCE
    // ============================================

    public function systemUpdates()
    {
        $currentVersion = SystemVersion::where('is_current', true)->first();
        $availableVersions = SystemVersion::where('is_available', true)
            ->where('is_current', false)
            ->orderBy('release_date', 'desc')
            ->get();
        return view('admin.settings.updates.updates', compact('currentVersion', 'availableVersions'));
    }

    public function versionInfo()
    {
        $version = SystemVersion::where('is_current', true)->first();
        return view('admin.settings.updates.version', compact('version'));
    }

    public function changelog()
    {
        $versions = SystemVersion::orderBy('release_date', 'desc')->get();
        return view('admin.settings.updates.changelog', compact('versions'));
    }

    public function optimizeSystem()
    {
        Artisan::call('optimize');
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('view:cache');
        
        return redirect()->route('admin.settings.index')
            ->with('success', 'System optimized successfully.');
    }

    // ============================================
    // REPORTS & MONITORING
    // ============================================

    public function systemReports()
    {
        return view('admin.settings.reports.system');
    }

    public function usageStatistics()
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('status', 'active')->count(),
            'total_logins_today' => LoginSession::whereDate('login_at', today())->count(),
            'total_audit_logs' => AuditLog::count(),
        ];
        return view('admin.settings.reports.usage', compact('stats'));
    }

    public function performanceMonitoring()
    {
        return view('admin.settings.reports.performance');
    }

    public function errorReports()
    {
        $errors = DB::table('failed_jobs')
            ->orderBy('failed_at', 'desc')
            ->limit(50)
            ->get();
        return view('admin.settings.reports.errors', compact('errors'));
    }

    // ============================================
    // BONUS FEATURES
    // ============================================

    public function customFields()
    {
        $fields = CustomField::orderBy('sort_order')->get()->groupBy('model_type');
        return view('admin.settings.bonus.custom-fields', compact('fields'));
    }

    public function moduleManagement()
    {
        $modules = Module::all();
        return view('admin.settings.bonus.modules', compact('modules'));
    }

    public function menuBuilder()
    {
        return view('admin.settings.bonus.menu-builder');
    }

    public function themeAppearance()
    {
        $settings = Setting::getByGroup('theme');
        return view('admin.settings.bonus.theme', compact('settings'));
    }

    public function licenseManagement()
    {
        $settings = Setting::getByGroup('license');
        return view('admin.settings.bonus.license', compact('settings'));
    }
}
