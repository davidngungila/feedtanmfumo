<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Loan;
use App\Models\SavingsAccount;
use App\Models\Investment;
use App\Models\Issue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $user->load('roles');
        
        // Get user statistics
        $stats = [
            'loans' => Loan::where('user_id', $user->id)->count(),
            'savings' => SavingsAccount::where('user_id', $user->id)->count(),
            'investments' => Investment::where('user_id', $user->id)->count(),
            'issues' => Issue::where('user_id', $user->id)->count(),
        ];
        
        return view('admin.profile.index', compact('user', 'stats'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('admin.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'bio' => ['nullable', 'string', 'max:1000'],
        ]);

        $user->update($validated);

        return redirect()->route('admin.profile.index')->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.profile.index')->with('success', 'Password updated successfully.');
    }

    public function settings()
    {
        $user = Auth::user();
        $preferences = $user->preferences ? json_decode($user->preferences, true) : [];
        return view('admin.profile.settings', compact('user', 'preferences'));
    }

    public function updateSettings(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            // Notification channels
            'email_notifications' => ['nullable', 'boolean'],
            'sms_notifications' => ['nullable', 'boolean'],
            'in_app_notifications' => ['nullable', 'boolean'],
            
            // Notification types
            'notify_loan_applications' => ['nullable', 'boolean'],
            'notify_payment_reminders' => ['nullable', 'boolean'],
            'notify_overdue_payments' => ['nullable', 'boolean'],
            'notify_new_members' => ['nullable', 'boolean'],
            'notify_system_alerts' => ['nullable', 'boolean'],
            'notify_issue_reports' => ['nullable', 'boolean'],
            'notify_account_activity' => ['nullable', 'boolean'],
            'notify_reports_ready' => ['nullable', 'boolean'],
            
            // Email digest
            'email_digest_frequency' => ['nullable', 'string', 'in:realtime,daily,weekly'],
            'email_digest_time' => ['nullable', 'string'],
            
            // Display preferences
            'theme' => ['nullable', 'string', 'in:light,dark,auto'],
            'date_format' => ['nullable', 'string'],
            'time_format' => ['nullable', 'string', 'in:12,24'],
            'number_format' => ['nullable', 'string', 'in:comma,space,period'],
            'items_per_page' => ['nullable', 'integer', 'in:10,20,50,100'],
            'currency_symbol' => ['nullable', 'string', 'in:TZS,USD,EUR'],
            
            // Language & Region
            'language' => ['nullable', 'string', 'in:en,sw'],
            'timezone' => ['nullable', 'string'],
            
            // Security
            'two_factor_enabled' => ['nullable', 'boolean'],
            'session_timeout' => ['nullable', 'integer'],
            'login_alerts' => ['nullable', 'string', 'in:all,new_device,none'],
            'activity_logging' => ['nullable', 'boolean'],
            
            // Dashboard
            'default_dashboard' => ['nullable', 'string', 'in:overview,loans,savings,investments'],
            'widgets' => ['nullable', 'array'],
            'widgets.*' => ['string', 'in:quick_stats,recent_activity,performance_metrics,alerts'],
            
            // Privacy
            'profile_visible' => ['nullable', 'boolean'],
            'show_activity_status' => ['nullable', 'boolean'],
        ]);

        // Merge with existing preferences
        $existing = $user->preferences ? json_decode($user->preferences, true) : [];
        
        // Handle checkboxes - if not present in request, set to false
        $checkboxFields = [
            'email_notifications', 'sms_notifications', 'in_app_notifications',
            'notify_loan_applications', 'notify_payment_reminders', 'notify_overdue_payments',
            'notify_new_members', 'notify_system_alerts', 'notify_issue_reports',
            'notify_account_activity', 'notify_reports_ready',
            'two_factor_enabled', 'activity_logging', 'profile_visible', 'show_activity_status'
        ];
        
        foreach ($checkboxFields as $field) {
            $validated[$field] = $request->has($field) ? (bool)$request->input($field) : false;
        }
        
        $preferences = array_merge($existing, array_filter($validated, function($value) {
            return $value !== null && $value !== '';
        }));

        $user->update([
            'preferences' => json_encode($preferences),
        ]);

        return redirect()->route('admin.profile.settings')->with('success', 'Settings updated successfully.');
    }
}
